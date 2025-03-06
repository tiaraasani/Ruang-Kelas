<?php
include "koneksi.php";

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data yang dikirimkan dari form
    $id_materi = $_POST['id_materi'];
    $judul_materi = $_POST['judulMateri'];
    $deskripsi = $_POST['deskripsi'];
    $id_kelas = $_POST['id_kelas'];

    $update_fields = [];

    // Cek apakah judul_materi tidak kosong
    if (!empty($judul_materi)) {
        $update_fields[] = "judul_materi='$judul_materi'";
    }

    // Cek apakah deskripsi tidak kosong
    if (!empty($deskripsi)) {
        $update_fields[] = "deskripsi='$deskripsi'";
    }

    // Jika ada field yang akan diupdate
    if (!empty($update_fields)) {
        // Gabungkan semua field yang akan diupdate
        $update_query = implode(", ", $update_fields);

        // Query untuk melakukan update data materi
        $query = "UPDATE materi SET $update_query WHERE id_materi='$id_materi'";

        // Jalankan query dan periksa apakah berhasil
        if (mysqli_query($koneksi, $query)) {
            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas&pesan=edit_berhasil';</script>";
            exit();
        } else {
            // Redirect kembali ke halaman sebelumnya dengan pesan error jika gagal
            echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas&pesan=edit_gagal';</script>";
        }
    } else {
        // Jika tidak ada field yang diupdate, redirect dengan pesan bahwa tidak ada perubahan yang dilakukan
        echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas&pesan=tidak_ada_perubahan';</script>";
    }
} else {
    // Jika tidak disubmit melalui form, redirect ke halaman form_kelas
    $id_kelas = $_POST['id_kelas'];
    echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas';</script>";
    exit();
}
?>