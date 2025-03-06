<?php
session_start();
include "koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
if (!isset($_SESSION['username'])) {
    die("Anda belum login");
}
$key = $_GET['cari'];
$no = 1;
$query = "SELECT * FROM minuman,jenis_minuman where minuman.id_jenis=jenis_minuman.id_jenis and
jenis_minuman.merek LIKE '%$key%'";
$dt_query = $koneksi->query($query);
?>