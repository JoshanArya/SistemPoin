<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Base query for violations summary per student
$sql = "SELECT 
    s.nis, 
    s.nama_siswa,
    COUNT(ps.id_pelanggaran_siswa) as total_pelanggaran,
    SUM(jp.poin) as total_poin,
    MAX(ps.tanggal) as tanggal_terakhir
FROM siswa s
INNER JOIN pelanggaran_siswa ps ON s.nis = ps.nis
INNER JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
WHERE s.status_siswa = 'aktif'";

// Handle search
$search = $_POST['search'] ?? $_GET['search'] ?? '';
if ($search) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (s.nis LIKE '%$search_escaped%' OR s.nama_siswa LIKE '%$search_escaped%')";
}

$sql .= " GROUP BY s.nis, s.nama_siswa";
$sql .= " ORDER BY total_poin DESC, tanggal_terakhir DESC";

$result = mysqli_query($conn, $sql);
$total_laporan = mysqli_num_rows($result);
?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Daftar <span class="text-primary fst-italic">Laporan Pelanggaran Siswa</span>
            </h2>
            <small class="text-muted">Total Siswa Bermasalah: <?= $total_laporan ?></small>
        </div>

        <div class="col-md-6">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari NIS atau nama siswa..." name="search"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <?php if ($search): ?>
                    <a href="pelanggaran_siswa.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="table-container shadow-lg" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-hover mb-0">
            <thead class="table-dark-custom">
                <tr>
                    <th class="text-center" style="width: 60px;">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th class="text-center" style="width: 200px;">Jumlah Pelanggaran</th>
                    <th class="text-center" style="width: 100px;">Total Poin</th>
                    <th class="text-center" style="width: 150px;">Tanggal Terakhir</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td class="text-center fw-semibold"><?= $no++ ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['nis']) ?></td>
                        <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                        <td class="text-center fw-semibold text-danger"><?= $row['total_pelanggaran'] ?> kali</td>
                        <td class="text-center">
                            <span class="poin-badge-detail bg-danger text-white"><?= $row['total_poin'] ?></span>
                        </td>
                        <td class="text-center">
                            <?= date('d/m/Y', strtotime($row['tanggal_terakhir'])) ?>
                        </td>
                        <td class="text-center">
                            <a class="btn-action btn-detail" href="detail_pelanggaran_siswa.php?nis=<?= $row['nis'] ?>"
                                title="Lihat Detail">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($total_laporan == 0): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x fs-1 d-block mb-3"></i>
                            <div>Tidak ada laporan pelanggaran siswa</div>
                            <?php if ($search): ?>
                                <div class="small">Coba ubah kata kunci pencarian</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . '/includes/footer.php'; ?>