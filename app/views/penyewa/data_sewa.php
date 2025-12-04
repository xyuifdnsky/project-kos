<?php
require_once __DIR__ . '/../../../config/database.php';
$conn = DatabaseConfig::getConnection();
session_start();

$id_pemilik = $_GET['id_pemilik'] ?? $_SESSION['user']['id_pemilik'];

// Cek hak akses admin / pemilik kos
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'pemilik'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data pesanan penyewa
// Ambil data pesanan penyewa yang hanya milik pemilik terkait
$query = "
    SELECT 
        sewa.id_sewa,
        users.nama_user,
        users.username,
        users.telepon,
        kamar.nomor_kamar,
        kamar.tipe_kamar,
        kamar.jenis_kamar
        kamar.harga,
        sewa.status,
        sewa.tanggal_pesan,
        sewa.tanggal_selesai
    FROM sewa
    JOIN users ON sewa.id_user = users.id_user
    JOIN kamar ON sewa.id_kamar = kamar.id_kamar
    WHERE kamar.id_pemilik = '$id_pemilik'
    ORDER BY sewa.tanggal_pesan DESC
";


$result = $conn->query($query);

// Proses aksi (Setujui / Tolak / Hapus)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $aksi = $_GET['aksi'];
    $id = $_GET['id'];

    if ($aksi == "setujui") {
        $conn->query("UPDATE sewa SET status='Disetujui' WHERE id_sewa='$id'");
    } 
    elseif ($aksi == "tolak") {
        $conn->query("UPDATE sewa SET status='Ditolak' WHERE id_sewa='$id'");
    }
    elseif ($aksi == "hapus") {
        $conn->query("DELETE FROM sewa WHERE id_sewa='$id'");
    }

header("Location: data_sewa.php?id_pemilik=$id_pemilik");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penyewa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary px-3">
    <span class="navbar-brand text-white">
        <i class="bi bi-people-fill"></i> Data Penyewa
    </span>

    <a href="../../../logout.php" class="btn btn-light btn-sm">Logout</a>
</nav>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-card-list"></i> Daftar Pesanan Penyewa
        </div>

        <div class="card-body">

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered text-center">
                    <thead class="table-warning">
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>No HP</th>
                            <th>Nomor Kamar</th>
                             <th>Tipe Kamar</th>
                            <th>Jenis Kamar</th>
                              <th>Harga</th>
                                 <th>Status</th>
                            <th>Tanggal Pesan</th>
                            <th>Tanggal Pesan</th>
                              <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['nama_user']; ?></td>
                                <td><?= $row['username']; ?></td>
                                <td><?= $row['telepon']; ?></td>
                                <td><?= $row['nomor_kamar']; ?> (<?= $row['tipe_kamar']; ?>)</td>
                                <td><?= ucfirst($row['jenis_kamar']); ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $row['status'] == 'Pending' ? 'bg-warning' : 
                                        ($row['status'] == 'Disetujui' ? 'bg-success' : 'bg-danger'); ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal_pesan'])); ?></td>
                                     <td><?= date('d-m-Y', strtotime($row['tanggal_pesan'])); ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal_selesai'])); ?></td>
                                <td>

                                    <?php if ($row['status'] == "Pending"): ?>
                                        <a href="?aksi=setujui&id=<?= $row['id_sewa']; ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i> Setujui
                                        </a>

                                        <a href="?aksi=tolak&id=<?= $row['id_sewa']; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-x-circle"></i> Tolak
                                        </a>
                                    <?php endif; ?>

                                    <a href="?aksi=hapus&id=<?= $row['id_sewa']; ?>" 
                                        onclick="return confirm('Yakin ingin hapus?')"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <div class="alert alert-info text-center">Belum ada penyewa yang melakukan pemesanan.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
