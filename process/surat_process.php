<?php
session_start();
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . '/config/config.php';

// Role check - BK/Admin only
if (!in_array($_SESSION['user_role'] ?? 'guru', ['bk', 'admin', 'manajemen'])) {
    header("Location: ../pages/dashboard.php");
    exit;
}

$nis = mysqli_real_escape_string($conn, $_POST['nis'] ?? '');
$jenis_surat = $_POST['jenis_surat'] ?? '';
$no_surat_input = mysqli_real_escape_string($conn, $_POST['no_surat'] ?? '');

if (empty($nis) || empty($jenis_surat)) {
    echo "<script>alert('NIS dan jenis surat wajib!');window.history.back();</script>";
    exit;
}
$jenis_surat_esc = mysqli_real_escape_string($conn, $jenis_surat);

// Format nomor surat lengkap (Contoh: 123/SMK TI/BG/IV/2026)
$bulan_romawi_list = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
$bulan_romawi_now = $bulan_romawi_list[date("n")];
$no_surat_full = $no_surat_input . "/SMK TI/BG/" . $bulan_romawi_now . "/" . date("Y");

// Ambil data tingkat siswa saat ini untuk arsip surat
$q_siswa = mysqli_query($conn, "SELECT t.tingkat FROM siswa s 
    JOIN kelas k ON s.id_kelas = k.id_kelas 
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat 
    WHERE s.nis = '$nis'");
$row_s = mysqli_fetch_assoc($q_siswa);

if (!$row_s) {
    echo "<script>alert('Siswa tidak ditemukan');window.history.back();</script>";
    exit;
}
$tingkat_saat_ini = $row_s['tingkat'];

// Ambil snapshot data Guru (Wali Kelas, BK, Waka) untuk disimpan di tabel perjanjian
// Ini penting agar jika di masa depan guru berubah, data surat yang lama tetap valid
$q_pejabat = mysqli_query($conn, "SELECT 
    (SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y' LIMIT 1) as waka,
    (SELECT nama_pengguna FROM guru g JOIN kelas k ON g.kode_guru = k.kode_guru JOIN siswa s ON k.id_kelas = s.id_kelas WHERE s.nis = '$nis') as walas,
    (SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK $tingkat_saat_ini' AND aktif = 'Y' LIMIT 1) as bk");
$pejabat = mysqli_fetch_assoc($q_pejabat);
$waka_nama = mysqli_real_escape_string($conn, $pejabat['waka'] ?? '');
$walas_nama = mysqli_real_escape_string($conn, $pejabat['walas'] ?? '');
$bk_nama = mysqli_real_escape_string($conn, $pejabat['bk'] ?? '');

// Ambil ID pelanggaran terakhir (opsional, jika ada)
$q_last_pel = mysqli_query($conn, "SELECT id_pelanggaran_siswa FROM pelanggaran_siswa WHERE nis = '$nis' ORDER BY tanggal DESC LIMIT 1");
$row_pel = mysqli_fetch_assoc($q_last_pel);
$id_pelanggaran = $row_pel['id_pelanggaran_siswa'] ?? 'NULL';


// Ambil ID tahun ajaran aktif
$tahun_query = mysqli_query($conn, "SELECT id_tahun_ajaran FROM tahun_ajaran WHERE aktif = 'Y'");
$tahun_id = mysqli_fetch_assoc($tahun_query)['id_tahun_ajaran'] ?? 1;
$school_id = 1; 

// Inisialisasi variabel khusus jenis surat
$tanggal_pemanggilan = "NULL";
$keperluan = "NULL";

if ($jenis_surat == 'Panggilan Orang Tua') {
    $datetime_combined = $_POST['tanggal'] . ' ' . $_POST['jam'];
    $tanggal_pemanggilan = "'" . mysqli_real_escape_string($conn, $datetime_combined) . "'";
    $keperluan = "'" . mysqli_real_escape_string($conn, $_POST['keperluan']) . "'";
} elseif ($jenis_surat == 'Surat Perjanjian Orang Tua' || $jenis_surat == 'Surat Perjanjian Siswa') {
    $keperluan = "'" . mysqli_real_escape_string($conn, $_POST['masalah'] ?? 'Pernyataan Kedisiplinan') . "'";
}

// Simpan data ke tabel surat_keluar
$query = "INSERT INTO surat_keluar (no_surat, jenis_surat, nis, tanggal_pembuatan_surat, id_profil_sekolah, id_tahun_ajaran, tingkat, tanggal_pemanggilan, keperluan, status_surat) 
          VALUES ('$no_surat_full', '$jenis_surat_esc', '$nis', CURDATE(), $school_id, $tahun_id, '$tingkat_saat_ini', $tanggal_pemanggilan, $keperluan, 'Sudah dicetak')";

if (mysqli_query($conn, $query)) {
    $id_surat_keluar = mysqli_insert_id($conn);
    
    // Proses Logika Khusus Jenis Surat (Menyimpan detail ke tabel masing-masing)
    if ($jenis_surat == 'Pindah Sekolah') {
        $sekolah_tujuan = mysqli_real_escape_string($conn, $_POST['pindah_ke']);
        $alasan_pindah = mysqli_real_escape_string($conn, $_POST['alasan_pindah']);
        $nama_ortu = mysqli_real_escape_string($conn, $_POST['nama_ortu']);
        $alamat_ortu = mysqli_real_escape_string($conn, $_POST['alamat']);

        mysqli_query($conn, "INSERT INTO surat_pindah (sekolah_tujuan, alasan_pindah, nama_ortu, alamat_ortu) 
                            VALUES ('$sekolah_tujuan', '$alasan_pindah', '$nama_ortu', '$alamat_ortu')");
        $id_pindah = mysqli_insert_id($conn);
        mysqli_query($conn, "UPDATE surat_keluar SET id_surat_pindah = $id_pindah WHERE id_surat_keluar = $id_surat_keluar");
    } 
    elseif ($jenis_surat == 'Surat Perjanjian Siswa') {
        $nama_ortu = mysqli_real_escape_string($conn, $_POST['nama_ortu']);
        $pekerjaan = mysqli_real_escape_string($conn, $_POST['pekerjaan']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

        mysqli_query($conn, "INSERT INTO perjanjian_siswa (tanggal, id_pelanggaran_siswa, status, tingkat, nama_ortu, pekerjaan_ortu, alamat_ortu, no_telp_ortu, wali_kelas, guru_bk, wakasek_kesiswaan) 
                            VALUES (NOW(), $id_pelanggaran, 'Masih Proses', '$tingkat_saat_ini', '$nama_ortu', '$pekerjaan', '$alamat', '$no_telp', '$walas_nama', '$bk_nama', '$waka_nama')");
        $id_p_siswa = mysqli_insert_id($conn);
        mysqli_query($conn, "UPDATE surat_keluar SET id_perjanjian_siswa = $id_p_siswa WHERE id_surat_keluar = $id_surat_keluar");
    }
    elseif ($jenis_surat == 'Surat Perjanjian Orang Tua') {
        $nama_ortu = mysqli_real_escape_string($conn, $_POST['nama_ortu']);
        $pekerjaan = mysqli_real_escape_string($conn, $_POST['pekerjaan']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

        mysqli_query($conn, "INSERT INTO perjanjian_orang_tua (tanggal, id_pelanggaran_siswa, status, tingkat, nama_ortu, pekerjaan_ortu, alamat_ortu, no_telp_ortu) 
                            VALUES (NOW(), $id_pelanggaran, 'Masih Proses', '$tingkat_saat_ini', '$nama_ortu', '$pekerjaan', '$alamat', '$no_telp')");
        $id_p_ortu = mysqli_insert_id($conn);
        mysqli_query($conn, "UPDATE surat_keluar SET id_perjanjian_ortu = $id_p_ortu WHERE id_surat_keluar = $id_surat_keluar");
    }

    // Mapping file cetak
    $file_cetak = [
        'Panggilan Orang Tua' => 'surat_panggilan_ortu.php',
        'Pindah Sekolah' => 'surat_pindah_sekolah.php',
        'Surat Perjanjian Orang Tua' => 'surat_perjanjian_ortu.php',
        'Surat Perjanjian Siswa' => 'surat_perjanjian_siswa.php'
    ];

    $target = $file_cetak[$jenis_surat] ?? '';

    if ($target) {
        // Kirim ulang data POST ke halaman cetak agar tampilan tetap sama
        echo "<form id='redirectForm' action='../pages/cetak/$target' method='POST'>";
        foreach ($_POST as $key => $value) {
            $val = ($key == 'no_surat') ? $no_surat_full : $value;
            echo "<input type='hidden' name='".htmlspecialchars($key)."' value='".htmlspecialchars($val)."'>";
        }
        echo "</form><script>document.getElementById('redirectForm').submit();</script>";
    } else {
        header("Location: ../pages/siswa/details.php?nis=$nis");
    }
} else {
    echo "<script>alert('Error save surat: " . mysqli_error($conn) . "');window.history.back();</script>";
}

?>
