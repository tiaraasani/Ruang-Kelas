<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id_pengumuman = $_POST['id_pengumuman'];
    $id_kelas = $_POST['id_kelas'];

    // Query untuk menghapus pengumuman dari database berdasarkan id_pengumuman
    $query = "DELETE FROM pengumuman WHERE id_pengumuman='$id_pengumuman'";

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        // Jika penghapusan berhasil, redirect ke halaman pengumuman dengan pesan sukses
        header("Location: form_kelas.php?id=$id_kelas");
                exit();
    } else {
        // Jika terjadi kesalahan, redirect ke halaman pengumuman dengan pesan gagal
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
        exit();
    }
} else {
    // Jika tidak ada parameter id_pengumuman, beri peringatan dan arahkan kembali ke halaman pengumuman
    echo "ID Pengumuman tidak diberikan.";
    // atau arahkan kembali ke halaman pengumuman
    // header("Location: halaman_pengumuman.php");
    exit();
}
?>