-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 20, 2026 at 12:47 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_poin_pelanggaran_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `kode_guru` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_pengguna` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','bk','guru','manajemen') COLLATE utf8mb4_general_ci DEFAULT 'guru',
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(70) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aktif` enum('Y','N') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telp` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`kode_guru`, `nama_pengguna`, `role`, `username`, `password`, `aktif`, `jabatan`, `telp`) VALUES
('0021.001', 'Drs. I Gusti Made Murjana, M.Pd.', 'admin', 'made', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Kepala Sekolah', '081805474228'),
('0021.002', 'I Nyoman Sucana, M.Kom.', 'manajemen', 'sucana', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Waka Kurikulum', '08123650940'),
('0021.003', 'Bagus Putu Eka Wijaya, S.Kom.', 'manajemen', 'guseka', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Waka Kesiswaan', '082146503026'),
('0021.004', 'Dewa Made Indra Suarmika, S.Kom.', 'manajemen', 'indra', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Waka Sarana Prasarana', '082237442222'),
('0021.005', 'I Gede Pradipta Adi Nugraha, M.Kom.', 'manajemen', 'dipta', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Waka Humas', '087861863842'),
('0021.006', 'I Gede Agung Abdi Prasetya, S.Ak.', 'guru', 'abdi', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Komka AN', '082247033088'),
('0021.007', 'A.A Gede Putra Dwi Artajaya, S.Si., M.Kom.', 'guru', 'artajaya', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Komka RPL', '082247033044'),
('0021.008', 'I Komang Arta Wijaya, M.Kom.', 'guru', 'arta', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Komka DKV', '082247033030'),
('0021.009', 'I Made Sastrawan Adi Putra, S.Kom.', 'guru', 'sastra', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Komka TKJ', '087837454455'),
('0021.010', 'Ni Wayan Sri Arini, ST., M.Kom.', 'guru', 'sriarini', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081338512340'),
('0021.011', 'I Putu Urip Sutresna Mantra, S.Kom.', 'guru', 'urip', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Ketua Lab', '087862656412'),
('0021.012', 'Ni Wayan Rumasni, S.Pd., M.Pd.', 'guru', 'rum', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081338638999'),
('0021.013', 'I Wayan Agus Wiranata, S.Pd.', 'guru', 'wiranata', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082291355411'),
('0021.014', 'Nyoman Hendra Adi Wijaya, S.Pd., M.Kom.', 'guru', 'hendra', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '085738216181'),
('0021.015', 'Dra. Ni Made Ayu Adnyani', 'guru', 'ayuadnyani', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081238437877'),
('0021.016', 'Abdul', 'manajemen', 'abdull', '$2y$10$YRLuJjYGKjf2/wFFexfkdumgD5uzpGf/I8ssV6cKPHK.HKhEdbqiG', 'Y', 'Waka Kurikulum', '08555111111'),
('0021.017', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '085738235218'),
('0021.018', 'Putu Yenny Suryantari, S.Pd.', 'guru', 'yenny', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081353285810'),
('0021.019', 'I Gusti Ayu Sri Erna Dewi, SE.', 'guru', 'erna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081220692219'),
('0021.020', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081338401856'),
('0021.021', 'Ida Ayu Indra Pratiwi, S.Sn.', 'guru', 'dayuindra', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081239588346'),
('0021.022', 'I Wayan Sudarsa, S.Kom.', 'guru', 'sudarsa', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081237896743'),
('0021.023', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082247033088'),
('0021.024', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082247033030'),
('0021.025', 'I Putu Dedy Karsana, S.Pd.', 'guru', 'dedy', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '08563769773'),
('0021.026', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081238437877'),
('0021.027', 'I Putu Agus Sujana Adi Putra, S.Pd.', 'guru', 'gussujana', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081237896743'),
('0021.028', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081933019479'),
('0021.029', 'Drs. I Gusti Putu Tirta Yasa, M.Pd.', 'guru', 'tirta', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081338401856'),
('0021.030', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081220692219'),
('0021.031', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081353285810'),
('0021.032', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081338512340'),
('0021.033', 'Ni Putu Anita Prahandari, S.Pd.', 'guru', 'anita', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082146167817'),
('0021.034', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081338638999'),
('0021.035', 'I Kadek Yogi Mayudana, M.Pd.', 'guru', 'yogi', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081933019479'),
('0021.036', 'Luh Putu Ayu Desiani, S.Kom., MM.', 'guru', 'desi', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081805474228'),
('0021.037', 'I Kadek Puji Aksama, S.Pd.', 'guru', 'aksama', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081933106676'),
('0021.038', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082146503026'),
('0021.039', 'Ida Gusti Ayu Rinjani, M.Pd.', 'bk', 'anjani', '$2y$10$L/11/fwOBwX3FJlyigeu.ehiLcZ1.cXX/ZugdZGP.fDSgHqWd25aK', 'Y', 'Guru BK XII', '081999976038'),
('0021.040', 'Ainul Mubsiroh, S.Pd.I, M.Pd.', 'guru', 'ain', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082247033044'),
('0021.041', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '087862656412'),
('0021.042', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082237442222'),
('0021.043', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '085953912558'),
('0021.044', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '08563769773'),
('0021.045', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '087863112233'),
('0021.046', 'Masri Kagatanaribe, M.Pd.', 'guru', 'masri', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '085739990443'),
('0021.047', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '087861863842'),
('0021.048', 'Luh Putu Trisma Prabawati, S.Kom.', 'guru', 'trisma', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '083114537674'),
('0021.049', 'Kadek Diah Kertiana, S.Kom.', 'guru', 'diah', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082247033484'),
('0021.050', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '08123650940'),
('0021.051', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '083114537674'),
('0021.052', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '085738235218'),
('0021.053', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082146167817'),
('0021.054', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081239588346'),
('0021.055', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081338401856'),
('0021.056', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '085738216181'),
('0021.057', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '083834969500'),
('0021.058', 'I Wayan Arik Sukariawan, S.Kom.', 'guru', 'arik', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082247033088'),
('0021.059', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082247033030'),
('0021.060', 'I Putu Eka Mahendra, S.Kom.', 'guru', 'eka', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '085739990443'),
('0021.061', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081238437877'),
('0021.062', 'Bella Cintya Devi, S.Kom.', 'guru', 'bella', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081999022333'),
('0021.063', 'Darsusanto, S.Ag.', 'guru', 'darsusanto', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081933019479'),
('0021.064', 'I Gusti Made Gunawan, S.Pd.', 'guru', 'gun', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '087837454455'),
('0021.065', 'Ni Wayan Sriyaningsih, S.Sos.', 'guru', 'anik', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081220692219'),
('0021.066', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081353285810'),
('0021.067', 'Nengah Dwi Rahayu, SE', 'guru', 'dwirahayu', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081338512340'),
('0021.068', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081933106676'),
('0021.069', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081338638999'),
('0021.070', 'Ni Putu Tirta Purnama Dewi, S.Pd', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081999022333'),
('0021.071', 'Ni Nyoman Damayanti, S.Pd., M.Pd.', 'guru', 'kotika', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081805474228'),
('0021.072', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '083117769593'),
('0021.073', 'Ni Wayan Lina Valentine, S.Pd.', 'guru', 'lina', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '08970147321'),
('0021.074', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '081999976038'),
('0021.075', 'Nama_Pengguna', 'guru', 'pengguna', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'N', 'Guru Mapel', '082247033044'),
('0021.076', 'Triono Doni Wijaya, S.Kom.', 'guru', 'doni', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '087863112233'),
('0021.077', 'Nuri Sutiyaningsih, M.Kom.', 'guru', 'nuri', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082291355411'),
('0021.078', 'Kadek Arie Wira Kusuma, S.Kom.', 'guru', 'ariewira', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082247033484'),
('0021.079', 'Ni Putu Linda Agustini, S.Pd.', 'guru', 'linda', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '085738235218'),
('0021.080', 'Ida Bagus Angga Baskara, S.Pd.', 'guru', 'baskara', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '087863112233'),
('0021.081', 'Tjok Istri Agung Rai Sintha Devi, S.Pd.', 'guru', 'sintha', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '0895347674833'),
('0021.082', 'Ida Bagus Maha Indra Prasada, S.Kom.', 'guru', 'mahaindra', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '083117769593'),
('0021.083', 'Ida Ayu Dewi Paramita, S.Pd.', 'guru', 'dayumita', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '0895375837712'),
('0021.084', 'Ni Luh Rosa Diarsanthi, S.Pd.', 'guru', 'rosa', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '083834969500'),
('0021.085', 'Yustina Mariana Odang, S.Pd.', 'guru', 'yustina', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '08123650940'),
('0021.086', 'Ida Ayu Amrita Pancajania, SE.', 'guru', 'amrita', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '083114537674'),
('0021.087', 'A.A Gde Radika Mahaprasta Putra', 'guru', 'radika', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '085738235218'),
('0021.088', 'Ni Ketut Supartini, SS.', 'guru', 'tini', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '082146167817'),
('0021.089', 'M. Agus Wahyudi, S.Pd.', 'guru', 'yudi', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081239588346'),
('0021.090', 'I Made Pranayama, S.Pd.', 'guru', 'pranayama', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081338401856'),
('0021.091', 'Ni Kadek Chandra Putri Irani, S.Pd., M.Pd.', 'guru', 'chandra', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '085953912558'),
('0021.092', 'I Dewa Ayu Setiyawati, S.Pd.', 'guru', 'dayu', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '083834969500'),
('0021.093', 'Finsensius Ratuaki, M.Pd.', 'bk', 'finsen', '$2y$10$L/11/fwOBwX3FJlyigeu.ehiLcZ1.cXX/ZugdZGP.fDSgHqWd25aK', 'Y', 'Guru BK X', '082247033088'),
('0021.094', 'Ni Putu Chintya Pradnya Suari, S.Pd.', 'bk', 'chintya', '$2y$10$L/11/fwOBwX3FJlyigeu.ehiLcZ1.cXX/ZugdZGP.fDSgHqWd25aK', 'Y', 'Guru BK XI', '082247033030'),
('0021.095', 'Adventina Puspita', 'guru', 'puspita', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '0895347674833'),
('0021.096', 'Aprianus Anjelius Foutnine, S.Fil', 'guru', 'anjel', '$2y$10$qdT68nIqcmlV.AwvGFNX0eqMASuBMFMu7AJq0a.CAoNNQWT8qfxca', 'Y', 'Guru Mapel', '081238437877'),
('0021.097', 'Joshan Arya', 'admin', 'joshan', '$2y$10$dezm4WwwZYCja9GEz5ppcuLx4Xt0fO0.eS7zgZu1DBaPb0XKX3XQC', 'Y', 'Kepala Sekolah', '085174060031'),
('0021.098', 'Joshan Arya2', 'bk', 'joshann', '$2y$10$w0rRxDWkqWAMGBT2QNFEq.CgiWg3D1.BP2ExKdT2kZBmPvAHRE2OS', 'Y', 'Guru BK XII', '0812313413141');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pelanggaran`
--

