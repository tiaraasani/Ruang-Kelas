<?php
include "koneksi.php";

// Periksa apakah metode yang digunakan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $isi = $_POST['isi_pengumuman'];
    $id_kelas = $_POST["id_kelas"];

    // Buat query untuk memasukkan data ke database
    $sql = "INSERT INTO pengumuman (isi_pengumuman, tanggal_pengumuman, id_kelas) VALUES ('$isi', NOW(), '$id_kelas')";

    // Jalankan query
    if (mysqli_query($koneksi, $sql)) {
        // Jika query berhasil, redirect ke halaman form_kelas.php dengan ID kelas
        header('location: form_kelas.php?id=' . $id_kelas);
    } else {
        // Jika terjadi kesalahan, tampilkan pesan kesalahan
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }
} else {
    // Jika bukan metode POST, tampilkan pesan kesalahan
    echo "Permintaan tidak valid.";
}

// Tutup koneksi ke database
mysqli_close($koneksi);
?>
