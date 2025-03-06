<?php
include "koneksi.php";

session_start();

$namaKelas = $_POST['namaKelas'];
$mapel = $_POST['mapel'];
$kodeKelas = $_POST['kodeKelas'];
$user = $_SESSION['username'];

$sql = "INSERT INTO kelas (namaKelas, mapel, kodeKelas, username) VALUES ('" . $namaKelas . "', '" . $mapel . "', '" . $kodeKelas . "', '" . $user. "')";

$query = $koneksi->query($sql);
if ($query === true) {
    header('location: form_buatkelas.php');
} else {
    echo "eror";
}
?>