<?php
session_start();
include "koneksi.php";
$user = $_POST['username'];
$psw = $_POST['password'];
$op = $_GET['op'];

if ($op == "in") {
    $sql = "SELECT * FROM user where username='$user' AND password='$psw'";
    $query = $koneksi->query($sql);
    if (mysqli_num_rows($query) == 1) {
        $data = $query->fetch_array();
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_role'] = $data['id_role'];
        if ($data['id_role'] == "1") {
            header("location: form_basic.php");
        } else if ($data['id_role'] == "2") {
            header('location: form_basic.php');
        } else {
            die("password salah <a href=\"javascript:history.back()\">kembali</a>");
        }
    }
} else if ($op == "out") {
    unset($_SESSION['username']);
    unset($_SESSION['id_role']);
    header('location:login.php');
}
?>