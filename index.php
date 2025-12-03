<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nama Kost | Pemesanan Online</title>
    <style>
        /* Gaya Dasar & Reset */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5; /* Warna latar belakang umum */
            padding-top: 80px;
        }
        
  
.header {
    background-color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;    
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    border-bottom: 1px solid #ddd;
}

/* Dorong menu ke kanan */
.nav-menu {
    display: flex;
    list-style: none;
    gap: 15px;
    margin: 0;
    padding: 0;
}

.nav-menu a {
    text-decoration: none;
    color: #000;
    font-weight: 600;
}

.nav-menu li {
    position: relative;
}

/* Dropdown hidden */
.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;

    background: white;
    padding: 10px 0;
    list-style: none;
    min-width: 150px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 5px;
    z-index: 999;
}

/* Show dropdown on hover */
.dropdown:hover .dropdown-content {
    display: block;
}

/* Style item dropdown */
.dropdown-content li a {
    padding: 10px 15px;
    display: block;
    text-decoration: none;
    color: black;
}

.dropdown-content li a:hover {
    background: #efefef;
}

        /* Konten Utama */
        .main-content {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 80px 5%;
            align-items: center;
            justify-content: center; /* Teks di tengah jika 1 kolom */
            min-height: 50vh; /* Mengurangi tinggi agar ada ruang untuk footer */
        }
        
       .text-area { width: 100%; max-width: 800px; text-align: center;} 
        
        .text-area h1 {
            color: #1a2a4b;
            font-size: 2.8em;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .text-area p {
            line-height: 1.8;
            margin-bottom: 40px;
            color: #666;
            font-size: 1.1em;
        }

        /* Tombol */
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            margin-right: 15px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        .btn-login { background-color: #1a2a4b; color: white; }
        .btn-login:hover { background-color: #0d1936; transform: translateY(-2px); }
        .btn-registrasi {
            background-color: transparent;
            color: #1a2a4b;
            border: 2px solid #1a2a4b;
        }
        .btn-registrasi:hover {
            background-color: #1a2a4b;
            color: white;
            transform: translateY(-2px);
        }

        /* Ilustrasi Rumah (SVG Placeholder) */
        .house-illustration {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Gaya Khusus untuk Ilustrasi Rumah (Digambar dengan SVG) */
        .house-svg {
            width: 80%;
            height: auto;
            max-width: 400px;
            stroke: #1a2a4b; /* Warna garis biru gelap */
            stroke-width: 2;
            fill: none; /* Bagian dalam kosong */
        }


.header .btn {
   text-decoration: none; /* Hilangkan underline */
    font-weight: bold;
    padding: 8px 18px;
    border-radius: 6px;
    margin-left: 10px;
    transition: 0.3s ease;
}

/* LOGIN → penuh warna */
#openRoleUser {
    background-color: #1a2a4b;
    color: white;
    border: none;
}

#openRoleUser:hover {
    background-color: #0d1936;
    transform: translateY(-2px);
}

/* REGISTRASI → border saja */
#openRole {
    background-color: transparent;
    color: #1a2a4b;
    border: 2px solid #1a2a4b;
}

#openRole:hover {
    background-color: #1a2a4b;
    color: white;
    transform: translateY(-2px);
}


.role-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(6px);
    display: none; 
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Kotak Popup */
.role-popup {
    background: white;
    border-radius: 16px;
    padding: 30px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: fadeIn 0.3s ease;
    text-align: center;
}

@keyframes fadeIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Tombol */
.role-btn {
    width: 100%;
    max-width: 350px;         /* tombol tidak selebar modal */
    padding: 15px;
    margin: 0 auto 15px auto; /* benar-benar ke tengah */

    display: flex;
    flex-direction: column;   /* teks utama + teks kecil vertikal */
    align-items: center;
    justify-content: center;

    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
    color: white;
    transition: transform 0.2s ease;
}

.role-btn:hover {
    transform: translateY(-3px);
}

.role-icon {
    font-size: 1.5rem;
    margin-right: 15px;
    width: 30px;
    text-align: center;
}

