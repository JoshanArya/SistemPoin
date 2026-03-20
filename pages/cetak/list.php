<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<style>
    .btn-warning {
        color: #fff !important;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        <i class="bi bi-printer me-2"></i>
                        Pilih Jenis Surat Cetak
                    </h2>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <a href="add_perjanjian_siswa.php" class="btn btn-primary w-100 shadow-sm h-100 p-4 text-start">
                            <i class="bi bi-file-earmark-text fs-1 mb-3 d-block"></i>
                            <h5 class="mb-2">Surat Perjanjian Siswa</h5>
                            <p class="mb-0">Buat surat perjanjian untuk siswa dan orang tua terkait pelanggaran</p>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="add_panggilan_ortu.php" class="btn btn-success w-100 shadow-sm h-100 p-4 text-start">
                            <i class="bi bi-telephone fs-1 mb-3 d-block"></i>
                            <h5 class="mb-2">Surat Panggilan Orang Tua</h5>
                            <p class="mb-0">Panggil orang tua/wali untuk membahas pelanggaran siswa</p>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="add_perjanjian_ortu.php" class="btn btn-warning w-100 shadow-sm h-100 p-4 text-start">
                            <i class="bi bi-file-earmark-check fs-1 mb-3 d-block"></i>
                            <h5 class="mb-2">Surat Perjanjian Orang Tua</h5>
                            <p class="mb-0">Perjanjian tertulis dengan orang tua/wali siswa</p>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="add_pindah_sekolah.php" class="btn btn-danger w-100 shadow-sm h-100 p-4 text-start">
                            <i class="bi bi-arrow-left-right fs-1 mb-3 d-block"></i>
                            <h5 class="mb-2">Surat Pindah Sekolah</h5>
                            <p class="mb-0">Surat untuk siswa pindah sekolah akibat pelanggaran berat</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>