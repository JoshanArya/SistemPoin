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
                    <div id="dropdown_siswa_container" style="display: none; position: absolute; color: #ffffff00;">
                        <small id="dropdown_siswa_text"></small>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Siswa <span class="text-danger">*</span></label>
                            <input list="nis_list" id="nis_input" class="form-control datalist-container"
                                placeholder="Ketik NIS atau nama siswa..." autocomplete="off" required
                                onchange="loadSiswaData(this.value)">
                            <datalist id="nis_list">
                                <?php
                                $siswa_query = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa");
                                while ($s = mysqli_fetch_assoc($siswa_query)) {
                                    echo '<option value="' . $s['nis'] . '">' . $s['nis'] . ' - ' . htmlspecialchars($s['nama_siswa']) . '</option>';
                                }
                                ?>
                            </datalist>
                        </div>

                        <div class="form-section-title" style="margin-bottom: -1.5rem;">
                            <i class="bi bi-people"></i> Pilih Orang Tua/Wali
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label">Hubungan Keluarga <span class="text-danger">*</span></label>
                                <div class="dropdown border rounded">
                                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                        type="button" id="dropdown_ortu" data-bs-toggle="dropdown">
                                        Pilih Ayah/Ibu/Wali
                                    </button>
                                    <ul class="dropdown-menu w-100 text-start">
                                        <li><a class="dropdown-item" href="#" onclick="selectOrtu('ayah'); return false;">Ayah</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="selectOrtu('ibu'); return false;">Ibu</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="selectOrtu('wali'); return false;">Wali</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-title" style="margin-bottom: -1.5rem;">
                            <i class="bi bi-card-text"></i> Data Orang Tua/Wali
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_ortu" readonly>
                                <input type="hidden" name="nama_ortu" id="nama_ortu_hidden">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pekerjaan" readonly>
                                <input type="hidden" name="pekerjaan" id="pekerjaan_hidden">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No Telp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_telp" readonly>
                                <input type="hidden" name="no_telp" id="no_telp_hidden">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alamat" readonly rows="2"></textarea>
                                <input type="hidden" name="alamat" id="alamat_hidden">
                            </div>
                        </div>

                        <div class="form-section-title" style="margin-bottom: -1.5rem;">
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
    const siswaOrtuData = {};

    function loadSiswaData(value) {
        const [nis, nama] = value.split(' - ');
        if (!nis || !nama) return;

        document.getElementById('nis').value = nis.trim();

        document.getElementById('dropdown_siswa_container').style.display = 'block';
        document.getElementById('dropdown_siswa_text').textContent = nama.trim() + ' (' + nis.trim() + ')';

        fetch('/SistemPoin/process/get_siswa_full.php?nis=' + encodeURIComponent(nis.trim()))
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                siswaOrtuData[nis.trim()] = data;
                document.getElementById('dropdown_ortu').textContent = 'Pilih Ayah/Ibu/Wali';
                clearOrtuFields();
            })
            .catch(err => console.error('AJAX error:', err));
    }

    function selectOrtu(ortuType) {
        const nis = document.getElementById('nis').value;

        console.log('selectOrtu called with:', ortuType, 'nis:', nis);

        if (!nis) {
            alert('Silakan pilih siswa terlebih dahulu');
            return false;
        }

        if (!siswaOrtuData[nis]) {
            console.log('Data not found for nis:', nis);
            console.log('Available keys:', Object.keys(siswaOrtuData));
            alert('Data siswa tidak ditemukan. Silakan pilih siswa lagi.');
            return false;
        }

        const data = siswaOrtuData[nis];
        console.log('Data siswa:', data);
        document.getElementById('ortu_type').value = ortuType;

        let nama = '';
        let pekerjaan = '';
        let alamat = '';
        let no_telp = '';
        let buttonText = '';

        if (ortuType === 'ibu') {
            nama = data.ibu || '';
            pekerjaan = data.pekerjaan_ibu || '';
            alamat = data.alamat_ibu || '';
            no_telp = data.no_telp_ibu || '';
            buttonText = 'Ibu';
        } else if (ortuType === 'wali') {
            nama = data.wali || '';
            pekerjaan = data.pekerjaan_wali || '';
            alamat = data.alamat_wali || '';
            no_telp = data.no_telp_wali || '';
            buttonText = 'Wali';
        } else {
            nama = data.ayah || '';
            pekerjaan = data.pekerjaan_ayah || '';
            alamat = data.alamat_ayah || '';
            no_telp = data.no_telp_ayah || '';
            buttonText = 'Ayah';
        }

        console.log('Filling fields with - nama:', nama, 'pekerjaan:', pekerjaan);

        // Tampilkan data di display fields
        document.getElementById('nama_ortu').value = nama;
        document.getElementById('pekerjaan').value = pekerjaan;
        document.getElementById('alamat').value = alamat;
        document.getElementById('no_telp').value = no_telp;
        document.getElementById('dropdown_ortu').textContent = buttonText;

        // Isi hidden fields untuk dikirim ke server
        document.getElementById('nama_ortu_hidden').value = nama;
        document.getElementById('pekerjaan_hidden').value = pekerjaan;
        document.getElementById('alamat_hidden').value = alamat;
        document.getElementById('no_telp_hidden').value = no_telp;

        return false;
    }

    function clearOrtuFields() {
        document.getElementById('nama_ortu').value = '';
        document.getElementById('nama_ortu_hidden').value = '';
        document.getElementById('pekerjaan').value = '';
        document.getElementById('pekerjaan_hidden').value = '';
        document.getElementById('alamat').value = '';
        document.getElementById('alamat_hidden').value = '';
        document.getElementById('no_telp').value = '';
        document.getElementById('no_telp_hidden').value = '';
    }

    // Before form submit, ensure hidden fields are updated and validate
    document.querySelector('form').addEventListener('submit', function (e) {
        const nis = document.getElementById('nis').value.trim();
        const ortu_type = document.getElementById('ortu_type').value.trim();
        const nama_ortu = document.getElementById('nama_ortu').value.trim();

        // Validasi data harus lengkap
        if (!nis) {
            e.preventDefault();
            alert('Silakan pilih siswa terlebih dahulu');
            return false;
        }

        if (!ortu_type || !nama_ortu) {
            e.preventDefault();
            alert('Silakan pilih orang tua/wali terlebih dahulu');
            return false;
        }

        // Update hidden fields
        document.getElementById('nama_ortu_hidden').value = document.getElementById('nama_ortu').value;
        document.getElementById('pekerjaan_hidden').value = document.getElementById('pekerjaan').value;
        document.getElementById('alamat_hidden').value = document.getElementById('alamat').value;
        document.getElementById('no_telp_hidden').value = document.getElementById('no_telp').value;
    });
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>