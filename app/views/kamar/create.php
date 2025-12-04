<?php
// PHP LOGIC (TIDAK DIUBAH)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once __DIR__ . '/../../../config/database.php';

$conn = DatabaseConfig::getConnection();
if (!$conn) die("Koneksi database gagal!");

session_start();

// CEK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];

// ================== INSERT KAMAR ==================
$msg = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nomor     = $_POST['nomor_kamar'];
    $tipe      = $_POST['tipe_kamar'];
    $jenis      = $_POST['jenis_kamar'];
    $harga     = $_POST['harga'];
    $status    = $_POST['status'];
    $fasilitas = $_POST['fasilitas'];
    $id_pemilik = $id_user;

    // ---- Upload gambar ----
    $gambar_name = null;

    if (!empty($_FILES['gambar']['name'])) {

       $dir = __DIR__ . "/gambar/";

        if (!file_exists($dir)) mkdir($dir, 0777, true);

        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar_name = "kamar_" . time() . "." . $ext;

        move_uploaded_file($_FILES['gambar']['tmp_name'], $dir . $gambar_name);
    }


$stmt = $conn->prepare("
    INSERT INTO kamar 
    (id_pemilik, nomor_kamar, tipe_kamar, jenis_kamar, harga, status, fasilitas, gambar, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$stmt->bind_param("isssssss",
    $id_pemilik,
    $nomor,
    $tipe,
    $jenis,
    $harga,
    $status,
    $fasilitas,
    $gambar_name
);

  if ($stmt->execute()) {
    header("Location: create.php?success=1");
    exit;
}

    else $msg = "Gagal menambah kamar!";
}

// ================== SELECT KAMAR ==================
if ($role == 'admin') {

    $result = $conn->query("
        SELECT kamar.*, users.nama_user AS pemilik
        FROM kamar 
        LEFT JOIN users ON kamar.id_pemilik = users.id_user
        ORDER BY id_kamar DESC
    ");

} elseif ($role == 'pemilik') {

    $result = $conn->query("
        SELECT kamar.*, users.nama_user AS pemilik
        FROM kamar 
        LEFT JOIN users ON kamar.id_pemilik = users.id_user
        ORDER BY id_kamar DESC
    ");


} else {

    $result = $conn->query("
        SELECT kamar.*, users.nama_user AS pemilik 
        FROM kamar 
        LEFT JOIN users ON kamar.id_pemilik = users.id_user
        WHERE kamar.status = 'tersedia'
    ");
}

// require_once '../templates/header.php'; // Baris ini dihapus agar tidak mengganggu tata letak
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kamar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">


<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #003c91;
        --bg-color: #f5f7fa;
        --sidebar-width: 260px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-color);
        margin: 0;
    }

    /* SIDEBAR */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: #ffffff;
        border-right: 1px solid #e0e4e8;
        padding-top: 80px; /* Jarak dari topbar */
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    }
    .sidebar a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 14px 25px;
        color: #333;
        text-decoration: none;
        border-left: 5px solid transparent;
        transition: all 0.3s;
        font-size: 0.95rem;
    }
    .sidebar a:hover {
        background: #e9ecef;
        color: var(--primary-color);
        border-left: 5px solid var(--primary-color);
    }
    .sidebar a.active {
        background: var(--primary-color);
        color: white !important;
        border-left: 5px solid var(--secondary-color);
        font-weight: 600;
    }
    .sidebar a i {
        font-size: 1.2rem;
    }

    /* TOP BAR */
    .top-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 70px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        padding: 0 30px;
        padding-left: calc(var(--sidebar-width) + 30px); /* Geser ke kanan jika sidebar aktif */
        z-index: 50;
    }
    .top-nav .user-info {
        font-weight: 600;
        color: var(--secondary-color);
    }


    /* CONTENT */
    .content {
        margin-left: var(--sidebar-width);
        padding: 30px;
        padding-top: 100px; /* Jarak dari topbar */
    }

    /* Form Input Style */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        transition: border-color 0.3s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Table */
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #f8f9fa;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
    }
    .table-data-container {
        overflow-x: auto;
    }
</style>
</head>

<body>

<div class="top-nav">
    <div class="fw-bold fs-5 text-dark">Kelola Data Kamar</div>
    
    <div class="ms-auto user-info">
        <i class="bi bi-person-circle"></i>&nbsp; <?= $_SESSION['user']['username'] ?> | <?= ucfirst($role) ?>
    </div>
</div>


