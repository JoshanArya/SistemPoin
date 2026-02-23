<?php
include '../../includes/header.php';
include '../../config/config.php';

$result = mysqli_query($conn, "SELECT * FROM siswa
JOIN kelas USING(id_kelas)
JOIN tingkat USING(id_tingkat)
JOIN program_keahlian USING(id_program_keahlian)");
$total_siswa = mysqli_num_rows($result);


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
                
                <div class="dropdown w-25 border rounded">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100" type="button">Semua Kelas</button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#" onclick="this.closest('.dropdown').querySelector('button').innerText=this.innerText">XII RPL 1</a></li>
                        <li><a class="dropdown-item" href="#" onclick="this.closest('.dropdown').querySelector('button').innerText=this.innerText">XII RPL 2</a></li>
                    </ul>
                </div>

                <div class="dropdown w-25 border rounded">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100" type="button">Semua Status</button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#" onclick="this.closest('.dropdown').querySelector('button').innerText=this.innerText">Aktif</a></li>
                        <li><a class="dropdown-item" href="#" onclick="this.closest('.dropdown').querySelector('button').innerText=this.innerText">Lulus</a></li>
                        <li><a class="dropdown-item" href="#" onclick="this.closest('.dropdown').querySelector('button').innerText=this.innerText">Pindah</a></li>
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
                                if($total_siswa['status'] == 'aktif') {
                                    echo '<span class="badge rounded-pill badge-aktif px-3 py-2">Aktif</span>';
                                } elseif($total_siswa['status'] == 'lulus') {
                                    echo '<span class="badge rounded-pill badge-lulus px-3 py-2">Lulus</span>';
                                } else {
                                    echo '<span class="badge rounded-pill badge-pindah px-3 py-2">Pindah</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <button class="btn-action btn-detail" title="Detail"><i class="bi bi-eye-fill"></i></button>
                                <button class="btn-action btn-edit" title="Edit"><i class="bi bi-pencil-fill"></i></button>
                                <button class="btn-action btn-delete" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>