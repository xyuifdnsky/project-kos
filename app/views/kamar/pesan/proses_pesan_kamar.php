<?php
require_once __DIR__ . '/../../../config/database.php';
$conn = DatabaseConfig::getConnection();
session_start();

if (!isset($_SESSION['user'])) {
    die("Anda harus login untuk memesan kamar.");
}

$id_user = $_SESSION['user']['id_user'];
$id_kamar = $_POST['id_kamar'];
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];

// Simpan ke tabel sewa
$sql = "
    INSERT INTO sewa (id_user, id_kamar, tanggal_mulai, tanggal_selesai, status)
    VALUES ('$id_user', '$id_kamar', '$tanggal_mulai', '$tanggal_selesai', 'aktif')
";

if ($conn->query($sql)) {
    // Update status kamar menjadi "tidak tersedia"
    $conn->query("UPDATE kamar SET status = 'tidak tersedia' WHERE id_kamar = '$id_kamar'");
    
    header("Location: sukses_pesan.php");
    exit;
} else {
    echo "Gagal memesan kamar: " . $conn->error;
}
