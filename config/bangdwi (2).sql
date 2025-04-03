-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Nov 2024 pada 16.44
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bangdwi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_alternatif`
--

CREATE TABLE `data_alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(100) NOT NULL,
  `c1` float NOT NULL,
  `c2` float NOT NULL,
  `c3` float NOT NULL,
  `c4` float NOT NULL,
  `tanggal_input` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_alternatif`
--

INSERT INTO `data_alternatif` (`id_alternatif`, `nama_alternatif`, `c1`, `c2`, `c3`, `c4`, `tanggal_input`) VALUES
(41, 'A1', 100, 100, 80, 80, '2024-11-14 00:10:23'),
(42, 'A2', 100, 100, 100, 100, '2024-11-14 00:10:23'),
(43, 'A3', 30, 100, 100, 100, '2024-11-14 00:10:23'),
(44, 'A4', 100, 100, 80, 100, '2024-11-14 00:10:23'),
(45, 'A5', 20, 50, 50, 50, '2024-11-14 00:10:23'),
(46, 'A6', 30, 100, 100, 50, '2024-11-14 00:10:23'),
(47, 'A7', 20, 80, 100, 80, '2024-11-14 00:10:23'),
(48, 'A8', 100, 100, 80, 100, '2024-11-14 00:10:23'),
(49, 'A9', 100, 100, 100, 80, '2024-11-14 00:10:23'),
(50, 'A10', 20, 100, 100, 100, '2024-11-14 00:10:23'),
(51, 'A11', 30, 100, 80, 100, '2024-11-14 00:10:23'),
(52, 'A12', 30, 100, 80, 100, '2024-11-14 00:10:23'),
(53, 'A13', 100, 80, 100, 100, '2024-11-14 00:10:23'),
(54, 'A14', 100, 100, 100, 100, '2024-11-14 00:10:23'),
(55, 'A16', 30, 100, 100, 80, '2024-11-14 00:10:23'),
(56, 'A15', 30, 100, 100, 100, '2024-11-14 00:10:23'),
(57, 'A17', 20, 80, 100, 80, '2024-11-14 00:10:23'),
(58, 'A18', 20, 100, 100, 80, '2024-11-14 00:10:23'),
(59, 'A19', 20, 80, 50, 80, '2024-11-14 00:10:23'),
(60, 'A20', 30, 100, 100, 80, '2024-11-14 00:10:23'),
(61, 'A21', 20, 100, 100, 80, '2024-11-14 00:10:23'),
(62, 'A22', 30, 100, 80, 80, '2024-11-14 00:10:23'),
(63, 'A23', 30, 100, 80, 100, '2024-11-14 00:10:23'),
(64, 'A24', 30, 80, 100, 100, '2024-11-14 00:10:23'),
(65, 'A25', 30, 80, 100, 100, '2024-11-14 00:10:23'),
(66, 'A26', 30, 100, 80, 100, '2024-11-14 00:10:23'),
(67, 'A27', 20, 80, 100, 80, '2024-11-14 00:10:23'),
(68, 'A28', 30, 80, 80, 80, '2024-11-14 00:10:23'),
(69, 'A100', 100, 100, 100, 100, '2024-11-14 00:10:23'),
(70, 'A101', 30, 10, 50, 10, '2024-11-14 00:10:23'),
(71, 'A51', 100, 100, 50, 100, '2024-11-14 00:10:23'),
(72, 'A1000', 100, 100, 100, 100, '2024-11-14 00:10:23'),
(73, 'A1001', 100, 100, 100, 100, '2024-11-14 00:10:23'),
(74, 'A1002', 30, 100, 100, 100, '2024-11-14 00:10:23'),
(75, 'A10011', 100, 80, 50, 80, '2024-11-18 22:43:39'),
(76, 'A10000', 30, 100, 100, 100, '2024-11-18 22:44:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_bobot`
--

