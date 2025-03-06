<?php
// Masukkan file koneksi database
include "koneksi.php";

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah pengguna sudah login
    session_start();
    if (!isset($_SESSION['username'])) {
        // Jika pengguna tidak login, alihkan ke halaman login atau tampilkan pesan kesalahan
        echo "Anda tidak memiliki izin untuk menambahkan tugas. Silakan login terlebih dahulu.";
        exit;
    }

    // Tangkap data yang dikirimkan dari formulir
    $id_kelas = $_POST['id_kelas'];
    $judul_tugas = $_POST['judulTugas'];
    $deskripsi_tugas = $_POST['deskripsiTugas'];

    // Tangkap data file yang diunggah
    $file_name = $_FILES["fileUploadTugas"]["name"];
    $file_tmp = $_FILES["fileUploadTugas"]["tmp_name"];
    $file_size = $_FILES["fileUploadTugas"]["size"];
    $file_path = "uploadTugas/" . $file_name; // Path untuk menyimpan file

    // Validasi ukuran file
    if ($file_size > 2 * 1024 * 1024) { // 2MB dalam byte
        echo "Ukuran file melebihi batas maksimum (2MB).";
    } else {
        // Simpan file ke direktori yang ditentukan
        if (move_uploaded_file($file_tmp, $file_path)) {
            // File berhasil diunggah, lanjutkan dengan penambahan data ke dalam tabel tugas

            // Ambil username pengguna yang sedang login dari sesi
            $username = $_SESSION['username'];

            // Query SQL untuk memasukkan data ke dalam tabel tugas
            $query = "INSERT INTO tugas (judul_tugas, deskripsi_tugas, id_kelas, deadline, file_tugas, tanggal_upload, username) 
                      VALUES ('$judul_tugas', '$deskripsi_tugas', '$id_kelas', NOW(), '$file_path', NOW(), '$username')";

            // Jalankan query
            if (mysqli_query($koneksi, $query)) {
                // Redirect ke halaman form_kelas.php setelah sukses mengunggah materi
                header('Location: form_kelas.php?id=' . $id_kelas);
                exit();
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
} else {
    // Jika bukan metode POST, tampilkan pesan kesalahan
    echo "Permintaan tidak valid.";
}
?>