CREATE TABLE `jenis_pelanggaran` (
  `id_jenis_pelanggaran` int NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `poin` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_pelanggaran`
--

INSERT INTO `jenis_pelanggaran` (`id_jenis_pelanggaran`, `jenis`, `poin`) VALUES
(1, 'Seragam Sekolah', 2),
(2, 'Kehadiran Di Sekolah', 5),
(3, 'Proses Belajar Mengajar', 6),
(4, 'Pelanggaran Norma Norma', 9),
(5, 'Pelanggaran Berat', 10),
(6, 'Kesopanan Berkendara', 8),
(7, 'Upacara Bendera', 4),
(8, 'Gay', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL,
  `id_tingkat` int DEFAULT NULL,
  `id_program_keahlian` int DEFAULT NULL,
  `rombel` int DEFAULT NULL,
  `kode_guru` char(8) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `id_tingkat`, `id_program_keahlian`, `rombel`, `kode_guru`) VALUES
(1, 3, 1, 1, '0021.036'),
(2, 3, 1, 2, '0021.082'),
(3, 3, 1, 3, '0021.003'),
(4, 3, 1, 4, '0021.039'),
(5, 3, 1, 5, '0021.007'),
(6, 3, 3, 1, '0021.011'),
(7, 3, 2, 1, '0021.004'),
(8, 3, 2, 2, '0021.091'),
(9, 3, 2, 3, '0021.025'),
(10, 3, 2, 4, '0021.076'),
(11, 3, 4, 1, '0021.060'),
(12, 2, 1, 1, '0021.005'),
(13, 2, 1, 2, '0021.077'),
(14, 2, 1, 3, '0021.078'),
(15, 2, 1, 4, '0021.002'),
(16, 2, 1, 5, '0021.048'),
(17, 2, 3, 1, '0021.079'),
(18, 2, 3, 2, '0021.033'),
(19, 2, 2, 1, '0021.021'),
(20, 2, 2, 2, '0021.029'),
(21, 2, 2, 3, '0021.014'),
(22, 2, 2, 4, '0021.084'),
(23, 2, 4, 1, '0021.006'),
(24, 1, 1, 1, '0021.008'),
(25, 1, 1, 2, '0021.081'),
(26, 1, 1, 3, '0021.015'),
(27, 1, 1, 4, '0021.022'),
(28, 1, 1, 5, '0021.035'),
(29, 1, 3, 1, '0021.009'),
(30, 1, 5, 1, '0021.019'),
(31, 1, 5, 2, '0021.018'),
(32, 1, 2, 1, '0021.010'),
(33, 1, 2, 2, '0021.037'),
(34, 1, 2, 3, '0021.012'),
(35, 1, 4, 1, '0021.062');

-- --------------------------------------------------------

--
-- Table structure for table `ortu_wali`
--

CREATE TABLE `ortu_wali` (
  `id_ortu_wali` int NOT NULL,
  `ayah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ibu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wali` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir_ayah` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir_ibu` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir_wali` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir_ayah` date DEFAULT NULL,
  `tanggal_lahir_ibu` date DEFAULT NULL,
  `tanggal_lahir_wali` date DEFAULT NULL,
  `pekerjaan_ayah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_wali` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp_ayah` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp_ibu` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_telp_wali` varchar(16) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_ayah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_ibu` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_wali` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ortu_wali`
--

INSERT INTO `ortu_wali` (`id_ortu_wali`, `ayah`, `ibu`, `wali`, `tempat_lahir_ayah`, `tempat_lahir_ibu`, `tempat_lahir_wali`, `tanggal_lahir_ayah`, `tanggal_lahir_ibu`, `tanggal_lahir_wali`, `pekerjaan_ayah`, `pekerjaan_ibu`, `pekerjaan_wali`, `no_telp_ayah`, `no_telp_ibu`, `no_telp_wali`, `alamat_ayah`, `alamat_ibu`, `alamat_wali`) VALUES
(1, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', ''),
(2, 'Lukman Halimah', 'Mega Rahma', NULL, 'Denpasar', 'Denpasar', NULL, '1980-05-10', '1982-08-15', NULL, 'Penjahit', 'Penjahit', NULL, '628621323300', '628621323300', NULL, 'Jalan Antasura, Perumahan Elit', 'Jalan Antasura, Perumahan Elit', NULL),
(3, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', ''),
(4, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', ''),
(11, '', 'Kepo', '', NULL, 'Denpasar', NULL, NULL, '1985-01-01', NULL, '', 'Boss', '', '', '1235551223', '', '', 'Kepo', '');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggaran_siswa`
--

CREATE TABLE `pelanggaran_siswa` (
  `id_pelanggaran_siswa` int NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `nis` char(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_jenis_pelanggaran` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggaran_siswa`
--

INSERT INTO `pelanggaran_siswa` (`id_pelanggaran_siswa`, `tanggal`, `nis`, `id_jenis_pelanggaran`, `keterangan`) VALUES
(1, '2026-01-02 09:26:32', '9124', 2, 'Terlambat masuk hari Senin'),
(2, '2026-01-02 09:26:35', '9125', 3, 'Tidak mengerjakan PR'),
(3, '2026-01-02 11:26:35', '9126', 3, 'Makan di kelas saat pelajaran Matematika'),
(4, '2026-03-17 00:00:00', '7012', 8, 'Ketahuan pacaran dengan cowo'),
(5, '2026-03-17 00:00:00', '9123', 2, 'Terlambah masuk kelas'),
(6, '2026-03-17 00:00:00', '9124', 8, 'Ketahuan Pacaran Dengan Gilang');

-- --------------------------------------------------------

--
-- Table structure for table `perjanjian_orang_tua`
--

CREATE TABLE `perjanjian_orang_tua` (
  `id_perjanjian_ortu` int(5) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_pelanggaran_siswa` int(5) DEFAULT NULL,
  `status` enum('Masih Proses','Selesai') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_dokumen` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tingkat` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ortu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pekerjaan_ortu` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_ortu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp_ortu` varchar(16) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perjanjian_orang_tua`
--

INSERT INTO `perjanjian_orang_tua` (`id_perjanjian_ortu`, `tanggal`, `id_pelanggaran_siswa`, `status`, `foto_dokumen`, `tingkat`, `nama_ortu`, `pekerjaan_ortu`, `alamat_ortu`, `no_telp_ortu`) VALUES
(1, '2026-01-02 09:26:31', 1, 'Masih Proses', NULL, 'X', 'Lukman Halimah', 'Penjahit', 'Jalan Antasura', '628621323300'),
(2, '2026-01-03 09:26:32', 2, 'Selesai', 'IMG_20260923.jpg', 'XI', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `perjanjian_siswa`
--

CREATE TABLE `perjanjian_siswa` (
  `id_perjanjian_siswa` int(5) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_pelanggaran_siswa` int(11) DEFAULT NULL,
  `status` enum('Masih Proses','Selesai') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_dokumen` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tingkat` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_ortu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pekerjaan_ortu` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_ortu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp_ortu` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `wali_kelas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `guru_bk` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `wakasek_kesiswaan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perjanjian_siswa`
--

INSERT INTO `perjanjian_siswa` (`id_perjanjian_siswa`, `tanggal`, `id_pelanggaran_siswa`, `status`, `foto_dokumen`, `tingkat`, `nama_ortu`, `pekerjaan_ortu`, `alamat_ortu`, `no_telp_ortu`, `wali_kelas`, `guru_bk`, `wakasek_kesiswaan`) VALUES
(1, '2026-01-02 09:26:31', 1, 'Masih Proses', NULL, 'X', '', '', '', '', '', '', ''),
(2, '2026-01-03 09:26:32', 2, 'Selesai', 'IMG_20260923.jpg', 'XI', '', '', '', '', '', '', ''),
(3, '2026-01-05 08:26:32', 3, 'Masih Proses', NULL, 'XI', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `profil_sekolah`
--

CREATE TABLE `profil_sekolah` (
  `id_profil_sekolah` int NOT NULL,
  `nama_sekolah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_sekolah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kota` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil_sekolah`
--

INSERT INTO `profil_sekolah` (`id_profil_sekolah`, `nama_sekolah`, `alamat_sekolah`, `kota`) VALUES
(1, 'SMKS TI Bali Global Denpasar', 'Kecamatan Denpasar Selatan, Kota Denpasar, Provinsi Bali', 'Denpasar');

-- --------------------------------------------------------

--
-- Table structure for table `program_keahlian`
--

CREATE TABLE `program_keahlian` (
  `id_program_keahlian` int NOT NULL,
  `program_keahlian` varchar(6) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_keahlian`
--

INSERT INTO `program_keahlian` (`id_program_keahlian`, `program_keahlian`, `deskripsi`) VALUES
(1, 'RPL', 'Rekayasa Perangkat Lunak'),
(2, 'DKV', 'Desain Komunikasi Visual'),
(3, 'TKJ', 'Teknik Komputer Jaringan'),
(4, 'AN', 'Animasi'),
(5, 'BD', 'Bisnis Digital');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_siswa` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` enum('Laki - Laki','Perempuan') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(70) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_siswa` enum('aktif','tidak_aktif','lulus','pindah') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_ortu_wali` int DEFAULT NULL,
  `id_kelas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `nama_siswa`, `jenis_kelamin`, `alamat`, `password`, `status_siswa`, `id_ortu_wali`, `id_kelas`) VALUES
('6712', 'Joshan', 'Laki - Laki', 'Kepo 123', '$2y$10$i8SKu3rq9zFk2eZsSd9Z8e0Az6pQ5JnutvAGrVWndinkBXY/47hO6', 'aktif', 11, 10),
('7012', 'Abdullah Musa', 'Laki - Laki', 'Jl Psr Paseban Bl B Lt3, Dki Jakarta', '$2y$10$.V6ZwUrLKYZ1hOOLJxylxOsblZuqOiPaHbZvi9aEWlVmRyl.aXgwG', 'pindah', 1, 24),
('8312', 'Juni Budi', 'Laki - Laki', 'Jl Gn Krakatau 387 A, Sumatera Utara', '$2y$10$.V6ZwUrLKYZ1hOOLJxylxOsblZuqOiPaHbZvi9aEWlVmRyl.aXgwG', 'lulus', 2, 2),
('9123', 'Wahyuni Yuliana', 'Perempuan', 'Jl MH Thamrin Kav 28-30 Plaza, Jakarta', '$2y$10$.V6ZwUrLKYZ1hOOLJxylxOsblZuqOiPaHbZvi9aEWlVmRyl.aXgwG', 'aktif', 4, 3),
('9124', 'Ryan', 'Laki - Laki', 'Psr Jatinegara Bl BKS/30, Dki Jakarta', '$2y$10$.V6ZwUrLKYZ1hOOLJxylxOsblZuqOiPaHbZvi9aEWlVmRyl.aXgwG', 'aktif', 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `surat_keluar`
--

CREATE TABLE `surat_keluar` (
  `id_surat_keluar` int(5) NOT NULL,
  `no_surat` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_surat` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_surat_pindah` int(5) DEFAULT NULL,
  `id_perjanjian_siswa` int(5) DEFAULT NULL,
  `id_perjanjian_ortu` int(5) DEFAULT NULL,
  `nis` char(5) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pembuatan_surat` date DEFAULT NULL,
  `id_profil_sekolah` int(2) DEFAULT NULL,
  `id_tahun_ajaran` int(3) DEFAULT NULL,
  `tingkat` varchar(3) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_pemanggilan` datetime DEFAULT NULL,
  `keperluan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_surat` enum('Belum dibuat','Sudah dicetak','Sudah ditandatangani/selesai') COLLATE utf8mb4_general_ci DEFAULT 'Sudah dicetak'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surat_keluar`
--

INSERT INTO `surat_keluar` (`id_surat_keluar`, `no_surat`, `jenis_surat`, `id_surat_pindah`, `id_perjanjian_siswa`, `id_perjanjian_ortu`, `nis`, `tanggal_pembuatan_surat`, `id_profil_sekolah`, `id_tahun_ajaran`, `tingkat`, `tanggal_pemanggilan`, `keperluan`, `status_surat`) VALUES
(1, '548/SMKTI/BG/XII/2025', 'Pindah Sekolah', 1, NULL, NULL, '7012', '2026-01-08', 1, 5, 'XI', NULL, NULL, 'Sudah dicetak'),
(2, '549/SMKTI/BG/XII/2025', 'Panggilan Orang Tua', NULL, NULL, NULL, '8312', '2026-01-08', 1, 5, 'XI', '2026-01-10 09:00:00', 'Pembinaan Kedisiplinan', 'Sudah dicetak'),
(3, '550/SMKTI/BG/I/2026', 'Pindah Sekolah', 2, NULL, NULL, '9123', '2026-01-10', 1, 5, 'X', NULL, NULL, 'Sudah dicetak');

-- --------------------------------------------------------

--
-- Table structure for table `surat_pindah`
--

CREATE TABLE `surat_pindah` (
  `id_surat_pindah` int(5) NOT NULL,
  `sekolah_tujuan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `alasan_pindah` text COLLATE utf8mb4_general_ci NOT NULL,
  `nama_ortu` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_ortu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surat_pindah`
--

INSERT INTO `surat_pindah` (`id_surat_pindah`, `sekolah_tujuan`, `alasan_pindah`, `nama_ortu`, `alamat_ortu`) VALUES
(1, 'SMAN 1 Surakarta', 'Mengikuti perpindahan dinas orang tua', 'Bpk. Ahmad', 'Surakarta'),
(2, 'SMKS Harapan', 'Mengikuti perpindahan dinas orang tua', 'Ibu Siti', 'Denpasar'),
(3, 'SMKN 2 Denpasar', 'Mengikuti perpindahan dinas orang tua', 'Bpk. Wayan', 'Badung');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id_tahun_ajaran` int NOT NULL,
  `tahun` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `aktif` enum('Y','N') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id_tahun_ajaran`, `tahun`, `aktif`) VALUES
(1, '2021/2022', 'N'),
(2, '2022/2023', 'N'),
(3, '2023/2024', 'N'),
(4, '2024/2025', 'N'),
(5, '2025/2026', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tingkat`
--

CREATE TABLE `tingkat` (
  `id_tingkat` int NOT NULL,
  `tingkat` varchar(6) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tingkat`
--

INSERT INTO `tingkat` (`id_tingkat`, `tingkat`) VALUES
(1, 'X'),
(2, 'XI'),
(3, 'XII');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`kode_guru`);

--
-- Indexes for table `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  ADD PRIMARY KEY (`id_jenis_pelanggaran`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_tingkat` (`id_tingkat`),
  ADD KEY `id_program_keahlian` (`id_program_keahlian`),
  ADD KEY `kode_guru` (`kode_guru`);

--
-- Indexes for table `ortu_wali`
--
ALTER TABLE `ortu_wali`
  ADD PRIMARY KEY (`id_ortu_wali`);

--
-- Indexes for table `pelanggaran_siswa`
--
ALTER TABLE `pelanggaran_siswa`
  ADD PRIMARY KEY (`id_pelanggaran_siswa`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_jenis_pelanggaran` (`id_jenis_pelanggaran`);

--
-- Indexes for table `perjanjian_orang_tua`
--
ALTER TABLE `perjanjian_orang_tua`
  ADD PRIMARY KEY (`id_perjanjian_ortu`),
  ADD KEY `id_pelanggaran_siswa` (`id_pelanggaran_siswa`);

--
-- Indexes for table `perjanjian_siswa`
--
ALTER TABLE `perjanjian_siswa`
  ADD PRIMARY KEY (`id_perjanjian_siswa`),
  ADD KEY `id_pelanggaran_siswa` (`id_pelanggaran_siswa`);

--
-- Indexes for table `profil_sekolah`
--
ALTER TABLE `profil_sekolah`
  ADD PRIMARY KEY (`id_profil_sekolah`);

--
-- Indexes for table `program_keahlian`
--
ALTER TABLE `program_keahlian`
  ADD PRIMARY KEY (`id_program_keahlian`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `siswa_ibfk_1` (`id_ortu_wali`);

--
-- Indexes for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD PRIMARY KEY (`id_surat_keluar`),
  ADD KEY `id_pindah_sekolah` (`id_surat_pindah`),
  ADD KEY `id_perjanjian_siswa` (`id_perjanjian_siswa`),
  ADD KEY `id_perjanjian_ortu` (`id_perjanjian_ortu`),
  ADD KEY `nis` (`nis`),
  ADD KEY `id_profil_sekolah` (`id_profil_sekolah`),
  ADD KEY `id_tahun_ajaran` (`id_tahun_ajaran`);

--
-- Indexes for table `surat_pindah`
--
ALTER TABLE `surat_pindah`
  ADD PRIMARY KEY (`id_surat_pindah`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id_tahun_ajaran`);

--
-- Indexes for table `tingkat`
--
ALTER TABLE `tingkat`
  ADD PRIMARY KEY (`id_tingkat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  MODIFY `id_jenis_pelanggaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `ortu_wali`
--
ALTER TABLE `ortu_wali`
  MODIFY `id_ortu_wali` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pelanggaran_siswa`
--
ALTER TABLE `pelanggaran_siswa`
  MODIFY `id_pelanggaran_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `perjanjian_orang_tua`
--
ALTER TABLE `perjanjian_orang_tua`
  MODIFY `id_perjanjian_ortu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `perjanjian_siswa`
--
ALTER TABLE `perjanjian_siswa`
  MODIFY `id_perjanjian_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profil_sekolah`
--
ALTER TABLE `profil_sekolah`
  MODIFY `id_profil_sekolah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_keahlian`
--
ALTER TABLE `program_keahlian`
  MODIFY `id_program_keahlian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  MODIFY `id_surat_keluar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `surat_pindah`
--
ALTER TABLE `surat_pindah`
  MODIFY `id_surat_pindah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id_tahun_ajaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tingkat`
--
ALTER TABLE `tingkat`
  MODIFY `id_tingkat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_tingkat`) REFERENCES `tingkat` (`id_tingkat`),
  ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`id_program_keahlian`) REFERENCES `program_keahlian` (`id_program_keahlian`),
  ADD CONSTRAINT `kelas_ibfk_3` FOREIGN KEY (`kode_guru`) REFERENCES `guru` (`kode_guru`);

--
-- Constraints for table `perjanjian_orang_tua`
--
ALTER TABLE `perjanjian_orang_tua`
  ADD CONSTRAINT `perjanjian_orang_tua_ibfk_1` FOREIGN KEY (`id_pelanggaran_siswa`) REFERENCES `pelanggaran_siswa` (`id_pelanggaran_siswa`);

--
-- Constraints for table `surat_keluar`
--
ALTER TABLE `surat_keluar`
  ADD CONSTRAINT `surat_keluar_ibfk_1` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`),
  ADD CONSTRAINT `surat_keluar_ibfk_2` FOREIGN KEY (`id_surat_pindah`) REFERENCES `surat_pindah` (`id_surat_pindah`),
  ADD CONSTRAINT `surat_keluar_ibfk_3` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tahun_ajaran` (`id_tahun_ajaran`),
  ADD CONSTRAINT `surat_keluar_ibfk_4` FOREIGN KEY (`id_perjanjian_siswa`) REFERENCES `perjanjian_siswa` (`id_perjanjian_siswa`),
  ADD CONSTRAINT `surat_keluar_ibfk_5` FOREIGN KEY (`id_perjanjian_ortu`) REFERENCES `perjanjian_orang_tua` (`id_perjanjian_ortu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
