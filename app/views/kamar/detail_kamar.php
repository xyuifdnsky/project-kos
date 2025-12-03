<?php
include '../../../config/database.php';
$conn = DatabaseConfig::getConnection();
if (!$conn) die("Koneksi database gagal!");

$id = $_GET['id'];

$q = mysqli_query($conn, 
    "SELECT k.*, u.nama_user AS pemilik
     FROM kamar k 
     LEFT JOIN users u ON k.id_pemilik = u.id_user
     WHERE id_kamar='$id'"
);

$data = mysqli_fetch_assoc($q);

$gambarList = explode(',', $data['gambar']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Kamar</title>
    <style>
        body { font-family: Arial; background: #fafafa; margin:0; padding:0; }
        .container { width: 92%; margin: auto; padding: 20px; }

        /* Bagian Header */
        .header-info { margin-top: 20px; }
        .header-info h1 { font-size: 28px; margin-bottom: 8px; }

        .tag { display: inline-block; padding: 7px 12px; background:#f0f0f0; border-radius:8px; margin-right:8px; }

        .info-row { display:flex; align-items:center; gap:20px; margin:12px 0; color:#555; font-size:15px; }

        .sisa { color: red; font-weight:bold; }

        .action-btns { margin-top: 10px; }
        .action-btns button {
            padding: 10px 18px;
            border-radius: 8px;
            border:1px solid #ddd;
            background:white;
            cursor:pointer;
        }

        /* Layout utama */
        .main { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }

        /* Galeri */
        .galeri { display: grid; grid-template-columns: 2fr 1fr; gap:15px; }
        .big-image img { width:100%; height:420px; object-fit:cover; border-radius:12px; }
        .small-images img { width:100%; height:200px; object-fit:cover; border-radius:12px; }

        .lihat { background:white; text-align:center; padding:20px; border-radius:10px; border:1px solid #ccc; font-weight:bold; cursor:pointer; }

        /* Card harga kanan */
        .price-card {
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 0 25px rgba(0,0,0,0.07);
            position: sticky;
            top:20px;
        }
        .harga { font-size:30px; font-weight:bold; color:#2C7AF1; }
        .btn-sewa {
            margin-top:15px;
            width:100%;
            padding:13px;
            font-size:16px;
            background:#08a828;
            color:white;
            border:none;
            border-radius:10px;
            cursor:pointer;
        }
        .btn-chat {
            width:100%;
            padding:12px;
            border:1px solid #0b9cdd;
            background:white;
            color:#0b9cdd;
            border-radius:10px;
            margin-top:10px;
            cursor:pointer;
        }

    </style>
</head>
<body>

<div class="container">

    <!-- =======================
         HEADER INFO KOS
    =========================== -->
    <div class="header-info">
        <h1><?= $data['tipe_kamar'] ?> - Kamar Nomor <?= $data['nomor_kamar'] ?></h1>

        <div class="tag">Kos <?= ucfirst($data['tipe_kamar']) ?></div>
        <div class="tag"><?= $data['status'] ?></div>


        <div class="action-btns">
            <button>♡ Simpan</button>
            <button>↗ Bagikan</button>
        </div>
    </div>

    <!-- =======================
         CONTENT UTAMA
    =========================== -->
    <div class="main">

        <!-- Galeri Foto -->
        <div>
            <div class="galeri">
                <div class="big-image">
                    <img src="../../gambar/<?= $gambarList[0] ?>">
                </div>

                <div class="small-images">
                    <img src="gambar/<?= $gambarList[0] ?>">
                    <img src="gambar/<?= $gambarList[0] ?>">
                    <div class="lihat">Lihat semua foto</div>
                </div>
            </div>

            <!-- Informasi Kos -->
            <div class="info-card">
                <h2>Kos dikelola oleh <?= $data['pemilik'] ?></h2>  
    </div>
            <div class="info-card">
                <h2>Fasilitas</h2>
                <p><?= nl2br($data['fasilitas']) ?></p>
            </div>
        </div>
        <!-- Card Harga -->
        <div class="price-card">
            <p style="color:red; font-weight:bold;">⚡ Diskon 170rb <span style="text-decoration:line-through; color:#888;">Rp3.425.000</span></p>
            <p class="harga">Rp<?= number_format($data['harga'],0,',','.') ?> <span style="font-size:15px;">/ Bulan pertama</span></p>
           <form action="pesan/pesan_kamar.php" method="GET">
    <input type="hidden" name="id_kamar" value="<?= $km['id'] ?>">
    <button class="btn btn-success w-100 mt-2">Pesan Kamar</button>
</form>

                <button class="btn-chat">💬 Tanya Pemilik</button>
                <button class="btn-sewa">Ajukan Sewa</button>
        </div>
    </div>
</div>

</body>
</html>
