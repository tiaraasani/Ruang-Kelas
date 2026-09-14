<?php
/**
 * @var array<int, array<string, mixed>> $classrooms
 * @var array<string, mixed>|null $selectedClassroom
 * @var array<int, array<string, mixed>> $assignments
 */
?>
<div class="page-heading">
    <div>
        <h1>Komentar</h1>
        <ol class="breadcrumb">
            <li><a href="<?= url('/dashboard') ?>">Beranda</a></li>
            <li class="active"><?= $selectedClassroom === null ? 'Komentar' : e($selectedClassroom['name']) ?></li>
        </ol>
    </div>
    <?php if ($classrooms !== []): ?>
        <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle" type="button">
                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                <?= $selectedClassroom === null ? 'Pilih Kelas' : e($selectedClassroom['name']) ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <?php foreach ($classrooms as $classroom): ?>
                    <li><a href="<?= url('/comments', ['classroom' => $classroom['id']]) ?>"><?= e($classroom['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<div class="wrapper-content content animated fadeInUp">
    <?php if ($selectedClassroom === null): ?>
        <div class="ibox">
            <div class="ibox-content">
                <div class="empty-state">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                    <p>Anda belum memiliki kelas untuk didiskusikan.</p>
                </div>
            </div>
        </div>
    <?php elseif ($assignments === []): ?>
        <div class="ibox">
            <div class="ibox-content">
                <div class="empty-state">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                    <p>Belum ada tugas di kelas ini, jadi belum ada yang bisa dikomentari.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($assignments as $assignment): ?>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5 class="font-weight-bold"><?= e($assignment['title']) ?></h5>
                </div>
                <div class="ibox-content">
                    <?php $this->insert('classroom/partials/discussion', [
                        'assignment' => $assignment,
                        'comments' => $assignment['comments'],
                        'return' => 'comments',
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
