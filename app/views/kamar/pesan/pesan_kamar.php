<?php
// PHP LOGIC (TIDAK DIUBAH)
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

                <div class="card-body p-4 p-md-5">

                    <h5 class="mb-4 text-primary"><i class="bi bi-house-door-fill me-2"></i> Ringkasan Kamar</h5>
                    <div class="summary-card mb-4">
                        <p class="mb-1">
                            Kamar: <span class="fw-bold text-dark"><?= $kamar['nomor_kamar'] ?> (Tipe <?= ucfirst($kamar['tipe_kamar']) ?>)</span>
                        </p>
                        <p class="mb-1">
                            Harga/Hari: <span class="fw-bold text-success">Rp<?= $harga_per_hari ?></span>
                        </p>
                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                            Dikelola oleh: <?= $kamar['pemilik'] ?>
                        </p>
                    </div>

                    <h5 class="mb-4 text-primary"><i class="bi bi-clock-fill me-2"></i> Detail Sewa</h5>
                    <form action="proses_pesan_kamar.php" method="POST">

                        <input type="hidden" name="id_kamar" value="<?= $id_kamar ?>">
                        <input type="hidden" id="hargaRaw" value="<?= $harga_raw ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai Sewa</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai Sewa</label>
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-calculator me-2"></i> Estimasi Biaya Sewa</h6>
                            <p class="mb-1">Durasi Sewa: <span id="durasiSewa" class="fw-bold">0 Hari</span></p>
                            <hr class="my-2">
                            <h5 class="mb-0">Total Biaya: <span id="totalBiaya" class="fw-bold text-primary">Rp0</span></h5>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill me-2"></i> Konfirmasi Pesanan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            
            <p class="text-center mt-3 text-muted" style="font-size: 0.9rem;">
                <a href="../detail_kamar.php?id=<?= $id_kamar ?>" class="text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Detail Kamar
                </a>
            </p>

        </div>
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