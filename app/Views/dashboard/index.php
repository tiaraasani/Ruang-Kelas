<?php
/**
 * @var \App\Core\Auth $auth
 * @var array<int, array<string, mixed>> $classrooms
 */
?>
<div class="page-heading">
    <div>
        <h1>Beranda</h1>
        <ol class="breadcrumb">
            <li class="active">Kelas Saya</li>
        </ol>
    </div>
    <?php if ($auth->isTeacher()): ?>
        <a href="<?= url('/classrooms/create') ?>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Buat Kelas</a>
    <?php else: ?>
        <a href="<?= url('/classrooms/join') ?>" class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i> Gabung Kelas</a>
    <?php endif; ?>
</div>

<div class="wrapper-content content">
    <?php if ($classrooms === []): ?>
        <div class="ibox">
            <div class="ibox-content">
                <div class="empty-state">
                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                    <?php if ($auth->isTeacher()): ?>
                        <p>Anda belum memiliki kelas. Buat kelas pertama Anda untuk mulai mengajar.</p>
                        <a href="<?= url('/classrooms/create') ?>" class="btn btn-primary">Buat Kelas</a>
                    <?php else: ?>
                        <p>Anda belum bergabung ke kelas mana pun. Minta kode kelas kepada guru Anda.</p>
                        <a href="<?= url('/classrooms/join') ?>" class="btn btn-primary">Gabung Kelas</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($classrooms as $classroom): ?>
                <div class="col-md-4 col-sm-6" style="margin-bottom: 24px;">
                    <a class="class-card" href="<?= url('/classrooms/' . $classroom['id']) ?>">
                        <div class="ibox">
                            <div class="cover">
                                <span class="subject-chip"><i class="fa fa-book" aria-hidden="true"></i></span>
                            </div>
                            <div class="body">
                                <div class="title"><?= e($classroom['name']) ?></div>
                                <div class="subject"><?= e($classroom['subject']) ?></div>
                                <?php if ($auth->isTeacher()): ?>
                                    <div style="margin-top: 12px;">
                                        <span class="badge-soft badge-teal"><i class="fa fa-key" aria-hidden="true"></i> <?= e($classroom['code']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
