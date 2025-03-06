<?php
include "navbar.php";
// Mengambil ID kelas dari URL jika ada, atau menggunakan nilai default 0 jika tidak ada
$id_kelas = isset($_GET['id']) ? $_GET['id'] : 0;
$id_tugas = isset($_GET['id_tugas']) ? $_GET['id_tugas'] : 0;

// Mengambil data kelas berdasarkan ID kelas
$result_kelas = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = $id_kelas");
$kelas = mysqli_fetch_assoc($result_kelas);
$namakelas = $kelas['namakelas']; // Menyimpan nama kelas untuk digunakan sebagai judul halaman

$user = $_SESSION['username'];
$kodekelas = $kelas['kodeKelas'];
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $namakelas; ?></title>

</head>

<body>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">RUANG KELAS</span>
                    </li>
                    <li>
                        <a href="aksi_logout.php">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Kelas / <?php echo $kodekelas; ?> </h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="form_basic.php">Home</a>
                    </li>
                    <li class="active">
                        <strong><?php echo $namakelas; ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
                <div class="pull-right">
                    <br>
                    <?php if ($id_role == 1) { ?>
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Tambah Konten <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-toggle="modal" data-target="#myModal">Tambah Materi</a></li>
                                <li><a href="#" data-toggle="modal" data-target="#myModalTambahTugas">Tambah Tugas</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="space-5"></div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class=" active"><a class="kelas-tab" data-toggle="tab" href="#tab-1"> Materi</a></li>
                            <li class=""><a class="kelas-tab" data-toggle="tab" href="#tab-2">Tugas</a></li>
                            <li class=""><a class="kelas-tab" data-toggle="tab" href="#tab-3">Pengumuman</a></li>
                            <li class=""><a class="kelas-tab" data-toggle="tab" href="#tab-4">Siswa</a></li>
                            <?php if ($id_role != 2) { ?>
                                <li class=""><a class="kelas-tab" data-toggle="tab" href="#tab-5">Nilai</a></li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">

                            <!-- tab materi-->
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <?php
                                    $result_materi = mysqli_query($koneksi, "SELECT * FROM materi WHERE id_kelas = '$id_kelas'");
                                    if (mysqli_num_rows($result_materi) > 0) {
                                        while ($materi = mysqli_fetch_assoc($result_materi)) {
                                            $nmfile = $materi['file']; // Nama file
                                            echo '<div class="ibox float-e-margins">';
                                            echo '    <div class="ibox-title">';
                                            echo '        <h5 class="font-weight-bold">' . $materi['judul_materi'] . '</h5>'; // Judul materi
                                            echo '    </div>';
                                            echo '    <div class="ibox-content">';
                                            echo '        <div class="row">';
                                            echo '            <div class="col-md-6">';
                                            echo '                <p><strong>Deskripsi:</strong></p>';
                                            echo '                <div class="well well-lg">' . nl2br($materi['deskripsi']) . '</div>'; // Deskripsi materi dalam div well well-lg
                                            echo '                <p><strong>Guru:</strong> <span class="text-primary">' . $user . '</span></p>'; // Nama pengguna yang mengunggah materi
                                            echo '                <p><strong>File:</strong> <a href="' . $nmfile . '">' . basename($nmfile) . '</a></p>'; // Nama file materi
                                            echo '                <a href="' . $nmfile . '" class="btn btn-primary btn-sm" download><i class="fa fa-download"></i> Unduh Materi</a>'; // Button download
                                            if ($id_role != 2) {
                                                echo '                <button class="btn btn-primary btn-sm edit-button" type="button" data-idmateri="' . $materi['id_materi'] . '" data-toggle="modal" data-target="#myModalEditMateri"><i class="fa fa-edit"></i> Edit Materi</button>'; // Button edit
                                                echo '                <button class="btn btn-danger btn-sm" type="button"><a href="aksi_delete_materi.php?id=' . $materi['id_materi'] . '" style="color: inherit; text-decoration: none;" onclick="return confirm(\'Apakah Anda yakin ingin menghapus materi ini?\');"><i class="fa fa-trash"></i> Hapus Materi</a></button>'; // Button delete
                                            }
                                            echo '            </div>';
                                            echo '        </div>';
                                            echo '    </div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p>Belum ada materi yang diunggah.</p>';
                                    }
                                    ?>
                                </div>
                            </div>



                            <!-- tab tugas-->
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <?php
                                    // Query untuk ambil data tugas dari database
                                    $result_tugas = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_kelas = '$id_kelas' ");
                                    if (mysqli_num_rows($result_tugas) > 0) {
                                        while ($tugas = mysqli_fetch_assoc($result_tugas)) {
                                            echo '<div class="ibox float-e-margins">';
                                            echo '    <div class="ibox-title">';
                                            echo '        <h5 class="font-weight-bold">' . $tugas['judul_tugas'] . '</h5>'; // Judul tugas
                                            echo '    </div>';
                                            echo '    <div class="ibox-content">';
                                            echo '        <div class="row">';
                                            echo '            <div class="col-md-6">';
                                            echo '                <p><strong>Deskripsi:</strong> <span class="text-muted">' . $tugas['deskripsi_tugas'] . '</span></p>'; // Deskripsi tugas
                                            echo '                <p><strong>Deadline:</strong> ' . $tugas['deadline'] . '</p>'; // Deadline tugas
                                            echo '                <p><strong>Guru:</strong> <span class="text-primary">' . $user . '</span></p>'; // Nama pengguna yang mengunggah tugas
                                            echo '                <p><strong>File:</strong> <a href="' . $tugas['file_tugas'] . '" class="text-primary">' . basename($tugas['file_tugas']) . '</a></p>'; // Nama file tugas
                                            echo '            </div>';
                                            if ($id_role != 1) {
                                                echo '            <div class="col-md-6">';
                                                echo '                <h5><strong>Tugas yang Dikumpulkan:</strong></h5>';
                                                $query_pengumpulan = mysqli_query($koneksi, "SELECT * FROM pengumpulan WHERE id_tugas = '{$tugas['id_tugas']}' AND username = '$user' ");
                                                if (mysqli_num_rows($query_pengumpulan) > 0) {
                                                    while ($pengumpulan = mysqli_fetch_assoc($query_pengumpulan)) {
                                                        $file_pengumpulan = $pengumpulan['file_kumpul'];
                                                        echo '<div class="p-2 mb-2 bg-light border rounded">';
                                                        echo '    <p><strong>File:</strong> <a href="' . $file_pengumpulan . '" class="text-primary">' . basename($file_pengumpulan) . '</a></p>';
                                                        echo '    <p><strong>Tanggal Kumpul:</strong> ' . $pengumpulan['tanggal_kumpul'] . '</p>';
                                                        if ($id_role == 2) {
                                                            echo '    <form action="aksi_tambahkomentar.php" method="POST">';
                                                            echo '        <input type="hidden" name="id_tugas" value="' . $tugas['id_tugas'] . '">';
                                                            echo '        <input type="hidden" name="id_kelas" value="' . $id_kelas . '">';
                                                            echo '        <input type="hidden" name="redirect_url_tugas" value="form_kelas.php?id=' . $id_kelas . '&id_tugas=' . $tugas['id_tugas'] . '#tab-2">';
                                                            echo '        <div class="input-group">';
                                                            echo '            <input type="text" name="isi_komentar" class="form-control" placeholder="Masukkan Komentar">';
                                                            echo '            <span class="input-group-btn">';
                                                            echo '                <button type="submit" class="btn btn-primary">Kirim</button>';
                                                            echo '            </span>';
                                                            echo '        </div>';
                                                            echo '    </form>';
                                                        }
                                                        echo '</div>';
                                                    }
                                                } else {
                                                    echo '                <p class="text-danger">Tidak ada tugas yang dikumpulkan.</p>';
                                                }
                                                echo '            </div>';
                                            }
                                            echo '        </div>';
                                            echo '        <div class="row mt-4">';
                                            echo '                <a href="' . $tugas['file_tugas'] . '" class="btn btn-primary btn-sm" download><i class="fa fa-download"></i> Download Tugas</a>'; // Button download
                                            if ($id_role != 1) {
                                                echo '                <button class="btn btn-success btn-sm kumpul-button" type="button" data-idtugas="' . $tugas['id_tugas'] . '" data-toggle="modal" data-target="#myModalKumpulTugas"><i class="fa fa-upload"></i> Kumpul Tugas</button>'; // Button kumpul tugas
                                            }
                                            if ($id_role != 2) {
                                                echo '                <button class="btn btn-info btn-sm edit-button" type="button" data-idtugas="' . $tugas['id_tugas'] . '" data-toggle="modal" data-target="#myModalEditTugas"><i class="fa fa-edit"></i> Edit Tugas</button>'; // Button edit
                                                echo '                <button class="btn btn-danger btn-sm" type="button" onclick="if(confirm(\'Apakah Anda yakin ingin menghapus tugas ini?\')) { window.location.href = \'aksi_delete_tugas.php?id=' . $tugas['id_tugas'] . '\'; }"><i class="fa fa-trash"></i> Delete Tugas</button>'; // Button delete
                                            }
                                            echo '            </div>';
                                            echo '        </div>';
                                            echo '    </div>';

                                        }
                                    } else {
                                        echo '<p>Belum ada tugas yang diunggah.</p>';
                                    }
                                    ?>
                                </div>
                            </div>



                            <!-- tab pengumuman-->
                            <div id="tab-3" class="tab-pane">
                                <div class="panel-body">
                                    <!-- Form input pengumuman -->
                                    <?php if ($id_role != 2) { ?>
                                        <form id="formPengumuman" method="POST" action="aksi_tambahpengumuman.php">
                                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                                            <div class="form-group">
                                                <label for="isiPengumuman">Isi Pengumuman</label>
                                                <textarea class="form-control" id="isi_pengumuman" name="isi_pengumuman"
                                                    rows="3" maxlength="500" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                        </form>
                                    <?php } ?>

                                    <?php
                                    // Query untuk ambil data pengumuman dari database
                                    $result_pengumuman = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE id_kelas = $id_kelas");

                                    if (mysqli_num_rows($result_pengumuman) > 0) {
                                        while ($pengumuman = mysqli_fetch_assoc($result_pengumuman)) {
                                            echo '<div class="ibox float-e-margins">';
                                            echo '    <div class="ibox-title">';
                                            echo '        <div>';
                                            echo '            <span class="pull-right text-right">';
                                            echo '                <small>Tanggal Pengumuman: ' . $pengumuman['tanggal_pengumuman'] . '</small>';
                                            echo '            </span>';
                                            echo '</br>';
                                            echo '                <textarea class="form-control" rows="3" readonly ">' . $pengumuman['isi_pengumuman'] . '</textarea>';
                                            echo '</br>';

                                            echo '        </div>';
                                            if ($id_role != 2) {
                                                echo '        <div class="ibox-tools">';
                                                echo '            <button class="btn btn-primary btn-sm edit-button" type="button" data-id_pengumuman="' . $pengumuman['id_pengumuman'] . '" data-isi_pengumuman="' . $pengumuman['isi_pengumuman'] . '" data-toggle="modal" data-target="#myModalEditPengumuman"><i class="fa fa-edit"></i> Edit Pengumuman</button>';
                                                echo '    <form action="aksi_hapuspengumuman.php" method="POST" style="display: inline-block;">';
                                                echo '        <input type="hidden" name="id_pengumuman" value="' . $pengumuman['id_pengumuman'] . '">';
                                                echo '        <input type="hidden" name="id_kelas" value="' . $pengumuman['id_kelas'] . '">';
                                                echo '        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus pengumuman ini?\');"><i class="fa fa-trash"></i> Hapus Pengumuman</button>';
                                                echo '    </form>';
                                                echo '</div>';
                                            }
                                            echo '    </div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p>Belum ada pengumuman yang ditambahkan.</p>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- tab siswa-->
                            <div id="tab-4" class="tab-pane">
                                <div class="panel-body">
                                    <?php
                                    $siswa = 2;
                                    $sql = "SELECT u.nama FROM user u JOIN daftar_kelas k on u.username = k.username WHERE k.id_kelas = '$id_kelas' AND u.id_role = $siswa";
                                    $result = $koneksi->query($sql);
                                    if ($result->num_rows > 0) {
                                        echo '<table class="table table-bordered">';
                                        echo '<thead>';
                                        echo '<tr>';
                                        echo '<th>Nama Siswa</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        $no = 1;

                                        // Output data dari setiap baris
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>' . $no . '. ' . $row['nama'] . '</td>';
                                            echo '</tr>';
                                            $no++;

                                        }

                                        echo '</tbody>';
                                        echo '</table>';
                                    } else {
                                        echo '<p>Belum ada data siswa.</p>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- tab nilai-->
                            <div id="tab-5" class="tab-pane">
                                <div class="panel-body">
                                    <div class="input-group-btn col-lg-2" style="margin-top: 15px;">
                                        <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"
                                            type="button">Daftar Tugas
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php
                                            $result_tugas = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_kelas = $id_kelas");

                                            while ($tugas = mysqli_fetch_assoc($result_tugas)) {
                                                // Menambahkan parameter ID tugas ke URL dengan mengambil nilai dari kolom id_tugas
                                                echo '<li><a href="form_kelas.php?id=' . $id_kelas . '&id_tugas=' . $tugas["id_tugas"] . '#tab-5">' . $tugas["judul_tugas"] . '</a></li>';
                                            }
                                            $result_nilai = mysqli_query($koneksi, "SELECT * FROM nilai_siswa WHERE id_tugas = $id_tugas");

                                            ?>
                                        </ul>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <form method="POST" action="aksi_tambahnilai.php">
                                                <input type="hidden" name="redirect_url"
                                                    value="<?php echo 'form_kelas.php?id=' . $id_kelas . '&id_tugas=' . $id_tugas . '#tab-5'; ?>">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Siswa </th>
                                                            <th>Tugas </th>
                                                            <th>Tanggal Kumpul </th>
                                                            <th>File </th>
                                                            <th>nilai </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $no = 1;
                                                        foreach ($result_nilai as $row) {
                                                            $isDisabled = $row['angka_nilai'] ? 'readonly' : ''; //agar nilainya ga berubah
                                                            echo "<tr>";
                                                            echo "<td>" . $no++ . "</td>";
                                                            echo "<td>" . $row['nama_siswa'] . "</td>";
                                                            echo "<td>" . $row['judul_tugas'] . "</td>";
                                                            echo "<td>" . $row['tanggal_kumpul'] . "</td>";
                                                            echo "<td><a href='" . $row['file_kumpul'] . "' target='_blank'>" . basename($row['file_kumpul']) . "</a></td>";
                                                            echo "<td>
                                                            <input type='hidden' name='id_kumpul[]' value='" . $row['id_kumpul'] . "'>
                                                            <input type='text' name='nilai[]' value='" . $row['angka_nilai'] . "' min='0' max='100' placeholder='Masukkan nilai' $isDisabled>
                                                          </td>";
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Simpan Semua
                                                        Nilai</button>
                                                    <button type="submit" class="btn btn-primary" id="hapus_semua"
                                                        name="hapus_semua" value="hapus_semua">Hapus Semua
                                                        Nilai</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- iCheck -->
        <script src="js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });

                // Reset form saat modal tambah materi ditampilkan
                $('#myModal').on('show.bs.modal', function (e) {
                    $('#tambahmateri')[0].reset();
                });

                // Validasi ukuran file sebelum mengirimkan form tambah materi
                $('#tambahmateri').on('submit', function (e) {
                    var fileSize = $('#fileUpload')[0].files[0].size;
                    if (fileSize > 2097152) { // 2MB dalam byte
                        alert('Ukuran file maksimal adalah 2MB.');
                        e.preventDefault();
                    }
                });

                // Reset form saat modal tambah tugas ditampilkan
                $('#myModalTambahTugas').on('show.bs.modal', function (e) {
                    $('#tambahtugas')[0].reset();
                });

                // Validasi ukuran file sebelum mengirimkan form tambah tugas
                $('#tambahtugas').on('submit', function (e) {
                    var fileSize = $('#fileUploadTugas')[0].files[0].size;
                    if (fileSize > 2097152) { // 2MB dalam byte
                        alert('Ukuran file maksimal adalah 2MB.');
                        e.preventDefault();
                    }
                });
            });
        </script>

        <!-- Modal kumpul tugas -->
        <div class="modal fade" id="myModalKumpulTugas" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabelKumpulTugas" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="myModalLabelKumpulTugas">Kumpul Tugas</h1>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="aksi_kumpultugas.php">
                            <input type="hidden" id="kumpul_id_tugas" name="id_tugas" value="<?php echo $id_tugas; ?>">
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">

                            <div class="form-group">
                                <label for="fileUploadKumpul">Upload File</label>
                                <input type="file" class="form-control" id="fileUploadKumpul" name="fileUploadKumpul"
                                    required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Kumpulkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal tambah tugas -->
        <div class="modal fade" id="myModalTambahTugas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="myModalLabelTambahTugas">Tambah Tugas</h1>
                    </div>
                    <div class="modal-body">
                        <form id="tambahtugas" method="POST" action="aksi_tambahtugas.php"
                            enctype="multipart/form-data">
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                            <div class="form-group">
                                <label for="judulTugas">Judul Tugas</label>
                                <input type="text" class="form-control" id="judulTugas" name="judulTugas" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsiTugas">Deskripsi Tugas</label>
                                <textarea class="form-control" id="deskripsiTugas" name="deskripsiTugas" maxlength="500"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="deadline">Deadline</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" required>
                            </div>
                            <div class="form-group">
                                <label for="fileUploadTugas">Upload File</label>
                                <input type="file" class="form-control" id="fileUploadTugas" name="fileUploadTugas"
                                    required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Posting</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal tambah materi -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="myModalLabel">Tambah Materi</h1>
                    </div>
                    <div class="modal-body">
                        <form id="tambahmateri" method="POST" action="aksi_tambahmateri.php"
                            enctype="multipart/form-data">
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">

                            <div class="form-group">
                                <label for="judulMateri">Judul Materi</label>
                                <input type="text" class="form-control" id="judulMateri" name="judulMateri" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Materi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" maxlength="500"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="fileUpload">Upload File</label>
                                <input type="file" class="form-control" id="fileUpload" name="fileUpload" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Posting</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Script untuk menghitung karakter yang tersisa
            document.getElementById('deskripsi').addEventListener('input', function () {
                var maxLength = this.getAttribute('maxlength');
                var currentLength = this.value.length;
                var charCount = maxLength - currentLength;
                document.getElementById('charCount').textContent = charCount;
            });
            document.getElementById('isi_pengumuman').addEventListener('input', function () {
                var maxLength = this.getAttribute('maxlength');
                var currentLength = this.value.length;
                var charCount = maxLength - currentLength;
                document.getElementById('charCount').textContent = charCount;
            });
        </script>



        <!-- Modal edit materi -->
        <div class="modal fade" id="myModalEditMateri" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabelEditMateri" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="myModalLabelEditMateri">Edit Materi</h1>
                    </div>
                    <div class="modal-body">
                        <form id="editmateri" method="POST" action="aksi_editmateri.php">
                            <input type="hidden" name="id_materi" id="edit_id_materi">
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">

                            <div class="form-group">
                                <label for="judulMateri">Judul Materi</label>
                                <input type="text" class="form-control" id="judulMateri" name="judulMateri">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Materi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal edit tugas -->
        <div class="modal fade" id="myModalEditTugas" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabelEditTugas" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="myModalLabelEditTugas">Edit Tugas</h1>
                    </div>
                    <div class="modal-body">
                        <form id="edittugas" method="POST" action="aksi_edittugas.php">
                            <input type="hidden" name="id_tugas" id="edit_id_tugas">
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">

                            <div class="form-group">
                                <label for="judulTugas">Judul Tugas</label>
                                <input type="text" class="form-control" id="judulTugas" name="judulTugas">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Tugas</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal edit pengumuman -->
        <div class="modal fade" id="myModalEditPengumuman" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabelEditPengumuman" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="myModalLabelEditPengumuman">Edit Pengumuman</h1>
                    </div>
                    <div class="modal-body">
                        <form id="editpengumuman" method="POST" action="aksi_editpengumuman.php">
                            <input type="hidden" name="id_pengumuman" id="edit_id_pengumuman">
                            <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">

                            <div class="form-group">
                                <label for="isi_pengumuman">Isi Pengumuman</label>
                                <input type="text" class="form-control" id="isi_pengumuman" name="isi_pengumuman"
                                    maxlength="500">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function setReadOnly() {
                // Mengambil semua elemen input dengan name 'nilai[]'
                var inputs = document.getElementsByName('nilai[]');

                // Mengatur atribut 'readonly' dan 'placeholder' untuk setiap elemen input
                inputs.forEach(function (input) {
                    input.setAttribute('readonly', 'readonly'); // Mengatur input menjadi readonly
                    input.setAttribute('placeholder', input.value); // Menetapkan nilai saat ini sebagai placeholder
                });
            }
        </script>



        <!--unttuk ambil id materi-->
        <script>
            $(document).ready(function () {
                $('.edit-button').click(function () {
                    var idMateri = $(this).data('idmateri'); // Mengambil id materi dari atribut data

                    // Menetapkan nilai ke input tersembunyi di dalam modal edit
                    $('#edit_id_materi').val(idMateri);
                });
            });
        </script>

        <!--unttuk ambil id pengumuman-->
        <script>
            $(document).ready(function () {
                $('.edit-button').click(function () {
                    var id_pengumuman = $(this).data('id_pengumuman'); // Mengambil id_pengumuman dari atribut data
                    var isi_pengumuman = $(this).data('isi_pengumuman'); // Mengambil isi_pengumuman dari atribut data

                    // Menetapkan nilai ke input tersembunyi di dalam modal edit
                    $('#edit_id_pengumuman').val(id_pengumuman);
                    $('#isi_pengumuman').val(isi_pengumuman);
                });
            });
        </script>


        <!--unttuk ambil id tugas-->
        <script>
            $(document).ready(function () {
                $('.edit-button').click(function () {
                    var idTugas = $(this).data('idtugas'); // Mengambil id tugas dari atribut data

                    // Menetapkan nilai ke input tersembunyi di dalam modal edit
                    $('#edit_id_tugas').val(idTugas);
                });

                $('.kumpul-button').click(function () {
                    var idTugas = $(this).data('idtugas'); // Mengambil id tugas dari atribut data

                    // Menetapkan nilai ke input tersembunyi di dalam modal edit
                    $('#kumpul_id_tugas').val(idTugas);
                });

                $('.kelas-tab').click(function () {
                    let href = $(this).attr('href')
                    let url = window.location.href;
                    let currentUrl = url.split("#");

                    if (currentUrl.length > 0 && currentUrl[0]) {
                        window.history.pushState(null, null, currentUrl[0] + href)
                    }
                });
                // $('.hapus_semua').click(function (e) {
                //     e.preventDefault()
                //     $('#aksi').val("")

                // });

                var url = window.location.href;
                var activetab = url.substring(url.indexOf("#") + 1);
                // $(".tab-pane").removeClass("active in");
                // $("#" + activetab).addClass("active in");
                $('a[href="#' + activetab + '"]').tab('show');

            });


        </script>


</body>

</html>