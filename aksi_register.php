<?php
include "koneksi.php";
$user = $_POST['username'];
$nama = $_POST['nama'];
$psw = $_POST['password'];
$id_role = $_POST['id_role'];
$sql = "INSERT INTO  user (username, nama, password, id_role) VALUES ('" . $user . "', '" . $nama . "',
    '" . $psw . "','" . $id_role . "')";
$query = $koneksi->query($sql);
if ($query === true) {
    header('location: form_login.php');
} else {
    echo "eror";
}
?>