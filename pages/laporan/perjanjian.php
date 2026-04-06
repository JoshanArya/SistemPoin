<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Handle search
$search = $_POST['search'] ?? $_GET['search'] ?? '';

// Filter status: default pertama kali tampil adalah 'Masih Proses'
$status_filter = $_POST['status'] ?? 'Semua';

$sql = "SELECT * FROM (
    SELECT sk.id_surat_keluar as id, po.tanggal, 'Perjanjian Ortu' as jenis, s.nis, s.nama_siswa,
    CONCAT(t.tingkat, ' ', p.program_keahlian, ' ', k.rombel) as kelas_name, po.status
    FROM perjanjian_orang_tua po 
    JOIN surat_keluar sk ON po.id_perjanjian_ortu = sk.id_perjanjian_ortu
    JOIN pelanggaran_siswa ps ON po.id_pelanggaran_siswa = ps.id_pelanggaran_siswa
    JOIN siswa s ON ps.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian

    UNION ALL

    SELECT sk.id_surat_keluar as id, ps.tanggal, 'Perjanjian Siswa' as jenis, s.nis, s.nama_siswa,
    CONCAT(t.tingkat, ' ', p.program_keahlian, ' ', k.rombel) as kelas_name, ps.status
    FROM perjanjian_siswa ps
    JOIN surat_keluar sk ON ps.id_perjanjian_siswa = sk.id_perjanjian_siswa
    JOIN pelanggaran_siswa pps ON ps.id_pelanggaran_siswa = pps.id_pelanggaran_siswa
    JOIN siswa s ON pps.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
) as combined";

$where = [];
if ($search) {
    $s = mysqli_real_escape_string($conn, $search);
    $where[] = "(nis LIKE '%$s%' OR nama_siswa LIKE '%$s%')";
}
if ($status_filter != 'Semua') {
    $st = mysqli_real_escape_string($conn, $status_filter);
    $where[] = "status = '$st'";
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY tanggal DESC";

$result = mysqli_query($conn, $sql);
$total_perjanjian = mysqli_num_rows($result);
?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Laporan <span class="text-primary fst-italic">Surat Perjanjian</span>
            </h2>
            <small class="text-muted">Total Perjanjian: <?= $total_perjanjian ?></small>
        </div>

        <div class="col-md-8">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2 align-items-center">
                <input type="text" class="form-control" style="max-width: 250px;" placeholder="Cari NIS atau nama..." name="search"
                    value="<?= htmlspecialchars($search) ?>">

                <input type="hidden" name="status" id="status" value="<?= htmlspecialchars($status_filter) ?>">
                <div class="dropdown border rounded" style="width: 160px;">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" id="dropdown_status"
                        type="button" data-bs-toggle="dropdown">
                        <?= $status_filter == 'Semua' ? 'Semua Status' : htmlspecialchars($status_filter) ?>
                    </button>
                    <ul class="dropdown-menu w-100 text-start">
                        <li><a class="dropdown-item" href="#"
                                onclick="setDropdown('status', 'dropdown_status', 'Semua Status', 'Semua')">Semua Status</a>
                        </li>
                        <li><a class="dropdown-item" href="#"
                                onclick="setDropdown('status', 'dropdown_status', 'Masih Proses', 'Masih Proses')">Masih Proses</a>
                        </li>
                        <li><a class="dropdown-item" href="#"
                                onclick="setDropdown('status', 'dropdown_status', 'Selesai', 'Selesai')">Selesai</a>
                        </li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <?php if ($search || $status_filter != 'Semua'): ?>
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
                            <?php
                            if ($row['status'] == 'Masih Proses') {
                                echo '<span class="badge rounded-pill badge-surat-belum px-3 py-2">• Masih Proses</span>';
                            } else {
                                echo '<span class="badge rounded-pill badge-surat-selesai px-3 py-2">• Selesai</span>';
                            }
                            ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td class="text-center d-flex justify-content-center gap-1 align-items-center">
                            <a class="btn-action btn-detail"
                                href="../cetak/surat_<?= strtolower(str_replace(' ', '_', trim($row['jenis']))) ?>.php?id=<?= $row['id'] ?>&nis=<?= $row['nis'] ?>"
                                title="Cetak Ulang">
                                <i class="bi bi-printer"></i>
                            </a>
                            <?php if ($row['status'] == 'Masih Proses'): ?>
                                <form action="../../process/surat_process.php" method="POST" class="d-inline"
                                    onsubmit="return confirm('Selesaikan status perjanjian ini?')">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id_surat" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="jenis" value="<?= $row['jenis'] ?>">
                                    <button type="submit" class="btn-action btn-edit" title="Selesaikan">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
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