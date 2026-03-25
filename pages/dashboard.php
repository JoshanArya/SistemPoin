<?php
include '../includes/header.php';
include '../config/config.php';

// Contoh Query untuk statistik (Silakan sesuaikan dengan nama tabelmu)
$total_siswa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa"));
$total_guru = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM guru"));
$siswa_bermasalah = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nis FROM pelanggaran_siswa"));
?>

<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="fw-bold">Selamat Datang, <span
                    class="text-primary fst-italic"><?php echo ($_SESSION['role'] == 'siswa') ? $_SESSION['nama_siswa'] : $_SESSION['nama_pengguna']; ?></span>
                <i class="bi bi-person-fill text-primary"></i></h4>
            <p class="text-muted">Berikut adalah ringkasan data Sistem Poin hari ini.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary-subtle text-primary rounded-3 p-3 me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Siswa</small>
                        <h3 class="fw-bold mb-0"><?php echo $total_siswa; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-danger-subtle text-danger rounded-3 p-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Siswa Melanggar</small>
                        <h3 class="fw-bold mb-0"><?php echo $siswa_bermasalah; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-success-subtle text-success rounded-3 p-3 me-3">
                        <i class="bi bi-person-check-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Total Guru</small>
                        <h3 class="fw-bold mb-0"><?php echo $total_guru; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow p-3">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-warning-subtle text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-trophy-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Poin Terinput</small>
                        <h3 class="fw-bold mb-0">1,240</h3>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php include '../includes/footer.php'; ?>