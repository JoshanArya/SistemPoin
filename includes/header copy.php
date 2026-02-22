<?php
// Cek status session agar tidak error notice
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SakuSiswa - Yadika 6</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #eee;
            padding: 0.8rem 0;
        }
        .navbar-brand {
            font-weight: 800;
            color: #1a374d !important;
            display: flex;
            align-items: center;
        }
        .nav-link {
            font-weight: 500;
            color: #555 !important;
            padding: 0.5rem 1rem !important;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #0d6efd !important;
        }
        .btn-logout {
            border-radius: 8px;
            font-weight: 600;
        }
        /* Indikator Menu Aktif */
        .nav-item.active-link .nav-link {
            color: #0d6efd !important;
            border-bottom: 2px solid #0d6efd;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-person-badge-fill me-2 text-primary"></i>
            SakuSiswa<span class="text-primary">.</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="/SistemPoin/pages/dashboard.php">Dashboard</a>
                </li>

                <?php if ($_SESSION['role'] == 'guru') : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Kelola Data
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li><a class="dropdown-item" href="/SistemPoin/pages/siswa/index.php">Data Siswa</a></li>
                        <li><a class="dropdown-item" href="#">Data Guru</a></li>
                        <li><a class="dropdown-item" href="#">Data Kelas</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pelanggaran</a>
                </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] == 'siswa') : ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">Riwayat Poin</a>
                </li>
                <?php endif; ?>

                <li class="nav-item ms-lg-3">
                    <span class="navbar-text me-3 d-none d-lg-inline text-dark fw-bold">
                        Hi, <?php echo ($_SESSION['role'] == 'guru') ? $_SESSION['nama_pengguna'] : $_SESSION['nama_siswa']; ?>
                    </span>
                    <a href="/SistemPoin/process/logout.php" class="btn btn-outline-danger btn-sm btn-logout">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>