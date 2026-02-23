<?php
include '../../includes/header.php';
include '../../config/config.php';
$total_siswa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa"));
$result_siswa

?>
<style>
    .table-container {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
    }
    /* Warna header tabel sesuai gambar */
    .table-dark-custom {
        background-color: #1a374d !important; 
        color: white !important;
    }
    .table thead th {
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .table tbody tr {
        border-bottom: 1px solid #f1f1f1;
    }
    /* Efek hover lembut */
    .table-hover tbody tr:hover {
        background-color: #fcfdfe;
    }
    /* Styling Badge Status */
    .badge-aktif {
        background-color: #e6f7ef;
        color: #27ae60;
    }
    .badge-nonaktif {
        background-color: #fdeaea;
        color: #eb5757;
    } 
    </style>
    <div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-4">
            <h2 class="main-title mb-0" style="color: #2d3436; font-weight: 700;" >Kelola <span class="text-primary fst-italic">Data Siswa</span></h2>
            <small class="text-muted">Total Data: <?php echo$total_siswa ?></small>
        </div>
        <div class="col-md-8">
            <div class="d-flex justify-content-md-end gap-2">
                <input type="text" class="form-control w-50" placeholder="Cari nama atau NIS..." name="nama">
                <select class="form-select w-25">
                    <option selected>Semua Kelas</option>
                    <option>XII RPL 1</option>
                    <option>XII RPL 2</option>
                </select>
                <select class="form-select w-25">
                    <option selected>Semua Status</option>
                    <option>Aktif</option>
                    <option>Nonaktif</option>
                </select>
                <button class="btn btn-primary px-4">Filter</button>
            </div>
        </div>
    </div>

    <div class="table-container rounded-3 shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark-custom">
                    <tr>
                        <th class="py-3 ps-4">NIS</th>
                        <th class="py-3">NAMA</th>
                        <th class="py-3">KELAS</th>
                        <th class="py-3 text-center">KESALAHAN</th>
                        <th class="py-3 text-center">STATUS</th>
                        <th class="py-3 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                
                    <tr>
                        <td class="ps-4 text-muted">0001</td>
                        <td class="fw-semibold">Abdullah Musa</td>
                        <td>XII RPL 1</td>
                        <td class="text-center">0 Poin</td>
                        <td class="text-center">
                            <span class="badge rounded-pill badge-aktif px-3 py-2">Aktif</span>
                        </td>
                        <td class="text-center">
                            <button class="btn me-1"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4 text-muted">0002</td>
                        <td class="fw-semibold">Juni Budi</td>
                        <td>XII RPL 2</td>
                        <td class="text-center">0 Poin</td>
                        <td class="text-center">
                            <span class="badge rounded-pill badge-nonaktif px-3 py-2">Nonaktif</span>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>