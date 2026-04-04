<?php
session_start();
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . '/config/config.php';

// Role check
if (!isset($_SESSION['username']) || !in_array($_SESSION['user_role'] ?? 'guru', ['guru', 'bk', 'admin'])) {
    header("Location: ../pages/dashboard.php");
    exit;
}

$action = $_POST['action'] ?? '';

if ($action == 'add') {
        $nis = mysqli_real_escape_string($conn, trim($_POST['nis']));
        $id_jenis = (int)$_POST['id_jenis_pelanggaran'];
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $keterangan = mysqli_real_escape_string($conn, trim($_POST['keterangan']));

        // Validation
        if (empty($nis) || empty($tanggal) || $id_jenis < 1) {
            echo "<script>alert('Error: NIS, tanggal, dan jenis pelanggaran wajib!');window.history.back();</script>";
            exit;
        }

        // Check siswa exists
        $check_siswa = mysqli_query($conn, "SELECT nis FROM siswa WHERE nis = '$nis'");
        if (mysqli_num_rows($check_siswa) == 0) {
            echo "<script>alert('Error: Siswa dengan NIS $nis tidak ditemukan!');window.history.back();</script>";
            exit;
        }

        // Check jenis pelanggaran valid
        $check_jenis = mysqli_query($conn, "SELECT id_jenis_pelanggaran FROM jenis_pelanggaran WHERE id_jenis_pelanggaran = $id_jenis");
        if (mysqli_num_rows($check_jenis) == 0) {
            echo "<script>alert('Error: Jenis pelanggaran tidak valid!');window.history.back();</script>";
            exit;
        }

        // Insert pelanggaran
        $query = "INSERT INTO pelanggaran_siswa (tanggal, nis, id_jenis_pelanggaran, keterangan) 
                  VALUES ('$tanggal', '$nis', $id_jenis, '$keterangan')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Pelanggaran berhasil ditambahkan!');window.location.href='../pages/siswa/list.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');window.history.back();</script>";
        }
    } elseif ($action == 'delete') {
        $id = (int)$_POST['id'];
        mysqli_query($conn, "DELETE FROM pelanggaran_siswa WHERE id_pelanggaran_siswa = $id");
        header("Location: ../pages/pelanggaran/list.php");
    } else {
        header("Location: ../pages/pelanggaran/list.php");
    }
?>
