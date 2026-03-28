<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
// Set timezone ke WITA (Asia/Makassar) agar jam default sesuai lokasi sekolah di Denpasar
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
    $query = mysqli_query($conn, "SELECT s.nis, s.nama_siswa, t.tingkat, p.program_keahlian, k.rombel
        FROM siswa s
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
        <div class="col-lg-8">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        <i class="bi bi-telephone me-2"></i>
                        Surat Panggilan Orang Tua/Wali
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
                    <form action="surat_panggilan_ortu.php" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="nis" value="<?= $row_siswa['nis'] ?>">

                        <div class="alert bg-primary-subtle border-primary mb-4">
                            <strong>Data Terpilih:</strong> <?= $row_siswa['nama_siswa'] ?> (<?= $row_siswa['nis'] ?>) - <?= $row_siswa['tingkat'] ?> <?= $row_siswa['program_keahlian'] ?> <?= $row_siswa['rombel'] ?>
                        </div>

                        <div class="form-section-title">
                            <i class="bi bi-card-text"></i> Detail Surat
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label">No Surat <span class="text-danger">*</span></label>
                                <input type="number" name="no_surat" class="form-control" required>
                                <input type="hidden" name="tanggal" value="<?= date('Y-m-d') ?>">
                                <input type="hidden" name="jam" value="<?= date('H:i') ?>">
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
                <?php elseif (!empty($nis)): ?>
                    <div class="alert alert-warning mt-3">Data siswa tidak ditemukan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>