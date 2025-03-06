<?php
include "navbar.php";
$isi_pengumuman = $_POST['isi_pengumuman'];
$tanggal_pengumuman = $_POST['tanggal_pengumuman'];
$id_kelas = $_POST['id_kelas'];
$username = $_SESSION['username'];
$id_kelas = $_POST['id_kelas'];

$query = "INSERT INTO pengumuman (isi_pengumuman, tanggal_pengumuman, username) VALUES ('$isi_pengumuman', now(), '$username') ";
if (mysqli_query($koneksi, $query)) {
    header('Location: form_kelas.php?id=' . $id_kelas);
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
}
?>