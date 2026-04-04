<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
// Set timezone ke WITA (Asia/Makassar)
date_default_timezone_set('Asia/Makassar');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Cek apakah diakses melalui ID (dari laporan/riwayat) atau POST (setelah tambah surat)
if (isset($_GET['id'])) {
    $id_surat = mysqli_real_escape_string($conn, $_GET['id']);
    $q_surat = mysqli_query($conn, "SELECT * FROM surat_keluar WHERE id_surat_keluar = '$id_surat'");
    $data_surat = mysqli_fetch_assoc($q_surat);

    if (!$data_surat) {
        die("Error: Data surat tidak ditemukan.");
    }

    $nis = $data_surat['nis'];
    $no_surat = $data_surat['no_surat'];
    $tanggal_input = date('Y-m-d', strtotime($data_surat['tanggal_pemanggilan']));
    $jam_input = date('H:i', strtotime($data_surat['tanggal_pemanggilan']));
    $keperluan = $data_surat['keperluan'];
} else {
    $nis = $_POST['nis'] ?? '';
    $no_surat = $_POST['no_surat'] ?? '';
    $tanggal_input = !empty($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');
    $jam_input = !empty($_POST['jam']) ? $_POST['jam'] : date('H:i');
    $keperluan = $_POST['keperluan'] ?? '';
}

// pisah format tanggal dan hari nya
$ambil_tanggal = explode("-", $tanggal_input);
// ambil tanggal nya
$hari = date("l", strtotime($tanggal_input));
// ubah format hari menjadi nama hari indonesia
$hari_indo = ["Monday" => "Senin", "Tuesday" => "Selasa", "Wednesday" => "Rabu", "Thursday" => "Kamis", "Friday" => "Jumat", "Saturday" => "Sabtu", "Sunday" => "Minggu"];
$hari = $hari_indo[$hari];

// buat array bulan (berfungsi untuk mengubah angka bulan menjadi nama bulan, contoh : 2 menjadi Februari)
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
// ubah format tanggal input menjadi nama bulan
$tanggal_input = date("d", strtotime($tanggal_input)) . " " . $bulan_indo[date("n", strtotime($tanggal_input))] . " " . date("Y", strtotime($tanggal_input));
// ubah format tanggal hari ini menjadi nama bulan
$tanggal = date("d") . " " . $bulan_indo[date("n")] . " " . date("Y");

// ubah format bulan menjadi romawi
$bulan_romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
$bulan_romawi = $bulan_romawi[date("n")];

// mengambil data siswa dari database join ke tabel ortu_wali, kelas, tingkat, dan program_keahlian
$query_siswa = mysqli_query($conn, "SELECT nis, nama_siswa, tingkat, program_keahlian, rombel, ayah, ibu, wali FROM siswa 
JOIN ortu_wali USING(id_ortu_wali)
JOIN kelas USING(id_kelas)
JOIN tingkat USING(id_tingkat)
JOIN program_keahlian USING(id_program_keahlian) WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

if (!$row_siswa) {
    die("Error: Data siswa tidak ditemukan untuk NIS: " . htmlspecialchars($nis));
}

// query untuk menampilkan data guru BK
$tingkat = $row_siswa['tingkat'];
if ($tingkat == 'XII') {
    $query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK XII' AND aktif = 'Y'");
} else if ($tingkat == 'XI') {
    $query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK XI' AND aktif = 'Y'");
} else {
    $query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK X' AND aktif = 'Y'");
}
$row_bk = mysqli_fetch_assoc($query_bk);
$guru_bk = $row_bk['nama_pengguna'];

// query untuk menampilkan data waka kesiswaan
$query_waka = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y'");
$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'];

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";
?>

<style>
    /* Mengatur font agar terlihat lebih formal untuk surat resmi */
    .page {
        font-family: "Times New Roman", Times, serif !important;
        font-size: 12pt;
        color: black;
        line-height: 1.5;
        padding: 10mm 20mm !important;
    }

    .table-info td {
        padding: 2px 0;
        vertical-align: top;
    }

    .signature-section {
        margin-top: 30px;
        width: 100%;
    }

    .sig-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sig-table td {
        width: 50%;
        padding: 5px 0;
    }

    .sig-space {
        height: 80px;
    }

    .underline-bold {
        text-decoration: underline;
        font-weight: bold;
    }

    @media print {
        nav, .navbar, .no-print, header, footer {
            display: none !important;
        }
        body {
            background: white !important;
            margin: 0;
            padding: 0;
        }
        .page {
            margin: 0 auto;
            box-shadow: none;
            border: none;
            width: 100%;
            min-height: auto;
        }
        @page {
            size: A4;
            margin: 1cm;
        }
    }
</style>

<!-- tombol navigasi no-print -->
<div class="no-print no-print-tools">
    <button onclick="history.back()" class="btn btn-cancel shadow-sm border"><i class="bi bi-arrow-left me-1"></i> Kembali</button>
    <button onclick="window.print()" class="btn btn-save shadow-sm border"><i class="bi bi-printer-fill me-1" style="color: #1a8cfd;"></i> Cetak Pernyataan</button>
</div>

<div class="page">
    <!-- Header / Kop Surat -->
    <div class="header">
        <img src="/SistemPoin/assets/img/kop.jpg" alt="kepala surat" width="100%" style="margin-top: -15px;">
    </div>
    <hr style="border: 1.5px solid black; margin-top: 0; margin-bottom: 20px;">

    <!-- Body Surat -->
    <div class="body-surat">
        <table class="table-info" style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 90px;">No.</td>
                <td style="width: 15px;">:</td>
                <!-- 
                Pada nomor surat contoh : 230/SMK TI/BG/II/2026, bagian angka Romawi “II” biasanya menunjukkan bulan diterbitkannya surat, bukan tanggal lengkap, bagian angka 2026 merujuk ke tahun pembuatan surat.

                Penjelasan Struktur Umum Nomor Surat :
                Nomor Surat Keluar / Kode Sekolah / Kode Perihal / Bulan (Romawi) / Tahun
                Jadi:
                    •	230 → Nomor urut surat keluar (surat ke-230 yang dicatat di buku agenda).
                    •	SMK TI → Kode nama sekolah.
                    •	BG → Bali Global.
                    •	II → Bulan surat dibuat (Pebruari).
                    •	2026 → Tahun pembuatan surat. 
                -->
                <td><?= htmlspecialchars($no_surat) ?></td>
            </tr>
            <tr>
                <td>Lamp.</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><b>Pemanggilan Orang Tua / Wali Siswa</b></td>
            </tr>
        </table>

        <p style="margin: 0; margin-bottom: 10px;">
            Kepada<br>
            Yth. Bapak/ Ibu
        </p>
        <table class="table-info" style="width: 100%; margin-left: 35px; margin-bottom: 20px;">
            <tr>
                <td style="width: 180px;">Orang Tua / Wali dari</td>
                <td style="width: 15px;">:</td>
                <td><?php echo $row_siswa['nama_siswa']; ?></td>
            </tr>
            <tr>
                <td>Kelas / Nis</td>
                <td>:</td>
                <!-- menampilkan data kelas, program keahlian, rombel, dan nis -->
                <td><?php echo $row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel'] ?>
                    / <?php echo $row_siswa['nis']; ?></td>
            </tr>
        </table>

        <p style="margin: 0; margin-bottom: 5px;">
            Dengan hormat,
        </p>
        <p style="margin: 0; margin-bottom: 10px;">
            Bersama surat ini, kami mengharapkan kehadiran Bapak / Ibu pada :
        </p>

        <table class="table-info" style="width: 100%; margin-left: 35px; margin-bottom: 20px;">
            <tr>
                <td style="width: 150px;">Hari / Tanggal</td>
                <td style="width: 15px;">:</td>
                <!-- menampilkan hari dan tanggal berdasarkan dari data yang di input dari file add_panggilan_ortu -->
                <td><?php echo $hari; ?> / <?php echo $tanggal_input; ?></td>
            </tr>
            <tr>
                <td>Pukul</td>
                <td>:</td>
                <!-- menampilkan jam berdasarkan dari data yang di input dari file add_panggilan_ortu -->
                <td><?php echo $jam_input; ?> WITA</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>SMK TI Bali Global Denpasar</td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <!-- menampilkan keperluan berdasarkan dari data yang di input dari file add_panggilan_ortu -->
                <td><?php echo $keperluan; ?></td>
            </tr>
        </table>

        <p style="margin:0;">
            <span style="display:inline-block; width: 45px;"></span>Demikian surat ini kami sampaikan, besar harapan
            kami pertemuan ini agar tidak diwakilkan.<br>
            Atas perhatian dan kerjasamanya, kami ucapkan terimakasih.
        </p>

        <br><br><br>
        <table class="table-info" style="width: 100%; text-align: left;margin-left: 40px;">
            <tr>
                <td style="width: 55%;">Mengetahui,</td>
                <td style="width: 45%;">Denpasar, <?php echo $tanggal ?></td>
            </tr>
            <tr>
                <td>Waka Kesiswaan</td>
                <td>Guru BK</td>
            </tr>
            <tr>
                <td colspan="2"><br><br><br></td>
            </tr>
            <tr>
                <!-- menampilkan nama waka kesiswaan dari database -->
                <td><u><?= $waka_kesiswaan ?></u></td>
                <!-- menampilkan nama guru bk dari database -->
                <td><u><?= $guru_bk ?></u></td>
            </tr>
        </table>
    </div>
</div>







<script>
    // ketika halaman selesai loading maka halaman akan otomatis di print
    window.onload = function () {
        window.print();
    }
</script>
<?php
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php";
?>