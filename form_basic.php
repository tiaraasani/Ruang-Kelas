<?php
include "navbar.php";
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Basic Form</title>

</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">

            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i>
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
                    <h1>Beranda </h1>
                    <ol class="breadcrumb">
                        <li>
                            <a href="form_basic.php">Home</a>
                        </li>
                        <li class="active">
                            <strong><?php echo $namakelas; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <?php

                    if ($id_role == 1) {
                        $result = mysqli_query($koneksi, "SELECT * FROM kelas where username = '$user' "); // Mengambil semua data kelas dari database
                    }
                    if ($id_role == 2) {
                        $result = mysqli_query($koneksi, "SELECT kelas.id_kelas, kelas.namakelas, kelas.mapel FROM kelas JOIN daftar_kelas ON kelas.id_kelas = daftar_kelas.id_kelas WHERE daftar_kelas.username = '$user'");
                    }
                    while ($row = mysqli_fetch_assoc($result)) { // Loop melalui hasil query dan tampilkan tautan untuk setiap kelas
                        echo '
            <div class="col-md-3">
                <div class="ibox">
                    <div class="ibox-content product-box">
                        <div class="product-desc">
                            <a href="form_kelas.php?id=' . $row["id_kelas"] . '" class="product-name">' . $row["namakelas"] . '</a> <!-- Menampilkan nama kelas -->
                            <div class="small m-t-xs">
                                Mata Pelajaran: ' . $row["mapel"] . ' <!-- Menampilkan mata pelajaran -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</body>
</html>