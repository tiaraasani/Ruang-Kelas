<header class="topbar">
    <button type="button" class="icon-btn" data-sidebar-toggle aria-label="Buka atau tutup menu">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </button>

    <div class="spacer"></div>

    <button type="button" class="icon-btn" data-theme-toggle aria-label="Ganti tema terang atau gelap">
        <i class="fa fa-moon-o" aria-hidden="true"></i>
    </button>

    <form method="POST" action="<?= url('/logout') ?>" class="logout-form">
        <?= csrf_field() ?>
        <button type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i> <span class="hide-mobile">Keluar</span></button>
    </form>
</header>
