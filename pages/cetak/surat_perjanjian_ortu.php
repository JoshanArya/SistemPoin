<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/Poin_Pelanggaran_Siswa_XIIRPL3');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

$nis = $_POST['nis'];
// Data Orang Tua / Wali (dikirim dari file add_perjanjian_siswa.php menggunakan method POST)
$nama_ortu = $_POST['nama_ortu'];
$tempat_lahir = $_POST['tempat_lahir'];
$pekerjaan = $_POST['pekerjaan'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];

// mengambil data siswa dari database join ke tabel ortu_wali, kelas, tingkat, dan program_keahlian
$query_siswa = mysqli_query($conn, "SELECT nis, nama_siswa, tingkat, program_keahlian, rombel, ayah, ibu, wali FROM siswa
JOIN ortu_wali USING(id_ortu_wali)
JOIN kelas USING(id_kelas)
JOIN tingkat USING(id_tingkat)
JOIN program_keahlian USING(id_program_keahlian) WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

// buat array bulan (berfungsi untuk mengubah angka bulan menjadi nama bulan, contoh : 2 menjadi Februari)
$bulan_indo = ["", "Januari", "Pebruari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
// hitung tanggal 3 bulan dari sekarang
$tgl_target = strtotime("+3 months");
// ambil 3 bulan dari sekarang
$bulan = $bulan_indo[date("n", $tgl_target)] . " " . date("Y", $tgl_target);

$tanggal = date("d") . " " . $bulan_indo[date("n")] . " " . date("Y");

$tanggal_lahir = $_POST['tanggal_lahir'];
//ubah format tanggal lahir
$tanggal_lahir = date("d", strtotime($tanggal_lahir)) . " " . $bulan_indo[date("n", strtotime($tanggal_lahir))] . " " . date("Y", strtotime($tanggal_lahir));

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

    .statement b {
        text-decoration: underline 1px dotted black;
        text-underline-offset: 5px;
    }
</style>

<!-- tombol kembali -->
<center class="no-print">

    <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <!-- tombol ini berfungsi untuk kembali ke halaman add_perjanjian_ortu.php dan mengirimkan nis yang sudah di cek menggunakan method post -->
        <form action="add_perjanjian_ortu.php" method="post" style="margin: 0;">
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
        <img src="/Poin_Pelanggaran_Siswa_XIIRPL3/gambar/kop.jpg" alt="kepala surat" width="100%">
    </div>

    <div class="title">SURAT PERNYATAAN ORANG TUA</div>
    <br>
    <div class="content">
        <p>Yang bertandatangan di bawah ini orang tua/wali siswa SMK TI Bali Global Denpasar :</p>

        <div class="indent">
            <div class="form-row">
                <div class="label">Nama</div>
                <div class="separator">:</div>
                <div class="field"><?= $nama_ortu ?></div>
            </div>
            <div class="form-row">
                <div class="label">Tempat/ tanggal Lahir</div>
                <div class="separator">:</div>
                <div class="field"><?= $tempat_lahir ?>/<?= $tanggal_lahir ?></div>
            </div>
            <div class="form-row">
                <div class="label">Pekerjaan</div>
                <div class="separator">:</div>
                <div class="field"><?= $pekerjaan ?></div>
            </div>
            <div class="form-row">
                <div class="label">Alamat Rumah</div>
                <div class="separator">:</div>
                <div class="field"><?= $alamat ?></div>
            </div>
            <div class="form-row">
                <div class="label">No. Hp./Telp.</div>
                <div class="separator">:</div>
                <div class="field"><?= $no_telp ?></div>
            </div>
        </div>
        <br>
        <br>

        <!-- menampilkan isi surat perjanjian orang tua/wali dengan menampilkan data siswa dari hasil query database line: 16-->
        <p class="statement">
            Menyatakan memang benar sanggup membina anak kami yang bernama
            <b><?php echo $row_siswa['nama_siswa']; ?></b>, Kelas :
            <b><?php echo $row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel'] ?></b>
            untuk lebih disiplin mengikuti proses pembelajaran dan mengikuti Tata Tertib Sekolah. <br><br>
            Demikian pernyataan kami dan jika tidak sesuai dengan pernyataan diatas, anak kami dapat dikeluarkan dari
            sekolah ini dengan rekomendasi pindah ke SMK lain yang serumpun.
        </p>

        <div class="signature-section">
            <div class="sig-block"></div>
            <div class="sig-block sig-right">
                <!-- menampilkan tanggal hari ini dengan menggunakan bulan bahasa indonesia line : 31-->
                <div>Denpasar, <?php echo $tanggal ?></div>
                <div>
                    Yang membuat pernyataan<br>
                    Orang Tua/Wali siswa
                </div>
                <!-- menampilkan nama orang tua/wali -->
                <div class="sig-name-plain"><?= $nama_ortu ?></div>
            </div>
        </div>

        <div class="indent">
            <div class="form-row">
                <div><u>NB : <br>
                        <!-- menampilkan bulan hari ini + 3 bulan kedepan -->
                        Jika siswa tidak bisa mengikuti proses pembelajaran sampai bulan <?= $bulan ?> maka <br>
                        Siswa dinyatakan mengundurkan diri.</u>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ketika halaman selesai loading maka halaman akan otomatis di print
    window.onload = function () {
        window.print();
    }
</script>
<?php
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php";
?>