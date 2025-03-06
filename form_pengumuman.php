<?php
include "navbar.php";
// Mengambil ID kelas dari URL jika ada, atau menggunakan nilai default 0 jika tidak ada
$id_kelas = isset($_GET['id']) ? $_GET['id'] : 0;

// Mengambil data kelas berdasarkan ID kelas
$result_kelas = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = $id_kelas");
$kelas = mysqli_fetch_assoc($result_kelas);
$namakelas = $kelas['namakelas']; // Menyimpan nama kelas untuk digunakan sebagai judul halaman
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman</title>

    <link href="css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote-bs3.css" rel="stylesheet">

    <link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">

</head>

<body>
    <div>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="container-fluid d-flex align-items-center justify-content-between">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " style="margin-top: 20px;"
                                href="#">
                                <i class="fa fa-bars"></i>
                            </a>
                        </div>
                        <div class="input-group-btn col-lg-2" style="margin-top: 15px;">
                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"
                                type="button">Daftar Kelas
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li>
                                    <?php
                                    $result = mysqli_query($koneksi, "SELECT * FROM kelas"); // Mengambil semua data kelas dari database
                                    while ($row = mysqli_fetch_assoc($result)) { // Loop melalui hasil query dan tampilkan tautan untuk setiap kelas
                                        // Menambahkan parameter ID kelas ke URL dengan mengambil nilai dari kolom id_kelas
                                        echo '<li><a href="form_pengumuman.php?id=' . $row["id_kelas"] . '">' . $row["namakelas"] . '</a></li>';
                                    }
                                    ?>
                                </li>
                            </ul>
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
                    </div>
                </nav>
            </div>

            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Kontainer untuk Pengumuman -->
                        <div class="ibox float-e-margins">

                            <div class="row wrapper border-bottom white-bg page-heading">
                                <div class="col-lg-12">
                                    <h1>Pengumuman</h1>
                                    <h3><?php echo $namakelas; ?></h3>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="wrapper wrapper-content animated fadeInRight ecommerce">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tabs-container">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SUMMERNOTE -->
    <script src="js/plugins/summernote/summernote.min.js"></script>

    <!-- Data picker -->
    <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function () {

            $('.summernote').summernote();

            $('.input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });
    </script>
</body>

</html>