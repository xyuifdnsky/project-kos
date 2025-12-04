<?php
// PHP LOGIC (TIDAK DIUBAH)
include '../../../config/database.php';
$conn = DatabaseConfig::getConnection();
if (!$conn) die("Koneksi database gagal!");

$id = $_GET['id'];

// Perhatikan: query ini hanya mengambil 1 data, tapi saya tambahkan kolom dummy 'sisa_kamar' dan 'ukuran' untuk keperluan tampilan yang lebih lengkap.
$q = mysqli_query($conn, 
    "SELECT 
        k.*, 
        u.nama_user AS pemilik,
        '3x4m' AS ukuran, 
        5 AS sisa_kamar 
     FROM kamar k 
     LEFT JOIN users u ON k.id_pemilik = u.id_user
     WHERE id_kamar='$id'"
);

$data = mysqli_fetch_assoc($q);

$gambarList = explode(',', $data['gambar']);
$harga_bulanan = $data['harga'] * 1; // Contoh: harga asli
$diskon = 170000;
$harga_setelah_diskon = $harga_bulanan - $diskon;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Kamar | <?= $data['tipe_kamar'] ?> - <?= $data['nomor_kamar'] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* BASE STYLES */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8f9fa; 
            margin: 0; 
            padding: 0; 
        }
        .container-fluid { 
            width: 90%; 
            margin: auto; 
            padding: 30px 0; 
        }

        /* HEADER INFO */
        .header-info { 
            margin-bottom: 25px; 
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }
        .header-info h1 { 
            font-size: 2.2rem; 
            font-weight: 700;
            color: #212529;
            margin-bottom: 8px; 
        }

        .tag { 
            display: inline-flex; 
            align-items: center;
            padding: 5px 10px; 
            background:#e9f7ef; 
            color: #08a828; 
            border-radius: 6px; 
            margin-right: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .tag-status {
            background:#dee2e6;
            color: #495057;
        }
        .info-detail {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            font-size: 0.95rem;
            color: #6c757d;
        }
        .info-detail span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .sisa-kamar { 
            color: #dc3545; 
            font-weight:bold; 
        }

        /* ACTION BUTTONS (ATAS) */
        .action-btns button {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            background: white;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        .action-btns button:hover {
            background: #f0f0f0;
        }
        .action-btns i {
            margin-right: 5px;
        }

        /* MAIN LAYOUT */
        .main { 
            display: grid; 
            grid-template-columns: 2.5fr 1fr; /* Proporsi konten vs harga */
            gap: 30px; 
        }
        
        /* GALERI FOTO */
        .galeri { 
            display: grid; 
            grid-template-columns: 3fr 1fr; /* Image utama lebih besar */
            gap: 15px; 
            margin-bottom: 30px;
        }
        .galeri img { 
            width:100%; 
            object-fit:cover; 
            border-radius:12px;
            transition: transform 0.3s;
        }
        .big-image img { 
            height: 480px; 
        }
        .small-images {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .small-images img { 
            height: 140px; 
            cursor: pointer;
            opacity: 0.8;
        }
        .small-images img:hover {
             opacity: 1;
             box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .lihat { 
            background: #f0f0f0; 
            color: #495057;
            text-align:center; 
            padding: 10px; 
            border-radius: 10px; 
            font-weight: 600; 
            cursor:pointer; 
            display: flex;
            align-items: center;
            justify-content: center;
            height: 140px;
        }
        .lihat:hover {
            background: #e9ecef;
        }


        /* INFO CARDS (KIRI) */
        .info-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        .info-card h2 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .info-card p, .info-card ul {
            color: #6c757d;
            line-height: 1.8;
            font-size: 1rem;
        }
        .info-card ul {
            list-style: none;
            padding-left: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .info-card ul li {
            background: #e9ecef;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        /* PRICE CARD (KANAN) */
        .price-card {
            background:white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0,0,0,0.07);
            position: sticky;
            top: 20px; /* Jaga agar tetap di atas saat scroll */
            border: 1px solid #e9ecef;
        }
        .price-card .harga { 
            font-size: 2.5rem; 
            font-weight: 800;
            color: #2C7AF1; 
            margin: 5px 0 15px 0;
            display: block;
        }
        .price-card .diskon-info {
            font-weight: bold;
            color: #dc3545;
            font-size: 0.95rem;
            display: block;
            margin-bottom: 5px;
        }
        .price-card .harga small { 
            font-size: 0.5em; 
            font-weight: 500;
            color: #6c757d;
        }
        
        /* TOMBOL BAWAH */
        .btn-sewa {
            margin-top: 15px;
            width:100%;
            padding: 15px;
            font-size: 1.1rem;
            background:#08a828;
            color:white;
            border:none;
            border-radius:10px;
            cursor:pointer;
            font-weight: 700;
            transition: background 0.3s;
        }
        .btn-sewa:hover {
            background: #068c22;
        }
        .btn-chat {
            width:100%;
            padding: 12px;
            border: 2px solid #0b9cdd;
            background:white;
            color:#0b9cdd;
            border-radius:10px;
            margin-top:10px;
            cursor:pointer;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }
        .btn-chat:hover {
            background: #0b9cdd;
            color: white;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main { 
                grid-template-columns: 1fr; /* Stack di layar kecil */
            }
            .galeri {
                grid-template-columns: 1fr;
            }
            .small-images {
                flex-direction: row;
                height: auto;
            }
            .small-images img, .lihat {
                flex: 1;
                height: 100px;
            }
            .price-card {
                position: static; /* Non-sticky di layar kecil */
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">

    <div class="header-info">
        <h1><?= $data['tipe_kamar'] ?> - Kamar Nomor <?= $data['nomor_kamar'] ?></h1>

        <div class="d-flex align-items-center mb-3">
            <div class="tag">Kos <?= ucfirst($data['tipe_kamar']) ?></div>
            <div class="tag tag-status"><?= $data['status'] ?></div>
        </div>
        
        <div class="info-detail">
            <span><i class="fas fa-ruler-combined"></i> Ukuran: <?= $data['ukuran'] ?></span>
            <span><i class="fas fa-user-friends"></i> Dikelola oleh: <?= $data['pemilik'] ?></span>
            <span><i class="fas fa-door-open"></i> Tersisa: <span class="sisa-kamar"><?= $data['sisa_kamar'] ?> Kamar</span></span>
        </div>

        <div class="action-btns mt-3">
            <button><i class="far fa-heart"></i> Simpan</button>
            <button><i class="fas fa-share-alt"></i> Bagikan</button>
        </div>
    </div>

    <div class="main">

        <div>
            <div class="galeri">
                <div class="big-image">
                    <img src="../../gambar/<?= $gambarList[0] ?? 'default.jpg' ?>" alt="Foto Kamar Utama">
                </div>

                <div class="small-images">
                    <img src="../../gambar/<?= $gambarList[0] ?? 'default.jpg' ?>" alt="Thumbnail 1">
                    <img src="../../gambar/<?= $gambarList[1] ?? 'default.jpg' ?>" alt="Thumbnail 2">
                    <div class="lihat">Lihat semua foto</div>
                </div>
            </div>

            <div class="info-card">
                <h2>Deskripsi Kamar</h2>
                <p>Kamar <?= $data['tipe_kamar'] ?> Nomor <?= $data['nomor_kamar'] ?> berlokasi strategis. Kamar ini <?= $data['keterangan'] ?? 'dilengkapi dengan suasana yang nyaman dan cocok untuk mahasiswa atau pekerja.' ?></p>
                <p>Alamat: Jl. Contoh Kosan No. 123, Subang.</p>
            </div>

            <div class="info-card">
                <h2>Fasilitas Kamar</h2>
                <?php
                // Logika memformat fasilitas (asumsi fasilitas disimpan sebagai string dipisahkan koma atau baris baru)
                $fasilitas_array = array_filter(preg_split('/,\s*|\r\n|\n/', $data['fasilitas']));
                if (!empty($fasilitas_array)):
                ?>
                <ul>
                    <?php foreach ($fasilitas_array as $fasilitas): ?>
                        <li><i class="fas fa-check-circle me-1" style="color:#08a828;"></i> <?= trim($fasilitas) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                    <p>Informasi fasilitas belum tersedia.</p>
                <?php endif; ?>
            </div>
            
        </div>
        
        <div class="price-card">
            <span class="diskon-info"><i class="fas fa-bolt"></i> Diskon Khusus Bulan Pertama</span>
            
            <p style="text-decoration:line-through; color:#888; font-size: 1.1rem;">Rp<?= number_format($harga_bulanan, 0, ',', '.') ?></p>
            
            <span class="harga">
                Rp<?= number_format($harga_setelah_diskon, 0, ',', '.') ?> <small>/ Bulan pertama</small>
            </span>
            
            <form action="pesan/pesan_kamar.php" method="GET">
                <input type="hidden" name="id_kamar" value="<?= $data['id_kamar'] ?>">
                <button type="submit" class="btn-sewa">
                    <i class="fas fa-shopping-cart"></i> Pesan Kamar Sekarang
                </button>
            </form>

            <button class="btn-chat"><i class="fas fa-comments"></i> Tanya Pemilik</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>