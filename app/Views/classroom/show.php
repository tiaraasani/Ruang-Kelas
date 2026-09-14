<?php
/**
 * Classroom page with tabs for materials, assignments, announcements, students and grades.
 *
 * @var array<string, mixed> $classroom
 * @var bool $isOwner
 * @var array<int, array<string, mixed>> $materials
 * @var array<int, array<string, mixed>> $assignments
 * @var array<int, array<string, mixed>> $announcements
 * @var array<int, array<string, mixed>> $students
 * @var array<int, array<string, mixed>> $mySubmissions   Keyed by assignment id (students only).
 * @var array<string, mixed>|null $selectedAssignment     Grades tab (teacher only).
 * @var array<int, array<string, mixed>> $submissions     Grades tab (teacher only).
 */
?>
<div class="page-heading">
    <div>
        <h2><?= e($classroom['name']) ?> <small><?= e($classroom['subject']) ?></small></h2>
        <ol class="breadcrumb">
            <li><a href="<?= url('/dashboard') ?>">Beranda</a></li>
            <li class="active"><?= e($classroom['name']) ?></li>
        </ol>
        <?php if ($isOwner): ?>
            <p style="margin: 8px 0 0;"><span class="badge-soft badge-teal"><i class="fa fa-key" aria-hidden="true"></i> Kode kelas: <?= e($classroom['code']) ?></span></p>
        <?php endif; ?>
    </div>
    <?php if ($isOwner): ?>
        <div class="btn-group">
            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-plus" aria-hidden="true"></i> Tambah Konten <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li><a href="#" data-toggle="modal" data-target="#modal-material-create"><i class="fa fa-file-text-o" aria-hidden="true"></i> Tambah Materi</a></li>
                <li><a href="#" data-toggle="modal" data-target="#modal-assignment-create"><i class="fa fa-tasks" aria-hidden="true"></i> Tambah Tugas</a></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div class="wrapper-content content animated fadeInUp">
    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-materials">Materi</a></li>
                    <li><a data-toggle="tab" href="#tab-assignments">Tugas</a></li>
                    <li><a data-toggle="tab" href="#tab-announcements">Pengumuman</a></li>
                    <li><a data-toggle="tab" href="#tab-students">Siswa</a></li>
                    <?php if ($isOwner): ?>
                        <li><a data-toggle="tab" href="#tab-grades">Nilai</a></li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div id="tab-materials" class="tab-pane active">
                        <div class="panel-body">
                            <?php $this->insert('classroom/tabs/materials', [
                                'materials' => $materials,
                                'isOwner' => $isOwner,
                            ]) ?>
                        </div>
                    </div>
                    <div id="tab-assignments" class="tab-pane">
                        <div class="panel-body">
                            <?php $this->insert('classroom/tabs/assignments', [
                                'assignments' => $assignments,
                                'isOwner' => $isOwner,
                                'mySubmissions' => $mySubmissions,
                            ]) ?>
                        </div>
                    </div>
                    <div id="tab-announcements" class="tab-pane">
                        <div class="panel-body">
                            <?php $this->insert('classroom/tabs/announcements', [
                                'classroom' => $classroom,
                                'announcements' => $announcements,
                                'isOwner' => $isOwner,
                            ]) ?>
                        </div>
                    </div>
                    <div id="tab-students" class="tab-pane">
                        <div class="panel-body">
                            <?php $this->insert('classroom/tabs/students', ['students' => $students]) ?>
                        </div>
                    </div>
                    <?php if ($isOwner): ?>
                        <div id="tab-grades" class="tab-pane">
                            <div class="panel-body">
                                <?php $this->insert('classroom/tabs/grades', [
                                    'classroom' => $classroom,
                                    'assignments' => $assignments,
                                    'selectedAssignment' => $selectedAssignment,
                                    'submissions' => $submissions,
                                ]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->insert('classroom/modals', ['classroom' => $classroom, 'isOwner' => $isOwner]) ?>
