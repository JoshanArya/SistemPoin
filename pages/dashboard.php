<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil data session
$role = $_SESSION['user_role'] ?? $_SESSION['role'] ?? 'guru';
$username = $_SESSION['username'] ?? '';
$nama_user = $_SESSION['nama_pengguna'] ?? 'Pengguna';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner p-4 rounded shadow bg-white border-start border-primary border-5">
                <h2 class="fw-bold mb-1">Selamat Datang, <span class="text-primary"><?= htmlspecialchars($nama_user) ?></span>!</h2>
                <p class="text-muted mb-0">Sistem Informasi Poin Pelanggaran Siswa - SMK TI Bali Global</p>
            </div>
        </div>
    </div>

    <?php if (in_array($role, ['admin', 'bk', 'manajemen'])): ?>
        <!-- DASHBOARD ADMIN / BK -->
        <?php
        // Statistik
        $total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa"))['total'];
        $total_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM guru"))['total'];
        $total_pelanggar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT nis) as total FROM pelanggaran_siswa"))['total'];

        // Riwayat Pelanggaran Terbaru
        $query_recent = "SELECT ps.tanggal, s.nama_siswa, jp.jenis, jp.poin 
                        FROM pelanggaran_siswa ps 
                        JOIN siswa s ON ps.nis = s.nis 
                        JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran 
                        ORDER BY ps.tanggal DESC LIMIT 5";
        $result_recent = mysqli_query($conn, $query_recent);
        ?>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-people-fill fs-3 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Siswa</h6>
                            <h3 class="fw-bold mb-0"><?= $total_siswa ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-exclamation-octagon-fill fs-3 text-danger"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Siswa Melanggar</h6>
                            <h3 class="fw-bold mb-0"><?= $total_pelanggar ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="bi bi-person-workspace fs-3 text-success"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Guru/Staff</h6>
                            <h3 class="fw-bold mb-0"><?= $total_guru ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Pelanggaran Terbaru</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive rounded">
                            <table class="table table-hover align-middle mb-0 rounded">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Tanggal</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Pelanggaran</th>
                                        <th class="text-center">Poin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($result_recent)): ?>
                                    <tr>
                                        <td class="ps-4"><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                        <td><?= htmlspecialchars($row['jenis']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-danger rounded-pill"><?= $row['poin'] ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if(mysqli_num_rows($result_recent) == 0): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data pelanggaran</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-center py-3">
                        <a href="/SistemPoin/pages/laporan/pelanggaran_siswa.php" class="btn btn-sm btn-outline-primary">Lihat Semua Laporan</a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($role == 'siswa'): ?>
        <!-- DASHBOARD SISWA -->
        <?php
        $nis = $username;
        $query_my_recent = "SELECT ps.tanggal, jp.jenis, jp.poin, ps.keterangan 
                           FROM pelanggaran_siswa ps 
                           JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran 
                           WHERE ps.nis = '$nis' 
                           ORDER BY ps.tanggal DESC LIMIT 5";
        $result_my_recent = mysqli_query($conn, $query_my_recent);
        
        $my_poin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jp.poin) as total FROM pelanggaran_siswa ps JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran WHERE ps.nis = '$nis'"))['total'] ?? 0;

        // Query Riwayat Surat
        $query_my_surat = "SELECT sk.no_surat, sk.jenis_surat, sk.status_surat, sk.tanggal_pembuatan_surat 
                          FROM surat_keluar sk 
                          WHERE sk.nis = '$nis' 
                          ORDER BY sk.tanggal_pembuatan_surat DESC LIMIT 5";
        $result_my_surat = mysqli_query($conn, $query_my_surat);
        ?>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <h6 class="opacity-75 mb-2">Total Poin Kamu</h6>
                        <h1 class="display-4 fw-bold mb-0"><?= $my_poin ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Riwayat Pelanggaran Terakhirmu</h5>
                    </div>
                    <div class="card-body p-0 rounded">
                        <div class="table-responsive rounded">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Tanggal</th>
                                        <th>Jenis Pelanggaran</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Poin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($result_my_recent)): ?>
                                    <tr>
                                        <td class="ps-4"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($row['jenis']) ?></td>
                                        <td class="small text-muted"><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark rounded-pill"><?= $row['poin'] ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if(mysqli_num_rows($result_my_recent) == 0): ?>
                                        <tr><td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-check-circle text-success fs-1 d-block mb-2"></i>
                                            Hebat! Kamu belum memiliki catatan pelanggaran.
                                        </td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-envelope-paper me-2 text-primary"></i>Riwayat Surat Terbaru</h5>
                    </div>
                    <div class="card-body p-0 rounded">
                        <div class="table-responsive rounded">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">No. Surat</th>
                                        <th>Jenis Surat</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($result_my_surat)): ?>
                                    <tr>
                                        <td class="ps-4"><?= htmlspecialchars($row['no_surat'] ?: '-') ?></td>
                                        <td><?= htmlspecialchars($row['jenis_surat']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal_pembuatan_surat'])) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $status = $row['status_surat'];
                                            $badge_class = ($status == 'Sudah dicetak') ? 'bg-info' : (($status == 'Selesai') ? 'bg-success' : 'bg-secondary');
                                            ?>
                                            <span class="badge <?= $badge_class ?> rounded-pill"><?= $status ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if(mysqli_num_rows($result_my_surat) == 0): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat surat</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- DASHBOARD GURU MAPEL -->
        <div class="row justify-content-center mt-5">
            <!-- <div class="col-md-8 text-center">
                <img src="/SistemPoin/assets/img/welcome_guru.svg" alt="Welcome" class="img-fluid mb-4" style="max-height: 250px;">
                <h4 class="text-muted">Gunakan menu di samping untuk melihat data siswa atau menginput pelanggaran baru.</h4>
            </div> -->
        </div>
    <?php endif; ?>
</div>

<style>
    .welcome-banner {
        border-left: 5px solid #0d6efd !important;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>

<?php include ROOTPATH . "/includes/footer.php"; ?>