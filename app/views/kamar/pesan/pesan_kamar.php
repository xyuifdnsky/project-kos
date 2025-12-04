<?php
require_once __DIR__ . '/../../../../config/database.php';
$conn = DatabaseConfig::getConnection();
session_start();

// Pastikan ID ada
if (!isset($_GET['id_kamar'])) {
    die("ID kamar tidak ditemukan di URL.");
}

$id_kamar = $_GET['id_kamar'];


// Query mengambil detail kamar berdasarkan ID
$q = $conn->query("
    SELECT 
        kamar.id_kamar AS id,
        kamar.nomor_kamar,
        kamar.tipe_kamar,
        kamar.harga,
        users.nama_user AS pemilik
    FROM kamar
    LEFT JOIN users ON kamar.id_pemilik = users.id_user
    WHERE kamar.id_kamar = '$id_kamar'
");

$kamar = $q->fetch_assoc();

if (!$kamar) {
    die("Data kamar tidak ditemukan di database.");
}

// Data untuk Tampilan dan Perhitungan JS
$harga_per_hari = number_format($kamar['harga'], 0, ',', '.');
$harga_raw = $kamar['harga']; // Harga mentah untuk perhitungan JS
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kamar | <?= $kamar['nomor_kamar'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f9;
        }
        .card {
            border-radius: 1rem;
            border: none;
        }
        .header-section {
            background-color: #305FCA;
            color: white;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
            display: flex;
            align-items: center;
        }
        .summary-card {
            border-left: 5px solid #305FCA;
            padding: 1rem;
            background-color: #fcfcfc;
            border-radius: 0.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #343a40;
        }
        .btn-primary {
            background-color: #305FCA;
            border-color: #305FCA;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #21469A;
            border-color: #21469A;
        }
    </style>
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <div class="card shadow">
                
                <div class="header-section">
                    <i class="bi bi-calendar-check-fill me-3" style="font-size: 1.5rem;"></i>
                    <h4 class="mb-0 text-white">Konfirmasi Pemesanan Kamar</h4>
                </div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tglMulai = document.getElementById('tanggal_mulai');
    const tglSelesai = document.getElementById('tanggal_selesai');
    const hargaRaw = parseFloat(document.getElementById('hargaRaw').value);
    const durasiSewaSpan = document.getElementById('durasiSewa');
    const totalBiayaSpan = document.getElementById('totalBiaya');

    // Fungsi untuk format angka ke Rupiah
    const formatRupiah = (angka) => {
        return 'Rp' + angka.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    // Fungsi utama untuk menghitung biaya
    const hitungBiaya = () => {
        const mulaiDate = new Date(tglMulai.value);
        const selesaiDate = new Date(tglSelesai.value);

        if (!tglMulai.value || !tglSelesai.value || mulaiDate >= selesaiDate) {
            // Tanggal tidak valid atau mulai >= selesai
            durasiSewaSpan.textContent = '0 Hari';
            totalBiayaSpan.textContent = 'Rp0';
            return;
        }

        // Hitung selisih hari (dalam milidetik)
        const selisihMili = selesaiDate.getTime() - mulaiDate.getTime();
        // Konversi milidetik ke hari (1000ms * 60s * 60m * 24h)
        const selisihHari = Math.ceil(selisihMili / (1000 * 60 * 60 * 24)); 
        
        const totalBiaya = selisihHari * hargaRaw;

        durasiSewaSpan.textContent = `${selisihHari} Hari`;
        totalBiayaSpan.textContent = formatRupiah(totalBiaya);
    };

    // Atur tanggal minimal (tidak boleh hari kemarin)
    const today = new Date().toISOString().split('T')[0];
    tglMulai.min = today;
    tglSelesai.min = today;

    // Tambahkan event listener untuk memanggil fungsi hitung saat tanggal berubah
    tglMulai.addEventListener('change', hitungBiaya);
    tglSelesai.addEventListener('change', hitungBiaya);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>