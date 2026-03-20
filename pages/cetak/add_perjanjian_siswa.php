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
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Surat Perjanjian Siswa
                    </h2>
                </div>

                <form action="surat_perjanjian_siswa.php" method="POST" class="needs-validation" novalidate>
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
                        <i class="bi bi-people"></i> Data Orang Tua/Wali
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nama Orang Tua/Wali <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ortu" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telp <span class="text-danger">*</span></label>
                            <input type="tel" name="no_telp" class="form-control" required>
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