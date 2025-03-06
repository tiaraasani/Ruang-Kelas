<?php
session_start();
include "koneksi.php";

$redirect_url_tugas = $_POST['redirect_url_tugas'] ?? '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isi_komentar = $_POST['isi_komentar'];
    $id_tugas = $_POST['id_tugas'];
    $username = $_SESSION['username'];
    $tanggal = $_POST['tanggal'];


    // Jalankan query untuk memasukkan komentar
    $query = "INSERT INTO komentar (isi_komentar, id_tugas, username, tanggal) VALUES ('$isi_komentar', '$id_tugas', '$username',NOW())";
    $result = mysqli_query($koneksi, $query); // Menjalankan query

    if ($result) {
        // Redirect ke halaman redirect_url_tugas
        header("Location: $redirect_url_tugas");
        exit();
    } else {
        echo "Gagal menambahkan komentar: " . mysqli_error($koneksi);
    }
}
?>