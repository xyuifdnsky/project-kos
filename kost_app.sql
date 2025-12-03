SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

-- DATABASE: kost_app
-- -------------------------
-- TABLE kamar
-- -------------------------
CREATE TABLE
  `kamar` (
    `id_kamar` int (11) NOT NULL,
    `id_pemilik` int (11) NOT NULL,
    `nomor_kamar` varchar(10) NOT NULL,
    `tipe_kamar` enum ('single', 'double', 'family') NOT NULL,
    `jenis_kamar` enum ('putra', 'putri', 'campur') NOT NULL,
    `harga` decimal(10, 2) NOT NULL,
    `status` enum ('tersedia', 'terisi', 'dipesan') NOT NULL DEFAULT 'tersedia',
    `fasilitas` text DEFAULT NULL,
    `gambar` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- -------------------------
-- TABLE keluhan
-- -------------------------
CREATE TABLE
  `keluhan` (
    `id_keluhan` int (11) NOT NULL,
    `id_user` int (11) NOT NULL,
    `tanggal_keluhan` date NOT NULL,
    `judul` varchar(100) NOT NULL,
    `deskripsi` text NOT NULL,
    `status` enum ('baru', 'diproses', 'selesai') DEFAULT 'baru',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- -------------------------
-- TABLE pembayaran
-- -------------------------
CREATE TABLE
  `pembayaran` (
    `id_pembayaran` int (11) NOT NULL,
    `id_user` int (11) NOT NULL,
    `id_kamar` int (11) NOT NULL,
    `tanggal_pembayaran` date NOT NULL,
    `jumlah_bayar` decimal(10, 2) NOT NULL,
    `metode_pembayaran` enum ('transfer', 'tunai', 'e-wallet') NOT NULL,
    `status` enum ('pending', 'lunas', 'gagal') DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- -------------------------
-- TABLE sewa
-- -------------------------
CREATE TABLE
  `sewa` (
    `id_sewa` int (11) NOT NULL,
    `id_user` int (11) NOT NULL,
    `id_kamar` int (11) NOT NULL,
    `tanggal_mulai` date NOT NULL,
    `tanggal_selesai` date NOT NULL,
    `status` enum ('aktif', 'selesai', 'batal') DEFAULT 'aktif',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- -------------------------
-- TABLE users
-- -------------------------
CREATE TABLE
  `users` (
    `id_user` int (11) NOT NULL,
    `username` varchar(50) NOT NULL,
    `nama_user` varchar(100) NOT NULL,
    `telepon` varchar(20) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` enum ('admin', 'pemilik', 'penyewa') NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- -------------------------
-- INDEXES
-- -------------------------S
ALTER TABLE `kamar` ADD PRIMARY KEY (`id_kamar`),
ADD KEY `id_pemilik` (`id_pemilik`);

ALTER TABLE `keluhan` ADD PRIMARY KEY (`id_keluhan`),
ADD KEY `id_user` (`id_user`);

ALTER TABLE `pembayaran` ADD PRIMARY KEY (`id_pembayaran`),
ADD KEY `id_kamar` (`id_kamar`),
ADD KEY `id_user` (`id_user`);

ALTER TABLE `sewa` ADD PRIMARY KEY (`id_sewa`),
ADD KEY `id_kamar` (`id_kamar`),
ADD KEY `id_user` (`id_user`);

ALTER TABLE `users` ADD PRIMARY KEY (`id_user`),
ADD UNIQUE KEY `username` (`username`),
ADD UNIQUE KEY `email` (`email`);

-- -------------------------
-- AUTO INCREMENTS
-- -------------------------
ALTER TABLE `kamar` MODIFY `id_kamar` int (11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `keluhan` MODIFY `id_keluhan` int (11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pembayaran` MODIFY `id_pembayaran` int (11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sewa` MODIFY `id_sewa` int (11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users` MODIFY `id_user` int (11) NOT NULL AUTO_INCREMENT;

-- -------------------------
-- FOREIGN KEY RELATIONS
-- -------------------------
ALTER TABLE `kamar` ADD CONSTRAINT fk_kamar_users FOREIGN KEY (`id_pemilik`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

ALTER TABLE `keluhan` ADD CONSTRAINT fk_keluhan_users FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

ALTER TABLE `pembayaran` ADD CONSTRAINT fk_pembayaran_user FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
ADD CONSTRAINT fk_pembayaran_kamar FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE;

ALTER TABLE `sewa` ADD CONSTRAINT fk_sewa_user FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
ADD CONSTRAINT fk_sewa_kamar FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE;

COMMIT;