<?php
include "navbar.php";
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buat Kelas</title>

</head>

<body>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">RUANG KELAS</span>
                    </li>
                    <li>
                        <a href="aksi_logout.php">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelas</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="form_basic.php">Home</a>
                    </li>
                    <li class="active">
                        <strong>Buat kelas</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-7">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Buat kelas</h5>
                        </div>
                        <div class="ibox-content">

                            <form id="form" action="aksi_buatkelas.php" method="POST" class="wizard-big">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="namaKelas">Nama Kelas</label>
                                                <input id="namaKelas" name="namaKelas" type="text" class="form-control"
                                                    placeholder="Masukkan nama kelas">
                                            </div>
                                            <div class="form-group">
                                                <label for="mapel">Mata Pelajaran</label>
                                                <input id="mapel" name="mapel" type="text" class="form-control"
                                                    placeholder="Masukkan mata pelajaran">
                                            </div>
                                            <?php
                                            include "koneksi.php";
                                            $auto = mysqli_query($koneksi, "select max(id_Kelas) as maxCode from kelas");
                                            $data = mysqli_fetch_array($auto);

                                            $kode = $data["maxCode"];
                                            $kode++;
                                            $huruf = "K";
                                            $kodeKelas = $huruf . sprintf("%03s", $kode)
                                                ?>
                                            <div class="form-group">
                                                <label for="kodeKelas">Kode Kelas</label>
                                                <input id="kodeKelas" name="kodeKelas" value="<?php echo $kodeKelas ?> "
                                                    type="text" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="text-center">
                                                <div>
                                                    <i class="fa fa-user" style="font-size: 180px; color: #e5e5e5;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-10 text-center">
                                            <button type="submit" class="btn btn-primary">Buat</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>