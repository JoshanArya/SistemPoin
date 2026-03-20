<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Role check - Guru/BK only
if (!in_array($_SESSION['user_role'] ?? 'guru', ['guru', 'bk'])) {
    echo "<script>alert('Akses ditolak');window.location.href='/SistemPoin/pages/dashboard.php';</script>";
    exit;
}

// Get siswa for dropdown
$siswa_query = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa");

// Get jenis pelanggaran
$jenis_query = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY jenis");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        Input <span class="text-primary fst-italic">Pelanggaran Siswa</span>
                    </h2>
                </div>

                <form action="/SistemPoin/process/pelanggaran_siswa_process.php" method="POST" class="needs-validation"
                    novalidate>
                    <input type="hidden" name="action" value="add">

                    <div class="form-section-title">
                        <i class="bi bi-person-exclamation"></i> Siswa Pelanggar
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Siswa <span class="text-danger">*</span></label>
                            <input type="hidden" name="nis" id="nis" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_siswa" data-bs-toggle="dropdown">
                                    Pilih Siswa
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php while ($s = mysqli_fetch_assoc($siswa_query)): ?>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="setDropdown('nis', 'dropdown_siswa', '<?= $s['nis'] ?> | <?= htmlspecialchars($s['nama_siswa']) ?>', '<?= $s['nis'] ?>')">
                                                <?= $s['nis'] ?> | <?= htmlspecialchars($s['nama_siswa']) ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="bi bi-exclamation-triangle"></i> Detail Pelanggaran
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Pelanggaran <span class="text-danger">*</span></label>
                            <input type="hidden" name="id_jenis_pelanggaran" id="id_jenis_pelanggaran" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_jenis" data-bs-toggle="dropdown">
                                    Pilih Jenis Pelanggaran
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php while ($jp = mysqli_fetch_assoc($jenis_query)): ?>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="setDropdown('id_jenis_pelanggaran', 'dropdown_jenis', '<?= htmlspecialchars($jp['jenis']) ?> (<?= $jp['poin'] ?> Poin)', '<?= $jp['id_jenis_pelanggaran'] ?>')">
                                                <?= htmlspecialchars($jp['jenis']) ?> (<?= $jp['poin'] ?> Poin)
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Pelanggaran</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>"
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Pelanggaran</label>
                        <textarea name="keterangan" class="form-control" rows="4"
                            placeholder="Detail kejadian pelanggaran (lokasi, waktu, saksi, dll).."></textarea>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="list.php" class="btn btn-cancel shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-save shadow-sm border">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Pelanggaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>