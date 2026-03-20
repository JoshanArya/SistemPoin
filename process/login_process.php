<?php
session_start();
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . '/config/config.php';

$username = $_POST['username'];
$input_password = $_POST['password'];

$queryguru = mysqli_query($conn, "SELECT nama_pengguna, username, password, role FROM guru WHERE username = '$username'");
$querysiswa = mysqli_query($conn, "SELECT nama_siswa, nis, password FROM siswa WHERE nis = '$username'");

if (mysqli_num_rows($queryguru) > 0) {
    $row_guru = mysqli_fetch_assoc($queryguru);
    if (password_verify($input_password, $row_guru['password'])) {
        $_SESSION['username'] = $row_guru['username'];
        $_SESSION['nama_pengguna'] = $row_guru['nama_pengguna'];
        $_SESSION['role'] = 'guru';  
        $_SESSION['user_role'] = $row_guru['role'];
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} elseif (mysqli_num_rows($querysiswa) > 0) {
    $row_siswa = mysqli_fetch_assoc($querysiswa);
    if (password_verify($input_password, $row_siswa['password'])) {
        $_SESSION['username'] = $row_siswa['nis'];
        $_SESSION['nama_siswa'] = $row_siswa['nama_siswa'];
        $_SESSION['role'] = 'siswa';
        $_SESSION['user_role'] = 'siswa';
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    header("Location: ../login.php?error=user_not_found");
}   
?>