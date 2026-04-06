<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Ambil parameter ID jika cetak ulang, atau dari POST jika baru dibuat
$id_surat = $_GET['id'] ?? null;
$nis = $_POST['nis'] ?? $_GET['nis'] ?? '';

if ($id_surat) {
    // CETAK ULANG: Ambil data dari database
    $id_surat = mysqli_real_escape_string($conn, $id_surat);
    $q_surat = mysqli_query($conn, "SELECT sk.no_surat, sk.tanggal_pembuatan_surat, sk.keperluan as masalah, ps.* 
        FROM surat_keluar sk 
        JOIN perjanjian_siswa ps ON sk.id_perjanjian_siswa = ps.id_perjanjian_siswa 
        WHERE sk.id_surat_keluar = '$id_surat'");
    $data_surat = mysqli_fetch_assoc($q_surat);
    
    $no_surat = $data_surat['no_surat'] ?? '---';
    $nama_ortu = $data_surat['nama_ortu'] ?? '---';
    $pekerjaan = $data_surat['pekerjaan_ortu'] ?? '---';
    $alamat = $data_surat['alamat_ortu'] ?? '---';
    $no_telp = $data_surat['no_telp_ortu'] ?? '---';
    $masalah = $data_surat['masalah'] ?? '---';
    $tanggal_input = $data_surat['tanggal'] ?? date('Y-m-d');
} else {
    // BARU DIBUAT: Ambil dari data POST form
    $nama_ortu = $_POST['nama_ortu'] ?? '';
    $pekerjaan = $_POST['pekerjaan'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $no_telp = $_POST['no_telp'] ?? '';
    $masalah = $_POST['masalah'] ?? '';
    $tanggal_input = $_POST['tanggal'] ?? date('Y-m-d');
}

if (empty($nis)) die("Error: NIS tidak ditemukan.");

// mengambil data siswa dari database join ke tabel ortu_wali, kelas, tingkat, program_keahlian, dan guru
$query_siswa = mysqli_query($conn, "SELECT nis, nama_siswa, tingkat, program_keahlian, rombel, ayah, ibu, wali, nama_pengguna, deskripsi FROM siswa
JOIN ortu_wali USING(id_ortu_wali)
JOIN kelas USING(id_kelas)
JOIN tingkat USING(id_tingkat)
JOIN program_keahlian USING(id_program_keahlian)
JOIN guru USING(kode_guru) WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

// mengambil data guru bimbingan konseling berdasarkan tingkat siswa
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

// mengambil data wakasek kesiswaan dari database
$query_waka = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y'");
$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'];

// buat array bulan (berfungsi untuk mengubah angka bulan menjadi nama bulan, contoh : 2 menjadi Februari)
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

// Format tanggal dari input
$tgl_obj = new DateTime($tanggal_input);
$tanggal_formatted = $tgl_obj->format('d') . ' ' . $bulan_indo[$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y');

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";
?>

<style>
    body {
        background-color: #f4f7f6;
    }

    .print-container {
        background: white;
        width: 210mm; /* Ukuran A4 */
        min-height: 297mm;
        padding: 20mm;
        margin: 20px auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: "Times New Roman", Times, serif;
        line-height: 1.5;
        color: black;
    }


    .header-img {
        width: 100%;
        margin-bottom: 10px;
    }

    .title-doc {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
        font-size: 16pt;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .content-section {
        margin-bottom: 20px;
    }

    .form-row {
        display: flex;
        margin-bottom: 5px;
    }

    .label-cell {
        width: 180px;
        flex-shrink: 0;
    }

    .separator-cell {
        width: 20px;
        flex-shrink: 0;
        text-align: center;
    }

    .field-cell {
        flex: 1;
        border-bottom: 1px dotted black;
        min-height: 22px;
        font-weight: bold;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin-top: 30px;
        gap: 30px;
    }

    .sig-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        page-break-inside: avoid;
        break-inside: avoid-page;
    }

    .sig-name {
        margin-top: 60px;
        font-weight: bold;
        text-decoration: underline;
    }

    .wakasek-wrapper {
        margin-top: 40px; 
        display: flex; 
        justify-content: center; 
        page-break-inside: avoid; 
        break-inside: avoid;
    }

    @media print {
        nav, .navbar, .no-print, header, footer {
            display: none !important;
        }
        body {
            background: white;
            margin: 0;
            padding: 20px;
        }
        .print-container {
            box-shadow: none;
            margin: 0 auto;
            width: 100%;
            padding: 10mm 10mm 30mm 10mm; Menambah padding bawah agar tidak mepet batas kertas
            min-height: auto;
            overflow: visible !important;
        }
    }
</style>

<div class="no-print no-print-tools">
    <button onclick="history.back()" class="btn btn-cancel shadow-sm border">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </button>
    <button onclick="window.print()" class="btn btn-save shadow-sm border">
        <i class="bi bi-printer-fill me-1" style="color: #1a8cfd;"></i> Cetak Pernyataan
    </button>
</div>

<div class="print-container">
    <img src="/SistemPoin/assets/img/kop.jpg" class="header-img" alt="Kop Surat" style="margin-top: -68px">

    <div class="title-doc">SURAT PERNYATAAN SISWA</div>

    <div class="content-section">
        <p>Yang bertandatangan di bawah ini:</p>
        <div class="indent">
            <div class="form-row">
                <div class="label-cell">Nama</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($row_siswa['nama_siswa']) ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">NIS</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($row_siswa['nis']) ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">Kelas</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= $row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel'] ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">Program Keahlian</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($row_siswa['deskripsi']) ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">Masalah</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($masalah) ?></div>
            </div>

            <div class="form-row">
                <div class="label-cell">Nama Orang Tua</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($nama_ortu) ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">Pekerjaan</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($pekerjaan) ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">Alamat Rumah</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($alamat) ?></div>
            </div>
            <div class="form-row">
                <div class="label-cell">No. Hp./Telp.</div>
                <div class="separator-cell">:</div>
                <div class="field-cell"><?= htmlspecialchars($no_telp) ?></div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <p style="text-align: justify; text-indent: 40px; margin-bottom: 0; margin-top: -15px">
            Menyatakan dan berjanji akan bersungguh-sungguh berubah dan bersedia mentaati aturan dan tata tertib sekolah. 
            Apabila selama masa pembinaan tidak mengalami perubahan, maka saya bersedia dikembalikan kepada orang tua/wali. 
            Demikian surat pernyataan ini saya buat dengan sesungguhnya tanpa ada tekanan dari siapapun.
        </p>
    </div>

    <div class="signature-grid">
        <div class="sig-box">
            <div>Mengetahui,</div>
            <div>Orang Tua/Wali Siswa</div>
            <div class="sig-name"><?= htmlspecialchars($nama_ortu) ?></div>
        </div>
        <div class="sig-box">
            <div>Denpasar, <?= $tanggal_formatted ?></div>
            <div>Siswa yang bersangkutan</div>
            <div class="sig-name"><?= htmlspecialchars($row_siswa['nama_siswa']) ?></div>
        </div>
        <div class="sig-box">
            <div>Guru Bimbingan Konseling</div>
            <div class="sig-name"><?= htmlspecialchars($guru_bk) ?></div>
        </div>
        <div class="sig-box">
            <div>Guru Wali Kelas</div>
            <div class="sig-name"><?= htmlspecialchars($row_siswa['nama_pengguna']) ?></div>
        </div>
    </div>

    <!-- Bagian Tanda Tangan Wakasek Kesiswaan yang diposisikan di tengah -->
    <div class="wakasek-wrapper">
        <div class="sig-box">
            <div>Mengetahui,</div>
            <div>Wakasek Kesiswaan</div>
            <div class="sig-name">( <?= htmlspecialchars($waka_kesiswaan) ?> )</div>
        </div>
    </div>
</div>

<script>
    // Menyertakan bagian footer (penutup halaman)
    window.onload = function () {
        window.print();
    }
</script>
<?php
include ROOTPATH . "/includes/footer.php";
?>