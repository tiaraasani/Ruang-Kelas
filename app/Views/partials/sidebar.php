<?php
/**
 * @var \App\Core\Auth $auth
 * @var array<string, mixed> $user
 * @var array<int, array<string, mixed>> $classrooms
 */

use App\Support\Role;

$currentPath = app()->request->path();
$onClassrooms = str_starts_with($currentPath, '/classrooms');
$initial = strtoupper(mb_substr((string) $user['name'], 0, 1));
?>
<nav class="navbar-static-side" role="navigation" aria-label="Menu utama">
    <a href="<?= url('/dashboard') ?>" class="sidebar-brand">
        <span class="brand-mark">RK</span>
        <span><?= e(config('app.name')) ?></span>
    </a>

    <div class="sidebar-profile">
        <span class="avatar"><?= e($initial) ?></span>
        <span class="meta">
            <span class="name"><?= e($user['name']) ?></span>
            <span class="role"><?= e(Role::label((int) $user['role'])) ?></span>
        </span>
    </div>

    <ul class="side-menu">
        <li class="menu-label">Menu</li>
        <li class="<?= $currentPath === '/dashboard' ? 'active' : '' ?>">
            <a href="<?= url('/dashboard') ?>"><i class="fa fa-th-large" aria-hidden="true"></i> <span>Beranda</span></a>
        </li>

        <li class="has-submenu <?= $onClassrooms ? 'open' : '' ?>">
            <a href="#" aria-expanded="<?= $onClassrooms ? 'true' : 'false' ?>">
                <i class="fa fa-graduation-cap" aria-hidden="true"></i> <span>Kelas</span>
                <i class="fa fa-angle-down caret-icon" aria-hidden="true"></i>
            </a>
            <ul class="submenu">
                <?php if ($auth->isTeacher()): ?>
                    <li><a href="<?= url('/classrooms/create') ?>"><i class="fa fa-plus" aria-hidden="true"></i> Buat Kelas</a></li>
                <?php else: ?>
                    <li><a href="<?= url('/classrooms/join') ?>"><i class="fa fa-sign-in" aria-hidden="true"></i> Gabung Kelas</a></li>
                <?php endif; ?>

                <?php foreach ($classrooms as $classroom): ?>
                    <li class="<?= $currentPath === '/classrooms/' . $classroom['id'] ? 'active' : '' ?>">
                        <a href="<?= url('/classrooms/' . $classroom['id']) ?>"><?= e($classroom['name']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>

        <li class="<?= $currentPath === '/comments' ? 'active' : '' ?>">
            <a href="<?= url('/comments') ?>"><i class="fa fa-comments-o" aria-hidden="true"></i> <span>Komentar</span></a>
        </li>
    </ul>
</nav>
