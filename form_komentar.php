<?php
include "navbar.php";

$user = $_SESSION['username'];
$query_komentar = "SELECT * FROM komentar WHERE username = '$username'";
$result_komentar = mysqli_query($koneksi, $query_komentar);
$id_role = $_SESSION['id_role'];



if ($id_role == 2) {
    $result_kelas = mysqli_query($koneksi, "SELECT kelas.id_kelas, kelas.namakelas FROM kelas JOIN daftar_kelas ON kelas.id_kelas = daftar_kelas.id_kelas WHERE daftar_kelas.username = '$user'"); // ambil semua data kelas
} else {
    $result_kelas = mysqli_query($koneksi, "SELECT * FROM kelas where username = '$user'");

}
$result_kelas = $result_kelas->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM tugas WHERE id_kelas = '$id' ";
    $result_tugas = mysqli_query($koneksi, $query);

} else if ($result_kelas) {
    $id = $result_kelas[0]['id_kelas']; //ambil id pada baris array pertama

    $query = "SELECT * FROM tugas WHERE id_kelas = '$id' ";
    $result_tugas = mysqli_query($koneksi, $query);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>komentar</title>
</head>

<body>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " style="margin-top: 20px;" href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <div class="input-group-btn col-lg-2" style="margin-top: 15px;">
                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">Daftar
                        Kelas
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <?php

                        foreach ($result_kelas as $index => $kelas) {
                            echo '<li><a href="form_komentar.php?id=' . $kelas["id_kelas"] . '">' . $kelas["namakelas"] . '</a></li>';
                        }

                        ?>
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
            </nav>
        </div>
        <div class="wrapper wrapper-content">

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Komentar</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="feed-activity-list">
                                <?php
                                $no = 1;
                                foreach ($result_tugas as $index => $tugas) {
                                    // Query untuk mendapatkan semua komentar
                                    $id_tugas = $tugas['id_tugas'];
                                    $query_komentar = "SELECT * FROM komentar WHERE id_tugas = '$id_tugas'";
                                    $result_komentar = mysqli_query($koneksi, $query_komentar);
                                    echo '<h3 class="font-weight-bold"> ' . $no . '. ' . $tugas['judul_tugas'] . '</h3>';
                                    echo '<table class="table table-bordered table-hover">';
                                    echo '<thead>';
                                    echo '<tr>';
                                    echo '<th>Username</th>';
                                    echo '<th>Komentar</th>';
                                    echo '<th>Tanggal</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                    if (mysqli_num_rows($result_komentar) > 0) {
                                        while ($komentar = mysqli_fetch_assoc($result_komentar)) {
                                            echo '<tr>';
                                            echo '<td><strong>' . $komentar["username"] . '</strong></td>';
                                            echo '<td>' . $komentar["isi_komentar"] . '</td>';
                                            echo '<td class="text-muted">' . $komentar["tanggal"] . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="3" class="text-center">Tidak ada komentar.</td></tr>';
                                    }
                                    echo '</tbody>';
                                    echo '</table>';
                                    $no++;
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>