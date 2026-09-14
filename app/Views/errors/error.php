<?php
/**
 * @var int $status
 * @var string $message
 */

$headline = match ($status) {
    403 => 'Akses Ditolak',
    404 => 'Halaman Tidak Ditemukan',
    405 => 'Metode Tidak Diizinkan',
    default => 'Terjadi Kesalahan',
};
?>
<div class="error-box animated fadeInUp">
    <div class="code"><?= (int) $status ?></div>
    <h3><?= e($headline) ?></h3>
    <p><?= e($message) ?></p>
    <a href="<?= url('/') ?>" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali ke Beranda</a>
</div>
