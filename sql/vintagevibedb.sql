-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 12:32 PM
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
-- Database: `vintagevibe`
--

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `src` varchar(200) NOT NULL,
  `alt` varchar(100) NOT NULL,
  `productid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `src`, `alt`, `productid`) VALUES
(1, '1726622708-dress7.jpg', 'dress1', 1),
(3, '1726622727-4.jpg', 'dress3', 2),
(4, '1726622708-3.jpg', 'dress4', 1),
(5, '1726622828-dress5.jpg', 'jacket1', 3),
(6, '1726622850-dress8.jpg', 'jacket2', 4),
(7, '1726622890-2.jpg', 'pants1', 5),
(8, '1726622688-dress1.jpg', 'pants2', 6),
(20, '1726622648-1.jpg', '', 20),
(22, '1726622625-dress4.jpg', '', 22),
(23, '1726622555-jacket2.jpg', '', 23),
(27, '1726622349-jacket7.jpg', '', 25),
(28, '1726622349-jacket6.jpg', '', 25),
(29, '1726622349-jacket5.jpg', '', 25),
(34, '1726622447-jacket3.jpg', '', 24),
(35, '1726622555-jacket1.jpg', '', 23),
(37, '1726622625-dress3.jpg', '', 22),
(38, '1726622688-dress2.jpg', '', 6),
(39, '1726622850-dress9.jpg', '', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productfk_image` (`productid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `productfk_image` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
