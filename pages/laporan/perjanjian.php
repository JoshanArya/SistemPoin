<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Query UNION perjanjian ortu + siswa
$sql = "SELECT po.id_perjanjian_ortu as id, po.tanggal, 'Perjanjian Ortu' as jenis, s.nis, s.nama_siswa,
    CONCAT(t.tingkat, ' ', p.program_keahlian, ' ', k.rombel) as kelas_name, po.status
    FROM perjanjian_orang_tua po 
    JOIN pelanggaran_siswa ps ON po.id_pelanggaran_siswa = ps.id_pelanggaran_siswa
    JOIN siswa s ON ps.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian

    UNION ALL

    SELECT ps.id_perjanjian_siswa as id, ps.tanggal, 'Perjanjian Siswa' as jenis, s.nis, s.nama_siswa,
    CONCAT(t.tingkat, ' ', p.program_keahlian, ' ', k.rombel) as kelas_name, ps.status
    FROM perjanjian_siswa ps
    JOIN pelanggaran_siswa pps ON ps.id_pelanggaran_siswa = pps.id_pelanggaran_siswa
    JOIN siswa s ON pps.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian";

// Handle search
$search = $_POST['search'] ?? $_GET['search'] ?? '';
if ($search) {
    $sql .= " WHERE (s.nis LIKE '%$search%' OR s.nama_siswa LIKE '%$search%')";
}
$sql .= " ORDER BY tanggal DESC";

$result = mysqli_query($conn, $sql);
$total_perjanjian = mysqli_num_rows($result);
?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Laporan <span class="text-primary fst-italic">Surat Perjanjian</span>
            </h2>
            <small class="text-muted">Total Perjanjian: <?= $total_perjanjian ?></small>
        </div>

        <div class="col-md-6">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari NIS atau nama siswa..." name="search"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <?php if ($search): ?>
                    <a href="perjanjian.php" class="btn btn-secondary">
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
                    <th>Kelas</th>
                    <th>Jenis Perjanjian</th>
                    <th>Status</th>
                    <th>Tanggal</th>
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
                        <td><?= htmlspecialchars($row['kelas_name']) ?></td>
                        <td><?= htmlspecialchars($row['jenis']) ?></td>
                        <td>
                            <span class="badge <?= $row['status'] == 'Selesai' ? 'bg-success' : 'bg-warning' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td class="text-center">
                            <a class="btn-action btn-detail"
                                href="../cetak/surat_<?= strtolower(str_replace(' ', '_', trim($row['jenis']))) ?>.php?id=<?= $row['id'] ?>"
                                title="Lihat/Cetak">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($total_perjanjian == 0): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x fs-1 d-block mb-3"></i>
                            Tidak ada surat perjanjian
                            <?php if ($search): ?>
                                <div class="small mt-2">Coba ubah kata kunci pencarian</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . '/includes/footer.php'; ?>