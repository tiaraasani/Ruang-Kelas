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
    $id_tugas = $_POST['id_tugas'];
    $id_kelas = $_POST['id_kelas'];

    // Tangkap data file yang diunggah
    $file_name = $_FILES["fileUploadKumpul"]["name"];
    $file_tmp = $_FILES["fileUploadKumpul"]["tmp_name"];
    $file_size = $_FILES["fileUploadKumpul"]["size"];
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

            // Cek apakah data sudah ada di tabel pengumpulan
            $cek_query = "SELECT * FROM pengumpulan WHERE id_tugas = '$id_tugas' AND username = '$username'";
            $cek_result = mysqli_query($koneksi, $cek_query);

            if (mysqli_num_rows($cek_result) > 0) {
                // Data sudah ada, lakukan update
                $update_query = "UPDATE pengumpulan SET file_kumpul = '$file_path', tanggal_kumpul = NOW() 
                                 WHERE id_tugas = '$id_tugas' AND username = '$username'";
                $update_result = mysqli_query($koneksi, $update_query);

                if ($update_result) {
                    // Redirect ke halaman form_kelas.php setelah sukses mengunggah materi
                    header('Location: form_kelas.php?id=' . $id_kelas);
                    exit();
                } else {
                    echo "Error: " . $update_query . "<br>" . mysqli_error($koneksi);
                }
            } else {
                // Data belum ada, lakukan insert
                $insert_query = "INSERT INTO pengumpulan (id_tugas, file_kumpul, tanggal_kumpul, username) 
                                 VALUES ('$id_tugas', '$file_path', NOW(), '$username')";
                $insert_result = mysqli_query($koneksi, $insert_query);

                if ($insert_result) {
                    // Redirect ke halaman form_kelas.php setelah sukses mengunggah materi
                    header('Location: form_kelas.php?id=' . $id_kelas);
                    exit();
                } else {
                    echo "Error: " . $insert_query . "<br>" . mysqli_error($koneksi);
                }
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
