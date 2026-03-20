<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Query untuk mengambil data kelas dengan Wali Kelas dan Guru BK
// Check if kode_guru_bk column exists
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM kelas LIKE 'kode_guru_bk'");
if (mysqli_num_rows($check_column) > 0) {
    // Column exists - use it
    $result = mysqli_query($conn, "
        SELECT 
            k.id_kelas,
            k.rombel,
            t.tingkat,
            pk.program_keahlian,
            g_wali.nama_pengguna AS nama_wali_kelas,
            COALESCE(g_bk_selected.nama_pengguna, g_bk_jabatan.nama_pengguna) AS nama_guru_bk
        FROM kelas k
        JOIN tingkat t ON k.id_tingkat = t.id_tingkat
        JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
        LEFT JOIN guru g_wali ON k.kode_guru = g_wali.kode_guru
        LEFT JOIN guru g_bk_selected ON k.kode_guru_bk = g_bk_selected.kode_guru
        LEFT JOIN guru g_bk_jabatan ON (
            g_bk_jabatan.jabatan = CONCAT('Guru BK ', t.tingkat)
        )
        ORDER BY t.id_tingkat, pk.program_keahlian, k.rombel
    ");
} else {
    // Column doesn't exist - use fallback only
    $result = mysqli_query($conn, "
        SELECT 
            k.id_kelas,
            k.rombel,
            t.tingkat,
            pk.program_keahlian,
            g_wali.nama_pengguna AS nama_wali_kelas,
            g_bk.nama_pengguna AS nama_guru_bk
        FROM kelas k
        JOIN tingkat t ON k.id_tingkat = t.id_tingkat
        JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
        LEFT JOIN guru g_wali ON k.kode_guru = g_wali.kode_guru
        LEFT JOIN guru g_bk ON (
            g_bk.jabatan = CONCAT('Guru BK ', t.tingkat)
        )
        ORDER BY t.id_tingkat, pk.program_keahlian, k.rombel
    ");
}

$total_kelas = mysqli_num_rows($result);
?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Kelola <span class="text-primary fst-italic">Kelas</span>
            </h2>
            <small class="text-muted">Total Data: <?php echo $total_kelas ?></small>
        </div>

        <div class="col-md-8">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari kelas..." name="nama">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>
                <a href="add.php" class="btn btn-primary py-2">
                    <i class="bi bi-plus-lg"></i> Tambah Kelas
                </a>
            </form>
        </div>
    </div>

    <div class="table-container" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-hover mb-0">
            <thead class="table-dark-custom">
                <tr>
                    <th class="text-center" style="width: 80px;">NO</th>
                    <th>KELAS</th>
                    <th class="text-start">WALI KELAS</th>
                    <th class="text-start">GURU BK</th>
                    <th class="text-center" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td class="text-center fw-semibold"><?= $no++ ?></td>
                        <td class="fw-semibold"><?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?></td>
                        <td class="text-start">
                            <?= $row['nama_wali_kelas'] ? $row['nama_wali_kelas'] : '<span class="text-muted">-</span>' ?>
                        </td>
                        <td class="text-start">
                            <?= $row['nama_guru_bk'] ? $row['nama_guru_bk'] : '<span class="text-muted">-</span>' ?>
                        </td>
                        <td class="text-center d-flex justify-content-center gap-1 px-0">
                            <a class="btn-action btn-edit" href="edit.php?id=<?= $row['id_kelas'] ?>" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="/SistemPoin/process/kelas_process.php" method="post"
                                onsubmit="return confirm('Ingin Menghapus data kelas <?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?>?')">
                                <input type="hidden" name="id" value="<?= $row['id_kelas'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button class="btn-action btn-delete" title="Hapus" type="submit">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($total_kelas == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Tidak ada data kelas
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>