<div class="sidebar">
    <a href="../auth/dashboard/pemilik/pemilik.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="../penyewa/penyewa.php"><i class="bi bi-people"></i> Data Penghuni</a>
    <a href="create.php" class="active"><i class="bi bi-door-open"></i> Kelola Kamar</a>
    <a href="../keluhan/lihat_keluhan.php"><i class="bi bi-chat-dots"></i> Keluhan</a>
    <a href="../pembayaran/transaksi.php"><i class="bi bi-credit-card"></i> Transaksi</a>
    <?php if ($role == 'admin'): ?>
        <a href="admin/tambah_admin.php"><i class="bi bi-person-gear"></i> Kelola Admin</a>
    <?php endif; ?>
    <a href="../logout.php" class="text-danger" style="color:red !important;"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>


<div class="content">

    <h2 class="mb-5 fw-bold text-dark"><i class="fa-solid fa-bed me-2 text-primary"></i> Tambah & Kelola Kamar</h2>

    <?php if(isset($msg)): ?>
        <div class="alert alert-<?= (isset($_GET['success']) && $_GET['success'] == 1) ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="row g-4 mb-5">
        <div class="col-md-7 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold fs-5">
                    <i class="bi bi-house-door-fill me-2"></i> Formulir Data Kamar
                </div>
                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-hashtag me-1"></i> Nomor Kamar</label>
                                <input type="text" class="form-control" name="nomor_kamar" placeholder="Contoh: A01" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-money-bill-wave me-1"></i> Harga (per bulan)</label>
                                <input type="number" class="form-control" name="harga" placeholder="Masukkan harga kamar" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-layer-group me-1"></i> Tipe Kamar</label>
                                <select name="tipe_kamar" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Tipe --</option>
                                    <option value="single">Single (1 Orang)</option>
                                    <option value="double">Double (2 Orang)</option>
                                    <option value="family">Family (3+ Orang)</option>
                                </select>
                            </div>

                          <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-house-user"></i> Jenis Kos</label>
                           <select name="jenis_kamar" class="form-control" required> >
                                <option value="">-- Pilih --</option>
                                <option value="putra">Putra</option>
    <option value="putri">Putri</option>
    <option value="campur">Campur</option>
</select>
                        </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-circle-info me-1"></i> Status Kamar</label>
                                <select name="status" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Status --</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="terisi">Terisi</option>
                                    <option value="dipesan">Dipesan</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-list-check me-1"></i> Fasilitas</label>
                                <textarea class="form-control" name="fasilitas" rows="3" placeholder="Contoh: AC, Kamar Mandi Dalam, Kasur Queen Size, Meja Belajar. Pisahkan dengan koma atau baris baru."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="fa-solid fa-image me-1"></i> Foto Kamar</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <button class="btn btn-primary mt-4 fw-bold"><i class="fa-solid fa-floppy-disk me-2"></i> Simpan Data Kamar</button>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="fa-solid fa-lightbulb me-2"></i> Tips Pengisian
                </div>
                <div class="card-body small text-muted">
                    <p>
                        Nomor Kamar: Gunakan format yang konsisten (misalnya, A01, B12) agar mudah diatur dan dicari penghuni.
                    </p>
                    <hr>
                    <p>
                        Fasilitas: Tulis fasilitas secara lengkap dan jelas, pisahkan dengan koma atau baris baru (misalnya: AC, Wifi Cepat, Lemari Pakaian 2 Pintu).
                    </p>
                    <hr>
                    <p>
                        Foto: Unggah foto kamar yang jelas dan menarik (minimal 3 foto) untuk meningkatkan minat penyewa.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <h3 class="mt-5 mb-3 fw-bold"><i class="bi bi-list-columns-reverse me-2 text-primary"></i> Daftar Kamar yang Sudah Terdaftar</h3>
    
    <div class="card shadow-sm border-0">
        <div class="card-body table-data-container">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe / Jenis</th>
                        <th>Harga</th>
                        <th class="text-center">Status</th>
                        <th>Pemilik</th>
                        <th class="text-center">Foto</th>
                    </tr>
                </thead>

        <tbody>
            <?php $no=1; while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= ucfirst($row['tipe_kamar']) ?></td>
             <td><?= ucfirst($row['jenis_kamar'] ?? '-') ?></td>

                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>

                <td>
                    <span class="badge <?= $row['status']=='tersedia'?'bg-success':'bg-danger' ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>

                        <td><?= $row['pemilik'] ?></td>

                        <td class="text-center">
                            <?php if($row['gambar']): ?>
                            <img src="gambar/<?= $row['gambar'] ?>" alt="Foto Kamar <?= $row['nomor_kamar'] ?>" 
                                 width="80" height="60" style="object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <span class="text-muted small">Tidak ada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if ($result->num_rows == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted fst-italic">Belum ada data kamar yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Memberikan fokus pada input pertama saat halaman dimuat
    const firstInput = document.querySelector('input[name="nomor_kamar"]');
    if (firstInput) {
        firstInput.focus();
    }
});
</script>

</body>
</html>