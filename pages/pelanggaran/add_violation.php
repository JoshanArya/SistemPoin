<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Role check - Guru/BK/Admin only
if (!in_array($_SESSION['user_role'] ?? 'guru', ['bk', 'admin', 'guru'])) {
    echo "<script>alert('Akses ditolak');window.location.href='/SistemPoin/pages/dashboard.php';</script>";
    exit;
}

// Ambil NIS jika ada pencarian
$nis = $_POST['nis'] ?? '';
$row_siswa = null;

if (!empty($nis)) {
    $nis_escaped = mysqli_real_escape_string($conn, $nis);
    $query = mysqli_query($conn, "SELECT s.nis, s.nama_siswa, t.tingkat, p.program_keahlian, k.rombel
        FROM siswa s
        JOIN kelas k USING(id_kelas)
        JOIN tingkat t ON k.id_tingkat = t.id_tingkat
        JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
        WHERE s.nis = '$nis_escaped'");
    $row_siswa = mysqli_fetch_assoc($query);
}

// Ambil daftar semua siswa untuk datalist
$query_all_siswa = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa ASC");

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

                <!-- Form Cari Siswa -->
                <form action="" method="post" class="mb-4 mt-3">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Cari Siswa (Nama atau NIS)</label>
                            <div class="datalist-container">
                                <input list="siswaList" name="nis" class="form-control" placeholder="Masukkan Nama atau NIS..." value="<?= htmlspecialchars($nis) ?>" required>
                                <datalist id="siswaList">
                                    <?php while ($s = mysqli_fetch_assoc($query_all_siswa)): ?>
                                        <option value="<?= $s['nis'] ?>"><?= $s['nama_siswa'] ?> (<?= $s['nis'] ?>)</option>
                                    <?php endwhile; ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-save shadow-sm border w-100">
                                <i class="bi bi-search me-2"></i>Cek Data Siswa
                            </button>
                        </div>
                    </div>
                </form>

                <?php if ($row_siswa): ?>
                <hr>
                <form action="/SistemPoin/process/pelanggaran_siswa_process.php" method="POST" class="needs-validation" id="violationForm" novalidate>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="nis" id="nis" value="<?= $row_siswa['nis'] ?>">

                    <div class="alert bg-primary-subtle border-primary mb-4">
                        <strong>Siswa Terpilih:</strong> <?= $row_siswa['nama_siswa'] ?> (<?= $row_siswa['nis'] ?>) - <?= $row_siswa['tingkat'] ?> <?= $row_siswa['program_keahlian'] ?> <?= $row_siswa['rombel'] ?>
                    </div>

                    <div class="form-section-title">
                        <i class="bi bi-exclamation-triangle"></i> Detail Pelanggaran
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Pelanggaran <span class="text-danger">*</span></label>
                            <input type="hidden" name="id_jenis_pelanggaran" id="id_jenis_pelanggaran" value="" required>
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_jenis" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih Jenis Pelanggaran
                                </button>
                                <ul class="dropdown-menu w-100 text-start" aria-labelledby="dropdown_jenis" style="height: 200px; overflow-y: auto;">
                                    <?php while ($jp = mysqli_fetch_assoc($jenis_query)): ?>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="setDropdown('id_jenis_pelanggaran', 'dropdown_jenis', '<?= htmlspecialchars($jp['jenis']) ?> (<?= $jp['poin'] ?> Poin)', '<?= $jp['id_jenis_pelanggaran'] ?>'); this.closest('.dropdown').querySelector('button').classList.remove('is-invalid');">
                                                <?= htmlspecialchars($jp['jenis']) ?> (<?= $jp['poin'] ?> Poin)
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                            <div class="invalid-feedback">
                                Pilih jenis pelanggaran.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Pelanggaran</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>"
                                required>
                            <div class="invalid-feedback">
                                Tanggal pelanggaran wajib diisi.
                            </div>
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
                <?php elseif (!empty($nis)): ?>
                    <div class="alert alert-warning mt-3">Data siswa tidak ditemukan. Silakan cari kembali.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <script>
        // Custom validation for dropdowns
        document.getElementById('violationForm').addEventListener('submit', function (event) {
            let form = this;
            let nisInput = document.getElementById('nis');
            let jenisInput = document.getElementById('id_jenis_pelanggaran');
            let dropdownJenisBtn = document.getElementById('dropdown_jenis');

            // Reset validation state
            dropdownJenisBtn.classList.remove('is-invalid');

            let isValid = true;

            if (jenisInput.value === '') {
                dropdownJenisBtn.classList.add('is-invalid');
                isValid = false;
            }

            if (!form.checkValidity() || !isValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    </script>

<?php include ROOTPATH . "/includes/footer.php"; ?>