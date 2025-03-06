<?php
include "koneksi.php";
session_start();
$kodeKelas = $_POST['kodeKelas'];

// Mengambil data kelas berdasarkan kodeKelas yang diberikan
$result_kelas = mysqli_query($koneksi, "SELECT * FROM kelas WHERE kodeKelas = '$kodeKelas'");
$kelas = mysqli_fetch_assoc($result_kelas);

if (!$kelas) {
    // Jika kelas tidak ditemukan, tampilkan pesan kesalahan
    echo "Kelas tidak ditemukan";
} else {
    $id_kelas = $kelas['id_kelas'];
    $user = $_SESSION['username'];

    // Periksa apakah pengguna sudah terdaftar dalam kelas
    $result_daftar = mysqli_query($koneksi, "SELECT * FROM daftar_kelas WHERE id_kelas = '$id_kelas' AND username = '$user'");
    $existing_entry = mysqli_fetch_assoc($result_daftar);
    if ($existing_entry) {
        // Jika pengguna sudah terdaftar dalam kelas, tampilkan pesan bahwa mereka sudah bergabung
        echo "Anda sudah bergabung";
    } else {
        // Jika pengguna belum terdaftar dalam kelas, masukkan mereka ke dalam tabel daftar_kelas
        $query = "INSERT INTO daftar_kelas (id_kelas, username) VALUES ('$id_kelas', '$user')";
        $a = $koneksi->query($query);
        if ($a === true) {
            header('location: form_kelas.php?id='.$id_kelas);
        } else {
            echo "Error";
        }
    }
}
?>
