<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// ambil NIS yang diminta
if (!isset($_GET['nis'])) {
    echo "<script>alert('NIS tidak ditemukan.');window.location.href='list.php';</script>";
    exit;
}
$nis = mysqli_real_escape_string($conn, $_GET['nis']);

// Query detail siswa dengan struktur database yang benar
$sql = "SELECT 
            s.nis, 
            s.nama_siswa, 
            s.jenis_kelamin, 
            s.alamat,
            s.status_siswa AS status,   
            o.id_ortu_wali,
            o.ayah, 
            o.ibu, 
            o.wali,
            o.pekerjaan_ayah, 
            o.pekerjaan_ibu, 
            o.pekerjaan_wali,
            o.no_telp_ayah, 
            o.no_telp_ibu, 
            o.no_telp_wali,
            o.alamat_ayah, 
            o.alamat_ibu, 
            o.alamat_wali,
            k.id_kelas, 
            t.tingkat, 
            p.program_keahlian, 
            k.rombel,
            g.nama_pengguna AS wali_kelas,
            g.kode_guru,
            g.telp AS telp_guru,
            g.jabatan AS jabatan_guru
        FROM siswa s
        LEFT JOIN ortu_wali o ON s.id_ortu_wali = o.id_ortu_wali
        LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
        LEFT JOIN tingkat t ON k.id_tingkat = t.id_tingkat
        LEFT JOIN program_keahlian p ON k.id_program_keahlian = p.id_program_keahlian
        LEFT JOIN guru g ON k.kode_guru = g.kode_guru
        WHERE s.nis = '$nis'";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data siswa tidak ditemukan.');window.location.href='list.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Query untuk mengambil total poin pelanggaran siswa
$sql_poin = "SELECT 
                COALESCE(SUM(jp.poin), 0) AS total_poin,
                COUNT(ps.id_pelanggaran_siswa) AS jumlah_pelanggaran
            FROM pelanggaran_siswa ps
            LEFT JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
            WHERE ps.nis = '$nis'";

$result_poin = mysqli_query($conn, $sql_poin);
$data_poin = mysqli_fetch_assoc($result_poin);

// Query untuk riwayat pelanggaran (5 terbaru)
$sql_riwayat = "SELECT 
                    ps.tanggal,
                    jp.jenis,
                    jp.poin,
                    ps.keterangan
                FROM pelanggaran_siswa ps
                JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
                WHERE ps.nis = '$nis'
                ORDER BY ps.tanggal DESC
                LIMIT 5";

$result_riwayat = mysqli_query($conn, $sql_riwayat);

// Query untuk surat keluar
$sql_surat = "SELECT 
                sk.no_surat,
                sk.jenis_surat,
                sk.tanggal_pembuatan_surat,
                sp.sekolah_tujuan,
                sp.alasan_pindah
            FROM surat_keluar sk
            LEFT JOIN surat_pindah sp ON sk.id_surat_pindah = sp.id_surat_pindah
            WHERE sk.nis = '$nis'
            ORDER BY sk.tanggal_pembuatan_surat DESC";

$result_surat = mysqli_query($conn, $sql_surat);

// Fungsi untuk mendapatkan badge status (dihapus, gunakan inline PHP)

// Fungsi untuk format tanggal Indonesia
function formatTanggal($tanggal) {
    if (empty($tanggal)) return '-';
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $tgl = date('d', strtotime($tanggal));
    $bln = date('n', strtotime($tanggal));
    $thn = date('Y', strtotime($tanggal));
    return $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
}
?>

