<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

$nis = $_GET['nis'];

// mengambil data siswa dari database
$query_siswa = mysqli_query($conn, "SELECT s.nis, s.nama_siswa, t.tingkat, p.program_keahlian, k.rombel, p.deskripsi FROM siswa s
JOIN kelas k ON s.id_kelas = k.id_kelas
JOIN tingkat t ON k.id_tingkat = t.id_tingkat
JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
WHERE s.nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

include ROOTPATH . "/includes/header.php";
?>

<style>
    /* Styling untuk tampilan cetak */
    @media print {

        \n .no-print,
        nav,
        header {
            display: none !important;
        }

        \n body {
            margin: 0;
            padding: 1cm;
            font-size: 12pt;
        }

        \n .page {
            box-shadow: none !important;
            border: none;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        \n table {
            font-size: 11pt;
        }

        \n img {
            max-width: 100%;
            height: auto;
        }

        \n
    }

    .page {
        width: 210mm;
        /* Standar A4 */
        min-height: 297mm;
        padding: 10mm;
        margin: 10px auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    .title {
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        text-decoration: underline;
        margin-top: 20px;
    }

    .content {
        margin-top: 30px;
    }

    /* Form Layout dengan garis bawah titik-titik sesuai gambar */
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
        /* Garis titik-titik */
        padding-bottom: 2px;
    }

    /* Table Styling */
    table {
        border-collapse: collapse;
        margin-top: 20px;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Button Styling */
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
</style>

<center class="no-print">
    <div class="btn-container">
        <button onclick="window.history.back()" class="btn btn-cancel shadow-sm border">
            <span>Batal</span>
        </button>
        <button onclick="window.print()" class="btn btn-save shadow-sm border">
            <span><i class="bi bi-printer-fill" style="color: #1a8cfd;"></i> Cetak Laporan</span>
        </button>
    </div>
</center>

<div class="page">
    <div class="header">
        <img src="/SistemPoin/assets/img/kop.jpg" alt="kepala surat" width="100%">
        <hr style="border: 2px solid black; margin-top: 2px;">
    </div>

    <div class="title">LAPORAN PELANGGARAN SISWA</div>

    <div class="content">
        <div class="form-row">
            <div class="label">Nama</div>
            <div class="separator">:</div>
            <div class="field"><?php echo $row_siswa['nama_siswa']; ?></div>
        </div>
        <div class="form-row">
            <div class="label">NIS</div>
            <div class="separator">:</div>
            <div class="field"><?php echo $row_siswa['nis']; ?></div>
        </div>
        <div class="form-row">
            <div class="label">Kelas</div>
            <div class="separator">:</div>
            <div class="field"><?php echo $row_siswa['tingkat'] . ' ' . $row_siswa['rombel'] ?></div>
        </div>
        <div class="form-row">
            <div class="label">Program Keahlian</div>
            <div class="separator">:</div>
            <div class="field"><?php echo $row_siswa['program_keahlian']; ?></div>
        </div>
        <div class="form-row">
            <div class="label">Pelanggaran</div>
            <div class="separator">:</div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Tanggal</th>
                        <th width="60%">Jenis Pelanggaran</th>
                        <th width="15%">Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Pastikan nama kolom 'poin' sesuai dengan di database (cek apakah 'poin' atau 'point')
                    $res = mysqli_query($conn, "SELECT tanggal, jenis, keterangan, poin FROM pelanggaran_siswa JOIN jenis_pelanggaran USING(id_jenis_pelanggaran) WHERE nis = '$nis'");

                    while ($p = mysqli_fetch_assoc($res)) {
                        $dt = date("d F Y", strtotime($p['tanggal']));
                        $tm = date("H:i:s", strtotime($p['tanggal']));
                        ?>
                        <tr>
                            <td class="text-center align-middle" rowspan="2"><?= $no++ ?></td>
                            <td class="text-center"><?= $dt ?><br><small><?= $tm ?></small></td>
                            <td class="fw-bold"><?= htmlspecialchars($p['jenis']) ?></td>
                            <td class="text-center align-middle fw-bold" rowspan="2" style="font-size: 1.2rem;">
                                <?= $p['poin'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-light">
                                <small class="text-muted">Detail Pelanggaran :</small> <br>
                                <?= htmlspecialchars($p['keterangan']) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <td colspan="3" class="text-end fw-bold">Total Poin</td>
                        <td class="text-center fw-bold text-danger">
                            <?php
                            // Jika di database namanya 'point', ubah SUM(poin) menjadi SUM(point)
                            $query_total = "SELECT SUM(jp.poin) as total FROM pelanggaran_siswa ps JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran WHERE ps.nis = '$nis'";
                            $sum_res = mysqli_query($conn, $query_total);

                            if ($sum_res) {
                                $total = mysqli_fetch_assoc($sum_res);
                                echo $total['total'] ?? 0;
                            } else {
                                echo "Error Col"; // Ini muncul jika nama kolom salah
                            }
                            ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <?php include ROOTPATH . "/includes/footer.php"; ?>