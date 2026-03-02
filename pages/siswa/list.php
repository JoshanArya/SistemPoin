<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM siswa
                JOIN kelas USING(id_kelas)
                JOIN tingkat USING(id_tingkat)
                JOIN program_keahlian USING(id_program_keahlian)");
$total_siswa = mysqli_num_rows($result);
$query_kelas = mysqli_query($conn, "SELECT tingkat, program_keahlian, rombel FROM kelas 
                JOIN tingkat USING(id_tingkat) 
                JOIN program_keahlian USING(id_program_keahlian)");

?>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;">
                Kelola <span class="text-primary fst-italic">Data Siswa</span>
            </h2>
            <small class="text-muted">Total Data: <?php echo $total_siswa ?></small>
        </div>
        
        <div class="col-md-8">
            <form action="" method="POST" class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari nama atau NIS..." name="nama">
                <input type="hidden" name="kelas" id="kelas" value="">
                <div class="dropdown w-25 border rounded">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_kelas" data-bs-toggle="dropdown">
                        Semua Kelas
                    </button>
                    <ul class="dropdown-menu kelas w-100 text-start">
                        <?php while($k = mysqli_fetch_assoc($query_kelas)): ?>
                        <li>
                            <a class="dropdown-item" href="#" onclick="setDropdown('kelas', 'dropdown_kelas', this.innerText, this.innerText)">
                                <?= $k['tingkat'].' '.$k['program_keahlian'].' '.$k['rombel'] ?>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <div class="dropdown w-25 border rounded">
                    <input type="hidden" name="status_siswa" id="status" value="">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" id="dropdown_status" type="button">Semua Status</button>
                    <ul class="dropdown-menu w-100 text-start">
                        <li><a class="dropdown-item" href="#" onclick="setDropdown('status', 'dropdown_status', this.innerText, 'aktif')">Aktif</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setDropdown('status', 'dropdown_status', this.innerText, 'tidak_aktif')">Tidak Aktif</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setDropdown('status', 'dropdown_status', this.innerText, 'pindah')">Pindah Sekolah</a></li>
                        <li><a class="dropdown-item" href="#" onclick="setDropdown('status', 'dropdown_status', this.innerText, 'lulus')">Lulus</a></li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="add.php" class="btn btn-primary w-25 py-2"><i class="bi bi-person-fill-add me-1"></i>Tambah Murid</a>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark-custom">
                    <tr>
                        <th class="py-2 ps-4">NIS</th>
                        <th>NAMA</th>
                        <th>KELAS</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $total_siswa): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $total_siswa['nis']?></td>
                            <td class="fw-semibold"><?= $total_siswa['nama_siswa']?></td>
                            <td><?= htmlspecialchars($total_siswa['tingkat'] . ' ' . $total_siswa['program_keahlian'] . ' ' . $total_siswa['rombel'] ) ?></td>
                            <td class="text-center">
                                <?php
                                if($total_siswa['status_siswa'] == 'aktif') {
                                    echo '<span class="badge rounded-pill badge-aktif px-3 py-2">Aktif</span>';
                                } elseif($total_siswa['status_siswa'] == 'lulus') {
                                    echo '<span class="badge rounded-pill badge-lulus px-3 py-2">Lulus</span>';
                                } elseif($total_siswa['status_siswa'] == 'tidak_aktif') {
                                    echo '<span class="badge rounded-pill badge-tidak-aktif px-3 py-2">Tidak Aktif</span>';
                                } else {
                                    echo '<span class="badge rounded-pill badge-pindah px-3 py-2">Pindah</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center d-flex justify-content-center gap-1  px-0">
                                <a class="btn-action d-inline-block btn-detail" title="Detail" href="details.php?nis=<?= $total_siswa['nis'] ?>" ><i class="bi bi-eye-fill"></i></a>
                                <a class="btn-action btn-edit"href="edit.php?nis=<?= $total_siswa['nis'] ?>" title="Edit"><i class="bi bi-pencil-fill"></i></a>    
                                <form action="/SistemPoin/process/siswa_process.php" method="post"onsubmit="return confirm('Ingin Menghapus data <?= $total_siswa['nama_siswa'] ?>?')">
                                    <!-- Kirim id dan action ke file proses -->
                                    <input type="hidden" name="nis" value="<?= $total_siswa['nis'] ?>">
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