<?php
/** @var array<string, mixed> $user */
?>
<div class="page-heading">
    <div>
        <h1>Gabung Kelas</h1>
        <ol class="breadcrumb">
            <li><a href="<?= url('/dashboard') ?>">Beranda</a></li>
            <li class="active">Gabung Kelas</li>
        </ol>
    </div>
</div>

<div class="wrapper-content content">
    <div class="row">
        <div class="col-lg-7 col-md-9">
            <div class="ibox">
                <div class="ibox-title"><h5>Masukkan kode kelas</h5></div>
                <div class="ibox-content">
                    <form method="POST" action="<?= url('/classrooms/join') ?>">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label>Login sebagai</label>
                            <input type="text" class="form-control" value="<?= e($user['name']) ?> (<?= e($user['username']) ?>)" readonly>
                        </div>
                        <div class="form-group">
                            <label for="code">Kode Kelas</label>
                            <input id="code" name="code" type="text" class="form-control" placeholder="Contoh: A1B2C3"
                                   required minlength="4" maxlength="20" pattern="[A-Za-z0-9]+" autocomplete="off"
                                   style="text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                            <p class="help-block"><i class="fa fa-info-circle" aria-hidden="true"></i> Kode terdiri dari huruf dan angka tanpa spasi. Minta kepada guru Anda.</p>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i> Gabung Kelas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
