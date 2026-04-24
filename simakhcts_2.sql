-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 08:38 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simakhcts`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip_admin` varchar(50) NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `noWA` varchar(20) NOT NULL,
  `email_admin` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `update_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biaya_pendidikan`
--

CREATE TABLE `biaya_pendidikan` (
  `id_bp` int(11) NOT NULL,
  `nama_bp` varchar(255) NOT NULL,
  `nominal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biaya_pendidikan`
--

INSERT INTO `biaya_pendidikan` (`id_bp`, `nama_bp`, `nominal`) VALUES
(1, 'Pembayaran DP Pertama', 5000000),
(2, 'Pelunasan Biaya Program', 10000000);

-- --------------------------------------------------------

--
-- Table structure for table `evaluasi`
--

CREATE TABLE `evaluasi` (
  `id_evaluasi` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_pengajar` int(11) DEFAULT NULL,
  `DUI1` int(11) DEFAULT 0,
  `DUI2` int(11) DEFAULT 0,
  `DUI3` int(11) DEFAULT 0,
  `DUI4` int(11) DEFAULT 0,
  `DUI5` int(11) DEFAULT 0,
  `DUI6` int(11) DEFAULT 0,
  `DUI7` int(11) DEFAULT 0,
  `DUI8` int(11) DEFAULT 0,
  `rata_rata` float DEFAULT 0,
  `status_kelulusan` enum('Lulus','Tidak Lulus','Pending') DEFAULT 'Pending',
  `catatan_pengajar` text DEFAULT NULL,
  `periode_semester` varchar(50) DEFAULT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluasi`
--

INSERT INTO `evaluasi` (`id_evaluasi`, `id_siswa`, `id_pengajar`, `DUI1`, `DUI2`, `DUI3`, `DUI4`, `DUI5`, `DUI6`, `DUI7`, `DUI8`, `rata_rata`, `status_kelulusan`, `catatan_pengajar`, `periode_semester`, `tanggal_input`) VALUES
(1, 9, 1, 85, 80, 78, 90, 85, 82, 88, 84, 84, 'Lulus', NULL, 'Semester Ganjil 2025/2026', '2026-04-22 07:06:16'),
(2, 10, 112, 80, 90, 90, 90, 100, 100, 90, 95, 91.875, 'Lulus', 'HAHA REMED LUHK', 'Semester Ganjil 2025', '2026-04-22 07:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `id_kurikulum` int(11) NOT NULL,
  `id_pengajar` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_kurikulum`, `id_pengajar`, `id_kelas`, `hari`, `jam_mulai`, `jam_selesai`, `ruang`) VALUES
(1, 1, 102, 6, 'Senin', '08:00:00', '10:00:00', 'R. Teori 101'),
(2, 6, 109, 1, 'Selasa', '10:30:00', '12:30:00', 'Lab Kitchen'),
(3, 11, 102, 1, 'Rabu', '13:00:00', '15:00:00', 'Bar Station'),
(4, 16, 109, 1, 'Kamis', '08:00:00', '10:00:00', 'R. Teori 102'),
(5, 21, 102, 1, 'Jumat', '10:30:00', '12:30:00', 'R. Teori 101'),
(6, 2, 109, 1, 'Senin', '13:00:00', '15:00:00', 'Lab Kitchen'),
(7, 7, 102, 1, 'Selasa', '08:00:00', '10:00:00', 'Bar Station'),
(8, 12, 109, 1, 'Rabu', '10:30:00', '12:30:00', 'R. Teori 102'),
(9, 17, 102, 1, 'Kamis', '13:00:00', '15:00:00', 'R. Teori 101'),
(10, 22, 109, 1, 'Jumat', '08:00:00', '10:00:00', 'Lab Kitchen');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `Id_program` int(11) NOT NULL,
  `kapasitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `Id_program`, `kapasitas`) VALUES
(1, 'Kelas A - Culinary', 1, 30),
(2, 'Kelas B - Culinary', 1, 30),
(3, 'Kelas A - Accommodation', 2, 30),
(4, 'Kelas A - Beverage', 3, 30),
(5, 'Kelas A - F&B Service', 5, 30),
(6, 'Kelas Khusus Remedial', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id_ks` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `Id_siswa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id_ks`, `id_kelas`, `Id_siswa`) VALUES
(1, 1, 9),
(16, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum`
--

CREATE TABLE `kurikulum` (
  `id_kurikulum` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `Id_semester` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurikulum`
--

INSERT INTO `kurikulum` (`id_kurikulum`, `id_program`, `Id_semester`, `id_mapel`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 1, 1, 3),
(4, 1, 1, 4),
(5, 1, 1, 5),
(6, 2, 1, 1),
(7, 2, 1, 2),
(8, 2, 1, 3),
(9, 2, 1, 4),
(10, 2, 1, 5),
(11, 3, 1, 1),
(12, 3, 1, 2),
(13, 3, 1, 3),
(14, 3, 1, 4),
(15, 3, 1, 5),
(16, 4, 1, 1),
(17, 4, 1, 2),
(18, 4, 1, 3),
(19, 4, 1, 4),
(20, 4, 1, 5),
(21, 5, 1, 1),
(22, 5, 1, 2),
(23, 5, 1, 3),
(24, 5, 1, 4),
(25, 5, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_harian`
--

CREATE TABLE `laporan_harian` (
  `id_laporan` int(11) NOT NULL,
  `id_magang` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `file_lampiran` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `magang`
--

CREATE TABLE `magang` (
  `id_magang` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL,
  `posisi` varchar(100) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `kontak_person` varchar(100) DEFAULT NULL,
  `status_magang` enum('draft','pending','disetujui','ditolak','berlangsung','selesai') DEFAULT NULL,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_admin` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `catatan_admin` text DEFAULT NULL,
  `no_sertifikat` varchar(50) DEFAULT NULL,
  `status_laporan` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `file_laporan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `magang`
--

INSERT INTO `magang` (`id_magang`, `id_siswa`, `nama_perusahaan`, `posisi`, `lokasi`, `tanggal_mulai`, `tanggal_selesai`, `kontak_person`, `status_magang`, `tanggal_pengajuan`, `status_admin`, `catatan_admin`, `no_sertifikat`, `status_laporan`, `file_laporan`) VALUES
(1, 10, 'Ritz Carlton', 'Customer Service', 'Malaysia', '2026-04-01', '2026-04-24', 'CEO - Gevinta - 0817150043', 'selesai', '2026-04-22 09:47:27', 'diterima', '', '001/017/HCTS/SL-1/2026', 'disetujui', 'laporan_1_1776929760.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id_mapel` int(11) NOT NULL,
  `kode_mapel` varchar(255) NOT NULL,
  `nama_mapel` varchar(255) NOT NULL,
  `beban_belajar` int(11) NOT NULL,
  `jenis` enum('Teori','Praktikum','Magang') NOT NULL,
  `durasi_belajar` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id_mapel`, `kode_mapel`, `nama_mapel`, `beban_belajar`, `jenis`, `durasi_belajar`) VALUES
(1, 'DUI1', 'English for Hospitality', 3, 'Teori', '02:30:00'),
(2, 'DUI2', 'Hotel & Cruise Ship Overview', 2, 'Teori', '01:40:00'),
(3, 'DUI3', 'Food & Beverage Service Foundation', 2, 'Praktikum', '05:20:00'),
(4, 'DUI4', 'Kitchen & Food Production Basics', 2, 'Praktikum', '05:20:00'),
(5, 'DUI5', 'Housekeeping & Laundry Fundamentals', 2, 'Praktikum', '05:20:00'),
(6, 'DUI6', 'Front Office & Guest Interaction', 2, 'Teori', '01:40:00'),
(7, 'DUI7', 'Basic Safety Training (BST) & STCW', 2, 'Praktikum', '05:20:00'),
(8, 'DUI8', 'Grooming & Professional Conduct', 1, 'Teori', '00:50:00'),
(9, 'MAG1', 'On the Job Training (Magang)', 8, 'Magang', '06:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_magang`
--

CREATE TABLE `nilai_magang` (
  `id_nilai` int(11) NOT NULL,
  `id_magang` int(11) NOT NULL,
  `job_knowledge` decimal(3,2) DEFAULT 0.00,
  `quantity_of_work` decimal(3,2) DEFAULT 0.00,
  `quality_of_work` decimal(3,2) DEFAULT 0.00,
  `character_val` decimal(3,2) DEFAULT 0.00,
  `personality` decimal(3,2) DEFAULT 0.00,
  `courtesy` decimal(3,2) DEFAULT 0.00,
  `personal_appearance` decimal(3,2) DEFAULT 0.00,
  `attendance` decimal(3,2) DEFAULT 0.00,
  `dinilai_oleh` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `evaluasi_laporan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai_magang`
--

INSERT INTO `nilai_magang` (`id_nilai`, `id_magang`, `job_knowledge`, `quantity_of_work`, `quality_of_work`, `character_val`, `personality`, `courtesy`, `personal_appearance`, `attendance`, `dinilai_oleh`, `created_at`, `evaluasi_laporan`) VALUES
(1, 1, 3.30, 3.30, 3.30, 3.30, 3.30, 3.30, 3.30, 3.12, 2, '2026-04-23 07:12:26', '');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_biaya_pendidikan` int(11) NOT NULL,
  `nominal` int(11) DEFAULT NULL,
  `bukti_file` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `status_pembayaran` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_siswa`, `id_biaya_pendidikan`, `nominal`, `bukti_file`, `deskripsi`, `tanggal_pembayaran`, `status_pembayaran`, `keterangan`) VALUES
(2, 9, 1, 5000000, '/public/uploads/pembayaran/PAY_1776839184_9.png', 'Pembayaran DP Pertama', '2026-04-22', 'diterima', ''),
(3, 10, 1, 5000000, '/public/uploads/pembayaran/PAY_1776842974_10.pdf', 'Pembayaran DP Pertama', '2026-04-22', 'diterima', '');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_pendaftaran` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_program` int(11) DEFAULT NULL,
  `nama_cs` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_wa` varchar(15) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `ktp` varchar(255) NOT NULL,
  `ijazah` varchar(255) NOT NULL,
  `foto_siswa` varchar(255) NOT NULL,
  `bukti_pendaftaran` varchar(255) NOT NULL,
  `surat_pernyataan` varchar(255) NOT NULL,
  `token_masuk` varchar(255) NOT NULL,
  `token_akses` varchar(50) NOT NULL,
  `token_expired` datetime DEFAULT NULL,
  `status_approval` varchar(20) DEFAULT 'pending',
  `status_berkas` varchar(20) DEFAULT 'pending',
  `jadwal_wawancara` datetime DEFAULT NULL,
  `hasil_akhir` varchar(20) DEFAULT 'pending',
  `email_belajar` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`id_pendaftaran`, `id_user`, `id_program`, `nama_cs`, `email`, `no_wa`, `tanggal_lahir`, `alamat`, `ktp`, `ijazah`, `foto_siswa`, `bukti_pendaftaran`, `surat_pernyataan`, `token_masuk`, `token_akses`, `token_expired`, `status_approval`, `status_berkas`, `jadwal_wawancara`, `hasil_akhir`, `email_belajar`) VALUES
(12, 106, NULL, 'Choi San Wiliam', 'wiliam302@gmail.com', '+628976532123', '2002-09-15', 'Ngaglik, Sleman, DIY', 'public/uploads/1776514530_5538_ktp.pdf', 'public/uploads/1776514530_9780_ijazah.pdf', 'public/uploads/1776514530_1928_foto_siswa.jpeg', 'public/uploads/1776514530_2333_bukti_pendaftaran.pdf', 'public/uploads/1776514530_5310_surat_pernyataan.pdf', 'REG-2026-0011', 'HCTS-XT01', NULL, 'disetujui', 'valid', '2026-04-18 19:25:00', 'pending', 'choi12.26@hcts.ac.id'),
(13, 107, NULL, 'Choi San Wiliam 2', 'wiliam302@gmail.com', '+628976532123', '2002-09-15', 'Ngaglik', 'public/uploads/1776515701_6852_ktp.pdf', 'public/uploads/1776515701_7069_ijazah.pdf', 'public/uploads/1776515701_9780_foto_siswa.jpeg', 'public/uploads/1776515701_9912_bukti_pendaftaran.pdf', 'public/uploads/1776515701_4299_surat_pernyataan.pdf', 'REG-2026-0013', 'HCTS-PR49', NULL, 'disetujui', 'valid', '2026-04-18 19:36:00', 'pending', 'choi13.26@hcts.ac.id'),
(14, 108, NULL, 'Choi San Wiliam 3', 'wiliam302@gmail.com', '+628976532123', '2002-09-15', 'Ngaglik, Sleman, DIY', 'public/uploads/1776529671_6722_ktp.pdf', 'public/uploads/1776529671_2485_ijazah.pdf', 'public/uploads/1776529671_8878_foto_siswa.jpeg', 'public/uploads/1776529671_7554_bukti_pendaftaran.pdf', 'public/uploads/1776529671_5076_surat_pernyataan.pdf', 'REG-2026-0014', 'HCTS-SZ53', NULL, 'disetujui', 'valid', '2026-04-18 23:32:00', 'pending', 'choi14.26@hcts.ac.id'),
(15, 110, NULL, 'GEVINTA APRILIA PUTRI', 'gevintap@gmail.com', '0181919191', '2026-04-01', 'Jl. Nakula 6 Blok C No. 517-B, Jakasetia', 'public/uploads/1776834082_6563_ktp.pdf', 'public/uploads/1776834082_6147_ijazah.pdf', 'public/uploads/1776834082_1988_foto_siswa.jpeg', 'public/uploads/1776834082_9763_bukti_pendaftaran.pdf', 'public/uploads/1776834082_2042_surat_pernyataan.pdf', 'REG-2026-0015', 'HCTS-NI21', NULL, 'menunggu_pimpinan', 'valid', '2026-04-22 12:10:00', 'pending', NULL),
(16, 111, 3, 'GEVINTA APRILIA PUTRI', 'gevintap@gmail.com', '0817150043', '2026-04-08', 'Jl. Nakula 6 Blok C No. 517-B, Jakasetia', 'public/uploads/1776835813_6185_ktp.pdf', 'public/uploads/1776835813_6394_ijazah.pdf', 'public/uploads/1776835813_7085_foto_siswa.jpeg', 'public/uploads/1776835813_2225_bukti_pendaftaran.pdf', 'public/uploads/1776835813_7451_surat_pernyataan.pdf', 'REG-2026-0016', 'HCTS-QX99', '2026-04-24 07:35:08', 'disetujui', 'valid', '2026-04-22 12:34:00', 'pending', 'gevinta16.26@hcts.ac.id'),
(17, 112, 2, 'Nafis Azzahra', 'nafis@gmail.com', '081171500431', '2006-03-15', 'Jl. Tambakbayan V No. 123, Caturtunggal, Sleman', 'public/uploads/1776842667_2460_ktp.pdf', 'public/uploads/1776842667_4401_ijazah.pdf', 'public/uploads/1776842667_2958_foto_siswa.jpeg', 'public/uploads/1776842667_3971_bukti_pendaftaran.pdf', 'public/uploads/1776842667_4584_surat_pernyataan.pdf', 'REG-2026-0017', 'HCTS-QB53', '2026-04-24 09:26:45', 'disetujui', 'valid', '2026-04-23 14:25:00', 'pending', 'nafis17.26@hcts.ac.id');

-- --------------------------------------------------------

--
-- Table structure for table `pengajar`
--

CREATE TABLE `pengajar` (
  `id_pengajar` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip_pengajar` varchar(255) NOT NULL,
  `nama_pengajar` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `noWA` varchar(20) NOT NULL,
  `spesialisasi` varchar(255) NOT NULL,
  `email_pengajar` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `update_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajar`
--

INSERT INTO `pengajar` (`id_pengajar`, `id_user`, `nip_pengajar`, `nama_pengajar`, `tanggal_lahir`, `alamat`, `noWA`, `spesialisasi`, `email_pengajar`, `password`, `profil`, `update_at`) VALUES
(1, 113, '124240106', 'Azahra', '2006-10-17', 'kwarasan', '098564737', 'Culinary', 'azahra48@hcts.ac.id', '$2y$10$2HM3hC4dVFNV7LBCLwJxxuk6dRUOeQqI1FKWuocCSzAFBOI5nai.m', '', '2026-04-23'),
(2, 114, '12345678', 'Dosen Test', '1985-01-01', 'Gedung HCTS Lt. 2', '081234567890', 'Culinary Arts', 'test.instruktur@hcts.ac.id', '$2y$10$Vg5sgSpFyEFXGMM1kwN46eZh.nBHiHIOEU7laNFXHRuHGoa.pPnx.', '', '2026-04-23');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_remedial`
--

CREATE TABLE `pengajuan_remedial` (
  `id_remedial` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_evaluasi` int(11) NOT NULL,
  `mapel_kode` varchar(10) NOT NULL,
  `alasan` text DEFAULT NULL,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_remedial` enum('pending','disetujui','selesai','ditolak') DEFAULT 'pending',
  `nilai_lama` int(11) DEFAULT NULL,
  `nilai_baru` int(11) DEFAULT NULL,
  `jadwal_remedial` datetime DEFAULT NULL,
  `catatan_pengajar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_remedial`
--

INSERT INTO `pengajuan_remedial` (`id_remedial`, `id_siswa`, `id_evaluasi`, `mapel_kode`, `alasan`, `tanggal_pengajuan`, `status_remedial`, `nilai_lama`, `nilai_baru`, `jadwal_remedial`, `catatan_pengajar`) VALUES
(1, 10, 2, 'DUI1', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 80, NULL, 'belajar yg bener makannya'),
(2, 10, 2, 'DUI2', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 90, NULL, ''),
(3, 10, 2, 'DUI3', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 90, NULL, ''),
(4, 10, 2, 'DUI4', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 90, NULL, ''),
(5, 10, 2, 'DUI5', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 100, NULL, ''),
(6, 10, 2, 'DUI7', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 90, NULL, ''),
(7, 10, 2, 'DUI8', 'MALAS BELAJAR', '2026-04-22 09:08:10', 'selesai', 70, 95, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_announcement` int(11) NOT NULL,
  `target_role` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','danger','success') DEFAULT 'info',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id_announcement`, `target_role`, `title`, `message`, `type`, `created_at`) VALUES
(1, 1, 'Pengumpulan Laporan Magang', 'Batas akhir pengumpulan laporan magang tahap 1 adalah tanggal 10 Mei 2026.', 'warning', '2026-04-23 15:17:53'),
(2, 1, 'Program Taiwan Dibuka', 'Selamat! Anda berhak mendaftar program internship internasional ke Taiwan jika sudah lulus.', 'info', '2026-04-23 15:17:53'),
(3, 2, 'Input Nilai Akhir Semester', 'Mohon segera melengkapi input nilai untuk kelas A1 paling lambat akhir minggu ini.', 'danger', '2026-04-23 15:17:53'),
(4, 5, 'Review Sertifikat Siswa', 'Ada 12 pengajuan sertifikat baru yang perlu diverifikasi.', 'warning', '2026-04-23 15:17:53'),
(5, 5, 'Update Sistem Selesai', 'Sistem SIMAK HCTS telah diperbarui ke versi 2.1. Silakan cek log perubahan.', 'success', '2026-04-23 15:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `pimpinan`
--

CREATE TABLE `pimpinan` (
  `id_pimpinan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip_pimpinan` varchar(255) NOT NULL,
  `nama_pimpinan` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `noWA` varchar(20) NOT NULL,
  `email_pimpinan` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profil` varchar(255) NOT NULL,
  `update_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id_program` int(11) NOT NULL,
  `nama_program` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id_program`, `nama_program`) VALUES
(1, 'Culinary Arts & Kitchen Operations'),
(2, 'Accommodation & Room Division Management'),
(3, 'Beverage Management & Mixology\r\n'),
(4, 'Laundry & Linen Management\r\n'),
(5, 'Food & Beverage Service Management ');

-- --------------------------------------------------------

--
-- Table structure for table `program_taiwan`
--

CREATE TABLE `program_taiwan` (
  `id_taiwan` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `status` enum('berminat','diajukan_mitra','lolos','ditolak') DEFAULT 'berminat',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_taiwan`
--

INSERT INTO `program_taiwan` (`id_taiwan`, `id_siswa`, `status`, `create_at`) VALUES
(1, 10, 'lolos', '2026-04-23 15:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_role` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_role`, `name`) VALUES
(1, 'siswa'),
(2, 'calon_siswa'),
(3, 'pengajar'),
(4, 'pimpinan'),
(5, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id_semester` int(11) NOT NULL,
  `nama_semester` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id_semester`, `nama_semester`) VALUES
(1, 'Ganjil 2024/2025'),
(2, 'Genap 2024/2025');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_pendaftaran` int(11) NOT NULL,
  `nim_siswa` varchar(50) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `id_program` int(11) NOT NULL,
  `email_belajar` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status_pembayaran` varchar(50) DEFAULT 'belum_bayar',
  `dp_notified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `id_user`, `id_pendaftaran`, `nim_siswa`, `nama_lengkap`, `id_program`, `email_belajar`, `password`, `status_pembayaran`, `dp_notified`) VALUES
(9, 111, 16, 'HC-2026-0016', 'GEVINTA APRILIA PUTRI', 3, 'gevinta16.26@hcts.ac.id', 'HCTS2026', 'lunas_dp', 0),
(10, 112, 17, 'HC-2026-0017', 'Nafis Azzahra', 2, 'nafis17.26@hcts.ac.id', 'HCTS2026', 'lunas_dp', 1);

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id_template` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `fields` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id_template`, `code`, `name`, `title`, `subtitle`, `content`, `fields`, `updated_at`) VALUES
(1, 'surat_pernyataan', 'Surat Pernyataan Pendaftaran', 'SURAT PERNYATAAN KEBENARAN DOKUMEN DAN PENERIMAAN KEPUTUSAN', 'Pendaftaran Hospital and Campus Tracking System (HCTS)', 'Saya yang bertanda tangan di bawah ini:\r\n\r\n[Daftar_Field]\r\n\r\nDengan ini menyatakan bahwa:\r\n\r\n1. Seluruh berkas, data, dan dokumen yang saya unggah melalui sistem HCTS adalah benar, akurat, dan sesuai dengan dokumen aslinya.\r\n2. Saya tidak melakukan pemalsuan data dalam bentuk apa pun dalam proses pendaftaran ini.\r\n3. Saya mengerti dan menyetujui bahwa data ini akan digunakan untuk keperluan tracking dan manajemen administrasi pada sistem HCTS.\r\n4. Apabila di kemudian hari ditemukan ketidaksesuaian atau pemalsuan pada dokumen yang saya berikan, saya bersedia menerima segala bentuk tindak lanjut dan sanksi sesuai dengan ketentuan hukum atau regulasi yang berlaku di lingkungan universitas/rumah sakit.\r\n5. Saya menerima sepenuhnya keputusan hasil verifikasi sistem tanpa tuntutan dalam bentuk apa pun.\r\n\r\nDemikian surat pernyataan ini saya buat dengan sadar dan tanpa paksaan dari pihak mana pun untuk digunakan sebagaimana mestinya.', '[{\"label\":\"Nama Lengkap\",\"placeholder\":\"[Nama Lengkap Pengguna]\"},{\"label\":\"NIM\\/NIDN\\/ID Pengguna\",\"placeholder\":\"[Nomor Identitas]\"},{\"label\":\"Instansi\\/Program Studi\",\"placeholder\":\"[Nama Instansi\\/Prodi]\"},{\"label\":\"Alamat\\/Kontak\",\"placeholder\":\"[Nomor Telepon\\/Email]\"}]', '2026-04-23 15:54:01'),
(2, 'laporan_harian', 'Laporan Kegiatan Harian', 'LAPORAN KEGIATAN HARIAN MAGANG', 'Hospital and Campus Tracking System (HCTS)', 'Berikut adalah laporan kegiatan harian selama melaksanakan program magang:', '[{\"label\":\"Nama\",\"placeholder\":\"[Nama Siswa]\"},{\"label\":\"Program\",\"placeholder\":\"[Program Studi]\"},{\"label\":\"Tempat Magang\",\"placeholder\":\"[Nama Perusahaan/Hotel]\"}]', '2026-04-23 15:42:35');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `create_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `role_id`, `is_active`, `create_at`) VALUES
(1, '124240111', 'gevintap@gmail.com', '$2y$10$mNDTV1uBP5U0bsumPK381.smviHzF2DXsUbLeTeMpDDXHj22DgDZy', 1, 0, '2026-03-04'),
(2, 'admin_123', 'emailAdmin@admin.com', '$2y$10$cWUXpEAI1vl9.Ngfafnw8uC2Q6vvviCBwbV06/MP5o5CRnSQ8Wb.2', 5, 1, '2026-03-04'),
(3, 'pimpinan', 'pimpinan@hcts.com', '$2y$10$qALxYQzqcmNXF.I2.kpbXeEKfED3h2J4lwI.w.w5wEfysO9ftDnZ.', 4, 1, '2026-03-04'),
(102, '124240114', 'gevintap@gmail.com', '$2y$10$biQneMzzR6TXeXwknRJCMedoUclmPhQiSfHnbbIB6Dy2hUFmPXsBW', 3, 1, '2026-02-26'),
(105, 'REG-2026-0011', 'wiliam302@gmail.com', 'HCTS-XT01', 2, 1, '2026-04-18'),
(106, 'Choi San Wiliam', 'choi12.26@hcts.ac.id', '$2y$10$VnkEe0tHCLcx98PQ/KhTfukpNNtBRNGTYKg8oGsbE0pkXAYcNsQCW', 1, 1, '2026-04-18'),
(107, 'REG-2026-0013', 'choi13.26@hcts.ac.id', '$2y$10$nzYDeuJovjFpbD1W3FT.Ae/jKOZ2PZor4LPhaQR/ugCkB6T2sGLJy', 1, 1, '2026-04-18'),
(108, 'HC-2026-0014', 'choi14.26@hcts.ac.id', '$2y$10$uziAg0zV0GSyhFSO.B1Jy.sw2h3/K1KJaTKl2AshtLAWiHKFU8zsK', 1, 1, '2026-04-18'),
(109, 'Sri Rahayu', 'Sri12345505050@hcts.ac.id', '$2y$10$wm.OAFiwPFF3h5GME9z5MOXCA4wKRAo990zCDSjO/S60k9.QWd9FS', 3, 1, '2026-04-18'),
(110, 'REG-2026-0015', 'gevintap@gmail.com', 'HCTS-NI21', 2, 1, '2026-04-22'),
(111, 'HC-2026-0016', 'gevinta16.26@hcts.ac.id', '$2y$10$UCQ0G2uQ4uMSjzFQJLb.COadpN.FgMK/qq8C0b6JU1zgyRqi7GvFS', 1, 1, '2026-04-22'),
(112, 'HC-2026-0017', 'nafis17.26@hcts.ac.id', '$2y$10$cr75g2UoOUIB0ABUPS22GOrPxs6a0blbsiNVqrTTivBptt5jHLTMS', 1, 1, '2026-04-22'),
(113, '124240106', 'azahra48@hcts.ac.id', '$2y$10$2uwvHEHodJbBDHDrDP4oieduUpDxLiNUaBtwP4Ri3gEUZ9Szk3f9y', 3, 1, '2026-04-23'),
(114, '12345678', 'test.instruktur@hcts.ac.id', '$2y$10$Vg5sgSpFyEFXGMM1kwN46eZh.nBHiHIOEU7laNFXHRuHGoa.pPnx.', 3, 1, '2026-04-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `fk_user_admin` (`id_user`);

--
-- Indexes for table `biaya_pendidikan`
--
ALTER TABLE `biaya_pendidikan`
  ADD PRIMARY KEY (`id_bp`);

--
-- Indexes for table `evaluasi`
--
ALTER TABLE `evaluasi`
  ADD PRIMARY KEY (`id_evaluasi`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `fk_kelas_program` (`Id_program`);

--
-- Indexes for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id_ks`),
  ADD KEY `fk_kelas_ks` (`id_kelas`),
  ADD KEY `fk_siswa_ks` (`Id_siswa`);

--
-- Indexes for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id_kurikulum`),
  ADD KEY `fk_kurikulum_program` (`id_program`),
  ADD KEY `fk_kurikulum_semester` (`Id_semester`),
  ADD KEY `fk_kurikulum_mapel` (`id_mapel`);

--
-- Indexes for table `laporan_harian`
--
ALTER TABLE `laporan_harian`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_magang` (`id_magang`);

--
-- Indexes for table `magang`
--
ALTER TABLE `magang`
  ADD PRIMARY KEY (`id_magang`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `nilai_magang`
--
ALTER TABLE `nilai_magang`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_magang` (`id_magang`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `fk_pembayaran_siswa` (`id_siswa`),
  ADD KEY `fk_pembayaran_bp` (`id_biaya_pendidikan`);

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD KEY `fk_pendaftaran_user` (`id_user`),
  ADD KEY `fk_pendaftaran_program` (`id_program`);

--
-- Indexes for table `pengajar`
--
ALTER TABLE `pengajar`
  ADD PRIMARY KEY (`id_pengajar`),
  ADD KEY `fk_pengajar_user` (`id_user`);

--
-- Indexes for table `pengajuan_remedial`
--
ALTER TABLE `pengajuan_remedial`
  ADD PRIMARY KEY (`id_remedial`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_announcement`);

--
-- Indexes for table `pimpinan`
--
ALTER TABLE `pimpinan`
  ADD PRIMARY KEY (`id_pimpinan`),
  ADD KEY `fk_user_pimpinan` (`id_user`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id_program`);

--
-- Indexes for table `program_taiwan`
--
ALTER TABLE `program_taiwan`
  ADD PRIMARY KEY (`id_taiwan`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id_semester`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD KEY `fk_siswa_user` (`id_user`),
  ADD KEY `fk_siswa_pendaftaran` (`id_pendaftaran`),
  ADD KEY `fk_program_siswa` (`id_program`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id_template`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biaya_pendidikan`
--
ALTER TABLE `biaya_pendidikan`
  MODIFY `id_bp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluasi`
--
ALTER TABLE `evaluasi`
  MODIFY `id_evaluasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id_ks` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `id_kurikulum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `laporan_harian`
--
ALTER TABLE `laporan_harian`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `magang`
--
ALTER TABLE `magang`
  MODIFY `id_magang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `nilai_magang`
--
ALTER TABLE `nilai_magang`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pengajar`
--
ALTER TABLE `pengajar`
  MODIFY `id_pengajar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengajuan_remedial`
--
ALTER TABLE `pengajuan_remedial`
  MODIFY `id_remedial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_announcement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pimpinan`
--
ALTER TABLE `pimpinan`
  MODIFY `id_pimpinan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `id_program` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `program_taiwan`
--
ALTER TABLE `program_taiwan`
  MODIFY `id_taiwan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id_semester` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id_template` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_user_admin` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_program` FOREIGN KEY (`Id_program`) REFERENCES `program` (`id_program`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD CONSTRAINT `fk_kelas_ks` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_ks` FOREIGN KEY (`Id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD CONSTRAINT `fk_kurikulum_mapel` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kurikulum_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id_program`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kurikulum_semester` FOREIGN KEY (`Id_semester`) REFERENCES `semester` (`id_semester`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laporan_harian`
--
ALTER TABLE `laporan_harian`
  ADD CONSTRAINT `laporan_harian_ibfk_1` FOREIGN KEY (`id_magang`) REFERENCES `magang` (`id_magang`);

--
-- Constraints for table `magang`
--
ALTER TABLE `magang`
  ADD CONSTRAINT `magang_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Constraints for table `nilai_magang`
--
ALTER TABLE `nilai_magang`
  ADD CONSTRAINT `nilai_magang_ibfk_1` FOREIGN KEY (`id_magang`) REFERENCES `magang` (`id_magang`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_bp` FOREIGN KEY (`id_biaya_pendidikan`) REFERENCES `biaya_pendidikan` (`id_bp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `fk_pendaftaran_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id_program`),
  ADD CONSTRAINT `fk_pendaftaran_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengajar`
--
ALTER TABLE `pengajar`
  ADD CONSTRAINT `fk_pengajar_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pimpinan`
--
ALTER TABLE `pimpinan`
  ADD CONSTRAINT `fk_user_pimpinan` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_program_siswa` FOREIGN KEY (`id_program`) REFERENCES `program` (`id_program`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_pendaftaran` FOREIGN KEY (`id_pendaftaran`) REFERENCES `pendaftaran` (`id_pendaftaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_program` FOREIGN KEY (`id_program`) REFERENCES `program` (`id_program`),
  ADD CONSTRAINT `fk_siswa_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
