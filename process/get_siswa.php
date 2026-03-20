<?php
header('Content-Type: application/json');
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . '/config/config.php';

$nis = $_GET['nis'] ?? '';
if (strlen($nis) !== 5) {
    echo json_encode(['nama_siswa' => '']);
    exit;
}

$nis = mysqli_real_escape_string($conn, $nis);
$query = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE nis = '$nis'");
$row = mysqli_fetch_assoc($query);

echo json_encode($row ?: ['nama_siswa' => '']);
?>