<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $action = $_POST['action'];

    if ($action == 'add') {
        // Ambil data dari form
        $kode_guru = trim($_POST['kode_guru']);
        $nama_pengguna = trim($_POST['nama_pengguna']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $jabatan = trim($_POST['jabatan']);
        $telp = trim($_POST['telp']);
        $aktif = $_POST['aktif'];

        // Validasi minimal
        if (empty($jabatan) || empty($aktif)) {
            echo "<script>alert('Error: Jabatan dan Status Keaktifan harus dipilih!');</script>";
            echo "<script>window.history.back();</script>";
            exit;
        }

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah kode_guru sudah ada (reuse dari guru tidak aktif)
        $check_existing = mysqli_query($conn, "SELECT kode_guru FROM guru WHERE kode_guru = '$kode_guru'");
        
        if (mysqli_num_rows($check_existing) > 0) {
            // Update data guru yang sudah ada (reuse)
            $query = "UPDATE guru SET nama_pengguna='$nama_pengguna', username='$username', password='$password_hash', aktif='$aktif', jabatan='$jabatan', telp='$telp' WHERE kode_guru='$kode_guru'";
        } else {
            // Insert data guru baru
            $query = "INSERT INTO guru (kode_guru, nama_pengguna, role, username, password, aktif, jabatan, telp) 
                      VALUES ('$kode_guru', '$nama_pengguna', 'Guru', '$username', '$password_hash', '$aktif', '$jabatan', '$telp')";
        }
        
        // Eksekusi dan cek apakah berhasil
        if (mysqli_query($conn, $query)) {
            // Redirect jika berhasil
            header("Location: ../pages/guru/list.php");
            exit;
        } else {
            // Tampilkan error jika gagal
            die("Error saving guru: " . mysqli_error($conn));
        }

    } elseif ($action == 'edit') {
        // Ambil data dari form
        $kode_guru = trim($_POST['kode_guru']);
        $nama_pengguna = trim($_POST['nama_pengguna']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $jabatan = trim($_POST['jabatan']);
        $telp = trim($_POST['telp']);
        $aktif = $_POST['aktif'];

        // Validasi minimal
        if (empty($jabatan) || empty($aktif)) {
            echo "<script>alert('Error: Jabatan dan Status Keaktifan harus dipilih!');</script>";
            echo "<script>window.history.back();</script>";
            exit;
        }

        // Jika password diisi, update password juga
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update_guru = "UPDATE guru SET nama_pengguna='$nama_pengguna', username='$username', password='$password_hash', aktif='$aktif', jabatan='$jabatan', telp='$telp' WHERE kode_guru='$kode_guru'";
        } else {
            // Jika password kosong, jangan update password
            $update_guru = "UPDATE guru SET nama_pengguna='$nama_pengguna', username='$username', aktif='$aktif', jabatan='$jabatan', telp='$telp' WHERE kode_guru='$kode_guru'";
        }

        if (mysqli_query($conn, $update_guru)) {
            header("Location: ../pages/guru/list.php");
            exit;
        } else {
            die('Error updating guru: ' . mysqli_error($conn));
        }

    } elseif ($action == 'delete') {
        $kode_guru = $_POST['kode_guru'];
        
        // Hapus data guru
        mysqli_query($conn, "DELETE FROM guru WHERE kode_guru='$kode_guru'");
        
        header("Location: ../pages/guru/list.php");
        exit;
    }
}
?>
