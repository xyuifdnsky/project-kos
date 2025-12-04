<?php
// PHP digunakan di sini hanya sebagai file container. 
// Anda bisa menambahkan logika PHP lain di sini jika diperlukan 
// (misalnya: mengambil nomor WhatsApp dari database atau konfigurasi).

// Tentukan Nomor WhatsApp Tujuan (GANTI DENGAN NOMOR ANDA)
$whatsapp_number = '6283875127164'; 
// Pesan default yang akan terisi otomatis di chat WhatsApp
$default_message = "Halo SiLoKos, saya perlu bantuan terkait layanan Anda. Mohon dibantu.";

// Buat link WhatsApp
$whatsapp_link = "https://wa.me/" . $whatsapp_number . "?text=" . urlencode($default_message);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan - SiLoKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .help-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px; /* Padding lebih besar */
            max-width: 450px;
            width: 100%;
            text-align: center;
        }
        .help-icon {
            font-size: 3.5rem; /* Ikon lebih besar */
            color: #305FCA;
            margin-bottom: 15px;
        }
        .title {
            font-weight: 800;
            font-size: 1.8rem;
            color: #1A202C;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 1rem;
            line-height: 1.5;
        }
        .btn-whatsapp {
            background-color: #25D366; /* Warna khas WhatsApp */
            color: white;
            font-weight: 700;
            padding: 18px; /* Tombol lebih besar */
            border-radius: 12px;
            border: none;
            text-decoration: none;
            display: block;
            font-size: 1.1rem;
            transition: background-color 0.3s, transform 0.1s;
        }
        .btn-whatsapp:hover {
            background-color: #1FAF59;
            color: white;
        }
        .btn-whatsapp:active {
            transform: scale(0.98); /* Efek interaktif saat diklik */
        }
        .btn-whatsapp i {
            margin-right: 10px;
        }
        .note {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #999;
        }
    </style>
</head>
<body>

<div class="help-card">
    <i class="bi bi-headset help-icon"></i>
    <h2 class="title">Butuh Bantuan Cepat?</h2>
    <p class="subtitle">
        Tim dukungan kami siap membantu Anda. Klik tombol di bawah ini untuk memulai *chat* langsung melalui WhatsApp.
    </p>

    <a href="<?= $whatsapp_link ?>" 
       class="btn-whatsapp" 
       target="_blank" 
       rel="noopener noreferrer" 
       id="whatsapp-btn">
        <i class="bi bi-whatsapp"></i> Chat via WhatsApp
    </a>
    
    <p class="mt-3 text-muted" style="font-size: 0.9rem;">
        Nomor Layanan: <?= $whatsapp_number ?>
    </p>

    <p class="note">
        Kami akan merespon secepatnya. Mohon jelaskan masalah Anda secara rinci untuk respon yang lebih cepat.
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const whatsappBtn = document.getElementById('whatsapp-btn');
    
    // Sedikit interaktivitas JS: Menambahkan kelas saat diklik
    whatsappBtn.addEventListener('click', function() {
        this.classList.add('btn-clicked');
        setTimeout(() => {
            this.classList.remove('btn-clicked');
        }, 300);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>