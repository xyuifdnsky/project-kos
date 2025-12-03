<?php
session_start();
include '../../../config/database.php';
$conn = databaseconfig::getConnection();

// Pastikan user login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];

// Ambil data user saat ini
$q = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'");
$user = mysqli_fetch_assoc($q);

// Jika submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_user    = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $nama_kos     = mysqli_real_escape_string($conn, $_POST['nama_kos']);
    $alamat_kos   = mysqli_real_escape_string($conn, $_POST['alamat_kos']);
    $lokasi_maps  = mysqli_real_escape_string($conn, $_POST['lokasi_maps']);
    $deskripsi    = mysqli_real_escape_string($conn, $_POST['deskripsi_kos']);

    // Upload foto profil jika ada
    $foto = $user['foto_profil'];  

    if (!empty($_FILES['foto_profil']['name'])) {
        $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
        $foto = "profil".time().".".$ext;
        move_uploaded_file($_FILES['foto_profil']['tmp_name'], "profil/$foto");
    }

    // Update user
    $sql = "
        UPDATE users SET
            nama_user      = '$nama_user',
            nama_kos       = '$nama_kos',
            alamat_kos     = '$alamat_kos',
            lokasi_maps    = '$lokasi_maps',
            deskripsi_kos  = '$deskripsi',
            foto_profil    = '$foto'
        WHERE id_user = '$id_user'
    ";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal update!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4" style="max-width:700px;">
    <h3 class="fw-bold mb-3">Edit Profil</h3>

    <form method="POST" enctype="multipart/form-data" class="p-4 bg-white rounded shadow">

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_user" class="form-control" value="<?= $user['nama_user'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Nama Kos</label>
            <input type="text" name="nama_kos" class="form-control" placeholder="Contoh: Kos Mawar Putih"
                   value="<?= $user['nama_kos'] ?>">
        </div>

        <div class="mb-3">
            <label>Alamat Kos</label>
            <textarea name="alamat_kos" class="form-control" rows="3"><?= $user['alamat_kos'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Deskripsi Kos</label>
            <textarea name="deskripsi_kos" class="form-control" rows="4"
                      placeholder="Isi deskripsi kos (fasilitas, peraturan, dll)"><?= $user['deskripsi_kos'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Foto Profil</label><br>
            <?php if (!empty($user['foto_profil'])): ?>
                <img src="profil/<?= $user['foto_profil'] ?>" width="100" class="rounded mb-2">
            <?php endif; ?>
            <input type="file" name="foto_profil" class="form-control">
        </div>

        <button class="btn btn-primary w-100">Simpan Perubahan</button>

    </form>
</div>

</body>
</html>
