<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - SiLoKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>     
        /* Global Styles */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #F0F2F5; /* Warna latar belakang lebih lembut */
            padding: 3rem 0; 
        }

        /* Typography */
        .title { 
            font-weight: 800; /* Dibuat lebih tebal */
            font-size: 1.8rem; 
            color: #1A202C; /* Warna teks gelap */
        }
        
        /* Card & Layout */
        .card { 
            border-radius: 16px; 
            border: none;
            overflow: hidden; 
        }

        /* Scrollable Terms Box */
        .scrollable-terms {
            height: 350px; /* Tinggi maksimum diperluas sedikit */
            overflow-y: auto; 
            border: 1px solid #E2E8F0;
            padding: 20px; /* Padding lebih besar */
            margin-bottom: 25px;
            border-radius: 10px;
            background-color: #FFFFFF; /* Latar belakang putih untuk kontras */
            line-height: 1.6;
        }

        /* Scrollbar Styling (Opsional, untuk estetika) */
        .scrollable-terms::-webkit-scrollbar {
            width: 8px;
        }
        .scrollable-terms::-webkit-scrollbar-thumb {
            background-color: #CBD5E0;
            border-radius: 10px;
        }
        .scrollable-terms::-webkit-scrollbar-track {
            background: #F7F7F7;
        }


        /* Term Item Styling */
        .term-item {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #E2E8F0; /* Garis pemisah putus-putus */
        }
        .term-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .term-label {
            color: #305FCA; /* Warna biru konsisten */
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 5px;
            display: block;
        }
        .term-text {
            color: #4A5568;
            font-size: 0.95rem;
            margin-bottom: 0; 
        }

        /* Checkbox & Button */
        .form-check-input:checked {
            background-color: #305FCA;
            border-color: #305FCA;
        }
        .form-check-label {
            font-size: 0.95rem;
            color: #1A202C;
        }

        .btn-register { 
            background-color: #305FCA; 
            color: white; 
            padding: 15px; /* Padding lebih besar */
            font-weight: 700; /* Dibuat lebih tebal */
            border-radius: 10px;
            transition: background-color 0.3s, opacity 0.3s, box-shadow 0.3s;
            text-decoration: none; 
            border: none;
        }
        .btn-register:hover { 
            background-color: #21469A; 
            color: white; 
            box-shadow: 0 4px 10px rgba(48, 95, 202, 0.3);
        }
        .btn-register:disabled { 
            opacity: 0.5; 
            cursor: not-allowed; 
            pointer-events: none; /* Nonaktifkan klik pada link */
        }

        .back-link {
            font-size: 0.9rem;
            color: #6B7280;
        }
        .back-link a {
            color: #305FCA;
            font-weight: 600;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body p-5"> <h2 class="title mb-5 text-center text-dark">Syarat dan Ketentuan Penggunaan</h2>
                    
                    <div class="scrollable-terms" id="termsContent">
                        
                        <div class="term-item">
                            <label class="term-label">1. Pendaftaran Akun</label>
                            <p class="term-text">Pengguna wajib mengisi data dengan **benar dan lengkap** saat mendaftar akun. Akun yang digunakan haruslah akun pribadi, dan pengguna bertanggung jawab penuh atas segala aktivitas yang terjadi melalui akun tersebut.</p>
                        </div>
                        
                        <div class="term-item">
                            <label class="term-label">2. Penggunaan Layanan</label>
                            <p class="term-text">Pengguna dilarang menggunakan layanan untuk kegiatan yang melanggar hukum, norma sosial, atau merugikan pihak lain, termasuk namun tidak terbatas pada penipuan atau penyebaran konten ilegal.</p>
                        </div>
                        
                        <div class="term-item">
                            <label class="term-label">3. Pembayaran dan Booking</label>
                            <p class="term-text">Semua pembayaran harus dilakukan sesuai dengan instruksi yang tertera. Proses booking dianggap sah setelah pembayaran diterima dan dikonfirmasi oleh sistem. Harga yang tertera sudah final, kecuali ada perjanjian tertulis lain.</p>
                        </div>                  
                        
                        <div class="term-item">
                            <label class="term-label">4. Pembatalan dan Pengembalian Dana</label>
                            <p class="term-text">Kebijakan pembatalan diatur oleh masing-masing Pemilik Kos. Pengembalian dana (*refund*) akan diproses sesuai dengan kebijakan yang disetujui Pemilik Kos dan mungkin dikenakan biaya administrasi.</p>
                        </div>
                        
                        <div class="term-item">
                            <label class="term-label">5. Privasi Data</label>
                            <p class="term-text">Kami berkomitmen menjaga **kerahasiaan data pribadi** pengguna sesuai dengan Kebijakan Privasi. Data hanya digunakan untuk keperluan layanan SiLoKos dan tidak akan disebarkan tanpa izin.</p>
                        </div>  
                        
                        <div class="term-item">
                            <label class="term-label">6. Perubahan Syarat dan Ketentuan</label>
                            <p class="term-text">Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Pengguna akan diberitahu mengenai perubahan signifikan melalui platform atau email.</p>
                        </div>  
                        
                        <div class="term-item">
                            <label class="term-label">7. Kontak dan Dukungan</label>
                            <p class="term-text">Untuk pertanyaan, keluhan, atau dukungan lebih lanjut, silakan hubungi layanan pelanggan kami melalui **[Alamat Email]** atau **[Nomor Telepon]**.</p>
                        </div>
                        
                    </div>
                    
                    <form>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="agreementCheck" disabled>
                            <label class="form-check-label" for="agreementCheck">
                                Saya telah membaca, memahami, dan menyetujui semua **Syarat dan Ketentuan** di atas.
                            </label>
                        </div>

                        <div class="d-grid">
                            <a href="../index.php" class="btn btn-register" id="continueBtn" disabled>Setuju dan Lanjutkan</a>
                        </div>
                    </form>
                    
                    <p class="text-center mt-4 back-link">Kembali ke halaman <a href="../index.php">Utama</a></p>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsContent = document.getElementById('termsContent');
    const agreementCheck = document.getElementById('agreementCheck');
    const continueBtn = document.getElementById('continueBtn');

    // 1. Logika untuk mengaktifkan Checkbox
    // Checkbox hanya bisa diaktifkan (disabled=false) jika pengguna sudah scroll hingga akhir konten.
    termsContent.addEventListener('scroll', function() {
        // Cek jika posisi scroll + tinggi yang terlihat >= total tinggi konten
        if (termsContent.scrollTop + termsContent.clientHeight >= termsContent.scrollHeight - 5) { // Toleransi 5px
            agreementCheck.disabled = false;
        }
    });

    // 2. Logika untuk mengaktifkan Tombol "Lanjutkan"
    // Tombol diaktifkan hanya jika checkbox dicentang.
    agreementCheck.addEventListener('change', function() {
        if (this.checked) {
            continueBtn.disabled = false;
            // Hapus atribut disabled dari link (<a>)
            continueBtn.style.pointerEvents = 'auto'; 
        } else {
            continueBtn.disabled = true;
            // Tambahkan kembali atribut disabled untuk link (<a>)
            continueBtn.style.pointerEvents = 'none';
        }
    });

    // 3. Status awal
    agreementCheck.disabled = true;
    continueBtn.disabled = true;
    continueBtn.style.pointerEvents = 'none'; // Nonaktifkan klik pada link secara default
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>