.role-text small {
    display: block;
    margin-top: 2px;
    font-weight: normal;
    opacity: 0.8;
}


/* Warna tombol */
.btn-penyewa { background-color: #3B82F6; }
.btn-pemilik { background-color: #10B981; }

/* logo */
.logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo-img {
    height: 40px;
    width: auto;
}

.logo-text {
    font-size: 1.5em;
    font-weight: bold;
}

  /* Responsif */
        @media (max-width: 900px) {
            .main-content { padding: 50px 5%; min-height: unset; }
            .text-area { width: 100%; text-align: center;}
            .text-area h1 { font-size: 2.2em; }
            .nav-menu { gap: 10px; }
            .nav-menu a { font-size: 12px; }
            
            /* Lokasi Footer Responsif */
            .location-content {
                flex-direction: column;
                text-align: center;
            }
            .location-info, .location-map {
                width: 100%;
            }
            .location-info { order: 2; }
            .location-map { order: 1; margin-bottom: 20px; }
            .location-info h3, .location-info p { text-align: center; }
        }

        
        /* --- Footer Lokasi Maps --- */
        .location-footer {
            background-color: #ffffff;
            padding: 50px 5%;
            margin-top: 50px; /* Jarak dari hero section */
            text-align: center;
            border-top: 1px solid #eee;
        }
        .location-footer h2 {
            color: #1a2a4b;
            font-size: 2em;
            margin-bottom: 10px;
        }
        .location-content {
            display: flex;
            max-width: 1200px;
            margin: 30px auto 0 auto;
            justify-content: space-between;
            align-items: center;
            gap: 40px;
            text-align: left;
        }
        .location-info {
            width: 45%;
        }
        .location-info h3 {
            color: #1a2a4b;
            font-size: 1.8em;
            margin-bottom: 10px;
        }
        .location-info p {
            color: #666;
            line-height: 1.6;
        }
        .location-map {
            width: 55%;
            min-height: 350px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .location-map iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .divider {
            height: 3px;
            width: 50px;
            background-color: #10b981; /* Hijau Aksen */
            margin: 10px auto 0 auto;
        }
    </style>




    </style>
</head>
<body>

    <header class="header">
    <div class="logo" style="display:flex; align-items:center; gap:10px;">
        <img src="gambar/logo-silokos.png" alt="Logo" style="height:40px;">
        <span style="font-size: 1.5em; font-weight: bold;">SiLoKos</span>
    </div>

       <nav>
    <ul class="nav-menu">

        <li class="dropdown">
            <a href="#" class="dropdown-btn">Cari Apa?</a>
            <ul class="dropdown-content">
                <li><a href="app/views/kamar/index.php">Booking</a></li>
                <li><a href="#">Menu 2</a></li>
                <li><a href="#">Menu 3</a></li>
            </ul>
        </li>

        <li><a href="#">Pusat Bantuan</a></li>
        <li><a href="ketentuan/syarat_ketentuan.php">Syarat dan Ketentuan</a></li>
        <li><a href="#" id="openRoleUserBar">Login</a></li>

    </ul>
</nav>

    </header>

    <div class="main-content">
        <div class="text-area">
            <h1>Temukan Kos Idaman Anda dengan Mudah</h1>
            <p>SiLoKos merupakan Sistem informasi pengeloaan dan pemesanan kos yang menyediakan data kamar, fasilitas, dan harga secara lengkap untuk memudahkan pengguna dalam memilih dan melakukan booking secara online.</p>
            
           <a href="#" id="openRoleUser" class="btn btn-primary">Login</a>
           <a href="#" id="openRole" class="btn btn-primary">Registrasi</a>
           <br><br>
           <br><br>
        </div>
        </div>

          <section class="location-footer">
        <h2>LOKASI KAMI</h2>
        <div class="divider"></div>

        <div class="location-content">
            <div class="location-info">
                <h3>Politeknik Negeri Subang</h3>
                <p>Informatics Management Building, Politeknik Negeri Subang, Main Campus Cibogo, Wanareja, Subang District, Subang Regency, West Java 41285, Indonesia.</p>
                <a href="https://maps.app.goo.gl/YourMapLinkHere"></a>
            </div>
            
            <div class="location-map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.858710319207!2d107.72624587426815!3d-6.536967060161839!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693b6e8d2e82e3%3A0x867c0067664c1266!2sPoliteknik%20Negeri%20Subang%20(POLSUB)!5e0!3m2!1sid!2sid!4v1701509000000!5m2!1sid!2sid" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    </section>


        <!-- POP UP Register -->
<div id="roleOverlay" class="role-overlay">
    <div class="role-popup">

        <h3 class="mb-4">Daftar Sebagai Apa?</h3>
        <p class="text-muted mb-4">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

        <a href="app/views/auth/register.php?role=penyewa" class="role-btn btn-penyewa">
            <span class="role-icon"><i class="fas fa-bed"></i></span>
            <span class="role-text">
                Penyewa
                <small>Mencari dan Menyewa Kos</small>
            </span>
        </a>

        <a href="app/views/auth/register.php?role=pemilik" class="role-btn btn-pemilik">
            <span class="role-icon"><i class="fas fa-house-user"></i></span>
            <span class="role-text">
                Pemilik Kos
                <small>Mengelola Properti & Penyewa</small>
            </span>
        </a>

        <button id="closeRole" class="btn btn-secondary mt-3 w-100">Tutup</button>

    </div>
</div>




<!-- pop up login -->
<div id="roleUserOverlay" class="role-overlay">
    <div class="role-popup">

        <h3 class="mb-4">Login Sebagai Apa?</h3>
        <p class="text-muted mb-4">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

        <a href="app/views/auth/login.php?role=penyewa" class="role-btn btn-penyewa">
            <span class="role-icon"><i class="fas fa-bed"></i></span>
            <span class="role-text">
                Penyewa
                <small>Mencari dan Menyewa Kos</small>
            </span>
        </a>

        <a href="app/views/auth/login.php?role=pemilik" class="role-btn btn-pemilik">
            <span class="role-icon"><i class="fas fa-house-user"></i></span>
            <span class="role-text">
                Pemilik Kos
                <small>Mengelola Properti & Penyewa</small>
            </span>
        </a>

        <button id="closeRoleUser" class="btn btn-secondary mt-3 w-100">Tutup</button>

    </div>
</div>

<!-- Login 2 -->
<div id="roleUserOverlayBar" class="role-overlay">
    <div class="role-popup">

        <h3 class="mb-4">Login Sebagai Apa?</h3>
        <p class="text-muted mb-4">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

        <a href="app/views/auth/login.php?role=penyewa" class="role-btn btn-penyewa">
            <span class="role-icon"><i class="fas fa-bed"></i></span>
            <span class="role-text">
                Penyewa
                <small>Mencari dan Menyewa Kos</small>
            </span>
        </a>

        <a href="app/views/auth/login.php?role=pemilik" class="role-btn btn-pemilik">
            <span class="role-icon"><i class="fas fa-house-user"></i></span>
            <span class="role-text">
                Pemilik Kos
                <small>Mengelola Properti & Penyewa</small>
            </span>
        </a>

        <button id="closeUserBar" class="btn btn-secondary mt-3 w-100">Tutup</button>

    </div>

<!-- Register -->
<script>
document.getElementById('openRole').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('roleOverlay').style.display = 'flex';
});

document.getElementById('closeRole').addEventListener('click', function() {
    document.getElementById('roleOverlay').style.display = 'none';
});
</script>

<!-- Login  Utama -->
<script>
document.getElementById('openRoleUser').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('roleUserOverlay').style.display = 'flex';
});

document.getElementById('closeRoleUser').addEventListener('click', function() {
    document.getElementById('roleUserOverlay').style.display = 'none';
});
</script>

<!-- Login Bar -->
<script>
document.getElementById('openRoleUserBar').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('roleUserOverlayBar').style.display = 'flex';
});

document.getElementById('closeUserBar').addEventListener('click', function() {
    document.getElementById('roleUserOverlayBar').style.display = 'none';
});
</script>




</body>
</html>