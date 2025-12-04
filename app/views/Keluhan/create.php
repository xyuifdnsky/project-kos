<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Keluhan - Pelaporan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #e74c3c; /* Merah untuk Keluhan */
            --secondary-color: #3498db; /* Biru untuk aksi */
            --bg-color: #f0f4f8;
            --card-bg: white;
            --border-radius: 12px;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 850px;
            background: var(--card-bg);
            margin: 50px auto;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            padding: 35px 40px;
        }

        h2 {
            margin-bottom: 25px;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 10px;
            display: inline-block;
        }

        .row {
            display: flex;
            gap: 30px;
            align-items: flex-start; /* Ensure columns start at the top */
        }

        .left, .right {
            width: 50%;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #333;
            font-size: 0.95rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            background: #fafafa;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        textarea {
            height: 150px;
            resize: vertical; /* Allow vertical resizing */
        }
        
        /* Tambahkan Ikon Placeholder */
        input[placeholder="NO. kamar"] { background: #fafafa url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23999" viewBox="0 0 16 16"><path d="M6.5 7a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-3z"/><path d="M4 1.5a.5.5 0 0 0-1 0V2H1.5A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14H4v.5a.5.5 0 0 0 1 0v-.5h2v.5a.5.5 0 0 0 1 0v-.5h2v.5a.5.5 0 0 0 1 0v-.5h2.5a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2H13V1.5a.5.5 0 0 0-1 0V2H4V1.5zM12 9.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>') no-repeat 98% 50%; padding-right: 40px; }
        input[placeholder="Nama"] { background: #fafafa url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23999" viewBox="0 0 16 16"><path d="M.002 12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v9zm6.5-4.5a.5.5 0 0 0-1 0v3a.5.5 0 0 0 1 0v-3zM8 8a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 1 0v-3A.5.5 0 0 0 8 8zm1.5.5a.5.5 0 0 0 1 0v3a.5.5 0 0 0-1 0v-3zM10.5 8a.5.5 0 0 0 1 0v3a.5.5 0 0 0-1 0v-3z"/></svg>') no-repeat 98% 50%; padding-right: 40px; }

        /* Garis pemisah tengah yang lebih modern */
        .divider {
            width: 1px;
            background: #ddd;
            height: auto;
            margin: 0;
        }

        /* Tombol bawah */
        .footer-btn {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn {
            font-weight: 600;
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Tombol Kembali */
        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
            border: 1px solid #bdc3c7;
        }
        .btn-back:hover {
            background: #bdc3c7;
            color: white;
        }

        /* Tombol Selanjutnya/Submit */
        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: none;
        }
        .btn-submit:hover {
            background: #c0392b;
            box-shadow: 0 4px 10px rgba(231, 76, 60, 0.4);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
                gap: 0;
            }
            .left, .right {
                width: 100%;
            }
            .divider {
                width: 100%;
                height: 1px;
                margin: 20px 0;
            }
        }
    </style>
</head>

<body>

<div class="container">
    
    <h2 title="Formulir Laporan Masalah"><i class="bi bi-exclamation-triangle-fill me-2"></i> Laporkan Keluhan</h2>

    <form action="proses_keluhan.php" method="POST" id="keluhanForm">
        
        <div class="row">

            <div class="left">
                <div class="input-group">
                    <label for="no_kamar">Nomor Kamar <span style="color:red;">*</span></label>
                    <input type="text" id="no_kamar" name="no_kamar" placeholder="Contoh: 101" required>
                </div>

                <div class="input-group">
                    <label for="nama">Nama Pelapor <span style="color:red;">*</span></label>
                    <input type="text" id="nama" name="nama" placeholder="Nama lengkap Anda" required>
                </div>

                <div class="input-group">
                    <label for="jenis_keluhan">Jenis Keluhan <span style="color:red;">*</span></label>
                    <select id="jenis_keluhan" name="jenis_keluhan" required>
                        <option value="" disabled selected>Pilih kategori keluhan</option>
                        <option value="Air">Air (Saluran, kran, WC)</option>
                        <option value="Listrik">Listrik (AC, Lampu, Stop Kontak)</option>
                        <option value="Kebersihan">Kebersihan (Area Umum, Sampah)</option>
                        <option value="Lainnya">Lainnya (Perabotan rusak, keamanan)</option>
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            <div class="right">
                <div class="input-group">
                    <label for="deskripsi">Deskripsi Keluhan <span style="color:red;">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan secara rinci masalah yang terjadi (Contoh: Kran kamar mandi bocor sejak tadi malam)" required></textarea>
                </div>

                <div class="input-group">
                    <label for="status_keluhan">Status Saat Ini</label>
                    <input type="text" id="status_keluhan" name="status_keluhan" value="Menunggu Verifikasi" disabled>
                    <small class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 5px;">*Status keluhan akan diperbarui oleh admin/pemilik.</small>
                </div>
            </div>

        </div>

        <div class="footer-btn">
            <button type="button" class="btn btn-back" onclick="history.back()">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button type="submit" class="btn btn-submit">
                Kirim Laporan <i class="bi bi-send-fill"></i>
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('keluhanForm');
    
    // JS: Terapkan validasi dasar sebelum submit (untuk meningkatkan UX)
    form.addEventListener('submit', function(e) {
        const noKamar = document.getElementById('no_kamar').value;
        const nama = document.getElementById('nama').value;
        const jenisKeluhan = document.getElementById('jenis_keluhan').value;
        const deskripsi = document.getElementById('deskripsi').value;

        if (!noKamar || !nama || !jenisKeluhan || !deskripsi) {
            e.preventDefault();
            alert('Mohon lengkapi semua bidang yang bertanda bintang (*).');
        }
        // Tambahkan efek interaktif pada tombol saat submit
        else {
            const submitBtn = document.querySelector('.btn-submit');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            submitBtn.disabled = true;
        }
    });

    // JS: Interaksi visual pada input
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--secondary-color)';
        });
        input.addEventListener('blur', function() {
            this.style.borderColor = '#ccc';
        });
    });
});
</script>

</body>
</html>