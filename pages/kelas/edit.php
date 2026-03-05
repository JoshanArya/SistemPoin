<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil ID kelas dari URL
$id_kelas = $_GET['id'] ?? '';

// Ambil data kelas yang akan diedit
$query_kelas = mysqli_query($conn, "
    SELECT k.*, t.tingkat, pk.program_keahlian, pk.deskripsi 
    FROM kelas k
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    WHERE k.id_kelas = '$id_kelas'
");

$kelas = mysqli_fetch_assoc($query_kelas);

if (!$kelas) {
    echo "<script>
        alert('Data kelas tidak ditemukan!');
        window.location.href = 'list.php';
    </script>";
    exit;
}

// Ambil data tingkat
$query_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY id_tingkat");

// Ambil data program keahlian
$query_pk = mysqli_query($conn, "SELECT * FROM program_keahlian ORDER BY program_keahlian");

// Ambil data guru untuk dropdown Wali Kelas (semua guru aktif)
$query_guru = mysqli_query($conn, "SELECT kode_guru, nama_pengguna, jabatan FROM guru WHERE aktif = 'Y' ORDER BY nama_pengguna");

// Ambil data guru BK
$query_guru_bk = mysqli_query($conn, "SELECT kode_guru, nama_pengguna, jabatan FROM guru WHERE aktif = 'Y' AND jabatan LIKE 'Guru BK%' ORDER BY jabatan");

// Ambil guru yang dipilih sebagai Wali Kelas
$wali_terpilih = $kelas['kode_guru'];

// Ambil guru BK yang dipilih - check if kode_guru_bk column exists first
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM kelas LIKE 'kode_guru_bk'");
if (mysqli_num_rows($check_column) > 0 && !empty($kelas['kode_guru_bk'])) {
    // Use explicitly selected BK teacher
    $kode_bk_terpilih = $kelas['kode_guru_bk'];
} else {
    // Fallback to BK teacher based on tingkat
    $query_bk_terpilih = mysqli_query($conn, "
        SELECT kode_guru, nama_pengguna, jabatan 
        FROM guru 
        WHERE aktif = 'Y' AND jabatan = CONCAT('Guru BK ', '{$kelas['tingkat']}')
    ");
    $guru_bk_terpilih = mysqli_fetch_assoc($query_bk_terpilih);
    $kode_bk_terpilih = $guru_bk_terpilih['kode_guru'] ?? '';
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        Edit <span class="text-primary fst-italic">Data Kelas</span>
                    </h2>
                </div>

                <form action="/SistemPoin/process/kelas_process.php" method="POST">
                    <input type="hidden" name="action" value="edit" />
                    <input type="hidden" name="id" value="<?= $kelas['id_kelas'] ?>" />

                    <div class="form-section-title">
                        <i class="bi bi-building"></i> Informasi Kelas
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Tingkat</label>
                            <input type="hidden" name="id_tingkat" id="id_tingkat" value="<?= $kelas['id_tingkat'] ?>">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_tingkat" data-bs-toggle="dropdown">
                                    <?= $kelas['tingkat'] ?>
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php while($tingkat = mysqli_fetch_assoc($query_tingkat)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('id_tingkat', 'dropdown_tingkat', this.innerText, '<?= $tingkat['id_tingkat'] ?>')">
                                            <?= $tingkat['tingkat'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Program Keahlian</label>
                            <input type="hidden" name="id_program_keahlian" id="id_program_keahlian" value="<?= $kelas['id_program_keahlian'] ?>">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_pk" data-bs-toggle="dropdown">
                                    <?= $kelas['program_keahlian'] ?> - <?= $kelas['deskripsi'] ?>
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php mysqli_data_seek($query_pk, 0); ?>
                                    <?php while($pk = mysqli_fetch_assoc($query_pk)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('id_program_keahlian', 'dropdown_pk', '<?= $pk['program_keahlian'] . ' - ' . $pk['deskripsi'] ?>', '<?= $pk['id_program_keahlian'] ?>')">
                                            <?= $pk['program_keahlian'] ?> - <?= $pk['deskripsi'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Rombel</label>
                            <input type="number" name="rombel" class="form-control" value="<?= $kelas['rombel'] ?>" min="1" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Informations Guru
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Wali Kelas</label>
                            <input type="hidden" name="kode_guru" id="kode_guru" value="<?= $wali_terpilih ?>">
                            <div class="dropdown border rounded">
                                <?php 
                                // Ambil nama guru terpilih
                                $wali_nama = "Pilih Wali Kelas";
                                if ($wali_terpilih) {
                                    $q_wali = mysqli_query($conn, "SELECT nama_pengguna, jabatan FROM guru WHERE kode_guru = '$wali_terpilih'");
                                    if ($w = mysqli_fetch_assoc($q_wali)) {
                                        $wali_nama = $w['nama_pengguna'];
                                    }
                                }
                                ?>
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_wali" data-bs-toggle="dropdown">
                                    <?= $wali_nama ?>
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru', 'dropdown_wali', 'Tidak Ada', '')">
                                            <em>Tidak Ada</em>
                                        </a>
                                    </li>
                                    <?php mysqli_data_seek($query_guru, 0); ?>
                                    <?php while($guru = mysqli_fetch_assoc($query_guru)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru', 'dropdown_wali', '<?= htmlspecialchars($guru['nama_pengguna']) ?>', '<?= $guru['kode_guru'] ?>')">
                                            <?= $guru['nama_pengguna'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Guru BK</label>
                            <input type="hidden" name="kode_guru_bk" id="kode_guru_bk" value="<?= $kode_bk_terpilih ?>">
                            <div class="dropdown border rounded">
                                <?php 
                                // Ambil nama guru BK terpilih
                                $bk_nama = "Pilih Guru BK";
                                if ($kode_bk_terpilih) {
                                    $q_bk = mysqli_query($conn, "SELECT nama_pengguna, jabatan FROM guru WHERE kode_guru = '$kode_bk_terpilih'");
                                    if ($bk = mysqli_fetch_assoc($q_bk)) {
                                        $bk_nama = $bk['nama_pengguna'];
                                    }
                                }
                                ?>
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_bk" data-bs-toggle="dropdown">
                                    <?= $bk_nama ?>
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru_bk', 'dropdown_bk', 'Tidak Ada', '')">
                                            <em>Tidak Ada</em>
                                        </a>
                                    </li>
                                    <?php mysqli_data_seek($query_guru_bk, 0); ?>
                                    <?php while($guru_bk = mysqli_fetch_assoc($query_guru_bk)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru_bk', 'dropdown_bk', '<?= htmlspecialchars($guru_bk['nama_pengguna']) ?>', '<?= $guru_bk['kode_guru'] ?>')">
                                            <?= $guru_bk['nama_pengguna'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="list.php" class="btn btn-cancel shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-save shadow-sm border">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>

