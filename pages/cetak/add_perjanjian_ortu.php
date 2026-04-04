<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
// Set timezone ke WITA
date_default_timezone_set('Asia/Makassar');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<?php
// Ambil NIS jika ada pencarian
$nis = $_POST['nis'] ?? '';
$row_siswa = null;

if (!empty($nis)) {
    $nis_escaped = mysqli_real_escape_string($conn, $nis);
    $query = mysqli_query($conn, "SELECT s.nis, s.nama_siswa, t.tingkat, p.program_keahlian, k.rombel,
        ow.ayah, ow.ibu, ow.wali, ow.pekerjaan_ayah, ow.pekerjaan_ibu, ow.pekerjaan_wali,
        ow.alamat_ayah, ow.alamat_ibu, ow.alamat_wali, ow.no_telp_ayah, ow.no_telp_ibu, ow.no_telp_wali
        FROM siswa s
        JOIN ortu_wali ow USING(id_ortu_wali)
        JOIN kelas k USING(id_kelas)
        JOIN tingkat t ON k.id_tingkat = t.id_tingkat
        JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
        WHERE s.nis = '$nis_escaped'");
    $row_siswa = mysqli_fetch_assoc($query);
}

// Ambil daftar semua siswa untuk datalist
$query_all_siswa = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa ORDER BY nama_siswa ASC");
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
                    <form action="../../process/surat_process.php" method="post">
                        <input type="hidden" name="nis" value="<?= $row_siswa['nis'] ?>">
                        <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">
                        <input type="hidden" name="jenis_surat" value="Surat Perjanjian Orang Tua">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="alert bg-primary-subtle border-primary">
                                    <strong>Data Terpilih:</strong> <?= $row_siswa['nama_siswa'] ?> (<?= $row_siswa['nis'] ?>) - <?= $row_siswa['tingkat'] ?> <?= $row_siswa['program_keahlian'] ?> <?= $row_siswa['rombel'] ?>
                                </div>
                            </div>

                            <div class="form-section-title" style="margin-bottom: -1.5rem;">
                                <i class="bi bi-people"></i> Pilih Orang Tua/Wali
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Gunakan Data Orang Tua / Wali</label>
                            <div class="dropdown border rounded">
                                    <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_select_ortu" data-bs-toggle="dropdown">
                                        Ayah
                                    </button>
                                    <ul class="dropdown-menu w-100 text-start">
                                        <li><a class="dropdown-item" href="#" onclick="updateOrtuData('Ayah', '<?= addslashes($row_siswa['ayah']) ?>', '<?= addslashes($row_siswa['pekerjaan_ayah']) ?>', '<?= addslashes($row_siswa['alamat_ayah']) ?>', '<?= addslashes($row_siswa['no_telp_ayah']) ?>'); return false;">Ayah</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="updateOrtuData('Ibu', '<?= addslashes($row_siswa['ibu']) ?>', '<?= addslashes($row_siswa['pekerjaan_ibu']) ?>', '<?= addslashes($row_siswa['alamat_ibu']) ?>', '<?= addslashes($row_siswa['no_telp_ibu']) ?>'); return false;">Ibu</a></li>
                                        <?php if (!empty($row_siswa['wali'])): ?>
                                            <li><a class="dropdown-item" href="#" onclick="updateOrtuData('Wali', '<?= addslashes($row_siswa['wali']) ?>', '<?= addslashes($row_siswa['pekerjaan_wali']) ?>', '<?= addslashes($row_siswa['alamat_wali']) ?>', '<?= addslashes($row_siswa['no_telp_wali']) ?>'); return false;">Wali</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-section-title" style="margin-bottom: -1.5rem;">
                                <i class="bi bi-card-text"></i> Data Orang Tua/Wali
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">No Surat <span class="text-danger">*</span></label>
                                <input type="number" name="no_surat" class="form-control" placeholder="Contoh: 001" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Orang Tua</label>
                                <input type="text" name="nama_ortu" id="nama_ortu" class="form-control" value="<?= htmlspecialchars($row_siswa['ayah']) ?>" readonly required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control" value="<?= htmlspecialchars($row_siswa['pekerjaan_ayah']) ?>" readonly required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Hp/Telp</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?= htmlspecialchars($row_siswa['no_telp_ayah']) ?>" readonly required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat Rumah</label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="2" readonly required><?= htmlspecialchars($row_siswa['alamat_ayah']) ?></textarea>
                            </div>

                            <!-- No_surat and Tanggal are removed/hidden as per request -->
                            <div class="col-12 mt-4 text-end">
                                <a href="list.php" class="btn btn-cancel shadow-sm border me-2">Batal</a>
                                <button type="submit" class="btn shadow-sm border btn-save">
                                    <i class="bi bi-printer me-2"></i>Cetak Surat
                                </button>
                            </div>
                        </div>
                    </form>
                    <script>
                        function updateOrtuData() {
                            const select = document.getElementById('select_ortu');
                            const opt = select.options[select.selectedIndex];
                            document.getElementById('nama_ortu').value = opt.getAttribute('data-nama');
                            document.getElementById('pekerjaan').value = opt.getAttribute('data-kerja');
                            document.getElementById('alamat').value = opt.getAttribute('data-alamat');
                            document.getElementById('no_telp').value = opt.getAttribute('data-telp');
                            document.getElementById('ortu_type_hidden').value = opt.value; // Update hidden ortu_type
                        }
                        // Initial call to set values if a student is already selected
                        document.addEventListener('DOMContentLoaded', function() {
                            if (document.getElementById('select_ortu')) {
                                updateOrtuData();
                            }
                        });
                    </script>
                <?php elseif (!empty($nis)): ?>
                    <div class="alert alert-warning mt-3">Data siswa tidak ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>
