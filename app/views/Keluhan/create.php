<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Keluhan</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        padding: 20px;
    }

    .container {
        width: 90%;
        max-width: 900px;
        background: #f2f2f2;
        margin: auto;
        border: 2px solid #000;
        border-radius: 15px;
        padding: 25px;
    }

    h2 {
        margin-bottom: 20px;
        font-size: 24px;
    }

    .row {
        display: flex;
        gap: 20px;
    }

    .left, .right {
        width: 50%;
    }

    .input-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #000;
        border-radius: 5px;
        font-size: 14px;
        background: #fafafa;
    }

    textarea {
        height: 135px;
        resize: none;
    }

    /* Garis pemisah tengah */
    .divider {
        width: 2px;
        background: black;
        margin: 0 10px;
    }

    /* Tombol bawah */
    .footer-btn {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn {
        font-weight: bold;
        font-size: 18px;
        cursor: pointer;
        background: none;
        border: none;
    }

    .btn:hover {
        color: #0073ff;
    }

</style>

</head>

<body>

<div class="container">

    <h2>Keluhan</h2>

    <form action="proses_keluhan.php" method="POST">
        
        <div class="row">

            <!-- KIRI -->
            <div class="left">
                <div class="input-group">
                    <input type="text" name="no_kamar" placeholder="NO. kamar">
                </div>

                <div class="input-group">
                    <input type="text" name="nama" placeholder="Nama">
                </div>

                <div class="input-group">
                    <label>Jenis keluhan</label>
                    <select name="jenis_keluhan">
                        <option>Pilih jenis keluhan</option>
                        <option>Air</option>
                        <option>Listrik</option>
                        <option>Kebersihan</option>
                        <option>Lainnya</option>
                    </select>
                </div>
            </div>

            <!-- GARIS PEMISAH -->
            <div class="divider"></div>

            <!-- KANAN -->
            <div class="right">
                <div class="input-group">
                    <textarea name="deskripsi" placeholder="Deskripsi keluhan"></textarea>
                </div>

                <div class="input-group">
                    <input type="text" name="status_keluhan" placeholder="Status keluhan">
                </div>
            </div>

        </div>

        <!-- BUTTON FOOTER -->
        <div class="footer-btn">
            <button type="button" class="btn" onclick="history.back()">Kembali</button>
            <button type="submit" class="btn">Selanjutnya</button>
        </div>

    </form>

</div>

</body>
</html>
