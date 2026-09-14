<div class="page-heading">
    <div>
        <h1>Buat Kelas</h1>
        <ol class="breadcrumb">
            <li><a href="<?= url('/dashboard') ?>">Beranda</a></li>
            <li class="active">Buat Kelas</li>
        </ol>
    </div>
</div>

<div class="wrapper-content content">
    <div class="row">
        <div class="col-lg-7 col-md-9">
            <div class="ibox">
                <div class="ibox-title"><h5>Detail kelas</h5></div>
                <div class="ibox-content">
                    <form method="POST" action="<?= url('/classrooms') ?>">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="name">Nama Kelas</label>
                            <input id="name" name="name" type="text" class="form-control" placeholder="Contoh: X IPA 1"
                                   value="<?= e(old('name')) ?>" required minlength="3" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label for="subject">Mata Pelajaran</label>
                            <input id="subject" name="subject" type="text" class="form-control" placeholder="Contoh: Matematika"
                                   value="<?= e(old('subject')) ?>" required minlength="2" maxlength="100">
                        </div>
                        <p class="help-block"><i class="fa fa-info-circle" aria-hidden="true"></i> Kode kelas dibuat otomatis dan ditampilkan setelah kelas tersimpan.</p>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Buat Kelas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
