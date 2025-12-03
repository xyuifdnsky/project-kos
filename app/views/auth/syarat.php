<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi - Kost App</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11
.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>     
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F7F7F7; padding: 2rem; }
.title { font-weight: 700; font-size: 26px; }
.input-group-text { background: #E6EEFF; border: 1px solid #CED4DA; }
.form-control { background: #F5F8FF; border: 1px solid #CED4DA; padding: 12px; }
.form-control:focus { box-shadow: none; border-color: #7AA7F9
; }
.form-check-label.checkbox-text { font-size: 14px; color: #555; }
.btn-register { background-color: #305FCA; color: white; padding: 13px; font-weight: 600; }
.btn-register:hover { background-color: #21469A; }
</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="title mb-4 text-center">Syarat dan Ketentuan</h2>
                    <form method="POST" action="register.php">
                        <div class="mb-3">
                            <label class="form-label
    fw-semibold">1. Pendaftaran Akun</label>
                            <p class="form-text">Pengguna wajib mengisi data dengan benar dan lengkap saat mendaftar akun.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">2. Penggunaan Layanan</label>
                            <p class="form-text">Pengguna bertanggung jawab atas aktivitas yang dilakukan melalui akun
    mereka.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">3. Pembayaran</label>
                            <p class="form-text">Semua pembayaran harus dilakukan sesuai dengan ketentuan yang berlaku.</p>
                        </div>                  
                        <div class="mb-3">
                            <label class="form-label
    fw-semibold">4. Pembatalan dan Pengembalian Dana</label>

                            <p class="form-text">Kebijakan pembatalan dan pengembalian dana diatur sesuai dengan peraturan yang berlaku.</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">5. Privasi</label>
                            <p class="form-text">Data pribadi pengguna akan dijaga kerahasiaannya sesuai dengan kebijakan privasi kami.</p>
                        </div>  
                        <div class="mb-3">
                            <label class="form-label
    fw-semibold">6. Perubahan Syarat dan Ketentuan</label>
                            <p class="form-text">Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
                        </div>  
                        <div class="mb-3">
                            <label class="form-label fw-semibold">7. Kontak</label>
                            <p class="form-text">Untuk pertanyaan lebih lanjut, silakan hubungi layanan pelanggan kami.</p>
                        </div>
                        <div class="d-grid">
                            <a href="register.php" class="btn btn-register">Setuju dan Lanjutkan</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

  