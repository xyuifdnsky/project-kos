<?php
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

    $stmt = $conn->prepare("
        SELECT kamar.*, users.nama_user AS pemilik 
        FROM kamar 
        LEFT JOIN users ON kamar.id_pemilik = users.id_user
        WHERE kamar.id_pemilik = ?
    ");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    $result = $conn->query("
        SELECT kamar.*, users.nama_user AS pemilik 
        FROM kamar 
        LEFT JOIN users ON kamar.id_pemilik = users.id_user
        WHERE kamar.status = 'tersedia'
    ");
}

require_once '../templates/header.php';
?>


<!-- ICONS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: white;
    border-right: 2px solid #e4e4e4;
    padding: 20px 0;
}
.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 22px;
    color: #333;
    text-decoration: none;
    border-left: 5px solid transparent;
}
.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
    color: white !important;
    border-left: 5px solid #003c91;
}

.content {
    margin-left: 0px;
    padding: 25px;
}

.topbar {
    display: flex;
    justify-content: flex-end;
    padding: 8px;
    font-weight: bold;
}
</style>


<!-- ================= SIDEBAR ================= -->
<div class="sidebar">
    <a href="../auth/dashboard/pemilik/pemilik.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="../penyewa/penyewa.php"><i class="bi bi-people"></i> Data Penghuni</a>
    <a href="../kamar/create.php" class="active"><i class="bi bi-door-open"></i> Tambah Kamar</a>
    <a href="../keluhan/lihat_keluhan.php"><i class="bi bi-chat-dots"></i> Keluhan</a>
    <a href="../pembayaran/transaksi.php"><i class="bi bi-credit-card"></i> Transaksi</a>
    <a href="../logout.php" style="color:red;"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>


<!-- ================= CONTENT ================= -->
<div class="content">

    <div class="topbar">
        <i class="bi bi-person-circle"></i>&nbsp; <?= $_SESSION['user']['username'] ?> | <?= ucfirst($role) ?>
    </div>

    <h2 class="mb-4"><i class="fa-solid fa-bed"></i> Tambah Kamar</h2>

    <?php if(isset($msg)): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>


    <!-- FORM TAMBAH KAMAR -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-hashtag"></i> Nomor Kamar</label>
                            <input type="text" class="form-control" name="nomor_kamar" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-house-user"></i> Tipe Kamar</label>
                            <select name="tipe_kamar" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="single">Single</option>
                                <option value="double">Double</option>
                                <option value="family">Family</option>
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

                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-money-bill-wave"></i> Harga</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-circle-info"></i> Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="tersedia">Tersedia</option>
                                <option value="terisi">Terisi</option>
                                <option value="dipesan">Dipesan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-image"></i> gambar Kamar</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fa-solid fa-list"></i> Fasilitas</label>
                            <textarea class="form-control" name="fasilitas"></textarea>
                        </div>

                        <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>

                    </form>

                </div>
            </div>
        </div>

        <!-- TIPS -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fa-solid fa-lightbulb"></i> Tips
                </div>
                <div class="card-body small text-muted">
                    Gunakan format A01, B12 untuk nomor kamar.<br><br>
                    Tulis fasilitas lengkap seperti AC, Wifi, Lemari.<br><br>
                    gambar kamar yang jelas lebih menarik penyewa.
                </div>
            </div>
        </div>
    </div>


    <!-- ================== TABEL DATA KAMAR ================== -->
    <h3 class="mt-4">Data Kamar</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor</th>
                <th>Tipe</th>
                 <th>Jenis</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Pemilik</th>
                <th>gambar</th>
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

                <td>
                    <?php if($row['gambar']): ?>
                    <img src="gambar/<?= $row['gambar'] ?>" width="60">



                    <?php else: ?>
                        <span class="text-muted">Tidak ada gambar</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


</div>

