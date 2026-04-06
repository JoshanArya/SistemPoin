<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Query for pindah sekolah
$sql = "SELECT sk.id_surat_keluar, sk.no_surat, sk.tanggal_pembuatan_surat, s.nis, s.nama_siswa,
    CONCAT(t.tingkat, ' ', p.program_keahlian, ' ', k.rombel) as kelas_name, sp.sekolah_tujuan, sp.alasan_pindah
    FROM surat_keluar sk 
    JOIN siswa s ON sk.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
    JOIN surat_pindah sp ON sk.id_surat_pindah = sp.id_surat_pindah
    WHERE sk.jenis_surat = 'Pindah Sekolah'";

// Handle search
$search = $_POST['search'] ?? $_GET['search'] ?? '';
if ($search) {
    $s = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (s.nis LIKE '%$s%' OR s.nama_siswa LIKE '%$s%' OR sk.no_surat LIKE '%$s%' OR sp.sekolah_tujuan LIKE '%$s%')";
}
$sql .= " ORDER BY sk.tanggal_pembuatan_surat DESC";

$result = mysqli_query($conn, $sql);
$total_pindah = mysqli_num_rows($result);
?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-5">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Laporan <span class="text-primary fst-italic">Surat Pindah Sekolah</span>
            </h2>
            <small class="text-muted">Total Surat: <?= $total_pindah ?></small>
        </div>

        <div class="col-md-7">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2 align-items-center">
                <input type="text" class="form-control" style="max-width: 300px;"
                    placeholder="Cari NIS, nama, no surat, atau sekolah tujuan..." name="search"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <?php if ($search): ?>
                    <a href="pindah_sekolah.php" class="btn btn-secondary">
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
                    <th>No Surat</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Sekolah Tujuan</th>
                    <th>Tanggal Surat</th>
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
                        <td class="fw-semibold"><?= htmlspecialchars($row['no_surat']) ?></td>
                        <td><?= htmlspecialchars($row['nis']) ?></td>
                        <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                        <td><?= htmlspecialchars($row['kelas_name']) ?></td>
                        <td><?= htmlspecialchars($row['sekolah_tujuan']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal_pembuatan_surat'])) ?></td>
                        <td class="text-center d-flex justify-content-center gap-1 align-items-center px-0">
                            <a href="../cetak/surat_pindah_sekolah.php?id=<?= $row['id_surat_keluar'] ?>&nis=<?= $row['nis'] ?>" 
                               class="btn-action btn-detail" title="Cetak Ulang">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($total_pindah == 0): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-x fs-1 d-block mb-3"></i>
                            Tidak ada surat pindah sekolah
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