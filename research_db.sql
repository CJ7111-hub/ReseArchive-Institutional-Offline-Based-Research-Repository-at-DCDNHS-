-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2026 at 03:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `research_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `manuscript_id` int(11) NOT NULL,
  `download_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manuscripts`
--

CREATE TABLE `manuscripts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `section` varchar(100) NOT NULL,
  `strand` varchar(100) NOT NULL,
  `date_uploaded` date NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `tags` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manuscripts`
--

INSERT INTO `manuscripts` (`id`, `title`, `author`, `section`, `strand`, `date_uploaded`, `file_path`, `tags`, `summary`, `status`) VALUES
(1, 'RESEARCHIVE: A LOCAL BASED RESEARCH REPOSITORY N DCDDNH', 'CLAVICILLA,CJ', 'TAUSUG', 'STEM', '2026-03-03', 'uploads/file_69a653cd0afe50.14371839.docx', NULL, NULL, 'Pending'),
(2, 'CAPSTONE_ICT: DEVELOPING DESKTOP APPLICATION FOR DCDNHS', 'LACUESTA, ELJAY Y.', 'TAUSUG', 'TVL-ICT', '2026-03-03', 'uploads/file_69a65608590719.58368024.pdf', NULL, NULL, 'Pending'),
(3, 'Health Assesment - Week1', 'JOHN MARL', 'T\'BOLI', 'STEM', '2026-03-03', 'uploads/file_69a692161a2704.58781934.pdf', NULL, NULL, 'Pending'),
(4, 'DCDNHS SCHOOL COOPERATIVE MANAGEMENT SYSTEM', 'BACALLA', 'MATIGSALUG', 'HUMSS', '2026-03-03', 'uploads/file_69a69355bcd935.71600893.docx', '2026', 'A WEB-BASED LOAN MANAGEMENT', 'Pending'),
(5, 'CoffinCart: Angel Funeral Management System', 'ALVIZA, REY VINCENT', 'IRANUN', 'ABM', '2026-03-03', 'uploads/file_69a693d2dc8a39.12901047.docx', '2026', NULL, 'Pending'),
(6, 'CAPSTONE_HUMSS', 'WELVYN, CELIO', 'MANSAKA', 'HUMSS', '2026-03-03', 'uploads/file_69a6948e7da324.80380962.pdf', '2026', NULL, 'Pending'),
(7, 'CAPSTONE_ABM', 'CASANDRA GRACE CELIO', 'TAUSUG', 'STEM', '2026-03-03', 'uploads/file_69a694e40e1837.69870076.pdf', NULL, NULL, 'Archived');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`) VALUES
(1, 'TAUSUG'),
(2, 'YAKAN'),
(3, 'SUBANEN'),
(4, 'T\'BOLI'),
(5, 'MARANAO'),
(6, 'MANSAKA'),
(7, 'TAGAKAOLO'),
(8, 'TAGABAWA');

-- --------------------------------------------------------

--
-- Table structure for table `strands`
--

CREATE TABLE `strands` (
  `id` int(11) NOT NULL,
  `strand_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `strands`
--

INSERT INTO `strands` (`id`, `strand_name`) VALUES
(1, 'TVL ICT'),
(2, 'TVL EIM'),
(3, 'HUMSS'),
(4, 'STEM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manuscript_id` (`manuscript_id`);

--
-- Indexes for table `manuscripts`
--
ALTER TABLE `manuscripts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `strands`
--
ALTER TABLE `strands`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manuscripts`
--
ALTER TABLE `manuscripts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `strands`
--
ALTER TABLE `strands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`manuscript_id`) REFERENCES `manuscripts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
