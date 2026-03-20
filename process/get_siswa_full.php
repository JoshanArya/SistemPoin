<?php
header('Content-Type: application/json');
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . '/config/config.php';

$nis = $_GET['nis'] ?? '';
if (empty($nis)) {
    echo json_encode(['error' => 'NIS required']);
    exit;
}

$nis = mysqli_real_escape_string($conn, $nis);
$query = mysqli_query($conn, "SELECT s.*, o.ayah, o.ibu, o.wali, o.pekerjaan_ayah, o.pekerjaan_ibu, o.pekerjaan_wali, 
    o.alamat_ayah, o.alamat_ibu, o.alamat_wali, o.no_telp_ayah, o.no_telp_ibu, o.no_telp_wali
    FROM siswa s 
    LEFT JOIN ortu_wali o ON s.id_ortu_wali = o.id_ortu_wali 
    WHERE s.nis = '$nis'");

$row = mysqli_fetch_assoc($query);

if ($row) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Siswa not found']);
}
?>