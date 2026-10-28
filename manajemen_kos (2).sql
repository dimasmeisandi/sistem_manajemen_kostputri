-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 28 Okt 2026 pada 06.15
-- Versi server: 8.0.30
-- Versi PHP: 8.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `manajemen_kos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_admin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_admin`) VALUES
(1, 'admin@sikos.com', 'admin123', 'Admin Utama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_fasilitas`
--

CREATE TABLE `detail_fasilitas` (
  `id_detail` int NOT NULL,
  `id_kamar` int DEFAULT NULL,
  `id_fasilitas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_fasilitas`
--

INSERT INTO `detail_fasilitas` (`id_detail`, `id_kamar`, `id_fasilitas`) VALUES
(1, 1, 4),
(2, 1, 5),
(3, 1, 6),
(4, 2, 4),
(5, 2, 5),
(6, 2, 6),
(7, 3, 4),
(8, 3, 5),
(9, 3, 6),
(10, 4, 4),
(11, 4, 5),
(12, 4, 6),
(13, 5, 4),
(14, 5, 5),
(15, 5, 6),
(16, 6, 4),
(17, 6, 5),
(18, 6, 6),
(19, 7, 4),
(20, 7, 5),
(21, 7, 6),
(22, 8, 4),
(23, 8, 5),
(24, 8, 6),
(25, 9, 4),
(26, 9, 5),
(27, 9, 6),
(28, 10, 4),
(29, 10, 5),
(30, 10, 6),
(31, 11, 4),
(32, 11, 5),
(33, 11, 6),
(34, 12, 4),
(35, 12, 5),
(36, 12, 6),
(37, 13, 4),
(38, 13, 5),
(39, 13, 6),
(40, 14, 4),
(41, 14, 5),
(42, 14, 6),
(43, 15, 4),
(44, 15, 5),
(45, 15, 6),
(46, 16, 4),
(47, 16, 5),
(48, 16, 6),
(49, 17, 4),
(50, 17, 5),
(51, 17, 6),
(52, 18, 4),
(53, 18, 5),
(54, 18, 6),
(55, 19, 4),
(56, 19, 5),
(57, 19, 6),
(58, 20, 4),
(59, 20, 5),
(60, 20, 6),
(61, 21, 4),
(62, 21, 5),
(63, 21, 6),
(64, 22, 4),
(65, 22, 5),
(66, 22, 6),
(67, 23, 4),
(68, 23, 5),
(69, 23, 6),
(70, 24, 4),
(71, 24, 5),
(72, 24, 6),
(73, 25, 4),
(74, 25, 5),
(75, 25, 6),
(76, 26, 4),
(77, 26, 5),
(78, 26, 6),
(79, 27, 4),
(80, 27, 5),
(81, 27, 6),
(82, 28, 4),
(83, 28, 5),
(84, 28, 6),
(85, 29, 4),
(86, 29, 5),
(87, 29, 6),
(88, 30, 4),
(89, 30, 5),
(90, 30, 6),
(91, 31, 4),
(92, 31, 5),
(93, 31, 6),
(94, 32, 4),
(95, 32, 5),
(96, 32, 6),
(97, 33, 4),
(98, 33, 5),
(99, 33, 6),
(100, 34, 4),
(101, 34, 5),
(102, 34, 6),
(103, 35, 4),
(104, 35, 5),
(105, 35, 6),
(106, 36, 4),
(107, 36, 5),
(108, 36, 6),
(109, 37, 4),
(110, 37, 5),
(111, 37, 6),
(112, 38, 4),
(113, 38, 5),
(114, 38, 6),
(115, 39, 4),
(116, 39, 5),
(117, 39, 6),
(118, 40, 4),
(119, 40, 5),
(120, 40, 6),
(128, 1, 1),
(129, 2, 1),
(130, 3, 1),
(131, 4, 1),
(132, 5, 1),
(133, 6, 1),
(134, 7, 1),
(135, 8, 1),
(136, 9, 1),
(137, 10, 1),
(138, 21, 1),
(139, 22, 1),
(140, 23, 1),
(141, 24, 1),
(142, 25, 1),
(143, 26, 1),
(144, 27, 1),
(145, 28, 1),
(146, 29, 1),
(147, 30, 1),
(159, 11, 2),
(160, 12, 2),
(161, 13, 2),
(162, 14, 2),
(163, 15, 2),
(164, 16, 2),
(165, 17, 2),
(166, 18, 2),
(167, 19, 2),
(168, 20, 2),
(169, 31, 2),
(170, 32, 2),
(171, 33, 2),
(172, 34, 2),
(173, 35, 2),
(174, 36, 2),
(175, 37, 2),
(176, 38, 2),
(177, 39, 2),
(178, 40, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id_fasilitas` int NOT NULL,
  `nama_fasilitas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fasilitas`
--

INSERT INTO `fasilitas` (`id_fasilitas`, `nama_fasilitas`) VALUES
(1, 'AC'),
(2, 'Kipas'),
(3, 'Kamar Mandi Dalam'),
(4, 'WiFi'),
(5, 'Kasur'),
(6, 'Lemari');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int NOT NULL,
  `nomor_kamar` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lantai` int DEFAULT NULL,
  `harga_kamar` decimal(15,2) DEFAULT NULL,
  `status_kamar` enum('Kosong','Terisi','Penuh') COLLATE utf8mb4_unicode_ci DEFAULT 'Kosong',
  `tipe_kamar_mandi` enum('Dalam','Luar') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `nomor_kamar`, `lantai`, `harga_kamar`, `status_kamar`, `tipe_kamar_mandi`) VALUES
(1, 'A1', 1, 1500000.00, 'Terisi', 'Dalam'),
(2, 'A2', 1, 1500000.00, 'Terisi', 'Dalam'),
(3, 'A3', 1, 1500000.00, 'Terisi', 'Dalam'),
(4, 'A4', 1, 1500000.00, 'Terisi', 'Dalam'),
(5, 'A5', 1, 1500000.00, 'Terisi', 'Dalam'),
(6, 'A6', 1, 1500000.00, 'Terisi', 'Dalam'),
(7, 'A7', 1, 1500000.00, 'Terisi', 'Dalam'),
(8, 'A8', 1, 1500000.00, 'Terisi', 'Dalam'),
(9, 'A9', 1, 1500000.00, 'Terisi', 'Dalam'),
(10, 'A10', 1, 1500000.00, 'Terisi', 'Dalam'),
(11, 'A11', 1, 900000.00, 'Penuh', 'Dalam'),
(12, 'A12', 1, 900000.00, 'Penuh', 'Dalam'),
(13, 'A13', 1, 900000.00, 'Penuh', 'Dalam'),
(14, 'A14', 1, 900000.00, 'Penuh', 'Dalam'),
(15, 'A15', 1, 900000.00, 'Penuh', 'Dalam'),
(16, 'A16', 1, 900000.00, 'Kosong', 'Dalam'),
(17, 'A17', 1, 900000.00, 'Kosong', 'Dalam'),
(18, 'A18', 1, 900000.00, 'Kosong', 'Dalam'),
(19, 'A19', 1, 900000.00, 'Kosong', 'Dalam'),
(20, 'A20', 1, 900000.00, 'Kosong', 'Dalam'),
(21, 'B1', 2, 1200000.00, 'Terisi', 'Luar'),
(22, 'B2', 2, 1200000.00, 'Terisi', 'Luar'),
(23, 'B3', 2, 1200000.00, 'Terisi', 'Luar'),
(24, 'B4', 2, 1200000.00, 'Terisi', 'Luar'),
(25, 'B5', 2, 1200000.00, 'Terisi', 'Luar'),
(26, 'B6', 2, 1200000.00, 'Terisi', 'Luar'),
(27, 'B7', 2, 1200000.00, 'Terisi', 'Luar'),
(28, 'B8', 2, 1200000.00, 'Terisi', 'Luar'),
(29, 'B9', 2, 1200000.00, 'Terisi', 'Luar'),
(30, 'B10', 2, 1200000.00, 'Terisi', 'Luar'),
(31, 'B11', 2, 700000.00, 'Penuh', 'Luar'),
(32, 'B12', 2, 700000.00, 'Penuh', 'Luar'),
(33, 'B13', 2, 700000.00, 'Penuh', 'Luar'),
(34, 'B14', 2, 700000.00, 'Penuh', 'Luar'),
(35, 'B15', 2, 700000.00, 'Penuh', 'Luar'),
(36, 'B16', 2, 700000.00, 'Kosong', 'Luar'),
(37, 'B17', 2, 700000.00, 'Kosong', 'Luar'),
(38, 'B18', 2, 700000.00, 'Kosong', 'Luar'),
(39, 'B19', 2, 700000.00, 'Kosong', 'Luar'),
(40, 'B20', 2, 700000.00, 'Terisi', 'Luar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `id_penghuni` int DEFAULT NULL,
  `id_kamar` int DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `bulan_bayar` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_bayar` decimal(15,2) DEFAULT NULL,
  `status_pembayaran` enum('Lunas','Belum Lunas') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_admin`, `id_penghuni`, `id_kamar`, `tanggal_bayar`, `bulan_bayar`, `jumlah_bayar`, `status_pembayaran`) VALUES
(1, 1, 1, 1, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(2, 1, 2, 2, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(3, 1, 3, 3, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(4, 1, 4, 4, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(5, 1, 5, 5, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(6, 1, 6, 6, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(7, 1, 7, 7, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(8, 1, 8, 8, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(9, 1, 9, 9, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(10, 1, 10, 10, '2026-03-05', 'Maret', 1500000.00, 'Lunas'),
(11, 1, 11, 11, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(12, 1, 12, 11, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(13, 1, 13, 12, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(14, 1, 14, 12, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(15, 1, 15, 13, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(16, 1, 16, 13, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(17, 1, 17, 14, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(18, 1, 18, 14, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(19, 1, 19, 15, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(20, 1, 20, 15, '2026-03-05', 'Maret', 900000.00, 'Lunas'),
(21, 1, 21, 21, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(22, 1, 22, 22, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(23, 1, 23, 23, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(24, 1, 24, 24, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(25, 1, 25, 25, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(26, 1, 26, 26, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(27, 1, 27, 27, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(28, 1, 28, 28, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(29, 1, 29, 29, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(30, 1, 30, 30, '2026-03-05', 'Maret', 1200000.00, 'Lunas'),
(31, 1, 31, 31, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(32, 1, 32, 31, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(33, 1, 33, 32, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(34, 1, 34, 32, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(35, 1, 35, 33, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(36, 1, 36, 33, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(37, 1, 37, 34, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(38, 1, 38, 34, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(39, 1, 39, 35, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(40, 1, 40, 35, '2026-03-05', 'Maret', 700000.00, 'Lunas'),
(64, 1, 1, 1, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(65, 1, 2, 2, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(66, 1, 3, 3, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(67, 1, 4, 4, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(68, 1, 5, 5, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(69, 1, 6, 6, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(70, 1, 7, 7, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(71, 1, 8, 8, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(72, 1, 9, 9, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(73, 1, 10, 10, '2026-04-05', 'April', 1500000.00, 'Lunas'),
(74, 1, 11, 11, '2026-04-05', 'April', 900000.00, 'Lunas'),
(75, 1, 12, 11, '2026-04-05', 'April', 900000.00, 'Lunas'),
(76, 1, 13, 12, '2026-04-05', 'April', 900000.00, 'Lunas'),
(77, 1, 14, 12, '2026-04-05', 'April', 900000.00, 'Lunas'),
(78, 1, 15, 13, '2026-04-05', 'April', 900000.00, 'Lunas'),
(79, 1, 16, 13, '2026-04-05', 'April', 900000.00, 'Lunas'),
(80, 1, 17, 14, '2026-04-05', 'April', 900000.00, 'Lunas'),
(81, 1, 18, 14, '2026-04-05', 'April', 900000.00, 'Lunas'),
(82, 1, 19, 15, '2026-04-05', 'April', 900000.00, 'Lunas'),
(83, 1, 20, 15, '2026-04-05', 'April', 900000.00, 'Lunas'),
(84, 1, 21, 21, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(85, 1, 22, 22, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(86, 1, 23, 23, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(87, 1, 24, 24, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(88, 1, 25, 25, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(89, 1, 26, 26, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(90, 1, 27, 27, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(91, 1, 28, 28, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(92, 1, 29, 29, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(93, 1, 30, 30, '2026-04-05', 'April', 1200000.00, 'Lunas'),
(94, 1, 31, 31, '2026-04-05', 'April', 700000.00, 'Lunas'),
(95, 1, 32, 31, '2026-04-05', 'April', 700000.00, 'Lunas'),
(96, 1, 33, 32, '2026-04-05', 'April', 700000.00, 'Lunas'),
(97, 1, 34, 32, '2026-04-05', 'April', 700000.00, 'Lunas'),
(98, 1, 35, 33, '2026-04-05', 'April', 700000.00, 'Lunas'),
(99, 1, 36, 33, '2026-04-05', 'April', 700000.00, 'Lunas'),
(100, 1, 37, 34, '2026-04-05', 'April', 700000.00, 'Lunas'),
(101, 1, 38, 34, '2026-04-05', 'April', 700000.00, 'Lunas'),
(102, 1, 39, 35, '2026-04-05', 'April', 700000.00, 'Lunas'),
(103, 1, 40, 35, '2026-04-05', 'April', 700000.00, 'Lunas'),
(127, 1, 1, 1, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(128, 1, 2, 2, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(129, 1, 3, 3, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(130, 1, 4, 4, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(131, 1, 5, 5, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(132, 1, 6, 6, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(133, 1, 7, 7, '2026-05-05', 'Mei', 1500000.00, 'Belum Lunas'),
(134, 1, 8, 8, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(135, 1, 9, 9, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(136, 1, 10, 10, '2026-05-05', 'Mei', 1500000.00, 'Lunas'),
(137, 1, 11, 11, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(138, 1, 12, 11, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(139, 1, 13, 12, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(140, 1, 14, 12, '2026-05-05', 'Mei', 900000.00, 'Belum Lunas'),
(141, 1, 15, 13, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(142, 1, 16, 13, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(143, 1, 17, 14, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(144, 1, 18, 14, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(145, 1, 19, 15, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(146, 1, 20, 15, '2026-05-05', 'Mei', 900000.00, 'Lunas'),
(147, 1, 21, 21, '2026-05-05', 'Mei', 1200000.00, 'Belum Lunas'),
(148, 1, 22, 22, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(149, 1, 23, 23, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(150, 1, 24, 24, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(151, 1, 25, 25, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(152, 1, 26, 26, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(153, 1, 27, 27, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(154, 1, 28, 28, '2026-05-05', 'Mei', 1200000.00, 'Belum Lunas'),
(155, 1, 29, 29, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(156, 1, 30, 30, '2026-05-05', 'Mei', 1200000.00, 'Lunas'),
(157, 1, 31, 31, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(158, 1, 32, 31, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(159, 1, 33, 32, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(160, 1, 34, 32, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(161, 1, 35, 33, '2026-05-05', 'Mei', 700000.00, 'Belum Lunas'),
(162, 1, 36, 33, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(163, 1, 37, 34, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(164, 1, 38, 34, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(165, 1, 39, 35, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(166, 1, 40, 35, '2026-05-05', 'Mei', 700000.00, 'Lunas'),
(190, NULL, 41, 40, '2026-06-15', 'Mei', 700000.00, 'Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `id_admin`, `tanggal`, `keterangan`, `jumlah`) VALUES
(1, 1, '2026-03-10', 'Bayar Listrik', 1200000.00),
(2, 1, '2026-03-12', 'Bayar WiFi Indihome', 450000.00),
(3, 1, '2026-04-10', 'Bayar Listrik', 1300000.00),
(4, 1, '2026-04-12', 'Bayar WiFi Indihome', 450000.00),
(5, 1, '2026-05-10', 'Perbaikan Kran Bocor Kamar A5', 150000.00),
(6, 1, '2026-06-15', 'listrikkk', 25000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penghuni`
--

CREATE TABLE `penghuni` (
  `id_penghuni` int NOT NULL,
  `id_kamar` int DEFAULT NULL,
  `nama_penghuni` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tgl_masuk` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penghuni`
--

INSERT INTO `penghuni` (`id_penghuni`, `id_kamar`, `nama_penghuni`, `jenis_kelamin`, `no_hp`, `alamat`, `tgl_masuk`) VALUES
(1, 1, 'Siska', 'Perempuan', NULL, NULL, '2026-01-01'),
(2, 2, 'Ahyeon', 'Perempuan', NULL, NULL, '2026-01-01'),
(3, 3, 'Joy', 'Perempuan', NULL, NULL, '2026-01-01'),
(4, 4, 'lisa', 'Perempuan', NULL, NULL, '2026-01-01'),
(5, 5, 'Eka', 'Perempuan', NULL, NULL, '2026-01-01'),
(6, 6, 'Rose', 'Perempuan', NULL, NULL, '2026-01-01'),
(7, 7, 'Gita', 'Perempuan', NULL, NULL, '2026-01-01'),
(8, 8, 'Jenny', 'Perempuan', NULL, NULL, '2026-01-01'),
(9, 9, 'Jisoo', 'Perempuan', NULL, NULL, '2026-01-01'),
(10, 10, 'Santi', 'Perempuan', NULL, NULL, '2026-01-01'),
(11, 11, 'Asa', 'Perempuan', NULL, NULL, '2026-02-01'),
(12, 11, 'Bulan', 'Perempuan', NULL, NULL, '2026-02-01'),
(13, 12, 'Winter', 'Perempuan', NULL, NULL, '2026-02-01'),
(14, 12, 'Novel', 'Perempuan', NULL, NULL, '2026-02-01'),
(15, 13, 'Karina', 'Perempuan', NULL, NULL, '2026-02-01'),
(16, 13, 'Novi', 'Perempuan', NULL, NULL, '2026-02-01'),
(17, 14, 'Gisell', 'Perempuan', NULL, NULL, '2026-02-01'),
(18, 14, 'Nadia', 'Perempuan', NULL, NULL, '2026-02-01'),
(19, 15, 'Yizhou', 'Perempuan', NULL, NULL, '2026-02-01'),
(20, 15, 'wenndy', 'Perempuan', NULL, NULL, '2026-02-01'),
(21, 21, 'Sana', 'Perempuan', NULL, NULL, '2026-01-05'),
(22, 22, 'Vina', 'Perempuan', NULL, NULL, '2026-01-05'),
(23, 23, 'Dahyun', 'Perempuan', NULL, NULL, '2026-01-05'),
(24, 24, 'Jihyo', 'Perempuan', NULL, NULL, '2026-01-05'),
(25, 25, 'Megawati', 'Perempuan', '088888', 'rere', '2026-01-05'),
(26, 26, 'Tzuyu', 'Perempuan', NULL, NULL, '2026-01-05'),
(27, 27, 'Amel', 'Perempuan', NULL, NULL, '2026-01-05'),
(28, 28, 'Mina', 'Perempuan', NULL, NULL, '2026-01-05'),
(29, 29, 'Nayeon', 'Perempuan', NULL, NULL, '2026-01-05'),
(30, 30, 'Dina', 'Perempuan', NULL, NULL, '2026-01-05'),
(31, 31, 'Chae', 'Perempuan', NULL, NULL, '2026-02-10'),
(32, 31, 'Hanni', 'Perempuan', NULL, NULL, '2026-02-10'),
(33, 32, 'Kazuha', 'Perempuan', NULL, NULL, '2026-02-10'),
(34, 32, 'Haerin', 'Perempuan', NULL, NULL, '2026-02-10'),
(35, 33, 'Sakura', 'Perempuan', NULL, NULL, '2026-02-10'),
(36, 33, 'Lesti', 'Perempuan', NULL, NULL, '2026-02-10'),
(37, 34, 'Danielle', 'Perempuan', NULL, NULL, '2026-02-10'),
(38, 34, 'Lulu', 'Perempuan', NULL, NULL, '2026-02-10'),
(39, 35, 'Minji', 'Perempuan', NULL, NULL, '2026-02-10'),
(40, 35, 'Nita', 'Perempuan', NULL, NULL, '2026-02-10'),
(41, 40, 'nuril', 'Laki-laki', '08975421111', 'jln jambu', '2026-02-02');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `detail_fasilitas`
--
ALTER TABLE `detail_fasilitas`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_kamar` (`id_kamar`),
  ADD KEY `id_fasilitas` (`id_fasilitas`);

--
-- Indeks untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id_fasilitas`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_penghuni` (`id_penghuni`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id_penghuni`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `detail_fasilitas`
--
ALTER TABLE `detail_fasilitas`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id_fasilitas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id_penghuni` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_fasilitas`
--
ALTER TABLE `detail_fasilitas`
  ADD CONSTRAINT `detail_fasilitas_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`),
  ADD CONSTRAINT `detail_fasilitas_ibfk_2` FOREIGN KEY (`id_fasilitas`) REFERENCES `fasilitas` (`id_fasilitas`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_penghuni`) REFERENCES `penghuni` (`id_penghuni`),
  ADD CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);

--
-- Ketidakleluasaan untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `penghuni`
--
ALTER TABLE `penghuni`
  ADD CONSTRAINT `penghuni_ibfk_1` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
