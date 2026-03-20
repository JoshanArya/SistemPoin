<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Only for siswa
if ($_SESSION['role'] != 'siswa') {
    echo "<script>alert('Akses ditolak');window.location.href='/SistemPoin/pages/dashboard.php';</script>";
    exit;
}

$nis = $_SESSION['username']; // self NIS

// Same query as details.php but for self
$sql = "SELECT 
            s.nis, s.nama_siswa, s.jenis_kelamin, s.alamat, s.status_siswa AS status,
            o.id_ortu_wali, o.ayah, o.ibu, o.wali,
            o.pekerjaan_ayah, o.pekerjaan_ibu, o.pekerjaan_wali,
            o.no_telp_ayah, o.no_telp_ibu, o.no_telp_wali,
            k.id_kelas, t.tingkat, p.program_keahlian, k.rombel,
            g.nama_pengguna AS wali_kelas
        FROM siswa s
        LEFT JOIN ortu_wali o ON s.id_ortu_wali = o.id_ortu_wali
        LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
        LEFT JOIN tingkat t ON k.id_tingkat = t.id_tingkat
        LEFT JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
        LEFT JOIN guru g ON k.kode_guru = g.kode_guru
        WHERE s.nis = '$nis'";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

// Poin summary
$sql_poin = "SELECT COALESCE(SUM(jp.poin), 0) AS total_poin, COUNT(ps.id_pelanggaran_siswa) AS jumlah_pelanggaran
            FROM pelanggaran_siswa ps
            LEFT JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran 
            WHERE ps.nis = '$nis'";
$data_poin = mysqli_fetch_assoc(mysqli_query($conn, $sql_poin));

function formatTanggal($tanggal)
{
    if (empty($tanggal))
        return '-';
    $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    return date('d', strtotime($tanggal)) . ' ' . $bulan[date('n', strtotime($tanggal))] . ' ' . date('Y', strtotime($tanggal));
}
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Saya</h4>
                </div>
                <div class="card-body">
                    <!-- Profile Header -->
                    <div class="text-center mb-4">
                        <div class="profile-icon-large mx-auto mb-3">
                            <i class="bi bi-person-circle" style="font-size: 80px; color: #6c757d;"></i>
                        </div>
                        <h3><?= htmlspecialchars($data['nama_siswa']) ?></h3>
                        <div class="badge fs-6 bg-info mb-2 px-3 py-2">
                            NIS: <?= htmlspecialchars($data['nis']) ?>
                        </div>
                        <div class="badge bg-success fs-6 px-3 py-2">
                            <?= ucfirst(str_replace('_', ' ', $data['status'])) ?>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4 text-center">
                            <div class="stat-box">
                                <div class="stat-number"><?= $data_poin['total_poin'] ?></div>
                                <div class="stat-label">Total Poin Pelanggaran</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stat-box">
                                <div class="stat-number"><?= $data_poin['jumlah_pelanggaran'] ?></div>
                                <div class="stat-label">Jumlah Pelanggaran</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="stat-box">
                                <div class="stat-number">
                                    <?= $data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel'] ?></div>
                                <div class="stat-label">Kelas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Info Table -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Pribadi</h6>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%">NIS</td>
                                        <td><strong><?= $data['nis'] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td><strong><?= $data['nama_siswa'] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td><?= $data['jenis_kelamin'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td><?= htmlspecialchars($data['alamat'] ?: '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Kelas</td>
                                        <td><strong><?= $data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel'] ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Wali Kelas</td>
                                        <td><?= htmlspecialchars($data['wali_kelas'] ?: '-') ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-icon-large i {
        color: #dee2e6;
    }

    .stat-box {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #0d6efd;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>

<?php include ROOTPATH . "/includes/footer.php"; ?>