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
                    <input type="hidden" name="nama_ortu" id="nama_ortu" value="">
                    <input type="hidden" name="pekerjaan" id="pekerjaan" value="">
                    <input type="hidden" name="alamat" id="alamat" value="">
                    <input type="hidden" name="no_telp" id="no_telp" value="">

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
                                    $siswa_query = mysqli_query($conn, "SELECT nis, nama_siswa, ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, alamat_ayah, alamat_ibu, alamat_wali, no_telp_ayah, no_telp_ibu, no_telp_wali FROM siswa JOIN ortu_wali USING(id_ortu_wali) ORDER BY nama_siswa");
                                    $siswa_data = [];
                                    while ($s = mysqli_fetch_assoc($siswa_query)): 
                                        $siswa_data[$s['nis']] = $s;
                                    ?>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="selectSiswa('<?= $s['nis'] ?>', '<?= htmlspecialchars($s['nama_siswa']) ?>')">
                                                <?= $s['nis'] ?> | <?= htmlspecialchars($s['nama_siswa']) ?>
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
                            <i class="bi bi-printer me-2"></i>Cetak Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const siswaData = <?php echo json_encode($siswa_data); ?>;

    function selectSiswa(nis, displayText) {
        document.getElementById('nis').value = nis;
        document.getElementById('dropdown_siswa').textContent = displayText;

        // Ambil data ortu
        const data = siswaData[nis];
        let nama_ortu = '';
        let pekerjaan = '';
        let alamat = '';
        let no_telp = '';

        // Prioritas: wali > ayah > ibu
        if (data.wali && data.wali.trim() !== '') {
            nama_ortu = data.wali;
            pekerjaan = data.pekerjaan_wali || '';
            alamat = data.alamat_wali || '';
            no_telp = data.no_telp_wali || '';
        } else if (data.ayah && data.ayah.trim() !== '') {
            nama_ortu = data.ayah;
            pekerjaan = data.pekerjaan_ayah || '';
            alamat = data.alamat_ayah || '';
            no_telp = data.no_telp_ayah || '';
        } else if (data.ibu && data.ibu.trim() !== '') {
            nama_ortu = data.ibu;
            pekerjaan = data.pekerjaan_ibu || '';
            alamat = data.alamat_ibu || '';
            no_telp = data.no_telp_ibu || '';
        }

        // Set hidden inputs
        document.getElementById('nama_ortu').value = nama_ortu;
        document.getElementById('pekerjaan').value = pekerjaan;
        document.getElementById('alamat').value = alamat;
        document.getElementById('no_telp').value = no_telp;
    }

    function setDropdown(inputId, buttonId, displayText, value) {
        document.getElementById(inputId).value = value;
        document.getElementById(buttonId).textContent = displayText;
    }
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>