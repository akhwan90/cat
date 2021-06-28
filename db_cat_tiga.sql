-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 28, 2021 at 11:11 PM
-- Server version: 5.7.19
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cat_tiga`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_admin`
--

CREATE TABLE `m_admin` (
  `id` int(6) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` enum('admin','guru','siswa') NOT NULL,
  `kon_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_admin`
--

INSERT INTO `m_admin` (`id`, `username`, `password`, `level`, `kon_id`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 0),
(28, '12090671', '047381507256cbcc7325c515565db1ba', 'siswa', 1),
(29, '12090672', '3a51b622fd32ed1a4a19d63aa06536a2', 'siswa', 2),
(30, '11090673', '42af3ba484d700ceac7708eaf8c51e01', 'siswa', 3),
(31, '11090674', '2c3e4855238fa4b45a1c6aa7f3d13867', 'siswa', 4),
(32, '12090675', '280aa5018e43e8e7a8c805a5597fc14f', 'siswa', 5),
(33, '11090676', '4a4967e9538bf349b057c38a5cffe7d8', 'siswa', 6),
(34, '12090677', 'baaf63831059795a0cedec6d705ad519', 'siswa', 7),
(35, '120000000', '287d338612470e92a608b94088d1387e', 'siswa', 9),
(36, '15090110', 'cef679e47d56eaf3e42b44e8fe08ddcb', 'siswa', 11),
(37, '15090111', '934eafdc2d0ca07bb03747b23548771a', 'siswa', 12),
(44, '1005', '2387337ba1e0b0249ba90f55b2ba2521', 'guru', 10);

-- --------------------------------------------------------

--
-- Table structure for table `m_guru`
--

CREATE TABLE `m_guru` (
  `id` int(6) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_guru`
--

INSERT INTO `m_guru` (`id`, `nip`, `nama`) VALUES
(10, '1005', 'Agus'),
(11, '1006', 'Budi'),
(12, '1007', 'Candra'),
(13, '1008', 'Dedi'),
(14, '1009', 'Eko'),
(15, '1010', 'Fajar'),
(16, '1011', 'Galuh'),
(17, '1012', 'Heri'),
(18, '1013', 'Indra'),
(19, '1014', 'Joko'),
(20, '1015', 'Kukuh'),
(21, '1016', 'Linda'),
(22, '1017', 'Michael'),
(23, '1018', 'Nawang'),
(24, '1019', 'Opan'),
(25, '1020', 'Putri'),
(26, '1021', 'Qisti'),
(27, '1022', 'Riris'),
(28, '1023', 'Sita');

--
-- Triggers `m_guru`
--
DELIMITER $$
CREATE TRIGGER `hapus_guru` AFTER DELETE ON `m_guru` FOR EACH ROW BEGIN
DELETE FROM m_soal WHERE m_soal.id_guru = OLD.id;
DELETE FROM m_admin WHERE m_admin.level = 'guru' AND m_admin.kon_id = OLD.id;
DELETE FROM tr_guru_mapel WHERE tr_guru_mapel.id_guru = OLD.id;
DELETE FROM tr_guru_tes WHERE tr_guru_tes.id_guru = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_mapel`
--

CREATE TABLE `m_mapel` (
  `id` int(6) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_mapel`
--

INSERT INTO `m_mapel` (`id`, `nama`) VALUES
(1, 'Bahasa Indonesia'),
(2, 'Bahasa Inggris'),
(3, 'Matematika'),
(4, 'IPA'),
(5, 'Bahasa Jawa');

--
-- Triggers `m_mapel`
--
DELIMITER $$
CREATE TRIGGER `hapus_mapel` AFTER DELETE ON `m_mapel` FOR EACH ROW BEGIN
DELETE FROM m_soal WHERE m_soal.id_mapel = OLD.id;
DELETE FROM tr_guru_mapel WHERE tr_guru_mapel.id_mapel = OLD.id;
DELETE FROM tr_guru_tes WHERE tr_guru_tes.id_mapel = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_siswa`
--

CREATE TABLE `m_siswa` (
  `id` int(6) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nim` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_siswa`
--

INSERT INTO `m_siswa` (`id`, `nama`, `nim`, `jurusan`) VALUES
(1, 'Agus Yudhoyono', '12090671', 'Teknik Informatika'),
(2, 'Edi Baskoro Yudhoyono', '12090672', 'Teknik Informatika'),
(3, 'Puan Maharani', '11090673', 'Sistem Informasi'),
(4, 'Kaesang Pangarep', '11090674', 'Sistem Informasi'),
(5, 'Anisa Pohan', '12090675', 'Teknik Informatika'),
(6, 'Gibran Rakabuming Raka', '11090676', 'Sistem Informasi'),
(7, 'Kahiyang Ayu', '12090677', 'Teknik Informatika'),
(9, 'Akhwan', '120000000', ''),
(11, 'Agus', '15090110', ''),
(12, 'Budi', '15090111', ''),
(13, 'Candra', '15090112', ''),
(14, 'Dedi', '15090113', ''),
(15, 'Eko', '15090114', ''),
(16, 'Fajar', '15090115', ''),
(17, 'Galuh', '15090116', ''),
(18, 'Heri', '15090117', ''),
(19, 'Indra', '15090118', ''),
(20, 'Joko', '15090119', ''),
(21, 'Kukuh', '15090120', ''),
(22, 'Linda', '15090121', ''),
(23, 'Michael', '15090122', ''),
(24, 'Nawang', '15090123', ''),
(25, 'Opan', '15090124', ''),
(26, 'Putri', '15090125', ''),
(27, 'Qisti', '15090126', ''),
(28, 'Riris', '15090127', ''),
(29, 'Sita', '15090128', '');

--
-- Triggers `m_siswa`
--
DELIMITER $$
CREATE TRIGGER `hapus_siswa` AFTER DELETE ON `m_siswa` FOR EACH ROW BEGIN
DELETE FROM tr_ikut_ujian WHERE tr_ikut_ujian.id_user = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `m_soal`
--

CREATE TABLE `m_soal` (
  `id` int(6) NOT NULL,
  `id_guru` int(6) NOT NULL,
  `id_mapel` int(6) NOT NULL,
  `bobot` int(2) NOT NULL,
  `file` varchar(150) NOT NULL,
  `tipe_file` varchar(50) NOT NULL,
  `soal` longtext NOT NULL,
  `opsi_a` longtext NOT NULL,
  `opsi_b` longtext NOT NULL,
  `opsi_c` longtext NOT NULL,
  `opsi_d` longtext NOT NULL,
  `opsi_e` longtext NOT NULL,
  `media_a` varchar(300) NOT NULL,
  `media_b` varchar(300) NOT NULL,
  `media_c` varchar(300) NOT NULL,
  `media_d` varchar(300) NOT NULL,
  `media_e` varchar(300) NOT NULL,
  `jawaban` varchar(5) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `jml_benar` int(6) NOT NULL,
  `jml_salah` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_soal`
--

INSERT INTO `m_soal` (`id`, `id_guru`, `id_mapel`, `bobot`, `file`, `tipe_file`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `media_a`, `media_b`, `media_c`, `media_d`, `media_e`, `jawaban`, `tgl_input`, `jml_benar`, `jml_salah`) VALUES
(52, 44, 1, 0, '1624780289_247dc6c2f35d10d8576c.png', 'png', '<p>tesss</p>', '', '', '', '', '', '', '', '', '', '', 'a', '2021-06-27 14:51:29', 0, 0),
(53, 44, 1, 0, '1624779389_33f67869d54b6c08c7c7.png', 'png', '<p>soal</p>', '<p>opsi a</p>', '<p>opsi b</p>', '<p>opsi c</p>', '<p>opsi d</p>', '<p>opsi e</p>', '', '', '', '', '', 'a', '2021-06-27 14:36:29', 0, 0),
(54, 44, 1, 0, '1624779445_f746704fff2389358a9c.png', 'png', '<p>soal satu</p>', '<p>opsi a</p>', '<p>opsi b</p>', '<p>opsi c</p>', '<p>opsi d</p>', '<p>opsi e</p>', '', '', '', '', '', 'c', '2021-06-27 14:37:25', 0, 0),
(55, 44, 3, 0, '', '', 'soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', '', '', '', '', '', 'jawab', '2021-06-27 18:04:05', 0, 0),
(56, 44, 3, 0, '', '', 'Soal ke 1', 'opsi A.1', 'opsi B.1', 'opsi C.1', 'opsi D.1', 'opsi E.1', '', '', '', '', '', 'a', '2021-06-27 18:04:05', 0, 0),
(57, 44, 3, 0, '', '', 'Soal ke 2', 'opsi A.2', 'opsi B.2', 'opsi C.2', 'opsi D.2', 'opsi E.2', '', '', '', '', '', 'b', '2021-06-27 18:04:05', 0, 0),
(58, 44, 3, 0, '', '', 'Soal ke 3', 'opsi A.3', 'opsi B.3', 'opsi C.3', 'opsi D.3', 'opsi E.3', '', '', '', '', '', 'e', '2021-06-27 18:04:05', 0, 0),
(59, 44, 3, 0, '', '', 'Soal ke 4', 'opsi A.4', 'opsi B.4', 'opsi C.4', 'opsi D.4', 'opsi E.4', '', '', '', '', '', 'd', '2021-06-27 18:04:05', 0, 0),
(60, 44, 3, 0, '', '', 'Soal ke 5', 'opsi A.5', 'opsi B.5', 'opsi C.5', 'opsi D.5', 'opsi E.5', '', '', '', '', '', 'e', '2021-06-27 18:04:05', 0, 0),
(61, 44, 3, 0, '', '', 'Soal ke 6', 'opsi A.6', 'opsi B.6', 'opsi C.6', 'opsi D.6', 'opsi E.6', '', '', '', '', '', 'c', '2021-06-27 18:04:05', 0, 0),
(62, 44, 3, 0, '', '', 'Soal ke 7', 'opsi A.7', 'opsi B.7', 'opsi C.7', 'opsi D.7', 'opsi E.7', '', '', '', '', '', 'a', '2021-06-27 18:04:05', 0, 0),
(63, 44, 3, 0, '', '', 'Soal ke 8', 'opsi A.8', 'opsi B.8', 'opsi C.8', 'opsi D.8', 'opsi E.8', '', '', '', '', '', 'b', '2021-06-27 18:04:05', 0, 0),
(64, 44, 3, 0, '', '', 'Soal ke 9', 'opsi A.9', 'opsi B.9', 'opsi C.9', 'opsi D.9', 'opsi E.9', '', '', '', '', '', 'b', '2021-06-27 18:04:05', 0, 0),
(65, 44, 3, 0, '', '', 'Soal ke 10', 'opsi A.10', 'opsi B.10', 'opsi C.10', 'opsi D.10', 'opsi E.10', '', '', '', '', '', 'c', '2021-06-27 18:04:05', 0, 0),
(66, 44, 3, 0, '', '', 'Soal ke 11', 'opsi A.11', 'opsi B.11', 'opsi C.11', 'opsi D.11', 'opsi E.11', '', '', '', '', '', 'd', '2021-06-27 18:04:05', 0, 0),
(67, 44, 3, 0, '', '', 'Soal ke 12', 'opsi A.12', 'opsi B.12', 'opsi C.12', 'opsi D.12', 'opsi E.12', '', '', '', '', '', 'e', '2021-06-27 18:04:05', 0, 0),
(68, 44, 3, 0, '', '', 'Soal ke 13', 'opsi A.13', 'opsi B.13', 'opsi C.13', 'opsi D.13', 'opsi E.13', '', '', '', '', '', 'a', '2021-06-27 18:04:05', 0, 0),
(69, 44, 3, 0, '', '', 'Soal ke 14', 'opsi A.14', 'opsi B.14', 'opsi C.14', 'opsi D.14', 'opsi E.14', '', '', '', '', '', 'a', '2021-06-27 18:04:05', 0, 0),
(70, 44, 3, 0, '', '', 'Soal ke 15', 'opsi A.15', 'opsi B.15', 'opsi C.15', 'opsi D.15', 'opsi E.15', '', '', '', '', '', 'd', '2021-06-27 18:04:05', 0, 0),
(71, 44, 3, 0, '', '', 'Soal ke 16', 'opsi A.16', 'opsi B.16', 'opsi C.16', 'opsi D.16', 'opsi E.16', '', '', '', '', '', 'c', '2021-06-27 18:04:05', 0, 0),
(72, 44, 3, 0, '', '', 'Soal ke 17', 'opsi A.17', 'opsi B.17', 'opsi C.17', 'opsi D.17', 'opsi E.17', '', '', '', '', '', 'd', '2021-06-27 18:04:05', 0, 0),
(73, 44, 3, 0, '', '', 'Soal ke 18', 'opsi A.18', 'opsi B.18', 'opsi C.18', 'opsi D.18', 'opsi E.18', '', '', '', '', '', 'e', '2021-06-27 18:04:05', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tr_guru_mapel`
--

CREATE TABLE `tr_guru_mapel` (
  `id` int(6) NOT NULL,
  `id_guru` int(6) NOT NULL,
  `id_mapel` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_guru_tes`
--

CREATE TABLE `tr_guru_tes` (
  `id` int(6) NOT NULL,
  `id_guru` int(6) NOT NULL,
  `id_mapel` int(6) NOT NULL,
  `nama_ujian` varchar(200) NOT NULL,
  `jumlah_soal` int(6) NOT NULL,
  `waktu` int(6) NOT NULL,
  `jenis` enum('acak','set') NOT NULL,
  `detil_jenis` varchar(500) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `terlambat` datetime NOT NULL,
  `token` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_guru_tes`
--

INSERT INTO `tr_guru_tes` (`id`, `id_guru`, `id_mapel`, `nama_ujian`, `jumlah_soal`, `waktu`, `jenis`, `detil_jenis`, `tgl_mulai`, `terlambat`, `token`) VALUES
(1, 44, 1, 'UTS', 10, 60, 'acak', '', '2021-06-24 07:05:00', '2021-06-30 23:59:00', '73285');

-- --------------------------------------------------------

--
-- Table structure for table `tr_guru_tes_soal`
--

CREATE TABLE `tr_guru_tes_soal` (
  `id` int(10) NOT NULL,
  `id_guru_tes` int(10) NOT NULL,
  `id_soal` int(10) NOT NULL,
  `urutan` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tr_ikut_ujian`
--

CREATE TABLE `tr_ikut_ujian` (
  `id` int(6) NOT NULL,
  `id_tes` int(6) NOT NULL,
  `id_user` int(6) NOT NULL,
  `list_soal` longtext NOT NULL,
  `list_jawaban` longtext NOT NULL,
  `jml_benar` int(6) NOT NULL,
  `nilai` decimal(10,2) NOT NULL,
  `nilai_bobot` decimal(10,2) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_admin`
--
ALTER TABLE `m_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kon_id` (`kon_id`);

--
-- Indexes for table `m_guru`
--
ALTER TABLE `m_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_mapel`
--
ALTER TABLE `m_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_siswa`
--
ALTER TABLE `m_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_soal`
--
ALTER TABLE `m_soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `tr_guru_mapel`
--
ALTER TABLE `tr_guru_mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `tr_guru_tes`
--
ALTER TABLE `tr_guru_tes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `tr_guru_tes_soal`
--
ALTER TABLE `tr_guru_tes_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_ikut_ujian`
--
ALTER TABLE `tr_ikut_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tes` (`id_tes`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_admin`
--
ALTER TABLE `m_admin`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `m_guru`
--
ALTER TABLE `m_guru`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `m_mapel`
--
ALTER TABLE `m_mapel`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_siswa`
--
ALTER TABLE `m_siswa`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `m_soal`
--
ALTER TABLE `m_soal`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `tr_guru_mapel`
--
ALTER TABLE `tr_guru_mapel`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_guru_tes`
--
ALTER TABLE `tr_guru_tes`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tr_guru_tes_soal`
--
ALTER TABLE `tr_guru_tes_soal`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_ikut_ujian`
--
ALTER TABLE `tr_ikut_ujian`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
