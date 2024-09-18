-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 12:37 PM
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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'dresses', ''),
(2, 'pants', ''),
(5, 'jackets', '');

-- --------------------------------------------------------

--
-- Table structure for table `era`
--

CREATE TABLE `era` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `era`
--

INSERT INTO `era` (`id`, `name`) VALUES
(1, '1930s'),
(2, '1940s'),
(5, '1950s'),
(6, '1960s');

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

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `personid` int(11) NOT NULL,
  `total` float NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `notes` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `send_status` enum('pending','sent','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_product`
--

CREATE TABLE `order_product` (
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `roleid` int(11) NOT NULL DEFAULT 3,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `name`, `surname`, `email`, `password`, `address`, `city`, `country`, `roleid`, `created_at`, `updated_at`) VALUES
(1, 'hajrije', 'mjeku', 'h@h.h', '$2y$10$8JmbsnO/SvxOz442k0VKBO19fnniWjU7Hq1JPWQPJRyLtM1bIPc6e', '', '', '', 3, '2024-09-10 17:04:34', '2024-09-10 17:04:34'),
(2, 'filan', 'fisteku', 'f@f.f', '$2y$10$d7R.7XfCLoaIw7QSykclEO.lqF7KPXBUtdp9NV/ujwICL8YTIAe6i', '', '', '', 3, '2024-09-10 17:17:46', '2024-09-10 17:17:46'),
(3, 'admin', 'admin', 'a@a.a', '$2y$10$NstPi1eFdp24W2xvuSyckeprE2fi2LkJrseAbBQHfqYpxkVK0Ze3O', 'admin', 'admin', 'admin', 1, '2024-09-10 18:26:50', '2024-09-10 18:26:50'),
(4, 'manager', 'manager', 'm@m.m', '$2y$10$Q16SIjjDkdoVEkBn2TDkgOYUCn2OxDMJih7NwVqLML7zLrBAakL6G', 'm', 'm', 'm', 2, '2024-09-10 18:30:16', '2024-09-12 19:22:58'),
(6, 'test', 'test2', 't@t.t', '$2y$10$kSeRLIAED8TIlfXJ9/WOKeFCKjyWsThXXiOEjHWtjQToi8U0pitz.', 't', 't', 't', 3, '2024-09-11 22:08:51', '2024-09-12 22:43:35'),
(7, 'test3', 'test3', 't3@t.t', 'test3', 'test', NULL, NULL, 3, '2024-09-12 22:45:25', '2024-09-12 22:45:25'),
(9, 'a', 'b', 'a@b.c', 'abc', 'a', 'b', 'c', 3, '2024-09-12 23:50:59', '2024-09-12 23:50:59'),
(15, 'j', 'j', 'j@j.j', '$2y$10$JJOsCHMniqqaS8gT8YVDceMYvMohdCB/nWgdeKTYVwIHgBeHsH51K', 'j', 'j', 'j', 3, '2024-09-13 00:19:46', '2024-09-13 00:21:18'),
(17, 'random', 'random', 'r@r.r', '$2y$10$KCXeJv0YT6y0L32IW1WDVO07kziLpukki1oUO3V/wDEyA/8WcUplS', 'rr', 'r', 'r', 3, '2024-09-17 21:56:28', '2024-09-18 01:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `size` enum('S','M','L') NOT NULL,
  `price` float NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `eraid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `size`, `price`, `qty`, `eraid`, `categoryid`, `created_at`, `updated_at`) VALUES
(1, 'Blue Vintage  Dress', 'S', 31, 1, 1, 1, '2024-09-14 22:42:39', '2024-09-18 03:16:38'),
(2, 'Pink Vintage  Dress', 'M', 19, 1, 2, 1, '2024-09-14 22:42:39', '2024-09-18 03:16:38'),
(3, 'Green & White Vintage  Dress', 'L', 49, 1, 5, 1, '2024-09-14 22:42:39', '2024-09-18 03:29:52'),
(4, 'Yellow Vintage Dress', 'M', 14, 1, 5, 1, '2024-09-14 22:42:39', '2024-09-18 03:16:38'),
(5, 'White Floral Vintage Dress', 'S', 23, 1, 1, 1, '2024-09-14 22:42:39', '2024-09-18 03:28:10'),
(6, 'Grey Vintage Dress', 'S', 44, 1, 2, 1, '2024-09-14 22:42:39', '2024-09-18 03:16:38'),
(20, 'Vintage Plaid Dress', 'L', 74, 1, 2, 1, '2024-09-15 16:09:59', '2024-09-18 03:24:24'),
(22, 'Dark Brown Vintage Dress', 'S', 56, 1, 6, 1, '2024-09-15 17:56:55', '2024-09-18 03:16:38'),
(23, 'White Vintage Jacket', 'L', 77, 1, 2, 5, '2024-09-15 17:57:52', '2024-09-18 03:16:38'),
(24, 'Dark Red Vintage Jacket', 'S', 45, 1, 2, 5, '2024-09-15 18:00:24', '2024-09-18 03:20:47'),
(25, 'Floral Vintage Jacket', 'M', 87, 1, 5, 5, '2024-09-15 19:41:52', '2024-09-18 03:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'admin', 'all functionalities'),
(2, 'manager', 'manages products & orders'),
(3, 'user', 'manage his account & buy items');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `era`
--
ALTER TABLE `era`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productfk_image` (`productid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personfk` (`personid`);

--
-- Indexes for table `order_product`
--
ALTER TABLE `order_product`
  ADD PRIMARY KEY (`orderid`,`productid`),
  ADD KEY `order_product_ibfk_2` (`productid`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rolefk` (`roleid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erafk` (`eraid`),
  ADD KEY `categoryfk` (`categoryid`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `era`
--
ALTER TABLE `era`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `productfk_image` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `personfk` FOREIGN KEY (`personid`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_product`
--
ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `rolefk` FOREIGN KEY (`roleid`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `categoryfk` FOREIGN KEY (`categoryid`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `erafk` FOREIGN KEY (`eraid`) REFERENCES `era` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
