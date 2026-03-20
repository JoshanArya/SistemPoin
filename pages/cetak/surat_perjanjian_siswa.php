<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/Poin_Pelanggaran_Siswa_XIIRPL3');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

$nis = $_POST['nis'];
// Data Orang Tua / Wali (dikirim dari file add_perjanjian_siswa.php menggunakan method POST)
$nama_ortu = $_POST['nama_ortu'];
$pekerjaan = $_POST['pekerjaan'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];

// mengambil data siswa dari database join ke tabel ortu_wali, kelas, tingkat, program_keahlian, dan guru
$query_siswa = mysqli_query($conn, "SELECT nis, nama_siswa, tingkat, program_keahlian, rombel, ayah, ibu, wali, nama_pengguna, deskripsi FROM siswa
JOIN ortu_wali USING(id_ortu_wali)
JOIN kelas USING(id_kelas)
JOIN tingkat USING(id_tingkat)
JOIN program_keahlian USING(id_program_keahlian)
JOIN guru USING(kode_guru) WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

// mengambil data guru bimbingan konseling berdasarkan tingkat siswa
$tingkat = $row_siswa['tingkat'];
if ($tingkat == 'XII') {
    $query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK XII' AND aktif = 'Y'");
} else if ($tingkat == 'XI') {
    $query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK XI' AND aktif = 'Y'");
} else {
    $query_bk = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK X' AND aktif = 'Y'");
}
$row_bk = mysqli_fetch_assoc($query_bk);
$guru_bk = $row_bk['nama_pengguna'];

// mengambil data wakasek kesiswaan dari database
$query_waka = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y'");
$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'];

// buat array bulan (berfungsi untuk mengubah angka bulan menjadi nama bulan, contoh : 2 menjadi Februari)
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$tanggal = date("d") . " " . $bulan_indo[date("n")] . " " . date("Y");

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

?>

<style>
    button {
        display: flex;
        height: 3em;
        align-items: center;
        justify-content: center;
        background-color: #eeeeee4b;
        border-radius: 3px;
        letter-spacing: 1px;
        transition: all 0.2s linear;
        cursor: pointer;
        border: none;
        background: #fff;
    }

    button>svg {
        margin-right: 5px;
        margin-left: 5px;
        font-size: 20px;
        transition: all 0.4s ease-in;
    }

    button:hover>svg {
        font-size: 1.2em;
        transform: translateX(-5px);
    }

    button:hover {
        box-shadow: 9px 9px 33px #d1d1d1, -9px -9px 33px #ffffff;
        transform: translateY(-2px);
    }

    /* animasi icon printer */
    .printer-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 100%;
    }

    .printer-container {
        height: 50%;
        width: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .printer-container svg {
        width: 100%;
        height: auto;
        transform: translateY(4px);
    }

    .printer-page-wrapper {
        width: 100%;
        height: 50%;
        display: flex;
        align-items: flex-start;
        justify-content: center;
    }

    .printer-page {
        width: 70%;
        height: 10px;
        border: 1px solid black;
        background-color: white;
        transform: translateY(0px);
        transition: all 0.3s;
        transform-origin: top;
    }

    .print-btn:hover .printer-page {
        height: 16px;
    }

    /* animasi icon printer */
</style>

<!-- tombol kembali -->
<center class="no-print">

    <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <!-- tombol ini berfungsi untuk kembali ke halaman add_perjanjian_siswa.php dan mengirimkan nis yang sudah di cek menggunakan method post -->
        <form action="add_perjanjian_siswa.php" method="post" style="margin: 0;">
            <input type="text" name="nis" value="<?= $nis ?>" hidden>
            <button type="submit">
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path
                        d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z">
                    </path>
                </svg>
                <span>Kembali</span>
            </button>
        </form>

        <!-- tombol ini berfungsi untuk print halaman ini -->
        <button class="print-btn" onclick="window.print()">
            <span class="printer-wrapper">
                <span class="printer-container">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 92 75">
                        <path stroke-width="5" stroke="black"
                            d="M12 37.5H80C85.2467 37.5 89.5 41.7533 89.5 47V69C89.5 70.933 87.933 72.5 86 72.5H6C4.067 72.5 2.5 70.933 2.5 69V47C2.5 41.7533 6.75329 37.5 12 37.5Z">
                        </path>
                        <mask fill="white" id="path-2-inside-1_30_7">
                            <path d="M12 12C12 5.37258 17.3726 0 24 0H57C70.2548 0 81 10.7452 81 24V29H12V12Z"></path>
                        </mask>
                        <path mask="url(#path-2-inside-1_30_7)" fill="black"
                            d="M7 12C7 2.61116 14.6112 -5 24 -5H57C73.0163 -5 86 7.98374 86 24H76C76 13.5066 67.4934 5 57 5H24C20.134 5 17 8.13401 17 12H7ZM81 29H12H81ZM7 29V12C7 2.61116 14.6112 -5 24 -5V5C20.134 5 17 8.13401 17 12V29H7ZM57 -5C73.0163 -5 86 7.98374 86 24V29H76V24C76 13.5066 67.4934 5 57 5V-5Z">
                        </path>
                        <circle fill="black" r="3" cy="49" cx="78"></circle>
                    </svg>
                </span>
                <span class="printer-page-wrapper"><span class="printer-page"></span></span>
            </span>
            <span>&nbsp;&nbsp;Cetak Lagi</span>
        </button>
    </div>

</center>


<div class="page">
    <!-- Header -->
    <div class="header">
        <!-- menampilkan gambar kop surat dari folder gambar-->
        <img src="/Poin_Pelanggaran_Siswa_XIIRPL3/gambar/kop.jpg" alt="kepala surat" width="100%">
    </div>

    <div class="title">SURAT PERNYATAAN SISWA</div>

    <div class="content">
        <p>Yang bertandatangan di bawah ini :</p>

        <div class="indent">
            <div class="form-row">
                <div class="label">Nama</div>
                <div class="separator">:</div>
                <!-- menampilkan nama siswa dari hasil query database line: 16-->
                <div class="field"><?php echo $row_siswa['nama_siswa']; ?></div>
            </div>
            <div class="form-row">
                <div class="label">NIS</div>
                <div class="separator">:</div>
                <!-- menampilkan nis siswa dari hasil query database line: 16-->
                <div class="field"><?php echo $row_siswa['nis']; ?></div>
            </div>
            <div class="form-row">
                <div class="label">Kelas</div>
                <div class="separator">:</div>
                <!-- menampilkan kelas siswa dari hasil query database line: 16-->
                <div class="field">
                    <?php echo $row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel'] ?>
                </div>
            </div>
            <div class="form-row">
                <div class="label">Program Keahlian</div>
                <div class="separator">:</div>
                <!-- menampilkan program keahlian siswa dari hasil query database line: 16-->
                <div class="field"><?php echo $row_siswa['deskripsi']; ?></div>
            </div>
            <div class="form-row">
                <div class="label">Masalah</div>
                <div class="separator">:</div>
                <!-- menampilkan masalah siswa dari database tabel masalah -->
                <div class="field-masalah"></div>
            </div>
        </div>

        <div class="indent">
            <div class="form-row">
                <div class="label">Nama Orang Tua</div>
                <div class="separator">:</div>
                <!-- menampilkan nama orang tua dari halaman add_perjanjian_siswa line : 10 -->
                <div class="field"><?= $nama_ortu ?></div>
            </div>
            <div class="form-row">
                <div class="label">Pekerjaan</div>
                <div class="separator">:</div>
                <!-- menampilkan pekerjaan orang tua dari halaman add_perjanjian_siswa line : 11 -->
                <div class="field"><?= $pekerjaan ?></div>
            </div>
            <div class="form-row">
                <div class="label">Alamat Rumah</div>
                <div class="separator">:</div>
                <!-- menampilkan alamat orang tua dari halaman add_perjanjian_siswa line : 12 -->
                <div class="field"><?= $alamat ?></div>
            </div>
            <div class="form-row">
                <div class="label">No. Hp./Telp.</div>
                <div class="separator">:</div>
                <!-- menampilkan no. hp orang tua dari halaman add_perjanjian_siswa line : 13 -->
                <div class="field"><?= $no_telp ?></div>
            </div>
        </div>

        <p class="statement">
            Menyatakan dan berjanji akan bersungguh-sungguh berubah dan bersedia mentaati aturan dan tata tertib
            sekolah.
            Apabila selama masa pembinaan tidak mengalami perubahan, maka siswa yang bersangkutan dikembalikan kepada
            orang tua/wali. <br>
            Demikian surat pernyataan ini saya buat dengan sesungguhnya tanpa ada tekanan dari siapapun.
        </p>

        <div class="signature-section">
            <div class="sig-block">
                <div>Mengetahui,</div>
                <div>Orang Tua/Wali siswa</div>
                <!-- menampilkan nama orang tua dari halaman add_perjanjian_siswa line : 10 -->
                <div class="sig-name-plain"><?= $nama_ortu ?></div>
            </div>
            <div class="sig-block sig-right">
                <!-- menampilkan tanggal hari ini menggunakan format tanggal indonesia -->
                <div>Denpasar, <?php echo $tanggal; ?></div>
                <div>Siswa yang bersangkutan</div>
                <!-- menampilkan nama siswa dari hasil query database line: 16 -->
                <div class="sig-name-plain"><?php echo $row_siswa['nama_siswa']; ?></div>
            </div>

            <div class="sig-block">
                <div>Guru Bimbingan Konseling</div>
                <!-- menampilkan nama guru bimbingan konseling dari hasil query database line: 17 -->
                <div class="sig-name" style="margin-top: 70px; border: none; text-decoration: underline;">
                    <?= $guru_bk ?>
                </div>
            </div>
            <div class="sig-block sig-right">
                <div>Guru Wali Kelas</div>
                <!-- menampilkan nama guru wali kelas dari hasil query database line: 16 -->
                <div class="sig-name" style="margin-top: 70px;"><?php echo $row_siswa['nama_pengguna']; ?></div>
            </div>
        </div>

        <div class="footer-sig">
            <div>Mengetahui</div>
            <div>Wakasek Kesiswaan</div>
            <div class="sig-name">
                <!-- menampilkan nama wakasek kesiswaan dari hasil query database line: 37 -->
                <?= $waka_kesiswaan ?>
            </div>
        </div>

    </div>
</div>

<script>
    // Menyertakan bagian footer (penutup halaman)
    window.onload = function () {
        window.print();
    }
</script>
<?php
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php";
?>