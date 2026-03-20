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
                        Surat Perjanjian Orang Tua/Wali
                    </h2>
                </div>

                <form action="surat_perjanjian_ortu.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="nis" id="nis" value="">
                    <input type="hidden" name="ortu_type" id="ortu_type" value="">

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
                                                onclick="loadSiswaData('<?= $s['nis'] ?>', '<?= htmlspecialchars($s['nama_siswa']) ?>')">
                                                <?= $s['nis'] ?> | <?= htmlspecialchars($s['nama_siswa']) ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="bi bi-people"></i> Pilih Orang Tua/Wali
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Hubungan <span class="text-danger">*</span></label>
                            <input type="hidden" name="ortu_type" id="ortu_type" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_ortu" data-bs-toggle="dropdown">
                                    Pilih Ayah/Ibu/Wali
                                </button>
                                <ul class="dropdown-menu w-100 text-start">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('ortu_type', 'dropdown_ortu', 'Ayah', 'ayah')">Ayah</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('ortu_type', 'dropdown_ortu', 'Ibu', 'ibu')">Ibu</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('ortu_type', 'dropdown_ortu', 'Wali', 'wali')">Wali</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="bi bi-card-text"></i> Detail Surat
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">No Surat <span class="text-danger">*</span></label>
                            <input type="number" name="no_surat" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>"
                                required>
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

    function loadSiswaData(nis, nama) {
        document.getElementById('nis').value = nis;
        document.getElementById('dropdown_siswa').textContent = nama + ' (' + nis + ')';
    }
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>