<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/SistemPoin');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$username_sess = $_SESSION['username'];
$role = $_SESSION['role'];
$message = "";
$error = "";

// Ambil data user saat ini dari database
if ($role == 'guru') {
    $query = mysqli_query($conn, "SELECT username, nama_pengguna FROM guru WHERE username = '$username_sess'");
    $user_data = mysqli_fetch_assoc($query);
} else {
    $query = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa WHERE nis = '$username_sess'");
    $user_data = mysqli_fetch_assoc($query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validasi kecocokan password baru jika diisi
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    }

    if (empty($error)) {
        if ($role == 'guru') {
            $new_username = mysqli_real_escape_string($conn, trim($_POST['username']));
            
            // Cek apakah username baru sudah digunakan oleh guru lain
            $check = mysqli_query($conn, "SELECT username FROM guru WHERE username = '$new_username' AND username != '$username_sess'");
            if (mysqli_num_rows($check) > 0) {
                $error = "Username sudah digunakan oleh pengguna lain.";
            } else {
                $sql = "UPDATE guru SET username = '$new_username'";
                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql .= ", password = '$hashed_password'";
                }
                $sql .= " WHERE username = '$username_sess'";
                
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['username'] = $new_username; // Update session dengan username baru
                    $username_sess = $new_username;
                    $message = "Profil berhasil diperbarui.";
                    $user_data['username'] = $new_username;
                } else {
                    $error = "Gagal memperbarui profil: " . mysqli_error($conn);
                }
            }
        } else {
            // Role Siswa - Hanya diperbolehkan mengubah password
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE siswa SET password = '$hashed_password' WHERE nis = '$username_sess'";
                if (mysqli_query($conn, $sql)) {
                    $message = "Password berhasil diperbarui.";
                } else {
                    $error = "Gagal memperbarui password.";
                }
            } else {
                $message = "Tidak ada perubahan yang disimpan.";
            }
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="form-container shadow-sm p-4 bg-white rounded border">
                <h2 class="main-title mb-4" style="color: #1a374d; font-weight: 700;">
                    <i class="bi bi-person-gear me-2"></i>Pengaturan Profil
                </h2>

                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($role == 'guru' ? $user_data['nama_pengguna'] : $user_data['nama_siswa']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><?= $role == 'guru' ? 'Username' : 'Username (NIS)' ?></label>
                        <input type="text" name="username" class="form-control <?= $role == 'siswa' ? 'bg-light' : '' ?>" 
                               value="<?= htmlspecialchars($role == 'guru' ? $user_data['username'] : $user_data['nis']) ?>" 
                               <?= $role == 'siswa' ? 'readonly' : 'required' ?>>
                        <?php if($role == 'siswa'): ?><small class="text-muted fst-italic">NIS tidak dapat diubah.</small><?php endif; ?>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3 text-primary"><i class="bi bi-shield-lock me-2"></i>Keamanan</h5>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/SistemPoin/pages/dashboard.php" class="btn btn-secondary shadow-sm">Batal</a>
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>