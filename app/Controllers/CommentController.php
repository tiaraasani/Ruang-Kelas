<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Response;
use App\Core\Validator;
use App\Repositories\AssignmentRepository;
use App\Repositories\CommentRepository;

final class CommentController extends Controller
{
    private readonly AssignmentRepository $assignments;
    private readonly CommentRepository $comments;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->assignments = new AssignmentRepository($this->db);
        $this->comments = new CommentRepository($this->db);
    }

    /** Comments grouped by assignment for one of the user's classrooms. */
    public function index(): void
    {
        $user = $this->user();
        $classrooms = $this->classroomsFor($user);
        $selectedId = $this->request->queryInt('classroom');
        $selected = null;

        foreach ($classrooms as $classroom) {
            if ($selectedId === null || (int) $classroom['id'] === $selectedId) {
                $selected = $classroom;
                break;
            }
        }

        if ($selectedId !== null && $selected === null) {
            Response::abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $assignments = $selected === null ? [] : $this->assignments->forClassroom((int) $selected['id']);

        foreach ($assignments as &$assignment) {
            $assignment['comments'] = $this->comments->forAssignment((int) $assignment['id']);
        }
        unset($assignment);

        $this->render('comments/index', [
            'title' => 'Komentar',
            'classrooms' => $classrooms,
            'selectedClassroom' => $selected,
            'assignments' => $assignments,
        ]);
    }

    public function store(int $assignmentId): void
    {
        $assignment = $this->assignments->find($assignmentId) ?? Response::abort(404, 'Tugas tidak ditemukan.');
        $classroom = $this->classroomOrFail((int) $assignment['classroom_id']);

        // Only a fixed set of return targets is accepted, never a user-supplied URL.
        $redirectTo = $this->request->post('return') === 'comments'
            ? url('/comments', ['classroom' => $classroom['id']])
            : url('/classrooms/' . $classroom['id']) . '#tab-assignments';

        $validator = (new Validator($this->request->all()))
            ->required('content', 'Komentar')
            ->length('content', 'Komentar', 1, 1000);

        if ($validator->fails()) {
            $this->session->flash('errors', $validator->errors());
            Response::redirect($redirectTo);
        }

        $this->comments->create($assignmentId, $this->auth->username(), $validator->value('content'));

        $this->session->flash('success', 'Komentar berhasil dikirim.');
        Response::redirect($redirectTo);
    }
}
