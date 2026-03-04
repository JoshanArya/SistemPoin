<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY id_jenis_pelanggaran");
$total_pelanggaran = mysqli_num_rows($result);

?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Kelola <span class="text-primary fst-italic">Jenis Pelanggaran</span>
            </h2>
            <small class="text-muted">Total Data: <?php echo $total_pelanggaran ?></small>
        </div>
        
        <div class="col-md-8">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari jenis pelanggaran..." name="nama">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>
                <a href="add.php" class="btn btn-primary py-2">
                    <i class="bi bi-plus-lg"></i> Tambah Pelanggaran
                </a>
            </form>
        </div>
    </div>

    <div class="table-container shadow-lg" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-hover mb-0">
            <thead class="table-dark-custom">
                <tr>
                    <th class="text-center" style="width: 80px;">No</th>
                    <th>Jenis Pelanggaran</th>
                    <th class="text-center" style="width: 120px;">Point</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td class="text-center fw-semibold"><?= $no++ ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($row['jenis']) ?></td>
                    <td class="text-center">
                        <span class="poin-badge-detail"><?= $row['poin'] ?></span>
                    </td>
                    <td class="text-center d-flex justify-content-center gap-1 px-0">
                        <a class="btn-action btn-edit" href="edit.php?id=<?= $row['id_jenis_pelanggaran'] ?>" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>    
                        <form action="/SistemPoin/process/pelanggaran_process.php" method="post" onsubmit="return confirm('Ingin Menghapus data <?= $row['jenis'] ?>?')">
                            <input type="hidden" name="id" value="<?= $row['id_jenis_pelanggaran'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn-action btn-delete" title="Hapus" type="submit">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                
                <?php if($total_pelanggaran == 0): ?>
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Tidak ada data pelanggaran
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
