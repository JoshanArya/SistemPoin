<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $action = $_POST['action'];

    if ($action == 'add') {
        // Ambil data dari form
        $jenis = trim($_POST['jenis']);
        $poin = intval($_POST['poin']);

        // Validasi minimal
        if (empty($jenis) || $poin <= 0) {
            echo "<script>alert('Error: Jenis Pelanggaran dan Point harus diisi!');</script>";
            echo "<script>window.history.back();</script>";
            exit;
        }

        // Insert data pelanggaran
        $query = "INSERT INTO jenis_pelanggaran (jenis, poin) VALUES ('$jenis', '$poin')";
        
        // Eksekusi dan cek apakah berhasil
        if (mysqli_query($conn, $query)) {
            // Redirect jika berhasil
            header("Location: ../pages/pelanggaran/list.php");
            exit;
        } else {
            // Tampilkan error jika gagal
            die("Error inserting pelanggaran: " . mysqli_error($conn));
        }

    } elseif ($action == 'edit') {
        // Ambil data dari form
        $id = intval($_POST['id']);
        $jenis = trim($_POST['jenis']);
        $poin = intval($_POST['poin']);

        // Validasi minimal
        if (empty($jenis) || $poin <= 0) {
            echo "<script>alert('Error: Jenis Pelanggaran dan Point harus diisi!');</script>";
            echo "<script>window.history.back();</script>";
            exit;
        }

        // Update data pelanggaran
        $update_pelanggaran = "UPDATE jenis_pelanggaran SET jenis='$jenis', poin='$poin' WHERE id_jenis_pelanggaran='$id'";

        if (mysqli_query($conn, $update_pelanggaran)) {
            header("Location: ../pages/pelanggaran/list.php");
            exit;
        } else {
            die('Error updating pelanggaran: ' . mysqli_error($conn));
        }

    } elseif ($action == 'delete') {
        $id = intval($_POST['id']);
        
        // Hapus data pelanggaran
        mysqli_query($conn, "DELETE FROM jenis_pelanggaran WHERE id_jenis_pelanggaran='$id'");
        
        header("Location: ../pages/pelanggaran/list.php");
        exit;
    }
}
?>