<div class="container py-4 detail-wrapper">
    <!-- Profile Header -->
    <div class="profile-header text-center">
        <div class="profile-icon-large mx-auto">
            <i class="bi bi-person-circle"></i>
        </div>
        <h1 class="student-name-large"><?= htmlspecialchars($data['nama_siswa']) ?></h1>
        <div class="student-nis-badge mb-3">
            <i class="bi bi-qr-code me-2"></i>NIS: <?= htmlspecialchars($data['nis']) ?>
        </div>
        <div>
            <?php
            if($data['status'] == 'aktif') {
                echo '<span class="badge rounded-pill badge-aktif px-3 py-2">Aktif</span>';
            } elseif($data['status'] == 'lulus') {
                echo '<span class="badge rounded-pill badge-lulus px-3 py-2">Lulus</span>';
            } elseif($data['status'] == 'tidak_aktif') {
                echo '<span class="badge rounded-pill badge-tidak-aktif px-3 py-2">Tidak Aktif</span>';
            } else {
                echo '<span class="badge rounded-pill badge-pindah px-3 py-2">Pindah</span>';
            }
            ?>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card-detail warning">
                <div class="stat-icon-detail warning mb-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-value-detail"><?= $data_poin['total_poin'] ?></div>
                <div class="stat-label-detail">Total Poin Pelanggaran</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-detail primary">
                <div class="stat-icon-detail primary mb-3">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div class="stat-value-detail"><?= $data_poin['jumlah_pelanggaran'] ?></div>
                <div class="stat-label-detail">Jumlah Pelanggaran</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-detail success">
                <div class="stat-icon-detail success mb-3">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value-detail">
                    <?= $data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel'] ?>
                </div>
                <div class="stat-label-detail">Kelas Saat Ini</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Informasi Dasar -->
        <div class="col-lg-4">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-person-badge"></i>
                    <span>Informasi Dasar</span>
                </div>
                <table class="table-custom-detail w-100 shadow">
                    <tr>
                        <th>NIS</th>
                        <td><?= htmlspecialchars($data['nis']) ?></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?= htmlspecialchars($data['nama_siswa']) ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>
                            <?php if($data['jenis_kelamin'] == 'Laki - Laki'): ?>
                                <i class="bi bi-gender-male text-primary me-1"></i>
                            <?php else: ?>
                                <i class="bi bi-gender-female text-danger me-1"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($data['jenis_kelamin']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= htmlspecialchars($data['alamat'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Informasi Kelas -->
        <div class="col-lg-4">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-building"></i>
                    <span>Informasi Kelas</span>
                </div>
                <table class="table-custom-detail w-100 shadow">
                    <tr>
                        <th>Tingkat</th>
                        <td><?= htmlspecialchars($data['tingkat'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Program Keahlian</th>
                        <td><?= htmlspecialchars($data['program_keahlian'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Rombel</th>
                        <td><?= htmlspecialchars($data['rombel'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Wali Kelas</th>
                        <td>
                            <?= htmlspecialchars($data['wali_kelas'] ?: '-') ?>
                            <?php if($data['kode_guru']): ?>
                                <br><small class="text-muted">(<?= $data['kode_guru'] ?>)</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Kontak -->
        <div class="col-lg-4">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-telephone"></i>
                    <span>Kontak</span>
                </div>
                <table class="table-custom-detail w-100 shadow">
                    <tr>
                        <th>Telp Ayah</th>
                        <td>
                            <?php if($data['no_telp_ayah']): ?>
                                <i class="bi bi-whatsapp text-success me-1"></i>
                                <?= htmlspecialchars($data['no_telp_ayah']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Telp Ibu</th>
                        <td>
                            <?php if($data['no_telp_ibu']): ?>
                                <i class="bi bi-whatsapp text-success me-1"></i>
                                <?= htmlspecialchars($data['no_telp_ibu']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Telp Wali</th>
                        <td>
                            <?php if($data['no_telp_wali']): ?>
                                <i class="bi bi-whatsapp text-success me-1"></i>
                                <?= htmlspecialchars($data['no_telp_wali']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Telp Wali Kelas</th>
                        <td>
                            <?php if($data['telp_guru']): ?>
                                <i class="bi bi-telephone text-primary me-1"></i>
                                <?= htmlspecialchars($data['telp_guru']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua/Wali -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-gender-male"></i>
                    <span>Data Ayah</span>
                </div>
                <table class="table-custom-detail w-100 shadow">
                    <tr>
                        <th>Nama</th>
                        <td><?= htmlspecialchars($data['ayah'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td><?= htmlspecialchars($data['pekerjaan_ayah'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= htmlspecialchars($data['alamat_ayah'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-gender-female"></i>
                    <span>Data Ibu</span>
                </div>
                <table class="table-custom-detail w-100 shadow">
                    <tr>
                        <th>Nama</th>
                        <td><?= htmlspecialchars($data['ibu'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td><?= htmlspecialchars($data['pekerjaan_ibu'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= htmlspecialchars($data['alamat_ibu'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-person-plus"></i>
                    <span>Data Wali</span>
                </div>
                <table class="table-custom-detail w-100 shadow">
                    <tr>
                        <th>Nama</th>
                        <td><?= htmlspecialchars($data['wali'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td><?= htmlspecialchars($data['pekerjaan_wali'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?= htmlspecialchars($data['alamat_wali'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Pelanggaran -->
    <?php if(mysqli_num_rows($result_riwayat) > 0): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Pelanggaran (5 Terbaru)</span>
                </div>
                <div class="timeline-detail">
                    <?php while($row = mysqli_fetch_assoc($result_riwayat)): ?>
                    <div class="timeline-item-detail">
                        <div class="timeline-date-detail">
                            <i class="bi bi-calendar3"></i>
                            <?= formatTanggal($row['tanggal']) ?>
                        </div>
                        <div class="timeline-content-detail">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?= htmlspecialchars($row['jenis']) ?></strong>
                                    <p class="mb-0 mt-1 text-muted small">
                                        <?= htmlspecialchars($row['keterangan'] ?: '-') ?>
                                    </p>
                                </div>
                                <span class="poin-badge-detail">
                                    <i class="bi bi-star me-1"></i><?= $row['poin'] ?> Poin
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Surat Keluar -->
    <?php if(mysqli_num_rows($result_surat) > 0): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="info-section-detail">
                <div class="info-title-detail">
                    <i class="bi bi-envelope-paper"></i>
                    <span>Surat Keluar</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle shadow" style="border-radius: 8px; overflow: hidden;">
                        <thead class="table-dark-custom">
                            <tr>
                                <th>No. Surat</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result_surat)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['no_surat'] ?: '-') ?></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <?= htmlspecialchars($row['jenis_surat'] ?: '-') ?>
                                    </span>
                                </td>
                                <td><?= formatTanggal($row['tanggal_pembuatan_surat']) ?></td>
                                <td>
                                    <?php if($row['jenis_surat'] == 'Pindah Sekolah' && $row['sekolah_tujuan']): ?>
                                        <small>Tujuan: <?= htmlspecialchars($row['sekolah_tujuan']) ?></small>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="action-buttons-detail no-print">
        <a href="list.php" class="btn btn-cancel">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <a href="edit.php?nis=<?= $data['nis'] ?>" class="btn btn-save">
            <i class="bi bi-pencil-square me-2"></i>Edit Data
        </a>
        <!-- <button onclick="window.print()" class="btn btn-primary-action btn-action">
            <i class="bi bi-printer me-2"></i>Cetak
        </button> -->
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>