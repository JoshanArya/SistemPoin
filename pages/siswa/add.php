<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil data kelas untuk dropdown
$query_kelas = mysqli_query($conn, "SELECT id_kelas, tingkat, program_keahlian, rombel FROM kelas 
                JOIN tingkat USING(id_tingkat) 
                JOIN program_keahlian USING(id_program_keahlian)");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        Tambah <span class="text-primary fst-italic">Siswa Baru</span>
                    </h2>
                </div>

                <form action="/SistemPoin/process/siswa_process.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="action" value="add" />

                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Identitas Peserta Didik
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS.." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_siswa" class="form-control" placeholder="Masukan Nama Siswa.."
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <input type="hidden" name="jenis_kelamin" id="jenis_kelamin" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_jk" data-bs-toggle="dropdown">
                                    Pilih Jenis Kelamin
                                </button>
                                <ul class="dropdown-menu w-100 text-start">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('jenis_kelamin', 'dropdown_jk', this.innerText, 'Laki - Laki')">Laki-Laki</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('jenis_kelamin', 'dropdown_jk', this.innerText, 'Perempuan')">Perempuan</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <input type="hidden" name="kelas" id="kelas" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_kelas" data-bs-toggle="dropdown">
                                    Pilih Kelas
                                </button>
                                <ul class="dropdown-menu kelas w-100 text-start">
                                    <?php while ($k = mysqli_fetch_assoc($query_kelas)): ?>
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="setDropdown('kelas', 'dropdown_kelas', this.innerText, '<?= $k['id_kelas'] ?>')">
                                                <?= $k['tingkat'] . ' ' . $k['program_keahlian'] . ' ' . $k['rombel'] ?>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="w-100">
                            <label class="form-label">Status Siswa</label>

                            <input type="hidden" name="status_siswa" id="status" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start"
                                    type="button" id="dropdown_status" data-bs-toggle="dropdown">
                                    Pilih Status Siswa
                                </button>
                                <ul class="dropdown-menu w-100 text-start">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('status', 'dropdown_status', this.innerText, 'aktif')">Aktif</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('status', 'dropdown_status', this.innerText, 'tidak_aktif')">Tidak
                                            Aktif</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('status', 'dropdown_status', this.innerText, 'pindah')">Pindah
                                            Sekolah</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            onclick="setDropdown('status', 'dropdown_status', this.innerText, 'lulus')">Lulus</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat Siswa</label>
                            <textarea name="alamat_siswa" class="form-control" rows="2"
                                placeholder="Alamat lengkap tempat tinggal siswa.."></textarea>
                        </div>
                    </div>

                    <div class="form-section-title mt-5 mb-3">
                        <i class="bi bi-people"></i> Informasi Orang Tua / Wali
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-gender-male"></i> Data Ayah</h6>
                            <input type="text" name="ayah" class="form-control mb-2" placeholder="Nama Ayah">
                            <input type="text" name="pekerjaan_ayah" class="form-control mb-2" placeholder="Pekerjaan">
                            <input type="number" name="telp_ayah" class="form-control mb-2" placeholder="No. Telp">
                            <textarea name="alamat_ayah" class="form-control" placeholder="Alamat Ayah"></textarea>
                        </div>
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-gender-female"></i> Data Ibu</h6>
                            <input type="text" name="ibu" class="form-control mb-2" placeholder="Nama Ibu">
                            <input type="text" name="pekerjaan_ibu" class="form-control mb-2" placeholder="Pekerjaan">
                            <input type="number" name="telp_ibu" class="form-control mb-2" placeholder="No. Telp">
                            <textarea name="alamat_ibu" class="form-control" placeholder="Alamat Ibu"></textarea>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-person-plus"></i> Data Wali (Opsional)
                            </h6>
                            <input type="text" name="wali" class="form-control mb-2" placeholder="Nama Wali">
                            <input type="text" name="pekerjaan_wali" class="form-control mb-2" placeholder="Pekerjaan">
                            <input type="number" name="telp_wali" class="form-control mb-2" placeholder="No. Telp">
                            <textarea name="alamat_wali" class="form-control" placeholder="Alamat Wali"></textarea>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="list.php" class="btn btn-cancel shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-save shadow-sm border">
                            <i class="bi bi-check-lg me-2"></i> Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>