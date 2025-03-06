<?php
session_start();
include "koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if (!isset($_SESSION['username'])) {
    die("Anda belum login");
}
$user = $_SESSION['username'];
$id_role = $_SESSION['id_role'];
$id_daftar = $_SESSION['id_daftar'];
?>

<head>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <link href="css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <!-- Morris -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="logo-element">
                            IN+
                        </div>
                    </li>
                    <li>
                        <a href="form_basic.php"><i class="fa fa-diamond"></i> <span
                                class="nav-label">Beranda</span></a>
                    </li>
                    <?php

                    if ($id_role == 1) {
                        ?>

                        <li>
                            <a href="#">
                                <i class="fa fa-th-large"></i>
                                <span class="nav-label">Kelas</span>
                                <span class="fa arrow"></span></a>
                            <!-- Beranda -->
                            <ul class="nav nav-second-level collapse">
                                <li><a href="form_buatkelas.php">Buat Kelas</a></li>
                                <?php
                                $result = mysqli_query($koneksi, "SELECT * FROM kelas where username = '$user'"); // Mengambil semua data kelas dari database
                                while ($row = mysqli_fetch_assoc($result)) { // Loop melalui hasil query dan tampilkan tautan untuk setiap kelas
                                    // Menambahkan parameter ID kelas ke URL dengan mengambil nilai dari kolom id_kelas
                                    echo '<li><a href="form_kelas.php?id=' . $row["id_kelas"] . '">' . $row["namakelas"] . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>

                    <?php }
                    if ($id_role == 2) {
                        ?>
                        <li>
                            <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Kelas</span> <span
                                    class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="form_daftarkelas.php">Daftar ke Kelas</a></li>
                                <?php
                                $result = mysqli_query($koneksi, "SELECT kelas.id_kelas, kelas.namakelas FROM kelas JOIN daftar_kelas ON kelas.id_kelas = daftar_kelas.id_kelas WHERE daftar_kelas.username = '$user'");
                                while ($row = mysqli_fetch_assoc($result)) { // Loop melalui hasil query dan tampilkan tautan untuk setiap kelas
                                    // Menambahkan parameter ID kelas ke URL dengan mengambil nilai dari kolom id_kelas
                                    echo '<li><a href="form_kelas.php?id=' . $row["id_kelas"] . '">' . $row["namakelas"] . '</a></li>';
                                }
                                ?>
                            </ul>

                        </li>
                    <?php } ?>
                    <li>
                        <a href="form_komentar.php"><i class="fa fa-diamond"></i> <span
                                class="nav-label">Komentar</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/curvedLines.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Jvectormap -->
    <script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>

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