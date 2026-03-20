<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";

$id_surat = $_GET['id'] ?? '';
if (empty($id_surat)) {
    echo "<script>history.back();</script>";
    exit;
}

// Ambil data surat_keluar
$query = mysqli_query($conn, "SELECT sk.no_surat, sk.tanggal_pembuatan_surat, s.nis, s.nama_siswa, t.tingkat, pk.program_keahlian, k.rombel FROM surat_keluar sk JOIN siswa s ON sk.nis = s.nis JOIN kelas k ON s.id_kelas = k.id_kelas JOIN tingkat t ON k.id_tingkat = t.id_tingkat JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian WHERE sk.id_surat_keluar = '$id_surat'");
$row = mysqli_fetch_assoc($query);
if (!$row) {
    echo "<script>history.back();</script>";
    exit;
}

$no_surat = $row['no_surat'] ?: '---';

$bulan_romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
$bulan_romawi = $bulan_romawi[date("n")];
$row_siswa = $row;

// Guru BK dan Waka
$tingkat = $row['tingkat'];
if ($tingkat == 'XII') {
    $bk_jabatan = 'Guru BK XII';
} else if ($tingkat == 'XI') {
    $bk_jabatan = 'Guru BK XI';
} else {
    $bk_jabatan = 'Guru BK X';
}
$query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = '$bk_jabatan' AND aktif = 'Y'");
$row_bk = mysqli_fetch_assoc($query_bk);
$guru_bk = $row_bk['nama_pengguna'] ?? '---';

$query_waka = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y'");
$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'] ?? '---';

$bulan_indo = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$tanggal = date("d") . " " . $bulan_indo[date("n")] . " " . date("Y");

include ROOTPATH . "/includes/header.php";
?>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .page,
        .page * {
            visibility: visible;
        }

        .page {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }

        .no-print * {
            display: none !important;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.4;
        }
    }

    .page {
        max-width: 210mm;
        padding: 20mm;
        margin: 0 auto;
    }

    .form-row {
        display: flex;
        margin-bottom: 10px;
        align-items: flex-end;
    }

    .label {
        width: 150px;
    }

    .separator {
        width: 20px;
    }

    .field {
        flex-grow: 1;
        border-bottom: 1px dotted #000;
        padding-bottom: 2px;
    }

    .table-info td {
        padding: 4px 0;
        vertical-align: top;
    }

    button {
        display: flex;
        height: 3em;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0 15px;
    }

    button:hover {
        background: #f9f9f9;
        transform: translateY(-2px);
    }
</style>

<center class="no-print">
    <div style="display: flex; justify-content: center; gap: 10px; margin: 20px 0;">
        <button onclick="window.history.back()">
            <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                <path
                    d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z">
                </path>
            </svg> Kembali
        </button>
        <button onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Cetak
        </button>
    </div>
</center>

<div class="page">
    <div class="header">
        <img src="/SistemPoin/assets/img/kop.jpg" alt="Kop Surat" style="max-height: 120px;">
    </div>

    <table class="table-info" style="width: 100%; margin-bottom: 25px;">
        <tr>
            <td style="width: 90px;">No.</td>
            <td style="width: 15px;">:</td>
            <td><?= htmlspecialchars($no_surat) ?>/SMK TI/BG/<?= $bulan_romawi ?>/<?= date("Y") ?></td>
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

    <p style="margin-bottom: 10px;">Kepada Yth. Bapak/Ibu</p>

    <table class="table-info" style="width: 100%; margin-left: 35px; margin-bottom: 25px;">
        <tr>
            <td style="width: 190px;">Orang Tua / Wali dari</td>
            <td>:</td>
            <td><?= htmlspecialchars($row_siswa['nama_siswa']) ?></td>
        </tr>
        <tr>
            <td>Kelas/NIS</td>
            <td>:</td>
            <td><?= htmlspecialchars($row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel']) ?>
                / <?= htmlspecialchars($row_siswa['nis']) ?></td>
        </tr>
    </table>

    <p>Dengan hormat,</p>
    <p>Bersama surat ini, kami mengharapkan kehadiran Bapak/Ibu pada: [Detail waktu/tempat - isi manual]</p>

    <p style="margin-left: 45px;">Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya, terima kasih.</p>

    <div style="margin-top: 60px; display: flex; justify-content: space-between;">
        <div style="text-align: center;">
            <div>Waka Kesiswaan</div>
            <div style="margin-top: 60px; border-bottom: 1px solid #000;"><?= htmlspecialchars($waka_kesiswaan) ?></div>
        </div>
        <div style="text-align: center;">
            <div>Guru BK</div>
            <div style="margin-top: 60px; border-bottom: 1px solid #000;"><?= htmlspecialchars($guru_bk) ?></div>
        </div>
    </div>
</div>

<script>
    window.onload = function () { window.print(); }
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>