CREATE TABLE `data_bobot` (
  `id_bobot` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `bobot` float NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_bobot`
--

INSERT INTO `data_bobot` (`id_bobot`, `nama`, `bobot`, `status`) VALUES
(122, '(C1) KESEHATAN TANAMAN', 0.4445, 'benefit'),
(123, '(C2) PERTUMBUHAN TANAMAN', 0.2832, 'benefit'),
(124, '(C3) WARNA DAUN', 0.1651, 'benefit'),
(125, '(C4) KUALITAS AKAR', 0.1072, 'benefit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_kriteria`
--

CREATE TABLE `data_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `id_bobot` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_kriteria`
--

INSERT INTO `data_kriteria` (`id_kriteria`, `id_bobot`, `nama`, `nilai`) VALUES
(19, 122, 'Bebas Kontaminasi', 100),
(22, 122, 'Terkontaminasi Ringan', 30),
(24, 122, 'Terkontaminasi Sedang', 20),
(26, 123, 'Pertumbuhan Sangat Baik (tinggi dan jumlah daun di atas rata-rata) ', 100),
(27, 123, 'Pertumbuhan Baik (tinggi dan jumlah daun sesuai rata-rata)', 80),
(31, 123, 'Pertumbuhan Sedang (tinggi dan jumlah daun sedikit di bawah rata-rata)', 50),
(32, 123, 'Pertumbuhan Kurang (tinggi dan jumlah daun jauh di bawah rata-rata)', 10),
(33, 124, 'Warna Hijau Tua (indikasi kesehatan dan nutrisi sangat baik)', 100),
(34, 124, 'Warna Hijau (indikasi kesehatan dan nutrisi baik)', 80),
(35, 124, 'Warna Hijau Pucat (indikasi kekurangan nutrisi ringan)', 50),
(37, 125, 'Akar Sangat Sehat (panjang dan kondisi akar sangat baik)', 100),
(38, 125, 'Akar Sehat (panjang dan kondisi akar baik)', 80),
(39, 125, 'Akar Cukup Sehat (panjang dan kondisi akar cukup baik)', 50),
(40, 125, 'Akar Tidak Sehat (panjang dan kondisi akar buruk)', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pengguna`
--

CREATE TABLE `data_pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `pengguna` varchar(50) NOT NULL,
  `katasandi` varchar(100) NOT NULL,
  `akses` varchar(15) NOT NULL,
  `status` varchar(15) NOT NULL,
  `profil` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_pengguna`
--

INSERT INTO `data_pengguna` (`id_pengguna`, `pengguna`, `katasandi`, `akses`, `status`, `profil`) VALUES
(1, 'rusli', '827ccb0eea8a706c4c34a16891f84e7b', 'owner', 'aktif', 'gambar-66dc6964c7a82.jpg'),
(2, 'manajer', '827ccb0eea8a706c4c34a16891f84e7b', 'manajer', 'nonaktif', 'gambar-66dc6982d7886.jpg'),
(6, 'ilham', '827ccb0eea8a706c4c34a16891f84e7b', 'staff', 'nonaktif', 'gambar-66dc69ea318c9.jpg'),
(13, 'aldo', '827ccb0eea8a706c4c34a16891f84e7b', 'manajer', 'aktif', 'default.jpg'),
(14, 'gita', '827ccb0eea8a706c4c34a16891f84e7b', 'staff', 'aktif', 'default.jpg'),
(15, 'ina', '827ccb0eea8a706c4c34a16891f84e7b', 'staff', 'aktif', 'default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_alternatif`
--
ALTER TABLE `data_alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `data_bobot`
--
ALTER TABLE `data_bobot`
  ADD PRIMARY KEY (`id_bobot`);

--
-- Indeks untuk tabel `data_kriteria`
--
ALTER TABLE `data_kriteria`
  ADD PRIMARY KEY (`id_kriteria`),
  ADD KEY `fk_id_bobot` (`id_bobot`);

--
-- Indeks untuk tabel `data_pengguna`
--
ALTER TABLE `data_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_alternatif`
--
ALTER TABLE `data_alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT untuk tabel `data_bobot`
--
ALTER TABLE `data_bobot`
  MODIFY `id_bobot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT untuk tabel `data_kriteria`
--
ALTER TABLE `data_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `data_pengguna`
--
ALTER TABLE `data_pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `data_kriteria`
--
ALTER TABLE `data_kriteria`
  ADD CONSTRAINT `fk_id_bobot` FOREIGN KEY (`id_bobot`) REFERENCES `data_bobot` (`id_bobot`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
