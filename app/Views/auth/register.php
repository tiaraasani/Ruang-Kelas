<?php
/**
 * @var bool $teacherRegistrationEnabled
 * @var int $passwordMinLength
 */
?>
<div class="auth-card animated fadeInUp">
    <div class="auth-brand">
        <span class="brand-mark">RK</span>
        <span class="brand-name"><?= e(config('app.name')) ?></span>
    </div>
    <h3>Buat akun</h3>
    <p class="subtitle">Daftar untuk mulai belajar dan mengajar.</p>

    <?php $this->insert('partials/flash') ?>

    <form method="POST" action="<?= url('/register') ?>" role="form">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Nama lengkap</label>
            <input id="name" type="text" name="name" class="form-control" placeholder="Nama lengkap"
                   value="<?= e(old('name')) ?>" required maxlength="100" autocomplete="name">
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" class="form-control" placeholder="Huruf, angka, titik, garis bawah"
                   value="<?= e(old('username')) ?>" required minlength="3" maxlength="30"
                   pattern="[A-Za-z0-9_.]+" title="Huruf, angka, titik, atau garis bawah" autocomplete="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="Minimal <?= (int) $passwordMinLength ?> karakter"
                   required minlength="<?= (int) $passwordMinLength ?>" autocomplete="new-password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Ulangi password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang password"
                   required minlength="<?= (int) $passwordMinLength ?>" autocomplete="new-password">
        </div>
        <?php if ($teacherRegistrationEnabled): ?>
            <div class="form-group">
                <label for="teacher_code">Kode registrasi guru <span class="text-muted">(opsional)</span></label>
                <input id="teacher_code" type="text" name="teacher_code" class="form-control" placeholder="Kosongkan jika Anda siswa"
                       autocomplete="off">
                <p class="help-block">Isi hanya jika Anda mendaftar sebagai guru.</p>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
    </form>

    <div class="auth-divider">Sudah punya akun?</div>
    <a class="btn btn-white btn-block" href="<?= url('/login') ?>">Login</a>
</div>
