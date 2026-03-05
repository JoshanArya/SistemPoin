<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');

include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $id_tingkat = $_POST['id_tingkat'] ?? '';
        $id_program_keahlian = $_POST['id_program_keahlian'] ?? '';
        $rombel = $_POST['rombel'] ?? '';
        $kode_guru = $_POST['kode_guru'] ?? '';
        $kode_guru_bk = $_POST['kode_guru_bk'] ?? '';

        // Validation
        if (empty($id_tingkat) || empty($id_program_keahlian) || empty($rombel)) {
            echo "<script>
                alert('Error: Tingkat, Program Keahlian, dan Rombel harus dipilih!');
                window.location.href = '../pages/kelas/add.php';
            </script>";
            exit;
        }

        // Check if kelas already exists
        $check = mysqli_query($conn, "SELECT id_kelas FROM kelas 
            WHERE id_tingkat = '$id_tingkat' 
            AND id_program_keahlian = '$id_program_keahlian' 
            AND rombel = '$rombel'");

        if (mysqli_num_rows($check) > 0) {
            echo "<script>
                alert('Error: Kelas tersebut sudah ada!');
                window.location.href = '../pages/kelas/add.php';
            </script>";
            exit;
        }

        // Insert new kelas - stores both homeroom teacher (kode_guru) and BK teacher (kode_guru_bk)
        // Check if kode_guru_bk column exists
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM kelas LIKE 'kode_guru_bk'");
        if (mysqli_num_rows($check_column) > 0) {
            $query = "INSERT INTO kelas (id_tingkat, id_program_keahlian, rombel, kode_guru, kode_guru_bk) 
                      VALUES ('$id_tingkat', '$id_program_keahlian', '$rombel', " . 
                      ($kode_guru ? "'$kode_guru'" : "NULL") . ", " . 
                      ($kode_guru_bk ? "'$kode_guru_bk'" : "NULL") . ")";
        } else {
            $query = "INSERT INTO kelas (id_tingkat, id_program_keahlian, rombel, kode_guru) 
                      VALUES ('$id_tingkat', '$id_program_keahlian', '$rombel', " . 
                      ($kode_guru ? "'$kode_guru'" : "NULL") . ")";
        }

        if (mysqli_query($conn, $query)) {
            echo "<script>
                alert('Data kelas berhasil ditambahkan!');
                window.location.href = '../pages/kelas/list.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menambahkan data kelas!');
                window.location.href = '../pages/kelas/add.php';
            </script>";
        }
    } 
    elseif ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $id_tingkat = $_POST['id_tingkat'] ?? '';
        $id_program_keahlian = $_POST['id_program_keahlian'] ?? '';
        $rombel = $_POST['rombel'] ?? '';
        $kode_guru = $_POST['kode_guru'] ?? '';
        $kode_guru_bk = $_POST['kode_guru_bk'] ?? '';

        if (empty($id)) {
            header("Location: ../pages/kelas/list.php");
            exit;
        }

        // Check if kode_guru_bk column exists
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM kelas LIKE 'kode_guru_bk'");
        if (mysqli_num_rows($check_column) > 0) {
            $query = "UPDATE kelas SET 
                id_tingkat = '$id_tingkat', 
                id_program_keahlian = '$id_program_keahlian', 
                rombel = '$rombel', 
                kode_guru = " . ($kode_guru ? "'$kode_guru'" : "NULL") . ",
                kode_guru_bk = " . ($kode_guru_bk ? "'$kode_guru_bk'" : "NULL") . " 
                WHERE id_kelas = '$id'";
        } else {
            $query = "UPDATE kelas SET 
                id_tingkat = '$id_tingkat', 
                id_program_keahlian = '$id_program_keahlian', 
                rombel = '$rombel', 
                kode_guru = " . ($kode_guru ? "'$kode_guru'" : "NULL") . " 
                WHERE id_kelas = '$id'";
        }

        if (mysqli_query($conn, $query)) {
            echo "<script>
                alert('Data kelas berhasil diubah!');
                window.location.href = '../pages/kelas/list.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal mengubah data kelas!');
                window.location.href = '../pages/kelas/edit.php?id=$id';
            </script>";
        }
    }
    elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        if (!empty($id)) {
            // Check if kelas has students
            $check_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE id_kelas = '$id'");
            $siswa_count = mysqli_fetch_assoc($check_siswa)['total'];

            if ($siswa_count > 0) {
                echo "<script>
                    alert('Tidak dapat menghapus kelas! Kelas ini masih memiliki $siswa_count siswa.');
                    window.location.href = '../pages/kelas/list.php';
                </script>";
                exit;
            }

            $delete = mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas = '$id'");
            
            if ($delete) {
                echo "<script>
                    alert('Data kelas berhasil dihapus!');
                    window.location.href = '../pages/kelas/list.php';
                </script>";
            } else {
                echo "<script>
                    alert('Gagal menghapus data kelas!');
                    window.location.href = '../pages/kelas/list.php';
                </script>";
            }
        }
    } else {
        header("Location: ../pages/kelas/list.php");
    }
} else {
    header("Location: ../pages/kelas/list.php");
}

