<div class="auth-card animated fadeInUp">
    <div class="auth-brand">
        <span class="brand-mark">RK</span>
        <span class="brand-name"><?= e(config('app.name')) ?></span>
    </div>
    <h3>Selamat datang kembali</h3>
    <p class="subtitle">Masuk untuk melanjutkan ke ruang kelas Anda.</p>

    <?php $this->insert('partials/flash') ?>

    <form method="POST" action="<?= url('/login') ?>" role="form">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" class="form-control" placeholder="Masukkan username"
                   value="<?= e(old('username')) ?>" required autofocus autocomplete="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="Masukkan password"
                   required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <div class="auth-divider">Belum punya akun?</div>
    <a class="btn btn-white btn-block" href="<?= url('/register') ?>">Buat akun baru</a>
</div>
