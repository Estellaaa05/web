-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 19, 2023 at 01:55 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `estella`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `gender` enum('female','male') NOT NULL,
  `date_of_birth` date NOT NULL,
  `account_status` enum('active','pending','inactive') NOT NULL,
  `customer_image` text,
  `registration_date_time` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`ID`, `username`, `email`, `password`, `first_name`, `last_name`, `gender`, `date_of_birth`, `account_status`, `customer_image`, `registration_date_time`) VALUES
(1, 'jessica', 'jessicalim0107@gmail.com', '$2y$10$7HoQZlaXTaGKZe0xwOJkEOVNPngn37aL9eKyC2gq4MauiootdKfI6', 'Jessica', 'Lim', 'female', '2001-07-25', 'pending', 'customer_uploads/825ce66cdc9e9c4b00618ceb7630946a3615b41d-doraemon.jpg', '2023-08-03 10:22:59'),
(2, 'ally06', 'allywoon0206@gmail.com', '$2y$10$88apibQiJ5.Jjuan14rPEu5HbABDfRKZlNLBfgsQQl3snCMJYBQV6', 'Ally', 'Woon', 'female', '2002-06-19', 'inactive', 'customer_uploads/0b5cbb2b1aa1517f960d95f5d09bd0e0c78eac13-jellyfish.jpg', '2023-08-03 07:32:48'),
(3, 'shelly05', 'shellylim0205@gmail.com', '$2y$10$fJR/RyogTvtYXpD/pARQa.1X5U2l845qedZv2Kkje9wiU/bHHKKpa', 'Shelly', 'Lim', 'female', '2002-05-09', 'active', 'customer_uploads/551fe3c06649417cbf1fc72929aea229a3c02e84-apple.jpeg', '2023-08-03 10:20:27'),
(4, 'shelbys', 'shelbysgu0510@gmail.com', '$2y$10$xrwqAJ12B1AOaBz7ssQ0kuerHb50b3luMqX5S5Ej7VPuKma0LKDUC', 'Shelbys', 'Gu', 'female', '2005-10-08', 'active', 'customer_uploads/916bd6756649b282055b7e650bad538a42c46364-rabbit.jpg', '2023-08-07 16:33:06'),
(5, 'veron', 'veronting1007@gmail.com', '$2y$10$4fFkl0K/kvrwqp53VWtr9uWxhRiTtCL2yTdqbVLlnf5BQzRQn3OzS', 'Veron', 'Ting', 'male', '2010-07-08', 'active', 'customer_uploads/403c5782572147b8d64c3e5fbe029e295b95115b-mario.jpg', '2023-08-08 02:54:58'),
(7, 'hester', 'hesterloh9606@gmail.com', '$2y$10$edp0xjTWkiXymjGF9ihZQurocAfA2Od1GuTDxXaY.fjNMDRcU4Y.u', 'Hester', 'Loh', 'female', '1996-06-10', 'active', 'customer_uploads/d6fdd44cc9f6217f459f67b0bd763cc7a72da0da-cat.jpeg', '2023-08-27 17:31:25'),
(8, 'amy', 'amyyap0602@gmail.com', '$2y$10$Z5Sgp.FFYZ6bWgFpTpkp0uZ7YyU7WcoubYLD3ZIF7kQqXKay7YuHe', 'Amy', 'Yap', 'female', '2006-02-09', 'active', 'customer_uploads/e459918db78414349cf13e9948330560101e6a77-flower.jpg', '2023-08-27 17:36:29'),
(9, 'hana', 'hana140727@gmail.com', '$2y$10$JpDDEVTKjOWCpuFRfmfXsOIP3znHeICzAUrIUYHs77jKDM60eFETi', 'Hana', 'Tan', 'female', '2014-07-27', 'active', NULL, '2023-08-27 17:37:55'),
(10, 'yiwen', 'yiwenwong@gmail.com', '$2y$10$O7U1TIIxJT6AWSrl8/tqy.9dZzlzSvz9GyF0bTWRuNGuDq4C9b9dS', 'Yiwen', 'Wong', 'male', '2013-02-05', 'active', NULL, '2023-08-27 17:42:46'),
(11, 'ben', 'benlim9806@gmail.com', '$2y$10$5CPLy44cSazLIZW3gkAf/uP5SUWiEP93TLQhoUTVymCyXlYkkjatC', 'Ben', 'Lim', 'male', '1998-06-16', 'active', 'customer_uploads/c5173232b7da62cbbfce1b8a4c187b14269f6829-pikachu.jpg', '2023-09-01 20:16:52'),
(17, 'Chewvy', 'chewvy0303@gmail.com', '$2y$10$rRdQjIUtgtOfzFBNZDERreg3t8LCOSEoNoE.rTRfPdzzUzk4u.Cti', 'Chewvy', 'Lau', 'female', '2003-03-08', 'pending', 'customer_uploads/c359e9a16530f1f8ac4716559b92e16041cf5f75-cow.jpg', '2023-09-08 23:31:28'),
(19, 'Enna', 'ennalim9707@gmail.com', '$2y$10$js4HRaVuGbqaWOyfkv5MQOcljHqsU7W9lM6SUScMFkJ24Ta5x7eaq', 'Enna', 'Lim', 'female', '1997-07-09', 'active', 'customer_uploads/4663ee188e70a799f72484ca9e1ec035f6aae040-minions.jpg', '2023-09-08 23:43:00'),
(22, 'Marry', 'marrylim0303@gmail.com', '$2y$10$A6.qSIS9A1hkDh34dJE1kunj1PQFQG43ipBSOBsugPsATVkXQQDPS', 'Marry', 'Lim', 'female', '2005-03-03', 'inactive', 'customer_uploads/e4120627125c92994b24126506b9c2fe9efb7721-onigiri.jpg', '2023-09-16 22:56:03');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `orderDetail_ID` int(50) NOT NULL AUTO_INCREMENT,
  `order_ID` int(50) NOT NULL,
  `product_ID` int(50) NOT NULL,
  `quantity` int(50) NOT NULL,
  `subtotal_price` decimal(11,2) NOT NULL,
  PRIMARY KEY (`orderDetail_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=225 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`orderDetail_ID`, `order_ID`, `product_ID`, `quantity`, `subtotal_price`) VALUES
(224, 136, 15, 2, '40.00'),
(221, 135, 28, 1, '12.00'),
(220, 134, 12, 1, '25.00'),
(219, 133, 8, 1, '10.00'),
(218, 132, 26, 1, '150.00'),
(217, 131, 3, 1, '4.00'),
(216, 130, 14, 1, '80.00'),
(215, 129, 8, 2, '20.00'),
(214, 129, 10, 1, '1800.00'),
(213, 128, 10, 1, '1800.00'),
(212, 128, 28, 2, '24.00'),
(211, 127, 12, 1, '25.00'),
(210, 127, 9, 1, '900.00'),
(209, 127, 7, 1, '8.99'),
(208, 126, 16, 2, '240.00'),
(207, 126, 15, 1, '20.00'),
(206, 124, 7, 2, '17.98'),
(203, 125, 16, 1, '120.00'),
(202, 125, 14, 1, '80.00'),
(205, 124, 13, 1, '50.00'),
(204, 124, 11, 1, '40.00'),
(197, 122, 12, 1, '25.00'),
(196, 121, 15, 1, '20.00'),
(195, 120, 13, 5, '250.00'),
(194, 120, 22, 3, '3600.00'),
(191, 119, 15, 1, '20.00'),
(190, 119, 14, 1, '80.00'),
(164, 114, 1, 1, '49.99'),
(163, 113, 16, 2, '320.00'),
(161, 111, 26, 1, '150.00'),
(153, 106, 26, 1, '150.00'),
(152, 105, 2, 1, '1.00'),
(149, 102, 22, 1, '1200.00'),
(147, 100, 1, 2, '99.98'),
(146, 100, 3, 1, '4.00'),
(145, 100, 7, 2, '17.98'),
(144, 100, 6, 3, '18.00'),
(143, 100, 5, 4, '45.40'),
(142, 100, 4, 5, '19.75'),
(141, 99, 1, 5, '249.95'),
(140, 98, 7, 1, '8.99'),
(139, 98, 5, 4, '45.40'),
(138, 98, 4, 5, '19.75'),
(137, 98, 2, 2, '2.00'),
(136, 97, 5, 2, '22.70'),
(135, 96, 7, 1, '8.99'),
(134, 96, 6, 2, '12.00'),
(133, 95, 4, 2, '7.90'),
(132, 95, 3, 1, '4.00'),
(131, 94, 6, 10, '60.00'),
(130, 93, 2, 1, '1.00'),
(129, 93, 1, 2, '99.98'),
(128, 92, 1, 1, '49.99');

-- --------------------------------------------------------

--
-- Table structure for table `order_summary`
--

DROP TABLE IF EXISTS `order_summary`;
CREATE TABLE IF NOT EXISTS `order_summary` (
  `order_ID` int(50) NOT NULL AUTO_INCREMENT,
  `customer_ID` int(50) NOT NULL,
  `total_price` decimal(11,2) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_summary`
--

INSERT INTO `order_summary` (`order_ID`, `customer_ID`, `total_price`, `order_date`) VALUES
(136, 2, '40.00', '2023-09-18 14:12:12'),
(135, 1, '12.00', '2023-09-18 13:13:42'),
(134, 9, '25.00', '2023-09-18 13:13:26'),
(133, 2, '10.00', '2023-09-18 13:13:13'),
(132, 5, '150.00', '2023-09-18 13:12:59'),
(131, 3, '4.00', '2023-09-18 13:12:48'),
(130, 4, '80.00', '2023-09-18 13:12:28'),
(129, 11, '1820.00', '2023-09-18 13:12:10'),
(128, 5, '1824.00', '2023-09-18 13:11:50'),
(127, 5, '933.99', '2023-09-18 13:11:22'),
(126, 2, '260.00', '2023-09-18 12:04:10'),
(125, 1, '200.00', '2023-09-17 01:46:57'),
(124, 1, '107.98', '2023-09-17 01:44:48'),
(122, 4, '25.00', '2023-09-17 01:43:30'),
(121, 1, '20.00', '2023-09-17 01:43:13'),
(120, 17, '3850.00', '2023-09-16 22:48:13'),
(119, 4, '100.00', '2023-09-16 22:33:55'),
(114, 3, '49.99', '2023-09-12 10:21:33'),
(113, 17, '320.00', '2023-09-12 10:21:10'),
(111, 17, '150.00', '2023-09-12 00:42:35'),
(106, 1, '150.00', '2023-09-08 23:40:18'),
(105, 17, '1.00', '2023-09-08 23:38:18'),
(102, 8, '1200.00', '2023-09-08 16:10:29'),
(100, 10, '205.11', '2023-08-27 17:43:33'),
(99, 9, '249.95', '2023-08-27 17:43:11'),
(98, 2, '76.14', '2023-08-26 00:35:07'),
(97, 1, '22.70', '2023-08-25 01:57:03'),
(96, 5, '20.99', '2023-08-25 01:56:58'),
(95, 2, '11.90', '2023-08-25 01:56:48'),
(94, 4, '60.00', '2023-08-25 01:56:34'),
(93, 2, '100.98', '2023-08-25 01:56:26'),
(92, 3, '49.99', '2023-08-25 01:56:16');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `category_ID` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `promotion_price` decimal(11,2) DEFAULT NULL,
  `manufacture_date` date NOT NULL,
  `expired_date` date DEFAULT NULL,
  `product_image` text,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_ID`, `description`, `price`, `promotion_price`, `manufacture_date`, `expired_date`, `product_image`, `modified`, `created`) VALUES
(1, 'Basketball', 3, 'A ball used in the NBA.', '49.99', '45.00', '2023-03-11', NULL, 'product_uploads/df758a2c72df210452092504a44f1cdcadfeeacc-Basketball.png', '2023-08-29 02:05:46', '2023-05-09 12:04:03'),
(2, 'Gatorade', 3, 'This is a very good drink for athletes.', '1.99', '1.00', '2022-07-12', '2023-09-13', 'product_uploads/53d89d4c72810ccd7eb37829efa6472953b6bc57-gatorade.jpg', '2023-08-29 02:05:36', '2022-09-21 12:14:29'),
(3, 'Glasses', 1, 'It will make you read better.', '6.00', '4.00', '2015-07-15', NULL, 'product_uploads/c8a232474db1b9c31bc3320815f80fa389ae1a2a-glasses.jpg', '2023-08-29 02:05:36', '2022-08-31 12:15:04'),
(4, 'Trash Can', 1, 'It will help you maintain cleanliness.', '3.95', '0.00', '2014-03-04', NULL, 'product_uploads/81f09ae206248d52da332be805c841f9ad95f24a-trashcan.jpg', '2023-08-27 07:12:53', '2015-08-02 12:16:08'),
(5, 'Mouse', 2, 'Very useful if you love your computer.', '11.50', '10.00', '2016-10-13', NULL, 'product_uploads/45302a7dc6c62968e1aa34d960d530aa1230800b-mouse.jpeg', '2023-08-29 02:05:36', '2017-06-13 12:17:58'),
(6, 'Earphone', 2, 'You need this one if you love music.', '7.00', '6.00', '2015-12-17', NULL, 'product_uploads/a181d7d7d846d478004fa9b0424ee47910803cf6-earphone.jpg', '2023-08-01 16:01:33', '2023-08-24 12:18:21'),
(7, 'Pillow', 1, 'Sleeping well is important.', '8.99', '0.00', '2017-12-05', NULL, NULL, '2023-08-27 07:13:20', '2023-08-22 12:18:56'),
(8, 'Tennis Ball', 3, 'Soft and long-lasting.', '10.00', '0.00', '2023-01-29', NULL, 'product_uploads/7ef842cc47522d5ed4012ac558390ff1e427105e-tennis_ball.jpg', '2023-08-31 15:59:05', '2023-08-31 23:59:05'),
(9, 'Bed', 1, 'Soft and comfortable.', '1000.00', '900.00', '2023-09-01', NULL, 'product_uploads/136b7d506d16cc70e56f012dcdb97e30663580a3-bed.jpg', '2023-08-31 16:28:59', '2023-09-01 00:28:59'),
(10, 'Laptop', 2, 'Portable.', '2000.00', '1800.00', '2023-09-01', NULL, 'product_uploads/ab076c3cdbec7bb6383d5a42a98c76364d7d776a-laptop.jpg', '2023-08-31 16:55:19', '2023-09-01 00:55:19'),
(11, 'Table', 1, 'A wide range of sizes and styles.', '50.00', '40.00', '2023-09-01', NULL, 'product_uploads/02a34bd4d0ca77fa30b0b2a65b5dd4b1deb86260-table.jpg', '2023-09-01 08:09:32', '2023-09-01 16:09:32'),
(12, 'Chair', 1, 'An ergonomic office chair.', '25.00', '0.00', '2023-09-01', NULL, 'product_uploads/a58074fb3d5d27d9bbbe23d82027ce708b955f48-chair.jpg', '2023-09-01 08:14:29', '2023-09-01 16:14:29'),
(13, 'Keyboard', 2, 'Great quality-of-life improvement.', '50.00', '0.00', '2023-09-01', '2023-10-07', 'product_uploads/9bc6956796c7c4c42463008c2e7ff74cf79ece6b-keyboard.jpg', '2023-09-01 08:25:39', '2023-09-01 16:25:39'),
(14, 'Wardrobe', 1, 'To let you organise your clothes, shoes or any other thing you want to store in a practical and stylish way.', '80.00', '0.00', '2023-09-01', NULL, 'product_uploads/2624093d11b2d963159349c01e602f0b082b5501-wardrobe1.jpg', '2023-09-01 08:30:07', '2023-09-01 16:30:07'),
(15, 'Washing powder', 1, 'Creaks down mud and clay stains better compared to liquid laundry detergent.', '20.00', '0.00', '2023-09-01', NULL, 'product_uploads/6000f1c2a6c3027431bc3a10090f1cc30d29e8fb-washing_powder.jpg', '2023-09-01 10:06:48', '2023-09-01 18:06:48'),
(16, 'Bag', 1, 'Perfectly on-trend and practical.', '120.00', '0.00', '2023-09-01', NULL, 'product_uploads/469f634f44421f3ca6a29622777d86457bcbb732-bag.jpg', '2023-09-01 10:34:50', '2023-09-01 18:34:50'),
(22, 'Tablet', 2, 'Portable.', '1200.00', '0.00', '2023-09-08', NULL, 'product_uploads/b8ebf8c931226eabba9559f9e1faebb58dbb2707-tablet.jpg', '2023-09-08 08:10:12', '2023-09-08 16:10:12'),
(26, 'Table Fan', 2, 'Portable.', '150.00', '0.00', '2023-09-08', NULL, 'product_uploads/6014cee60eed3963285a29820ce7b8ce1e206097-table_fan.jpg', '2023-09-08 15:40:04', '2023-09-08 23:40:04'),
(28, 'Clock', 1, 'To measure and indicate time.', '15.00', '12.00', '2023-09-12', NULL, 'product_uploads/7b0fc3341d43be7db07cd92c6752c263e5450955-clockt1.jpg', '2023-09-11 16:47:10', '2023-09-12 00:47:10'),
(29, 'Maggi Hot Cup', 10, 'Delicious', '5.00', '0.00', '2023-09-05', '2024-08-10', 'product_uploads/895a3fe3fabb37f016b600e1703415fa98259560-hotcup.jpeg', '2023-09-18 05:08:59', '2023-09-18 13:08:59'),
(30, 'Burger', 10, 'Delicious and easy to eat.', '9.00', '0.00', '2023-09-18', '2023-09-21', 'product_uploads/244f49a879d8395c72f3aa25ef70663dcd375bdf-burger.jpg', '2023-09-18 06:11:44', '2023-09-18 14:11:44'),
(31, 'Football', 3, 'Good quality and long-lasting.', '10.00', '0.00', '2023-09-18', NULL, 'product_uploads/056f4ede5051d3295390a2a2acb7fc80feeed331-football.jpg', '2023-09-18 06:14:16', '2023-09-18 14:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(20) NOT NULL,
  `category_description` text NOT NULL,
  `req_expiredDate` text,
  PRIMARY KEY (`category_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`category_ID`, `category_name`, `category_description`, `req_expiredDate`) VALUES
(1, 'Household', 'Sofa, pillow and furniture.', NULL),
(2, 'Electronic Device', 'gadgets, earphone and keyboard.', NULL),
(3, 'Sports', 'Basketball, racket and skipping rope.', NULL),
(10, 'Food', 'Bread, Rice and Noodles.', 'yes');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
