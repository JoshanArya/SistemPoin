<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil data tingkat
$query_tingkat = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY id_tingkat");

// Ambil data program keahlian
$query_pk = mysqli_query($conn, "SELECT * FROM program_keahlian ORDER BY program_keahlian");

// Ambil data guru untuk dropdown Wali Kelas (semua guru aktif)
$query_guru = mysqli_query($conn, "SELECT kode_guru, nama_pengguna, jabatan FROM guru WHERE aktif = 'Y' ORDER BY nama_pengguna");

// Ambil data guru BK
$query_guru_bk = mysqli_query($conn, "SELECT kode_guru, nama_pengguna, jabatan FROM guru WHERE aktif = 'Y' AND jabatan LIKE 'Guru BK%' ORDER BY jabatan");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        Tambah <span class="text-primary fst-italic">Kelas Baru</span>
                    </h2>
                </div>

                <form action="/SistemPoin/process/kelas_process.php" method="POST">
                    <input type="hidden" name="action" value="add" />

                    <div class="form-section-title">
                        <i class="bi bi-building"></i> Informasi Kelas
                    </div>
                    <div class="row g-3 mb-4">
<div class="col-md-6">
                            <label class="form-label">Tingkat</label>
                            <input type="hidden" name="id_tingkat" id="id_tingkat" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_tingkat" data-bs-toggle="dropdown">
                                    Pilih Tingkat
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php while($tingkat = mysqli_fetch_assoc($query_tingkat)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('id_tingkat', 'dropdown_tingkat', this.innerText, '<?= $tingkat['id_tingkat'] ?>'); autoSelectGuruBK(<?= $tingkat['id_tingkat'] ?>)">
                                            <?= $tingkat['tingkat'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Program Keahlian</label>
                            <input type="hidden" name="id_program_keahlian" id="id_program_keahlian" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_pk" data-bs-toggle="dropdown">
                                    Pilih Program Keahlian
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <?php while($pk = mysqli_fetch_assoc($query_pk)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('id_program_keahlian', 'dropdown_pk', '<?= $pk['program_keahlian'] . ' - ' . $pk['deskripsi'] ?>', '<?= $pk['id_program_keahlian'] ?>')">
                                            <?= $pk['program_keahlian'] ?> - <?= $pk['deskripsi'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Rombel</label>
                            <input type="number" name="rombel" class="form-control" placeholder="Masukkan Nomor Rombel (1, 2, 3...)" min="1" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-section-title">
                        <i class="bi bi-person-badge"></i> Informations Guru
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Wali Kelas</label>
                            <input type="hidden" name="kode_guru" id="kode_guru" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_wali" data-bs-toggle="dropdown">
                                    Pilih Wali Kelas
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru', 'dropdown_wali', 'Tidak Ada', '')">
                                            <em>Tidak Ada</em>
                                        </a>
                                    </li>
                                    <?php while($guru = mysqli_fetch_assoc($query_guru)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru', 'dropdown_wali', '<?= htmlspecialchars($guru['nama_pengguna']) ?>', '<?= $guru['kode_guru'] ?>')">
                                            <?= $guru['nama_pengguna'] ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Guru BK</label>
                            <input type="hidden" name="kode_guru_bk" id="kode_guru_bk" value="">
                            <div class="dropdown border rounded">
                                <button class="btn dropdown-toggle-filter dropdown-toggle w-100 text-start" type="button" id="dropdown_bk" data-bs-toggle="dropdown">
                                    Pilih Guru BK
                                </button>
                                <ul class="dropdown-menu w-100 text-start kelas">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru_bk', 'dropdown_bk', 'Tidak Ada', '')">
                                            <em>Tidak Ada</em>
                                        </a>
                                    </li>
                                    <?php 
                                    // Reset pointer untuk query guru bk
                                    mysqli_data_seek($query_guru_bk, 0);
                                    while($guru_bk = mysqli_fetch_assoc($query_guru_bk)): ?>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="setDropdown('kode_guru_bk', 'dropdown_bk', '<?= htmlspecialchars($guru_bk['nama_pengguna']) ?>', '<?= $guru_bk['kode_guru'] ?>')">
                                            <?= $guru_bk['nama_pengguna'] ?>
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
                            <i class="bi bi-check-lg me-2"></i> Simpan Data Kelas
                        </button>
                    </div>
                </form>
                
                <script>
                // Auto-select Guru BK based on Tingkat
                // X (id_tingkat=1) -> Finsensius Ratuaki (0021.093)
                // XI (id_tingkat=2) -> Ni Putu Chintya Pradnya Suari (0021.094)
                // XII (id_tingkat=3) -> Ida Gusti Ayu Rinjani (0021.039)
                function autoSelectGuruBK(id_tingkat) {
                    var guruBKMap = {
                        '1': { kode: '0021.093', nama: 'Finsensius Ratuaki, M.Pd.' },
                        '2': { kode: '0021.094', nama: 'Ni Putu Chintya Pradnya Suari, S.Pd.' },
                        '3': { kode: '0021.039', nama: 'Ida Gusti Ayu Rinjani, M.Pd.' }
                    };
                    
                    if (guruBKMap[id_tingkat]) {
                        document.getElementById('kode_guru_bk').value = guruBKMap[id_tingkat].kode;
                        document.getElementById('dropdown_bk').innerText = guruBKMap[id_tingkat].nama;
                    }
                }
                </script>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>

