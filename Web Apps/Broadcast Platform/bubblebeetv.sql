-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 25, 2020 at 05:37 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bubblebeetv`
--

-- --------------------------------------------------------

--
-- Table structure for table `Broadcaster`
--

CREATE TABLE `Broadcaster` (
  `Username` varchar(25) NOT NULL,
  `Age` int(10) UNSIGNED NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Location` set('New Territories','Kowloon','Hong Kong Island') NOT NULL,
  `url` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Cashout`
--

CREATE TABLE `Cashout` (
  `Username` varchar(25) NOT NULL,
  `Time` datetime NOT NULL DEFAULT current_timestamp(),
  `Amount` int(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Cashout`
--

INSERT INTO `Cashout` (`Username`, `Time`, `Amount`) VALUES
('carlos', '2020-05-04 18:15:31', 750000),
('carlos', '2020-05-04 18:15:42', 750000),
('carlos', '2020-05-04 18:25:20', 100000),
('carlos', '2020-05-04 18:25:22', 100000),
('carlos', '2020-05-04 18:25:26', 100000);

-- --------------------------------------------------------

--
-- Table structure for table `Gift`
--

CREATE TABLE `Gift` (
  `Gift_ID` int(11) NOT NULL,
  `Gift_Name` varchar(25) NOT NULL,
  `Price` int(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Gift`
--

INSERT INTO `Gift` (`Gift_ID`, `Gift_Name`, `Price`) VALUES
(1, 'Diamond', 100),
(2, 'Happy Birthday', 200),
(3, 'Drive You Around', 500),
(4, 'Play You Some Music', 1000),
(5, 'Cheers', 2000),
(6, 'Warm Your Body', 3000),
(7, 'PIZZA TIME', 5000),
(8, 'FREEZE', 10000),
(9, 'Dont Panic', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `GiftTransaction`
--

CREATE TABLE `GiftTransaction` (
  `Sender` varchar(25) NOT NULL,
  `Receiver` varchar(25) NOT NULL,
  `Gift_ID` varchar(4) NOT NULL,
  `Time` datetime NOT NULL DEFAULT current_timestamp(),
  `Quantity` int(10) UNSIGNED NOT NULL,
  `Cashed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `GiftTransaction`
--

INSERT INTO `GiftTransaction` (`Sender`, `Receiver`, `Gift_ID`, `Time`, `Quantity`, `Cashed`) VALUES
('Admin', 'eie3117', '2', '2020-05-25 19:32:35', 1, 0),
('eie3117', 'carlos', '9', '2020-05-25 19:56:02', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `Username` varchar(25) NOT NULL,
  `Nickname` varchar(25) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Salt` varchar(8) NOT NULL,
  `Email` varchar(80) NOT NULL,
  `Profile_image` longblob DEFAULT NULL,
  `Hashtags` set('Artistic','Educational','FPS','RPG','Mobile','Sport') NOT NULL,
  `Wallet` int(255) UNSIGNED NOT NULL DEFAULT 0,
  `Btc_token` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Private_key` varchar(255) DEFAULT NULL,
  `Activated` tinyint(1) NOT NULL DEFAULT 0,
  `Token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`Username`, `Nickname`, `Password`, `Salt`, `Email`, `Profile_image`, `Hashtags`, `Wallet`, `Btc_token`, `Address`, `Private_key`, `Activated`, `Token`) VALUES
('Admin', '', 'admin', '12344321', 'bubblebeetv02@gmail.com', NULL, 'RPG', 0, '8b2a1ed72d984917a83f6b5ae136cf7d', 'n2Jb3UgEC5Doe5QQ79FD2xfRtUBuVVBu5v', '15206fb22da0714e322eb3a5e2667d103ff183cb73a40284e0067485be68a199', 1, ''),
('eie3117', 'eie3117', '$2y$10$mVQj/OA.8tYf5ViZneMwZupSc5of4Tw7Naa4s3JL2lE.21QqcttDW', 'KX1zqUrz', 'sugary0917@gmail.com', 0x31625f6c6f6e675f696d672e6a7067, 'Educational', 0, '0869b399275d4c5fb7958b20cf748c06', 'mqW3YqNLRtQ9cLHwzkhMogW96kpMTNmua1', 'c45fd96a5ef690334f515e320e074905d16f1677051847a0ecd901964992a53e', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Broadcaster`
--
ALTER TABLE `Broadcaster`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `Cashout`
--
ALTER TABLE `Cashout`
  ADD PRIMARY KEY (`Username`,`Time`);

--
-- Indexes for table `Gift`
--
ALTER TABLE `Gift`
  ADD PRIMARY KEY (`Gift_ID`);

--
-- Indexes for table `GiftTransaction`
--
ALTER TABLE `GiftTransaction`
  ADD PRIMARY KEY (`Sender`,`Receiver`,`Gift_ID`,`Time`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Gift`
--
ALTER TABLE `Gift`
  MODIFY `Gift_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
