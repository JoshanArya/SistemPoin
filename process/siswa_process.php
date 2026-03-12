<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $action = $_POST['action'];

    if ($action == 'add') {
        // 1. Ambil Data Utama Siswa
        $nis = trim($_POST['nis']);
        $nama_siswa = trim($_POST['nama_siswa']);
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = trim($_POST['alamat_siswa']);
        $kelas_value = $_POST['kelas'];
        $status_siswa = trim($_POST['status_siswa']);
        
        if (empty($jenis_kelamin) || empty($kelas_value) || empty($status_siswa)) {
            echo "<script>alert('Error: Jenis Kelamin, Kelas, dan Status Siswa harus dipilih!');</script>";
            echo "<script>window.history.back();</script>";
            exit;
        }

        // Jika form mengirimkan id_kelas langsung (angka), gunakan itu.
        if (is_numeric($kelas_value) && intval($kelas_value) > 0) {
            $id_kelas = intval($kelas_value);
        } else {
            // 2. Pecah string kelas untuk mendapatkan komponennya
            $kelas_parts = explode(" ", $kelas_value);
            
            // Pastikan array memiliki minimal 3 elemen
            if (count($kelas_parts) < 3) {
                die("Error: Format kelas tidak valid!");
            }

            $tingkat = $kelas_parts[0];
            $program_keahlian = $kelas_parts[1];
            $rombel = $kelas_parts[2];

            // 3. Ambil ID Kelas dari database
            $query_kelas = mysqli_query($conn, "SELECT id_kelas FROM kelas 
                                JOIN program_keahlian USING(id_program_keahlian) 
                                JOIN tingkat USING(id_tingkat) 
                                WHERE tingkat = '$tingkat' AND program_keahlian = '$program_keahlian' AND rombel = '$rombel'");
            
            if (!$query_kelas || mysqli_num_rows($query_kelas) == 0) {
                die("Error: Kelas tidak ditemukan di database!");
            }
            
            $id_kelas = mysqli_fetch_assoc($query_kelas)['id_kelas'];
        }

        // 4. Ambil Data Orang Tua / Wali
        $ayah = trim($_POST['ayah']);
        $ibu = trim($_POST['ibu']);
        $wali = trim($_POST['wali']);
        $pekerjaan_ayah = trim($_POST['pekerjaan_ayah']);
        $pekerjaan_ibu = trim($_POST['pekerjaan_ibu']);
        $pekerjaan_wali = trim($_POST['pekerjaan_wali']);
        $telp_ayah = trim($_POST['telp_ayah']);
        $telp_ibu = trim($_POST['telp_ibu']);
        $telp_wali = trim($_POST['telp_wali']);
        $alamat_ayah = trim($_POST['alamat_ayah']);
        $alamat_ibu = trim($_POST['alamat_ibu']);
        $alamat_wali = trim($_POST['alamat_wali']);

        // 5. Insert Data Orang Tua / Wali
        $query_ortu = "INSERT INTO ortu_wali (ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, no_telp_ayah, no_telp_ibu, no_telp_wali, alamat_ayah, alamat_ibu, alamat_wali) 
                       VALUES ('$ayah', '$ibu', '$wali', '$pekerjaan_ayah', '$pekerjaan_ibu', '$pekerjaan_wali', '$telp_ayah', '$telp_ibu', '$telp_wali', '$alamat_ayah', '$alamat_ibu', '$alamat_wali')";
        
        if (!mysqli_query($conn, $query_ortu)) {
            die("Error inserting ortu_wali: " . mysqli_error($conn));
        }

        // 6. Ambil ID Orang Tua / Wali yang baru dibuat
        $id_ortu_wali = mysqli_insert_id($conn);

        // 7. Hash Password
        $password_input = password_hash("Siswa12345*!", PASSWORD_DEFAULT);

        // 8. Insert Data Siswa
        $query = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, alamat, password, status_siswa, id_ortu_wali, id_kelas) 
                  VALUES ('$nis', '$nama_siswa', '$jenis_kelamin', '$alamat', '$password_input', '$status_siswa', '$id_ortu_wali', '$id_kelas')";
        
        // 9. Eksekusi dan cek apakah berhasil
        if (mysqli_query($conn, $query)) {
            // Redirect jika berhasil
            header("Location: ../pages/siswa/list.php");
            exit;
        } else {
            // Tampilkan error jika gagal
            die("Error inserting siswa: " . mysqli_error($conn));
        }

    } elseif ($action == 'edit') {
        // Ambil data dari form (nis readonly sehingga dikirim)
        $nis = trim($_POST['nis']);
        $nama_siswa = trim($_POST['nama_siswa']);
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = trim($_POST['alamat_siswa']);
        $kelas_value = $_POST['kelas'];
        $status_siswa = trim($_POST['status_siswa']);

        // Validasi minimal
        if (empty($jenis_kelamin) || empty($kelas_value) || empty($status_siswa)) {
            echo "<script>alert('Error: Jenis Kelamin, Kelas, dan Status Siswa harus dipilih!');</script>";
            echo "<script>window.history.back();</script>";
            exit;
        }

        // Tentukan id_kelas (bisa berupa id langsung atau teks)
        if (is_numeric($kelas_value) && intval($kelas_value) > 0) {
            $id_kelas = intval($kelas_value);
        } else {
            $kelas_parts = explode(" ", $kelas_value);
            if (count($kelas_parts) < 3) die("Error: Format kelas tidak valid!");
            $tingkat = $kelas_parts[0];
            $program_keahlian = $kelas_parts[1];
            $rombel = $kelas_parts[2];
            $qk = mysqli_query($conn, "SELECT id_kelas FROM kelas 
                            JOIN program_keahlian USING(id_program_keahlian) 
                            JOIN tingkat USING(id_tingkat) 
                            WHERE tingkat = '$tingkat' AND program_keahlian = '$program_keahlian' AND rombel = '$rombel'");
            if (!$qk || mysqli_num_rows($qk) == 0) die("Error: Kelas tidak ditemukan di database!");
            $id_kelas = mysqli_fetch_assoc($qk)['id_kelas'];
        }

        // Ambil id_ortu_wali dari siswa
        $res = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis = '$nis'");
        $id_ortu_wali = null;
        if ($res && mysqli_num_rows($res) > 0) {
            $id_ortu_wali = mysqli_fetch_assoc($res)['id_ortu_wali'];
        }

        // Ambil data orang tua / wali dari form
        $ayah = trim($_POST['ayah']);
        $ibu = trim($_POST['ibu']);
        $wali = trim($_POST['wali']);
        $pekerjaan_ayah = trim($_POST['pekerjaan_ayah']);
        $pekerjaan_ibu = trim($_POST['pekerjaan_ibu']);
        $pekerjaan_wali = trim($_POST['pekerjaan_wali']);
        $telp_ayah = trim($_POST['telp_ayah']);
        $telp_ibu = trim($_POST['telp_ibu']);
        $telp_wali = trim($_POST['telp_wali']);
        $alamat_ayah = trim($_POST['alamat_ayah']);
        $alamat_ibu = trim($_POST['alamat_ibu']);
        $alamat_wali = trim($_POST['alamat_wali']);

        if ($id_ortu_wali) {
            // Update ortu_wali
            $update_ortu = "UPDATE ortu_wali SET ayah='$ayah', ibu='$ibu', wali='$wali', pekerjaan_ayah='$pekerjaan_ayah', pekerjaan_ibu='$pekerjaan_ibu', pekerjaan_wali='$pekerjaan_wali', no_telp_ayah='$telp_ayah', no_telp_ibu='$telp_ibu', no_telp_wali='$telp_wali', alamat_ayah='$alamat_ayah', alamat_ibu='$alamat_ibu', alamat_wali='$alamat_wali' WHERE id_ortu_wali='$id_ortu_wali'";
            if (!mysqli_query($conn, $update_ortu)) {
                die('Error updating ortu_wali: ' . mysqli_error($conn));
            }
        } else {
            // Insert baru jika tidak ada
            $query_ortu = "INSERT INTO ortu_wali (ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, no_telp_ayah, no_telp_ibu, no_telp_wali, alamat_ayah, alamat_ibu, alamat_wali) 
                           VALUES ('$ayah', '$ibu', '$wali', '$pekerjaan_ayah', '$pekerjaan_ibu', '$pekerjaan_wali', '$telp_ayah', '$telp_ibu', '$telp_wali', '$alamat_ayah', '$alamat_ibu', '$alamat_wali')";
            if (!mysqli_query($conn, $query_ortu)) {
                die("Error inserting ortu_wali: " . mysqli_error($conn));
            }
            $id_ortu_wali = mysqli_insert_id($conn);
        }

        // Update data siswa
        // tidak mengubah kolom nis, hanya data lain
        $update_siswa = "UPDATE siswa SET nama_siswa='$nama_siswa', jenis_kelamin='$jenis_kelamin', alamat='$alamat', status_siswa='$status_siswa', id_ortu_wali='$id_ortu_wali', id_kelas='$id_kelas' WHERE nis='$nis'";
        if (mysqli_query($conn, $update_siswa)) {
            header("Location: ../pages/siswa/list.php");
            exit;
        } else {
            die('Error updating siswa: ' . mysqli_error($conn));
        }

    } elseif ($action == 'delete') {
        $nis = $_POST['nis'];
        
        // Mengambil ID ortu_wali dari siswa
        $result = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis='$nis'");
        if ($result && mysqli_num_rows($result) > 0) {
            $id_ortu_wali = mysqli_fetch_assoc($result)['id_ortu_wali'];

            // Menghapus data siswa
            mysqli_query($conn, "DELETE FROM siswa WHERE nis='$nis'");

            // Menghapus data perjanjian ortu
            // mysqli_query($conn, "DELETE FROM perjanjian_orang_tua WHERE id_ortu_wali='$id_ortu_wali'");
            
            // Menghapus data ortu_wali
            mysqli_query($conn, "DELETE FROM ortu_wali WHERE id_ortu_wali='$id_ortu_wali'");
        }
        
        header("Location: ../pages/siswa/list.php");
        exit;
    }
}
?>

