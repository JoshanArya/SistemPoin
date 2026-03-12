<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil data untuk dropdown kelas
$query_kelas_dropdown = mysqli_query($conn, "SELECT tingkat, program_keahlian, rombel FROM kelas 
                JOIN tingkat USING(id_tingkat) 
                JOIN program_keahlian USING(id_program_keahlian)
                ORDER BY tingkat.id_tingkat, program_keahlian.program_keahlian, kelas.rombel");

// Ambil data filter dari form
$search = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$kelas_filter = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
$status_filter = isset($_POST['status_siswa']) ? trim($_POST['status_siswa']) : '';

// Build WHERE clause
$conditions = [];

// Search by nama or NIS
if (!empty($search)) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(siswa.nama_siswa LIKE '%$search_escaped%' OR siswa.nis LIKE '%$search_escaped%')";
}

// Filter by kelas
if (!empty($kelas_filter) && $kelas_filter != 'Semua Kelas') {
    $kelas_parts = explode(' ', $kelas_filter);
    if (count($kelas_parts) >= 3) {
        $tingkat = mysqli_real_escape_string($conn, $kelas_parts[0]);
        $program_keahlian = mysqli_real_escape_string($conn, $kelas_parts[1]);
        $rombel = mysqli_real_escape_string($conn, $kelas_parts[2]);
        $conditions[] = "tingkat.tingkat = '$tingkat' AND program_keahlian.program_keahlian = '$program_keahlian' AND kelas.rombel = '$rombel'";
    }
}

// Filter by status
if (!empty($status_filter) && $status_filter != 'Semua Status') {
    $status_escaped = mysqli_real_escape_string($conn, $status_filter);
    $conditions[] = "siswa.status_siswa = '$status_escaped'";
}

// Build query
$sql_where = count($conditions) > 0 ? " WHERE " . implode(" AND ", $conditions) : "";

// Query untuk mengambil data siswa dengan filter
$result = mysqli_query($conn, "SELECT siswa.*, 
                CONCAT(tingkat.tingkat, ' ', program_keahlian.program_keahlian, ' ', kelas.rombel) as kelas_name
                FROM siswa
                JOIN kelas USING(id_kelas)
                JOIN tingkat USING(id_tingkat)
                JOIN program_keahlian USING(id_program_keahlian)" . $sql_where . " 
                ORDER BY siswa.nis ASC");

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
                <input type="text" class="form-control w-50" placeholder="Cari nama atau NIS..." name="nama" value="<?= htmlspecialchars($search) ?>">
                <input type="hidden" name="kelas" id="kelas" value="<?= htmlspecialchars($kelas_filter) ?>">
                <div class="dropdown w-25 border rounded">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_kelas" data-bs-toggle="dropdown">
                        <?= !empty($kelas_filter) ? htmlspecialchars($kelas_filter) : 'Semua Kelas' ?>
                    </button>
                    <ul class="dropdown-menu kelas w-100 text-start">
                        <li>
                            <a class="dropdown-item" href="#" onclick="setDropdown('kelas', 'dropdown_kelas', 'Semua Kelas', '')">
                                Semua Kelas
                            </a>
                        </li>
                        <?php while($k = mysqli_fetch_assoc($query_kelas_dropdown)): ?>
                        <li>
                            <a class="dropdown-item" href="#" onclick="setDropdown('kelas', 'dropdown_kelas', this.innerText, this.innerText)">
                                <?= $k['tingkat'].' '.$k['program_keahlian'].' '.$k['rombel'] ?>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <div class="dropdown w-25 border rounded">
                    <input type="hidden" name="status_siswa" id="status" value="<?= htmlspecialchars($status_filter) ?>">
                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" id="dropdown_status" type="button">
                        <?= !empty($status_filter) ? htmlspecialchars($status_filter) : 'Semua Status' ?>
                    </button>
                    <ul class="dropdown-menu w-100 text-start">
                        <li><a class="dropdown-item" href="#" onclick="setDropdown('status', 'dropdown_status', 'Semua Status', '')">Semua Status</a></li>
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

    <div class="table-container" style="max-height: 500px; overflow-y: auto;">
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
                    <?php if($total_siswa > 0): ?>
                        <?php foreach($result as $siswa): ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $siswa['nis']?></td>
                            <td class="fw-semibold"><?= $siswa['nama_siswa']?></td>
                            <td><?= htmlspecialchars($siswa['kelas_name']) ?></td>
                            <td class="text-center">
                                <?php
                                if($siswa['status_siswa'] == 'aktif') {
                                    echo '<span class="badge rounded-pill badge-aktif px-3 py-2">• Aktif</span>';
                                } elseif($siswa['status_siswa'] == 'lulus') {
                                    echo '<span class="badge rounded-pill badge-lulus px-3 py-2">• Lulus</span>';
                                } elseif($siswa['status_siswa'] == 'tidak_aktif') {
                                    echo '<span class="badge rounded-pill badge-tidak-aktif px-3 py-2">• Tidak Aktif</span>';
                                } else {
                                    echo '<span class="badge rounded-pill badge-pindah px-3 py-2">• Pindah</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center d-flex justify-content-center gap-1  px-0">
                                <a class="btn-action d-inline-block btn-detail" title="Detail" href="details.php?nis=<?= $siswa['nis'] ?>" ><i class="bi bi-eye-fill"></i></a>
                                <a class="btn-action btn-edit"href="edit.php?nis=<?= $siswa['nis'] ?>" title="Edit"><i class="bi bi-pencil-fill"></i></a>    
                                <form action="/SistemPoin/process/siswa_process.php" method="post"onsubmit="return confirm('Ingin Menghapus data <?= $siswa['nama_siswa'] ?>?')">
                                    <!-- Kirim id dan action ke file proses -->
                                    <input type="hidden" name="nis" value="<?= $siswa['nis'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn-action btn-delete" title="Hapus" type="submit"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada data siswa ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>

