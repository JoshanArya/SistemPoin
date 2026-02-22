

<?php
include '../includes/header.php';
include '../config/config.php';

// Contoh Query untuk statistik (Silakan sesuaikan dengan nama tabelmu)
$total_siswa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa"));
$total_guru = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM guru"));
// Asumsi ada kolom poin di tabel siswa atau tabel pelanggaran
$siswa_bermasalah = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT nis FROM pelanggaran_siswa")); 
?>

<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="fw-bold">Selamat Datang, <span class="text-primary fst-italic"><?php echo ($_SESSION['role'] == 'guru') ? $_SESSION['nama_pengguna'] : $_SESSION['nama_siswa']; ?></span> <i class="bi bi-person-fill text-primary"></i></h4>
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

    <!-- <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Pelanggaran Terbaru</h6>
                </div>
                <div class="table-responsive p-3">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="fw-semibold">Abdullah Musa</span></td>
                                <td>Terlambat Masuk</td>
                                <td><span class="badge bg-danger">10</span></td>
                                <td><small class="text-muted">5 Menit lalu</small></td>
                            </tr>
                            <tr>
                                <td><span class="fw-semibold">Juni Budi</span></td>
                                <td>Baju Keluar</td>
                                <td><span class="badge bg-danger">5</span></td>
                                <td><small class="text-muted">1 Jam lalu</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-4 mb-4">
                <h5 class="fw-bold">Butuh Bantuan?</h5>
                <p class="small opacity-75">Gunakan tombol di bawah untuk akses cepat menu utama.</p>
                <div class="d-grid gap-2">
                    <a href="siswa/index.php" class="btn btn-light text-primary fw-bold">Kelola Siswa</a>
                    <a href="#" class="btn btn-outline-light">Input Pelanggaran Baru</a>
                </div>
            </div>
        </div>
    </div> -->
</div>

<style>
    .bg-primary-subtle { background-color: #e7f1ff; }
    .bg-danger-subtle { background-color: #f8d7da; }
    .bg-success-subtle { background-color: #d1e7dd; }
    .bg-warning-subtle { background-color: #fff3cd; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
</style>

<?php include '../includes/footer.php'; ?>