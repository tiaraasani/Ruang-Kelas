<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Validator;
use App\Repositories\AnnouncementRepository;
use App\Repositories\AssignmentRepository;
use App\Repositories\CommentRepository;
use App\Repositories\MaterialRepository;
use App\Repositories\SubmissionRepository;
use App\Services\ClassCodeGenerator;

final class ClassroomController extends Controller
{
    private const CODE_PATTERN = '/^[A-Z0-9]{4,20}$/';

    private readonly MaterialRepository $materials;
    private readonly AssignmentRepository $assignments;
    private readonly AnnouncementRepository $announcements;
    private readonly SubmissionRepository $submissions;
    private readonly CommentRepository $comments;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->materials = new MaterialRepository($this->db);
        $this->assignments = new AssignmentRepository($this->db);
        $this->announcements = new AnnouncementRepository($this->db);
        $this->submissions = new SubmissionRepository($this->db);
        $this->comments = new CommentRepository($this->db);
    }

    public function create(): void
    {
        $this->render('classroom/create', ['title' => 'Buat Kelas']);
    }

    public function store(): void
    {
        $validator = (new Validator($this->request->all()))
            ->required('name', 'Nama kelas')
            ->length('name', 'Nama kelas', 3, 100)
            ->required('subject', 'Mata pelajaran')
            ->length('subject', 'Mata pelajaran', 2, 100);

        if ($validator->fails()) {
            $this->failWith($validator->errors(), '/classrooms/create', $this->oldInput());
        }

        $code = (new ClassCodeGenerator($this->classrooms))->generate();
        $classroomId = $this->classrooms->create(
            $validator->value('name'),
            $validator->value('subject'),
            $code,
            $this->auth->username(),
        );

        $this->success(sprintf('Kelas berhasil dibuat. Kode kelas: %s', $code), '/classrooms/' . $classroomId);
    }

    public function joinForm(): void
    {
        $this->render('classroom/join', ['title' => 'Gabung Kelas']);
    }

    public function join(): void
    {
        $code = strtoupper($this->request->post('code'));

        if ($code === '' || preg_match(self::CODE_PATTERN, $code) !== 1) {
            $this->failWith(['Kode kelas harus terdiri dari 4-20 huruf atau angka.'], '/classrooms/join');
        }

        $classroom = $this->classrooms->findByCode($code);

        if ($classroom === null) {
            $this->failWith(['Kelas dengan kode tersebut tidak ditemukan.'], '/classrooms/join');
        }

        $classroomId = (int) $classroom['id'];

        if ($this->classrooms->isEnrolled($classroomId, $this->auth->username())) {
            $this->session->flash('error', 'Anda sudah bergabung di kelas ini.');
            $this->redirect('/classrooms/' . $classroomId);
        }

        $this->classrooms->enroll($classroomId, $this->auth->username());
        $this->success(sprintf('Anda berhasil bergabung ke kelas %s.', $classroom['name']), '/classrooms/' . $classroomId);
    }

    public function show(int $classroomId): void
    {
        $classroom = $this->classroomOrFail($classroomId);
        $user = $this->user();
        $isOwner = $this->policy->isOwner($classroom, $user);
        $assignments = $this->assignments->forClassroom($classroomId);

        foreach ($assignments as $index => $assignment) {
            $assignments[$index]['comments'] = $this->comments->forAssignment((int) $assignment['id']);
        }

        $data = [
            'title' => (string) $classroom['name'],
            'classroom' => $classroom,
            'isOwner' => $isOwner,
            'materials' => $this->materials->forClassroom($classroomId),
            'assignments' => $assignments,
            'announcements' => $this->announcements->forClassroom($classroomId),
            'students' => $this->classrooms->students($classroomId),
            'mySubmissions' => [],
            'selectedAssignment' => null,
            'submissions' => [],
        ];

        if ($isOwner) {
            $selectedId = $this->request->queryInt('assignment');

            foreach ($assignments as $assignment) {
                if ((int) $assignment['id'] === $selectedId) {
                    $data['selectedAssignment'] = $assignment;
                    $data['submissions'] = $this->submissions->forAssignment($selectedId);
                    break;
                }
            }
        } else {
            foreach ($assignments as $assignment) {
                $submission = $this->submissions->findForStudent((int) $assignment['id'], (string) $user['username']);

                if ($submission !== null) {
                    $data['mySubmissions'][(int) $assignment['id']] = $submission;
                }
            }
        }

        $this->render('classroom/show', $data);
    }
}
