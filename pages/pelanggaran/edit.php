<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Ambil data pelanggaran berdasarkan id
$id = $_GET["id"];
$result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jenis_pelanggaran WHERE id_jenis_pelanggaran = '$id'"));

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="main-title mb-0" style="color: #1a374d; font-weight: 700;">
                        Edit <span class="text-primary fst-italic">Jenis Pelanggaran</span>
                    </h2>
                </div>

                <form action="/SistemPoin/process/pelanggaran_process.php" method="POST">
                    <input type="hidden" name="action" value="edit" />
                    <input type="hidden" name="id" value="<?= $result['id_jenis_pelanggaran'] ?>" />

                    <div class="form-section-title">
                        <i class="bi bi-exclamation-triangle"></i> Detail Pelanggaran
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Jenis Pelanggaran</label>
                            <input type="text" name="jenis" class="form-control"
                                placeholder="Masukkan Jenis Pelanggaran.." value="<?= $result['jenis'] ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Point</label>
                            <input type="number" name="poin" class="form-control" placeholder="Masukkan Point.."
                                value="<?= $result['poin'] ?>" min="1" required>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="d-flex justify-content-end gap-3">
                        <a href="list.php" class="btn btn-cancel shadow-sm border">Batal</a>
                        <button type="submit" class="btn btn-save shadow-sm border">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>