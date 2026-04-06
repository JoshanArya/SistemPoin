<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";
// Menyertakan tampilan header (bagian atas halaman) - Navbar tetap aman di sini
include ROOTPATH . "/includes/header.php";

// Ambil parameter ID jika cetak ulang, atau dari POST jika baru dibuat
$id_surat = $_GET['id'] ?? null;
$nis = $_POST['nis'] ?? $_GET['nis'] ?? '';

if ($id_surat) {
    // CETAK ULANG: Ambil data dari database berdasarkan ID Surat Keluar
    $id_surat = mysqli_real_escape_string($conn, $id_surat);
    $q_surat = mysqli_query($conn, "SELECT sk.no_surat, sk.tanggal_pembuatan_surat, po.* 
        FROM surat_keluar sk 
        JOIN perjanjian_orang_tua po ON sk.id_perjanjian_ortu = po.id_perjanjian_ortu 
        WHERE sk.id_surat_keluar = '$id_surat'");
    $data_surat = mysqli_fetch_assoc($q_surat);
    
    $no_surat = $data_surat['no_surat'] ?? '---';
    $nama_ortu = $data_surat['nama_ortu'] ?? '---';
    $pekerjaan = $data_surat['pekerjaan_ortu'] ?? '---';
    $alamat = $data_surat['alamat_ortu'] ?? '---';
    $no_telp = $data_surat['no_telp_ortu'] ?? '---';
    $tanggal_input = $data_surat['tanggal'] ?? date('Y-m-d');
} else {
    // BARU DIBUAT: Ambil dari data POST form
    $ortu_type = $_POST['ortu_type'] ?? 'ayah';
    $no_surat = $_POST['no_surat'] ?? '---';
    $tanggal_input = $_POST['tanggal'] ?? date('Y-m-d');
}

// Validasi data
if (empty($nis)) {
    die("Error: NIS tidak ditemukan.");
}

// Escape NIS untuk mencegah SQL Injection
$nis_escaped = mysqli_real_escape_string($conn, $nis);

// Ambil data siswa
$query_siswa = mysqli_query($conn, "SELECT s.nis, s.nama_siswa, t.tingkat, p.program_keahlian, k.rombel,
    ow.ayah, ow.ibu, ow.wali,
    ow.pekerjaan_ayah, ow.pekerjaan_ibu, ow.pekerjaan_wali,
    ow.alamat_ayah, ow.alamat_ibu, ow.alamat_wali,
    ow.no_telp_ayah, ow.no_telp_ibu, ow.no_telp_wali
FROM siswa s
JOIN ortu_wali ow USING(id_ortu_wali)
JOIN kelas k USING(id_kelas)
JOIN tingkat t ON k.id_tingkat = t.id_tingkat
JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
WHERE s.nis = '$nis_escaped'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

if (!$row_siswa) {
    die("Error: Data siswa tidak ditemukan.");
}
if (!$id_surat) {
    // Logika penentuan data orang tua hanya jika bukan cetak ulang
    $nama_ortu = $_POST['nama_ortu'] ?? ($row_siswa['ayah'] ?? '');
    $pekerjaan = $_POST['pekerjaan'] ?? ($row_siswa['pekerjaan_ayah'] ?? '');
    $alamat = $_POST['alamat'] ?? ($row_siswa['alamat_ayah'] ?? '');
    $no_telp = $_POST['no_telp'] ?? ($row_siswa['no_telp_ayah'] ?? '');

    if ($ortu_type === 'ibu') {
        $nama_ortu = $row_siswa['ibu'];
        $pekerjaan = $row_siswa['pekerjaan_ibu'];
        $alamat = $row_siswa['alamat_ibu'];
        $no_telp = $row_siswa['no_telp_ibu'];
    } elseif ($ortu_type === 'wali') {
        $nama_ortu = $row_siswa['wali'];
        $pekerjaan = $row_siswa['pekerjaan_wali'];
        $alamat = $row_siswa['alamat_wali'];
        $no_telp = $row_siswa['no_telp_wali'];
    }
}

// Format tanggal
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$tgl_input_obj = new DateTime($tanggal_input);
$tanggal_formatted = $tgl_input_obj->format('d') . ' ' . $bulan_indo[$tgl_input_obj->format('n')] . ' ' . $tgl_input_obj->format('Y');

$tgl_target_obj = clone $tgl_input_obj;
$tgl_target_obj->modify('+3 months');
$bulan_target = $bulan_indo[$tgl_target_obj->format('n')] . ' ' . $tgl_target_obj->format('Y');
?>

<style>
    /* TAMPILAN DI LAYAR WEB (Berdasarkan Gambar UI Anda) */
    body {
        background-color: #f4f7f6;
    }

    .print-container {
        background: white;
        width: 210mm;
        /* Ukuran A4 */
        min-height: 297mm;
        padding: 20mm;
        margin: 20px auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: "Times New Roman", Times, serif;
        line-height: 1.5;
        color: black;
    }

    .no-print {
        margin-bottom: 20px;
    }

    /* Tombol Style */
    .btn-action {
        padding: 8px 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        background: white;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* FORM LAYOUT (Sesuai PDF) */
    .header-img {
        width: 100%;
        margin-bottom: 10px;
    }

    .title-doc {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
        font-size: 16pt;
        margin: 20px 0;
    }

    .table-data {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }

    .table-data tr {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .table-data td {
        padding: 5px;
        vertical-align: middle;
    }

    .label-cell {
        width: 180px;
        flex-shrink: 0;
    }

    .separator-cell {
        width: 10px;
        flex-shrink: 0;
        text-align: center;
    }

    .table-data td:last-child {
        flex: 1;
        border-bottom: 1px dotted black;
        min-height: 20px;
        padding-bottom: 5px;
    }

    .signature-wrapper {
        margin-top: 50px;
        display: flex;
        justify-content: flex-end;
    }

    .signature-box {
        text-align: center;
        width: 250px;
    }

    .sig-space {
        height: 80px;
    }

    /* TAMPILAN KHUSUS SAAT PRINT */
    @media print {

        /* Sembunyikan Navbar dan Tombol */
        nav,
        .navbar,
        .no-print,
        header,
        footer {
            display: none !important;
        }

        body {
            background: white;
            margin: 0;
            padding: 80px;
        }

        .print-container {
            margin: 0;
            padding: 0;
            box-shadow: none;
            width: 100%;
        }

        /* Pastikan gambar KOP muncul */
        .header-img {
            width: 100%;
        }
    }

    .btn-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
    }

    button {
        display: flex;
        height: 3em;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border-radius: 3px;
        cursor: pointer;
        border: 1px solid #ddd;
        padding: 0 15px;
        transition: all 0.2s linear;
    }

    button:hover {
        background-color: #f9f9f9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .statement b{
        text-decoration: underline 1px dotted black;
        text-underline-offset: 5px;
    }

    /* .field {
        flex-grow: 1;
        border-bottom: 1px dotted black;
        position: relative;
        top: -5px;
        text-decoration: underline 1px dotted black;
        text-underline-offset: 4px;
    } */
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
    <img src="/SistemPoin/assets/img/kop.jpg" class="header-img" alt="Kop Surat">

    <div class="title-doc">SURAT PERNYATAAN ORANG TUA</div>

    <p>Yang bertandatangan di bawah ini orang tua/wali siswa SMK TI Bali Global Denpasar :</p>

    <div class="indent">
        <div class="form-row">
            <div class="label">Nama</div>
            <div class="separator">:</div>
            <div class="field"><?= htmlspecialchars($nama_ortu) ?></div>
        </div>
        <div class="form-row">
            <div class="label">Pekerjaan</div>
            <div class="separator">:</div>
            <div class="field"><?= htmlspecialchars($pekerjaan) ?></div>
        </div>
        <div class="form-row">
            <div class="label">Alamat Rumah</div>
            <div class="separator">:</div>
            <div class="field"><?= htmlspecialchars($alamat) ?></div>
        </div>
        <div class="form-row">
            <div class="label">No. Hp./Telp.</div>
            <div class="separator">:</div>
            <div class="field"><?= htmlspecialchars($no_telp) ?></div>
        </div>

        <br>
    </div>

    <p class="statement" style="text-align: justify;">
        Menyatakan memang benar sanggup membina anak kami yang bernama
        <b><?= htmlspecialchars($row_siswa['nama_siswa']) ?></b>,
        Kelas :
        <b><?= htmlspecialchars($row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel']) ?></b>
        untuk lebih disiplin mengikuti proses pembelajaran dan mengikuti Tata Tertib Sekolah.
        <br><br>
    </p>

    <p style="text-align: justify;">
        Demikian pernyataan kami dan jika tidak sesuai dengan pernyataan diatas, anak kami dapat dikeluarkan dari
        sekolah ini dengan rekomendasi pindah ke SMK lain yang serumpun.
    </p>

    <div class="signature-wrapper">
        <div class="signature-box">
            <div>Denpasar, <?= $tanggal_formatted ?></div>
            <div>Yang membuat pernyataan,</div>
            <div>Orang Tua/Wali siswa</div>
            <div class="sig-space"></div>
            <div style="text-decoration: underline; font-weight: bold;"><?= htmlspecialchars($nama_ortu) ?></div>
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 10pt;">
        <strong><u>NB:</u></strong><br>
        <i>Jika siswa tidak bisa mengikuti proses pembelajaran sampai bulan <?= $bulan_target ?> maka siswa dinyatakan
            mengundurkan diri.</i>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>