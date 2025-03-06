<?php
include "navbar.php";
session_start();
$user = $_SESSION['username'];
$id_kelas = isset($_GET['id']) ? $_GET['id'] : 0;
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Kelas</title>

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
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-7">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Gabung ke kelas</h5>
                        </div>
                        <div class="ibox-content">

                            <form id="form" action="aksi_daftarkelas.php" class="wizard-big" method="POST">
                                <h3>
                                    Saat ini anda login sebagai
                                </h3>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input id="username" name="username" type="text" class="form-control"
                                                    value="<?php echo $user; ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="kodeKelas">Kode Kelas</label>
                                                <input id="kodeKelas" name="kodeKelas" type="text" class="form-control"
                                                    placeholder="Masukkan kode kelas">
                                                <!-- <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>"> -->
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="text-center">
                                                <div>
                                                    <i class="fa fa-user" style="font-size: 150px; color: #e5e5e5;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <h3>Untuk login menggunakan kode kelas</h3>
                                                <ul>
                                                    <li>Gunakan akun yang diberi otoritas</li>
                                                    <li>Gunakan kode kelas yang terdiri dari 5-7 huruf atau angka,
                                                        tanpa spasi atau simbol</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-10 text-center">
                                                <button type="submit" class="btn btn-primary">Gabung</button>
                                            </div>
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

    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
</body>