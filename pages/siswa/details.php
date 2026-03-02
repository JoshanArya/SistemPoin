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
            s.status,
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

// Fungsi untuk mendapatkan badge status (menggunakan class dari style.css)
function getStatusBadge($status) {
    switch($status) {
        case 'aktif':
            return '<span class="badge badge-aktif">Aktif</span>';
        case 'tidak_aktif':
            return '<span class="badge badge-tidak-aktif">Tidak Aktif</span>';
        case 'pindah':
            return '<span class="badge badge-pindah">Pindah Sekolah</span>';
        case 'lulus':
            return '<span class="badge badge-lulus">Lulus</span>';
        default:
            return '<span class="badge bg-secondary">Tidak Diketahui</span>';
    }
}

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

<!-- Additional CSS specific to detail page (non-conflicting with style.css) -->
<style>
/* Detail Page Specific Styles - Melengkapi style.css */
.detail-wrapper {
    padding: 20px 0;
}

/* Profile Header - Menggunakan warna dari style.css */
.profile-header {
    background: linear-gradient(135deg, #1a374d 0%, #2d3436 100%);
    color: white;
    padding: 2.5rem 2rem;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: rgba(0, 0, 0, 0.15) 0px 4px 12px;
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 10%, transparent 70%);
    transform: rotate(45deg);
}

.profile-icon-large {
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    border: 4px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(5px);
}

.student-name-large {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
}

.student-nis-badge {
    background: rgba(255,255,255,0.15);
    padding: 0.5rem 1.5rem;
    border-radius: 30px;
    display: inline-block;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255,255,255,0.2);
    font-size: 1rem;
}

/* Stat Cards - Menggunakan warna dari style.css */
.stat-card-detail {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 4px 8px;
    border: 1px solid #e7f1ff;
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.stat-card-detail:hover {
    transform: translateY(-5px);
    box-shadow: rgba(0, 0, 0, 0.15) 0px 4px 12px;
}

.stat-card-detail.warning {
    border-left: 4px solid #eb5757;
}

.stat-card-detail.success {
    border-left: 4px solid #3aa04b;
}

.stat-card-detail.primary {
    border-left: 4px solid #28a8fd;
}

.stat-icon-detail {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.stat-icon-detail.warning {
    background: #eb575749;
    color: #eb5757;
}

.stat-icon-detail.success {
    background: #31c54a6b;
    color: #3aa04b;
}

.stat-icon-detail.primary {
    background: #28a8fd44;
    color: #28a8fd;
}

.stat-value-detail {
    font-size: 2rem;
    font-weight: 700;
    color: #1a374d;
    margin-bottom: 0.2rem;
}

.stat-label-detail {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Info Sections */
.info-section-detail {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 4px 8px;
    border: 1px solid #e7f1ff;
    height: 100%;
    transition: all 0.3s ease;
}

.info-section-detail:hover {
    box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
}

.info-title-detail {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a374d;
    margin-bottom: 1.5rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid #e7f1ff;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-title-detail i {
    color: #28a8fd;
    font-size: 1.3rem;
}

.info-item-detail {
    margin-bottom: 1rem;
    padding: 0.5rem;
    border-bottom: 1px dashed #e7f1ff;
}

.info-item-detail:last-child {
    border-bottom: none;
}

.info-label-detail {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
}

.info-value-detail {
    font-weight: 500;
    color: #2d3436;
    font-size: 1rem;
}

/* Timeline for Pelanggaran */
.timeline-detail {
    position: relative;
    padding: 1rem 0;
}

.timeline-item-detail {
    padding: 1rem 0 1rem 2rem;
    border-left: 3px solid #28a8fd;
    position: relative;
    margin-left: 1rem;
    transition: all 0.3s ease;
}

.timeline-item-detail:hover {
    border-left-color: #eb5757;
}

.timeline-item-detail::before {
    content: '';
    position: absolute;
    left: -9px;
    top: 1.5rem;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #28a8fd;
    border: 3px solid white;
    box-shadow: 0 0 0 3px rgba(40, 168, 253, 0.2);
}

.timeline-item-detail:hover::before {
    background: #eb5757;
}

.timeline-date-detail {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.timeline-content-detail {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e7f1ff;
}

.poin-badge-detail {
    background: #eb575749;
    color: #eb5757;
    padding: 0.2rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

/* Table Custom */
.table-custom-detail {
    margin-bottom: 0;
}

.table-custom-detail tr {
    border-bottom: 1px solid #e7f1ff;
}

.table-custom-detail tr:last-child {
    border-bottom: none;
}

.table-custom-detail th {
    width: 40%;
    font-weight: 600;
    color: #495057;
    background: #e7f1ff;
    padding: 0.8rem 1rem;
    border: none;
}

.table-custom-detail td {
    padding: 0.8rem 1rem;
    border: none;
    background: transparent;
}

/* Action Buttons - Menggunakan class dari style.css dengan tambahan */
.action-buttons-detail {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header {
        padding: 1.5rem;
    }
    
    .student-name-large {
        font-size: 1.8rem;
    }
    
    .profile-icon-large {
        width: 80px;
        height: 80px;
        font-size: 2.5rem;
    }
    
    .action-buttons-detail {
        justify-content: center;
    }
}

@media print {
    .no-print, .action-buttons-detail, .btn-action {
        display: none !important;
    }
    
    .profile-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

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
            <?= getStatusBadge($data['status']) ?>
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
                <table class="table-custom-detail w-100">
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
                <table class="table-custom-detail w-100">
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
                <table class="table-custom-detail w-100">
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
                <table class="table-custom-detail w-100">
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
                <table class="table-custom-detail w-100">
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
                <table class="table-custom-detail w-100">
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
                    <table class="table table-hover align-middle">
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
        <button onclick="window.print()" class="btn btn-primary-action btn-action">
            <i class="bi bi-printer me-2"></i>Cetak
        </button>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>