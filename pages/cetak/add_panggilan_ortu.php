<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        <i class="bi bi-telephone me-2"></i>
                        Surat Panggilan Orang Tua/Wali
                    </h2>
                </div>

                <form action="surat_panggilan_ortu.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="nis" id="nis" value="">

                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Pilih Siswa
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">NIS Siswa <span class="text-danger">*</span></label>
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_siswa" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih Siswa (Data Otomatis Terisi)
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
                        <i class="bi bi-card-text"></i> Detail Surat
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">No Surat <span class="text-danger">*</span></label>
                            <input type="number" name="no_surat" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jam</label>
                            <input type="time" name="jam" class="form-control" value="<?= date('H:i') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control" rows="4" required
                            placeholder="Contoh: Pembahasan pelanggaran berat siswa..."></textarea>
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
    function loadSiswaData(nis, nama) {
        document.getElementById('nis').value = nis;
        document.getElementById('dropdown_siswa').textContent = nama + ' (' + nis + ')';

        // Load full data
        fetch(`/SistemPoin/process/get_siswa_full.php?nis=${nis}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Data siswa tidak lengkap');
                }
                // Data ortu auto-used in surat generation
            })
            .catch(() => alert('Error loading data'));
    }
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>