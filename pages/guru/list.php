<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'Y'");
$total_guru = mysqli_num_rows($result);
$query_kelas = mysqli_query($conn, "SELECT tingkat, program_keahlian, rombel FROM kelas 
                JOIN tingkat USING(id_tingkat) 
                JOIN program_keahlian USING(id_program_keahlian)");

?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Kelola <span class="text-primary fst-italic">Data Guru</span>
            </h2>
            <small class="text-muted">Total Data: <?php echo $total_guru ?></small>
        </div>
        
        <div class="col-md-8">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari nama atau NIS..." name="nama">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="add.php" class="btn btn-primary py-2"><i class="bi bi-person-fill-add me-1"></i>Tambah Guru</a>
            </form>
        </div>
    </div>

    <div class="table-container" style="max-height: 500px; overflow-y: auto;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark-custom" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th class="py-2 ps-4">KODE GURU</th>
                        <th>NAMA</th>
                        <th>USERNAME</th>
                        <th>JABATAN</th>
                        <th class="text-center">NOMOR TELFON</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $total_guru): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $total_guru['kode_guru']?></td>
                            <td class="fw-semibold"><?= $total_guru['nama_pengguna']?></td>
                            <td class="fw-semibold"><?= $total_guru['username']?></td>
                            <td><?= htmlspecialchars($total_guru['jabatan']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($total_guru['telp']) ?></td>
                            <td class="text-center d-flex justify-content-center gap-1  px-0">
                                <!-- <a class="btn-action d-inline-block btn-detail" title="Detail" href="details.php?kode_guru=<?= $total_guru['kode_guru'] ?>" ><i class="bi bi-eye-fill"></i></a> -->
                                <a class="btn-action btn-edit"href="edit.php?kode_guru=<?= $total_guru['kode_guru'] ?>" title="Edit"><i class="bi bi-pencil-fill"></i></a>    
                                <form action="/SistemPoin/process/guru_process.php" method="post"onsubmit="return confirm('Ingin Menghapus data <?= $total_guru['nama_pengguna'] ?>?')">
                                    <!-- Kirim id dan action ke file proses -->
                                    <input type="hidden" name="kode_guru" value="<?= $total_guru['kode_guru'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn-action btn-delete" title="Hapus" type="submit"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>