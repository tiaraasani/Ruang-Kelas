<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_pengumuman = $_POST['id_pengumuman'];
    $isi_pengumuman = $_POST['isi_pengumuman'];
    $id_kelas = $_POST['id_kelas'];


    // Cek apakah isi_pengumuman tidak kosong
    if (!empty($isi_pengumuman)) {
        // Query untuk melakukan update data pengumuman
        $query = "UPDATE pengumuman SET isi_pengumuman='$isi_pengumuman' WHERE id_pengumuman='$id_pengumuman'";

        // Jalankan query dan periksa apakah berhasil
        if (mysqli_query($koneksi, $query)) {
            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas&pesan=edit_berhasil';</script>";
            exit();
        } else {
            // Menampilkan error dari MySQL jika query gagal
            echo "Error updating record: " . mysqli_error($koneksi);
            // Redirect kembali ke halaman sebelumnya dengan pesan error jika gagal
            echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas&pesan=edit_gagal';</script>";
        }
    } else {
        // Jika tidak ada isi_pengumuman, redirect dengan pesan bahwa tidak ada perubahan yang dilakukan
        echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas&pesan=tidak_ada_perubahan';</script>";
    }
} else {
    // Jika tidak disubmit melalui form, redirect ke halaman form_kelas
    echo "<script>window.location.href = 'form_kelas.php?id=$id_kelas';</script>";
    exit();
}
?>