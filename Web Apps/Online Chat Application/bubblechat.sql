-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 31, 2020 at 05:45 PM
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
-- Database: `bubblechat`
--

-- --------------------------------------------------------

--
-- Table structure for table `Chatroom`
--

CREATE TABLE `Chatroom` (
  `RoomID` int(10) NOT NULL,
  `RoomName` varchar(30) NOT NULL,
  `Owner` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Chatroom`
--

INSERT INTO `Chatroom` (`RoomID`, `RoomName`, `Owner`) VALUES
(21, 'Chat 21', 'user1'),
(22, 'Testing', 'user1'),
(24, 'test2', 'test2');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `Username` varchar(25) NOT NULL,
  `Nickname` varchar(25) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Salt` varchar(8) NOT NULL,
  `Age` int(10) UNSIGNED NOT NULL,
  `Email` varchar(80) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Location` enum('Central & Western','Eastern','Southern','Wan Chai','Kowloon City','Kwun Tong','Sham Shui Po','Wong Tai Sin','Yau Tsim Mong','Islands','Kwai Tsing','North','Sai Kung','Sha Tin','Tai Po','Tsuen Wan','Tuen Mun','Yuen Long','') NOT NULL,
  `Profile_image` longblob DEFAULT NULL,
  `Token` varchar(100) NOT NULL,
  `Activated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`Username`, `Nickname`, `Password`, `Salt`, `Age`, `Email`, `Gender`, `Location`, `Profile_image`, `Token`, `Activated`) VALUES
('test2', 'test2', '$2y$10$A1ZZXzMWWt1l3Z/5ADt/yOH0Ly9oPb4j9VXvLpaHRuXOAM1l8/C/a', 'eKBULl8X', 3, 'sugary0917@gmail.com', 'male', 'Central & Western', 0x627562626c656265652e6a7067, '', 1),
('user1', 'User1', '$2y$10$oyISlCXdgj.s1WGFsDAis.8y2I/fWbCscEZPMlGsnrz2hUNfNH3fK', 'Sj7uEXJA', 20, 'eatclolo@gmail.com', 'male', 'Yuen Long', NULL, '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Chatroom`
--
ALTER TABLE `Chatroom`
  ADD PRIMARY KEY (`RoomID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Chatroom`
--
ALTER TABLE `Chatroom`
  MODIFY `RoomID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
