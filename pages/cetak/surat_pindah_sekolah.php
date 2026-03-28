<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/includes/header.php";

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Gunakan null coalescing operator (??) untuk menghindari error undefined index
$nis = $_POST['nis'] ?? '';
$no_surat = $_POST['no_surat'] ?? '---';
$nama_ortu = $_POST['nama_ortu'] ?? '';
$alamat_ortu = $_POST['alamat'] ?? ''; // Sesuai dengan name='alamat' di form Anda
$pindah_ke = $_POST['pindah_ke'] ?? '';
$tanggal_input = $_POST['tanggal'] ?? date('Y-m-d');
$alasan_pindah = $_POST['alasan_pindah'] ?? '';

// Ambil data siswa dengan JOIN
$query_siswa = mysqli_query($conn, "SELECT * FROM siswa 
    JOIN ortu_wali USING(id_ortu_wali)
    JOIN kelas USING(id_kelas)
    JOIN tingkat USING(id_tingkat)
    JOIN program_keahlian USING(id_program_keahlian) 
    WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

// Format Bulan Romawi
$bulan_romawi_arr = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
$bulan_romawi = $bulan_romawi_arr[date("n")];

// Fetch Nama Kepala Sekolah
$query_kepsek = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Kepala Sekolah' AND aktif = 'Y' LIMIT 1");
$row_kepsek = mysqli_fetch_assoc($query_kepsek);
$kepsek = $row_kepsek['nama_pengguna'] ?? '(Nama Kepala Sekolah)';

// Tanggal Indonesia
$bulan_indo = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$tgl_obj = strtotime($tanggal_input);
$tanggal = date("d", $tgl_obj) . " " . $bulan_indo[date("n", $tgl_obj)] . " " . date("Y", $tgl_obj);

?>

<style>
    @media print {

        .no-print,
        nav,
        header {
            display: none !important;
        }

        body {
            margin: 0;
            padding: 1cm;
            font-size: 12pt;
        }

        .page {
            box-shadow: none !important;
            border: none;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        table {
            font-size: 11pt;
        }

        img {
            max-width: 100%;
            height: auto;
        }

    }

    /* Styling khusus cetak */
    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 10mm 20mm;
        margin: 10px auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        font-family: "Times New Roman", Times, serif;
        font-size: 12pt;
        line-height: 1.5;
    }

    .title {
        text-align: center;
        font-weight: bold;
        margin-bottom: 25px;
        text-transform: uppercase;
    }

    .indent {
        margin-left: 30px;
        margin-bottom: 15px;
    }

    .form-row {
        display: flex;
        margin-bottom: 3px;
    }

    .label {
        width: 180px;
    }

    .separator {
        width: 20px;
    }

    .field {
        flex: 1;
        font-weight: bold;
    }

    .signature-section {
        margin-top: 40px;
        display: flex;
        justify-content: flex-end;
    }

    .sig-right {
        text-align: left;
        width: 300px;
    }

    .sig-name-plain {
        margin-top: 80px;
        font-weight: bold;
        text-decoration: underline;
    }

    @media print {
        body {
            background: none;
        }

        .no-print {
            display: none;
        }

        .page {
            margin: 0;
            box-shadow: none;
            width: 100%;
        }
    }
</style>

<div class="no-print no-print-tools">
    <form action="add_perjanjian_siswa.php" method="post" style="margin: 0;">
        <input type="hidden" name="nis" value="<?= $nis ?>">
        <button type="submit" class="btn btn-cancel shadow-sm border">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </button>
    </form>
    <button onclick="window.print()" class="btn btn-save shadow-sm border">
        <i class="bi bi-printer-fill me-1" style="color: #1a8cfd;"></i> Cetak Pernyataan
    </button>
</div>

<div class="page">
    <div class="header">
        <img src="/SistemPoin/assets/img/kop.jpg" alt="Kop Surat" width="100%">
    </div>
    <hr style="border: 2px solid black; margin-top: 0;">

    <div class="title">
        <u>KETERANGAN PINDAH SEKOLAH</u><br>
        <span style="font-size: 11pt; font-weight: normal;">Nomor: <?= $no_surat ?>/SMK
            TI/BG/<?= $bulan_romawi ?>/<?= date("Y") ?></span>
    </div>

    <div class="content">
        <p>Yang bertandatangan di bawah ini Kepala SMK TI BALI GLOBAL Denpasar, Kecamatan Denpasar Selatan, Kota
            Denpasar, Provinsi Bali, menerangkan bahwa:</p>

        <div class="indent">
            <div class="form-row">
                <div class="label">Nama Siswa</div>
                <div class="separator">:</div>
                <div class="field"><?= $row_siswa['nama_siswa'] ?? '---' ?></div>
            </div>
            <div class="form-row">
                <div class="label">Kelas / Program</div>
                <div class="separator">:</div>
                <div class="field">
                    <?= ($row_siswa['tingkat'] ?? '') . ' ' . ($row_siswa['program_keahlian'] ?? '') . ' ' . ($row_siswa['rombel'] ?? '') ?>
                </div>
            </div>
            <div class="form-row">
                <div class="label">Nomor Induk Siswa</div>
                <div class="separator">:</div>
                <div class="field"><?= $row_siswa['nis'] ?? '---' ?></div>
            </div>
            <div class="form-row">
                <div class="label">Jenis Kelamin</div>
                <div class="separator">:</div>
                <div class="field"><?= $row_siswa['jenis_kelamin'] ?? '---' ?></div>
            </div>
            <div class="form-row">
                <div class="label">Alamat</div>
                <div class="separator">:</div>
                <div class="field"><?= $row_siswa['alamat'] ?? '---' ?></div>
            </div>
        </div>

        <p>Sesuai dengan surat permohonan pindah sekolah dari Orang Tua / Wali siswa:</p>

        <div class="indent">
            <div class="form-row">
                <div class="label">Nama Orang Tua/Wali</div>
                <div class="separator">:</div>
                <div class="field"><?= $nama_ortu ?></div>
            </div>
            <div class="form-row">
                <div class="label">Alamat</div>
                <div class="separator">:</div>
                <div class="field"><?= $alamat_ortu ?></div>
            </div>
        </div>

        <p style="text-align: justify;">
            Telah mengajukan permohonan pindah sekolah ke <strong><?= $pindah_ke ?></strong>, dengan alasan
            <strong><?= $alasan_pindah ?></strong>. Segala kelengkapan administrasi yang bersangkutan telah
            diselesaikan.
        </p>

        <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

        <div class="signature-section">
            <div class="sig-right">
                <div>Denpasar, <?= $tanggal ?></div>
                <div>Kepala SMK TI Bali Global Denpasar,</div>
                <div class="sig-name-plain"><?= $kepsek ?></div>
                <!-- <div>NIP. .................................</div> -->
            </div>
        </div>
    </div>
</div>

<script>
    // Aktifkan print otomatis saat halaman dimuat
    window.onload = function () {
        // window.print(); // Hapus komentar jika ingin langsung print
    }
</script>

<?php
include "../../includes/footer.php";
?>