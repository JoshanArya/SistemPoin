<?php
session_start();
if(!isset($_SESSION['username'])){
    echo "<script>alert('Anda belum login');window.location.href='/SistemPoin/login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SakuSiswa.</title>
    <link rel="stylesheet" href="/SistemPoin/assets/css/style.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
        }
        .navbar-nav {
            margin: 0;
            padding: 0;
        }
        
        .nav-link {
            font-weight: 600;
            color: #555 !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease-in-out;
            position: relative;
        }

        .nav-link:hover {
            color: #0d6efd !important;
            transform: translateY(-1px);
        }

        .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .nav-item.dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            transition: background 0.2s ease;
            padding: 0.7rem 1.2rem;
        }
        .dropdown-item:hover {
            background-color: #e9e9e9;
        }
        
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top bg-white py-3 shadow">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="/SistemPoin/pages/dashboard.php">
        <img src="/SistemPoin/assets/img/Logo.svg" alt="SakuSiswa Logo" style="height: 32px; width: auto;" class="ms-2">
        Saku<span style="color: #1a8cfd;">Siswa.</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
                <a class="nav-link active" href="/SistemPoin/pages/dashboard.php">Dashboard</a>
            </li>

            <?php 
            // Role-based navigation
            if ($_SESSION['role'] == 'siswa') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="/SistemPoin/pages/siswa/my_profile.php"><i class="bi bi-person me-1"></i>Profil Saya</a>
                </li>
            <?php } else { // guru/admin/bk/manajemen
                $show_admin = ($_SESSION['user_role'] == 'admin');
                $show_surat = in_array($_SESSION['user_role'], ['bk', 'manajemen', 'admin']);
                $show_reports = in_array($_SESSION['user_role'], ['admin', 'bk', 'manajemen']);
            ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-bs-toggle="dropdown">Kelola Data</a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <?php if($show_admin): ?>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/guru/list.php"><i class="bi bi-person-workspace me-2" style="color: #1a8cfd;"></i>Data Guru</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/siswa/list.php"><i class="bi bi-people-fill me-2" style="color: #1a8cfd;"></i>Data Siswa</a></li>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/pelanggaran/list.php"><i class="bi bi-exclamation-octagon-fill me-2" style="color: #1a8cfd;"></i>Jenis Pelanggaran</a></li>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/kelas/list.php"><i class="bi bi-door-open-fill me-2" style="color: #1a8cfd;"></i>Data Kelas</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/SistemPoin/pages/pelanggaran/add_violation.php">Pelanggaran</a>
                </li>
                <?php if($show_surat): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/SistemPoin/pages/cetak/list.php">Cetak Surat</span></a>
                </li>
                <?php endif; ?>
                <?php if($show_reports): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Laporan</a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item" href="/SistemPoin/pages/laporan/pelanggaran_siswa.php"><i class="bi bi-file-earmark-text-fill me-2" style="color: #1a8cfd;"></i>Pelanggaran Siswa</a></li>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/laporan/panggilan_ortu.php"><i class="bi bi-file-earmark-text-fill me-2" style="color: #1a8cfd;"></i>Surat Panggilan Ortu</a></li>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/laporan/perjanjian.php"><i class="bi bi-file-earmark-text-fill me-2" style="color: #1a8cfd;"></i>Surat Perjanjian</a></li>
                        <li><a class="dropdown-item" href="/SistemPoin/pages/laporan/pindah_sekolah.php"><i class="bi bi-file-earmark-text-fill me-2" style="color: #1a8cfd;"></i>Surat Pindah</a></li>
                    </ul>
                </li>
                <?php endif; 
            } ?>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-bold text-primary" href="#" id="profileDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo ($_SESSION['role'] == 'guru') ? $_SESSION['nama_pengguna'] : $_SESSION['nama_siswa']; ?>
                    <small class="d-none d-md-inline ms-1">(<?= $_SESSION['user_role'] ?? 'user' ?>)</small>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-fill me-2" style="color: #1a8cfd;"></i> Edit Profil</a></li>
                    <li><a href="/SistemPoin/logout.php" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

