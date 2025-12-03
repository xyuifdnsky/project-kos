<?php
require_once __DIR__ . '/../../../../config/database.php';
$conn = DatabaseConfig::getConnection();
session_start();

if (!isset($_GET['id_kamar'])) {
    die("ID kamar tidak ditemukan.");
}

$id_kamar = $_GET['id_kamar'];

// Ambil data kamar
$kamar = $conn->query("
    SELECT * FROM kamar WHERE id_kamar = '$id_kamar'
")->fetch_assoc();


if (!$kamar) {
    die("Kamar tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesan Kamar</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4">

        <h3 class="mb-3">Pesan Kamar</h3>

        <p><b>Kamar:</b> <?= $kamar['nomor_kamar'] ?></p>
        <p><b>Tipe:</b> <?= ucfirst($kamar['tipe_kamar']) ?></p>
        <p><b>Harga per hari:</b> Rp<?= number_format($kamar['harga'], 0, ',', '.') ?></p>

        <form action="proses_pesan_kamar.php" method="POST">

            <input type="hidden" name="id_kamar" value="<?= $id_kamar ?>">

            <div class="mb-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tanggal_mulai" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" name="tanggal_selesai" required>
            </div>

            <button class="btn btn-primary">Konfirmasi Pesanan</button>
        </form>

    </div>

</div>

</body>
</html>
