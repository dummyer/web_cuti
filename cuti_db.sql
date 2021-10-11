-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2021 at 05:05 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cuti_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `list_cuti`
--

CREATE TABLE `list_cuti` (
  `id_cuti` int(11) NOT NULL,
  `type_cuti` int(11) NOT NULL,
  `desc_cuti` longtext NOT NULL,
  `attachment` varchar(100) DEFAULT NULL,
  `tgl_mulai_cuti` date NOT NULL,
  `jumlah_hari_cuti` int(11) NOT NULL,
  `user_cuti` int(11) NOT NULL,
  `requested_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `hr_nik_approve` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `list_cuti`
--

INSERT INTO `list_cuti` (`id_cuti`, `type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `status`, `hr_nik_approve`) VALUES
(2, 1, 'Cuti tahunan dari tanggal 10 oktober 2021 hingga 13 oktober 2021', '', '2021-10-11', 4, 1902, '2021-10-01 12:01:05', 1, 1901),
(3, 2, 'Cuti sakit', '', '2021-10-13', 2, 1903, '2021-10-13 12:01:05', 1, 1901),
(13, 1, 'cuti tahunan', NULL, '2021-10-11', 4, 1901, '2021-10-11 14:25:17', 2, NULL),
(14, 1, '', NULL, '2021-10-16', 3, 1902, '2021-10-11 14:32:45', 1, 1901),
(15, 1, '', NULL, '2021-10-16', 7, 1902, '2021-10-11 14:34:52', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id_notification` int(11) NOT NULL,
  `judul_notification` varchar(100) NOT NULL,
  `desc_notification` longtext NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `type_notification` int(11) NOT NULL,
  `receiver_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `nama_role`) VALUES
(1, 'HR'),
(2, 'EMPLOYEE');

-- --------------------------------------------------------

--
-- Table structure for table `status_cuti`
--

CREATE TABLE `status_cuti` (
  `id_status_cuti` int(11) NOT NULL,
  `judul_status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status_cuti`
--

INSERT INTO `status_cuti` (`id_status_cuti`, `judul_status`) VALUES
(1, 'APPROVED'),
(2, 'PENDING'),
(3, 'REJECTED');

-- --------------------------------------------------------

--
-- Table structure for table `type_cuti`
--

CREATE TABLE `type_cuti` (
  `id_type_cuti` int(11) NOT NULL,
  `nama_type_cuti` varchar(45) NOT NULL,
  `desc_type_cuti` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `type_cuti`
--

INSERT INTO `type_cuti` (`id_type_cuti`, `nama_type_cuti`, `desc_type_cuti`) VALUES
(1, 'TAHUNAN', ''),
(2, 'SAKIT', ''),
(3, 'MELAHIRKAN', ''),
(4, 'BERSAMA', ''),
(5, 'ALASAN KHUSUS', '');

-- --------------------------------------------------------

--
-- Table structure for table `type_notification`
--

CREATE TABLE `type_notification` (
  `id_type_notif` int(11) NOT NULL,
  `name_type_notif` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `type_notification`
--

INSERT INTO `type_notification` (`id_type_notif`, `name_type_notif`) VALUES
(1, 'PUBLIC'),
(2, 'PERSONAL');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nik_user` int(11) NOT NULL,
  `nama_user` varchar(45) NOT NULL,
  `department` varchar(45) NOT NULL,
  `role` int(11) NOT NULL,
  `password` varchar(45) NOT NULL,
  `join_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nik_user`, `nama_user`, `department`, `role`, `password`, `join_date`) VALUES
(1, 1901, 'Jejemon', 'HR', 1, 'd54e99a6c03704e95e6965532dec148b', '2021-05-11'),
(2, 1902, 'Rina', 'MARKETING', 2, 'fc4ddc15f9f4b4b06ef7844d6bb53abf', '2021-07-21'),
(3, 1903, 'Bubu', 'INTERNAL AUDIT', 2, '944626adf9e3b76a3919b50dc0b080a4', '2019-05-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `list_cuti`
--
ALTER TABLE `list_cuti`
  ADD PRIMARY KEY (`id_cuti`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notification`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `status_cuti`
--
ALTER TABLE `status_cuti`
  ADD PRIMARY KEY (`id_status_cuti`);

--
-- Indexes for table `type_cuti`
--
ALTER TABLE `type_cuti`
  ADD PRIMARY KEY (`id_type_cuti`);

--
-- Indexes for table `type_notification`
--
ALTER TABLE `type_notification`
  ADD PRIMARY KEY (`id_type_notif`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `list_cuti`
--
ALTER TABLE `list_cuti`
  MODIFY `id_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status_cuti`
--
ALTER TABLE `status_cuti`
  MODIFY `id_status_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `type_cuti`
--
ALTER TABLE `type_cuti`
  MODIFY `id_type_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `type_notification`
--
ALTER TABLE `type_notification`
  MODIFY `id_type_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
