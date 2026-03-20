<?php
session_start();
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . '/config/config.php';

// Role check - BK/Admin only
if (!in_array($_SESSION['user_role'] ?? 'guru', ['bk', 'admin', 'manajemen'])) {
    header("Location: ../pages/dashboard.php");
    exit;
}

$action = $_POST['action'] ?? '';
$nis = mysqli_real_escape_string($conn, $_POST['nis']);
$jenis_surat = mysqli_real_escape_string($conn, $_POST['jenis_surat']);

if (empty($nis) || empty($jenis_surat)) {
    echo "<script>alert('NIS dan jenis surat wajib!');window.history.back();</script>";
    exit;
}

// Check siswa
$check_siswa = mysqli_query($conn, "SELECT nis FROM siswa WHERE nis = '$nis'");
if (mysqli_num_rows($check_siswa) == 0) {
    echo "<script>alert('Siswa tidak ditemukan');window.history.back();</script>";
    exit;
}

// Get current tahun ajaran & school
$tahun_query = mysqli_query($conn, "SELECT id_tahun_ajaran FROM tahun_ajaran WHERE aktif = 'Y'");
$tahun_id = mysqli_fetch_assoc($tahun_query)['id_tahun_ajaran'] ?? 1;

$school_id = 1; // Default from profil_sekolah

// Generate no_surat
$prefix = strtoupper(substr($jenis_surat, 0, 3));
$no_surat = $prefix . '/' . date('Ymd') . '/' . rand(100,999);

// Save to surat_keluar
$query = "INSERT INTO surat_keluar (no_surat, jenis_surat, nis, tanggal_pembuatan_surat, id_profil_sekolah, id_tahun_ajaran) 
          VALUES ('$no_surat', '$jenis_surat', '$nis', CURDATE(), $school_id, $tahun_id)";

if (mysqli_query($conn, $query)) {
    $id_surat_keluar = mysqli_insert_id($conn);
    
    // For pindah sekolah, save additional data
    if ($jenis_surat == 'Pindah Sekolah') {
        $sekolah_tujuan = mysqli_real_escape_string($conn, $_POST['sekolah_tujuan']);
        $alasan_pindah = mysqli_real_escape_string($conn, $_POST['alasan_pindah']);
        mysqli_query($conn, "INSERT INTO surat_pindah (sekolah_tujuan, alasan_pindah) VALUES ('$sekolah_tujuan', '$alasan_pindah')");
        $id_pindah = mysqli_insert_id($conn);
        mysqli_query($conn, "UPDATE surat_keluar SET id_surat_pindah = $id_pindah WHERE id_surat_keluar = $id_surat_keluar");
    }
    
    // Redirect to print page with ID
    header("Location: ../pages/surat/" . strtolower(str_replace(' ', '_', $jenis_surat)) . ".php?id_surat=$id_surat_keluar&nis=$nis");
} else {
    echo "<script>alert('Error save surat: " . mysqli_error($conn) . "');window.history.back();</script>";
}
?>

