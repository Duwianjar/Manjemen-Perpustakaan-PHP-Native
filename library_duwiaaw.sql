-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2023 at 03:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_duwiaaw`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `fullname`, `email`, `password`) VALUES
(1, 'duwianjar', 'Duwi Anjar Ariwibowo', 'duwianjarariwibowo@gmail.com', '4079568ce1127c733d58264a8ec047eb'),
(18, 'admin', 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `photo_filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `publisher`, `amount`, `photo_filename`) VALUES
(1, 'Humor Informatika', 'Ariwibowo', 'Republika', 5, 'Humor Informatika_20231226_084031.jpg'),
(2, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 14, 'Laskar Pelangi_20231226_083807.png'),
(3, 'Ayat-Ayat Cinta', 'Habiburrahman El Shirazy', 'Republika', 13, 'Ayat-Ayat Cinta_20231226_083821.jpg'),
(4, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 12, 'Bumi Manusia_20231226_083907.jpg'),
(5, 'Cinta di Dalam Gelas', 'Andrea Hirata', 'Bentang Pustaka', 20, 'Cinta di Dalam Gelas_20231226_083950.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `borrowing`
--

CREATE TABLE `borrowing` (
  `id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `loan_date` date NOT NULL,
  `return_date` date NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowing`
--

INSERT INTO `borrowing` (`id`, `borrower_id`, `book_id`, `loan_date`, `return_date`, `status`) VALUES
(15, 2, 2, '2023-12-01', '2023-12-06', 'Belum Kembali'),
(17, 1, 5, '2023-12-22', '2023-12-29', 'Sudah Kembali'),
(18, 7, 1, '2023-12-22', '2023-12-29', 'Sudah Kembali'),
(19, 3, 5, '2023-12-22', '2023-12-29', 'Sudah Kembali'),
(20, 5, 3, '2023-12-26', '2024-01-02', 'Belum Kembali'),
(21, 4, 3, '2023-12-27', '2024-01-03', 'Belum Kembali');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `photo_filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `fullname`, `address`, `phone_number`, `gender`, `photo_filename`) VALUES
(1, 'Saiful Jamil', 'Jalan Raya Srandakan Km.1 Trimurti, Srandakan Bantul 55762 Daerah Istimewa Yogyakarta.', '+628523678921', 'laki-laki', 'Saiful Jamil_20231226_063745.jpg'),
(2, 'Calista Fiesty', 'JL. KALIURANG KM. 5 / 94, SLEMAN, YOGYAKARTA', '+6287353987232', 'perempuan', 'Calista Fiesty_20231226_063133.jpg'),
(3, 'Ahmad Yani', 'Jl. Merdeka No. 123, Jakarta', '+628123456789', 'laki-laki', 'Ahmad Yani_20231226_063141.jpg'),
(4, 'Siti Solehah', 'Jl. Pahlawan No. 45, Surabaya', '+628212345678', 'perempuan', 'Siti Solehah_20231226_062232.jpg'),
(5, 'Wahyu Putra', 'Jl. Cendrawasih No. 89, Yogyakarta', '+628345367362', 'laki-laki', 'Wahyu Putra_20231226_063239.jpg'),
(6, 'Samuel Wijaya', 'Jl. Diponegoro No. 21, Semarang', '+62862412467883', 'laki-laki', 'Samuel Wijaya_20231226_063912.jpg'),
(7, 'Sayuti Melik', 'banarab no09 kemayoran.', '+6293837373737', 'laki-laki', 'Sayuti Melik_20231226_063220.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowing`
--
ALTER TABLE `borrowing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `borrower_id` (`borrower_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `borrowing`
--
ALTER TABLE `borrowing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowing`
--
ALTER TABLE `borrowing`
  ADD CONSTRAINT `borrowing_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `member` (`id`),
  ADD CONSTRAINT `borrowing_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
