<?php
// Masukkan file koneksi database
include "koneksi.php";

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah pengguna sudah login
    session_start();
    if (!isset($_SESSION['username'])) {
        // Jika pengguna tidak login, alihkan ke halaman login atau tampilkan pesan kesalahan
        echo "Anda tidak memiliki izin untuk mengunggah materi. Silakan login terlebih dahulu.";
        exit;
    }

    // Tangkap data yang dikirimkan dari formulir
    $id_kelas = $_POST['id_kelas'];
    $judul_materi = $_POST['judulMateri'];
    $deskripsi = $_POST['deskripsi'];

    // Tangkap data file yang diunggah
    $file_name = $_FILES["fileUpload"]["name"];
    $file_tmp = $_FILES["fileUpload"]["tmp_name"];
    $file_size = $_FILES["fileUpload"]["size"];
    $file_path = "uploads/" . $file_name; // Path untuk menyimpan file

    // Validasi ukuran file
    if ($file_size > 2 * 1024 * 1024) { // 2MB dalam byte
        echo "Ukuran file melebihi batas maksimum (2MB).";
    } else {
        // Simpan file ke direktori yang ditentukan
        if (move_uploaded_file($file_tmp, $file_path)) {
            // File berhasil diunggah, lanjutkan dengan penambahan data ke dalam tabel materi

            // Ambil username pengguna yang sedang login dari sesi
            $username = $_SESSION['username'];

            // Query SQL untuk memasukkan data ke dalam tabel materi
            $query = "INSERT INTO materi (username, id_kelas, judul_materi, deskripsi, file) 
                      VALUES ('$username', '$id_kelas', '$judul_materi', '$deskripsi', '$file_path')";
            
            // Jalankan query
            if (mysqli_query($koneksi, $query)) {
                // Redirect ke halaman form_kelas.php setelah sukses mengunggah materi
                header('Location: form_kelas.php?id=' . $id_kelas);
                exit();
            } else {
                // Jika terjadi kesalahan, tampilkan pesan kesalahan
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
