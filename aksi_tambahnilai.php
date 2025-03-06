<?php
include 'koneksi.php'; // Sertakan file koneksi ke database
// Ambil nilai-nilai dari form
$id_kumpul = $_POST['id_kumpul'] ?? '';
$nilai = $_POST['nilai'] ?? '';
$redirect_url = $_POST['redirect_url'] ?? '';

if (isset($_POST['hapus_semua']) && $_POST['hapus_semua']) {

    $id_kumpul = implode(",", $_POST['id_kumpul']);

    $insert_query = "DELETE FROM nilai WHERE id_kumpul IN ($id_kumpul) ";

    $delete_result = mysqli_query($koneksi, $insert_query);

    header("Location: $redirect_url");
    exit();

}


// Hitung jumlah data yang dikirim
$jumlah_data = count($id_kumpul);

$nilai_sudah_diinput = false;

// Loop untuk setiap baris data
for ($i = 0; $i < $jumlah_data; $i++) {
    // Ambil nilai id_kumpul dan nilai
    $id_kumpul_baris = $id_kumpul[$i];
    $nilai_baris = $nilai[$i];

    // Cek apakah nilai sudah diinput sebelumnya
    $cek_nilai_query = "SELECT * FROM nilai WHERE id_kumpul = '$id_kumpul_baris'";
    $cek_nilai_result = mysqli_query($koneksi, $cek_nilai_query)->fetch_assoc();

    if ($cek_nilai_result && $cek_nilai_result['angka_nilai'] > 0) {
        $nilai_sudah_diinput = true;
        continue;
    }

    if ($cek_nilai_result) {

        // Lakukan operasi insert
        $update_query = "UPDATE nilai SET angka_nilai = $nilai_baris WHERE id_kumpul = ".$cek_nilai_result['id_kumpul'];
        $update_result = mysqli_query($koneksi, $update_query);

        // Periksa apakah insert berhasil
        if (!$update_result) {
            // Jika terjadi kesalahan, tampilkan pesan error
            echo "Error: " . mysqli_error($koneksi);
            // Hentikan loop dan hentikan proses
            break;
        }
    } else {
        // Lakukan operasi insert
        $insert_query = "INSERT INTO nilai (id_kumpul, angka_nilai) VALUES ('$id_kumpul_baris', '$nilai_baris')";
        $insert_result = mysqli_query($koneksi, $insert_query);

        // Periksa apakah insert berhasil
        if (!$insert_result) {
            // Jika terjadi kesalahan, tampilkan pesan error
            echo "Error: " . mysqli_error($koneksi);
            // Hentikan loop dan hentikan proses
            break;
        }
    }

}

header("Location: $redirect_url");
exit();
?>