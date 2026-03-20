<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        <i class="bi bi-arrow-left-right me-2"></i>
                        Surat Pindah Sekolah
                    </h2>
                </div>

                <form action="surat_pindah_sekolah.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="nis" id="nis" value="">

                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Pilih Siswa
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Siswa <span class="text-danger">*</span></label>
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_siswa" data-bs-toggle="dropdown">
                                    Pilih Siswa
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php
                                    $siswa_query = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa");
                                    while ($s = mysqli_fetch_assoc($siswa_query)): ?>
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
                        <i class="bi bi-arrow-left-right"></i> Detail Pindah
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">No Surat <span class="text-danger">*</span></label>
                            <input type="number" name="no_surat" class="form-control" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label">Sekolah Tujuan <span class="text-danger">*</span></label>
                            <input type="text" name="pindah_ke" class="form-control" placeholder="Nama sekolah tujuan"
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alasan Pindah <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="4" required
                            placeholder="Alasan pindah sekolah..."></textarea>
                    </div>

                    <div class="form-section-title">
                        <i class="bi bi-people"></i> Data Orang Tua/Wali
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ortu" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat_ortu" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="list.php" class="btn btn-cancel shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-save shadow-sm border">
                            <i class="bi bi-printer me-2"></i>Cetak Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setDropdown(inputId, buttonId, displayText, value) {
        document.getElementById(inputId).value = value;
        document.getElementById(buttonId).textContent = displayText;
    }
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>