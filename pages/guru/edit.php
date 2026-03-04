<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil data guru berdasarkan kode_guru
$kode_guru = $_GET["kode_guru"];
$result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM guru WHERE kode_guru = '$kode_guru'"));

// Daftar jabatan tetap
$jabatan_list = [
    'Kepala Sekolah',
    'Waka Kurikulum',
    'Waka Kesiswaan',
    'Waka Sarana Prasarana',
    'Waka Humas',
    'Komka RPL',
    'Komka TKJ',
    'Komka DKV',
    'Komka AN',
    'Komka BD',
    'Guru Mapel',
    'Guru BK X',
    'Guru BK XI',
    'Guru BK XII',
    'Ketua Lab'
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        Edit <span class="text-primary fst-italic">Data Guru</span>
                    </h2>
                </div>

                <form action="/SistemPoin/process/guru_process.php" method="POST">
                    <input type="hidden" name="action" value="edit" />

                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Identitas Guru
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Kode Guru</label>
                            <input type="text" name="kode_guru" class="form-control" value="<?= $result['kode_guru'] ?>" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_pengguna" class="form-control" placeholder="Masukkan Nama Lengkap.." value="<?= $result['nama_pengguna'] ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan Username.." value="<?= $result['username'] ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan Password Baru..">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Jabatan</label>
                            <input type="hidden" name="jabatan" id="jabatan" value="<?= $result['jabatan'] ?>">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_jabatan" data-bs-toggle="dropdown">
                                    <?= !empty($result['jabatan']) ? $result['jabatan'] : 'Pilih Jabatan' ?>
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php foreach($jabatan_list as $jab): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('jabatan', 'dropdown_jabatan', this.innerText, '<?= $jab ?>')">
                                            <?= $jab ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="number" name="telp" class="form-control" placeholder="Masukkan Nomor Telepon.." value="<?= $result['telp'] ?>" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Status Keaktifan</label>
                            <input type="hidden" name="aktif" id="aktif" value="<?= $result['aktif'] ?>">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_aktif" data-bs-toggle="dropdown">
                                    <?= $result['aktif'] == 'Y' ? 'Aktif' : 'Tidak Aktif' ?>
                                </button>
                                <ul class="dropdown-menu w-100 text-start">
                                    <li><a class="dropdown-item" href="#" onclick="setDropdown('aktif', 'dropdown_aktif', this.innerText, 'Y')">Aktif</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="setDropdown('aktif', 'dropdown_aktif', this.innerText, 'N')">Tidak Aktif</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="list.php" class="btn btn-cancel shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-save shadow-sm border">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>
