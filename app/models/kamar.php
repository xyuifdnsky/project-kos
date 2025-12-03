<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/../../config/database.php';

$conn = DatabaseConfig::getConnection();
if (!$conn) {
    die("Koneksi database gagal!");
}

session_start();

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['level'] ?? 'unknown';

// insert kamar

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {

    $nomor = $_POST['nomor_kamar'];
    $tipe = $_POST['tipe_kamar'];
    $harga = $_POST['harga'];
    $fasilitas = $_POST['fasilitas'];
    $id_pemilik = $id_user;
    $status = "tersedia"; // <-- WAJIB

    $stmt = $conn->prepare("INSERT INTO kamar 
        (id_pemilik, nomor_kamar, tipe_kamar, harga, status, fasilitas, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");

    $stmt->bind_param("isssss", $id_pemilik, $nomor, $tipe, $harga, $status, $fasilitas);

    if ($stmt->execute()) {
        $msg = "Kamar berhasil ditambahkan!";
    } else {
        $msg = "Gagal: " . $stmt->error;
    }
}

// ================== SELECT ==================
if ($role == 'admin') {
    $result = $conn->query("SELECT kamar.*, users.nama_lengkap as pemilik 
                            FROM kamar 
                            LEFT JOIN users ON kamar.id_pemilik = users.id_user 
                            ORDER BY id_kamar DESC");
} elseif ($role == 'pemilik') {
    $stmt = $conn->prepare("SELECT kamar.*, users.nama_lengkap as pemilik 
                            FROM kamar 
                            LEFT JOIN users ON kamar.id_pemilik = users.id_user
                            WHERE kamar.id_pemilik = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT kamar.*, users.nama_lengkap as pemilik 
                            FROM kamar 
                            LEFT JOIN users ON kamar.id_pemilik = users.id_user 
                            WHERE kamar.status = 'tersedia'");
}
?>

<div class="container mt-4">

<?php if(isset($msg)): ?>
    <div class="alert alert-info alert-dismissible fade show">
        <?= $msg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<h3>Data Kamar</h3>
<hr>

<?php if ($role == 'admin' || $role == 'pemilik'): ?>
<form method="post" action="" class="mb-4 p-3 border rounded">

    <div class="row">
        <div class="col-md-4">
            <label>Nomor Kamar</label>
            <input type="text" name="nomor_kamar" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label>Tipe</label>
            <select class="form-control" name="tipe_kamar" required>
                <option value="single">Single</option>
                <option value="double">Double</option>
                <option value="family">Family</option>
            </select>
        </div>

        <div class="col-md-4">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
    </div>

    <label class="mt-2">Fasilitas</label>
    <textarea class="form-control" name="fasilitas"></textarea>

    <button name="simpan" class="btn btn-primary mt-3">Simpan</button>
</form>
<?php endif; ?>


<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nomor Kamar</th>
            <th>Tipe</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Pemilik</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nomor_kamar'] ?></td>
            <td><?= ucfirst($row['tipe_kamar']) ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td>
                <span class="badge <?= $row['status']=='tersedia'?'bg-success':'bg-danger' ?>">
                    <?= ucfirst($row['status']) ?>
                </span>
            </td>
            <td><?= $row['pemilik'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>
