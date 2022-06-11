-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2022 at 07:44 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payesh_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_activation`
--

CREATE TABLE `tbl_activation` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `activation_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banners`
--

CREATE TABLE `tbl_banners` (
  `id` int(11) NOT NULL,
  `image` text COLLATE utf8_persian_ci NOT NULL,
  `link` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT 1,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_banners`
--

INSERT INTO `tbl_banners` (`id`, `image`, `link`, `enabled`, `createdAt`) VALUES
(1, 'https://wallpapercave.com/wp/jgbFbWR.jpg', '', 1, '2022-04-18 22:10:26'),
(2, 'https://wallpapers-fenix.eu/full/190722/214910489.jpg', '', 1, '2022-04-18 22:10:26'),
(3, 'https://wallpaperaccess.com/full/1172461.jpg', '', 1, '2022-04-18 22:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bookmark`
--

CREATE TABLE `tbl_bookmark` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `propertyId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city`
--

CREATE TABLE `tbl_city` (
  `id` int(11) NOT NULL,
  `CityName` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_city`
--

INSERT INTO `tbl_city` (`id`, `CityName`) VALUES
(1, 'شیراز');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `phone` text COLLATE utf8_persian_ci NOT NULL,
  `address` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dealtype`
--

CREATE TABLE `tbl_dealtype` (
  `id` int(11) NOT NULL,
  `dealType` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images`
--

CREATE TABLE `tbl_images` (
  `id` int(11) NOT NULL,
  `propertyId` int(11) NOT NULL,
  `img` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property`
--

CREATE TABLE `tbl_property` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_persian_ci NOT NULL,
  `description` text COLLATE utf8_persian_ci NOT NULL,
  `price` int(11) NOT NULL,
  `minPrice` int(11) NOT NULL,
  `maxPrice` int(11) NOT NULL,
  `propertyTypeId` int(11) NOT NULL,
  `hasElevator` int(11) NOT NULL,
  `hasParking` int(11) NOT NULL,
  `hasWarehouse` int(11) NOT NULL,
  `hasBalcony` int(11) NOT NULL,
  `bedsNo` float NOT NULL,
  `toiletsNo` int(11) NOT NULL,
  `cityId` int(11) NOT NULL,
  `regionId` text COLLATE utf8_persian_ci NOT NULL,
  `address` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `isConfirmed` int(11) NOT NULL,
  `dealTypeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_propertytype`
--

CREATE TABLE `tbl_propertytype` (
  `Id` int(11) NOT NULL,
  `PropertyType` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_propertytype`
--

INSERT INTO `tbl_propertytype` (`Id`, `PropertyType`) VALUES
(1, 'آپارتمان'),
(2, 'ویلا'),
(3, 'واحد مسکونی'),
(4, 'سوییت');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_region`
--

CREATE TABLE `tbl_region` (
  `id` int(11) NOT NULL,
  `regionName` text COLLATE utf8_persian_ci NOT NULL,
  `cityId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_region`
--

INSERT INTO `tbl_region` (`id`, `regionName`, `cityId`) VALUES
(1, 'فلکه کوزه گری', 1),
(2, 'بلوار رحمت', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `RoleId` int(11) NOT NULL,
  `RoleName` text COLLATE utf8_persian_ci NOT NULL,
  `UserPermission` tinyint(4) NOT NULL,
  `PropertyPermission` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`RoleId`, `RoleName`, `UserPermission`, `PropertyPermission`) VALUES
(1, 'مدیر راهبر', 1, 1),
(2, 'کاربر نماینده', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `lastName` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `nationalId` varchar(10) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `birthDate` text COLLATE utf8_persian_ci NOT NULL,
  `fatherName` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `phoneNumber` varchar(15) COLLATE utf8_persian_ci NOT NULL,
  `address` text COLLATE utf8_persian_ci NOT NULL,
  `roleId` int(11) NOT NULL DEFAULT 2,
  `verified` int(11) NOT NULL DEFAULT 0,
  `enabled` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `firstName`, `lastName`, `nationalId`, `birthDate`, `fatherName`, `phoneNumber`, `address`, `roleId`, `verified`, `enabled`, `createdAt`) VALUES
(1000, 'حمید', 'سرخ رو', '2560123123', '2022-04-12', 'تقی', '0936936111', 'فسا', 0, 1, 1, '2022-04-12 18:34:34'),
(1002, 'Hamid', 'Sorkhroo', '255255000', '1991-05-02', 'Taqi', '0936936000', 'fasa', 2, 0, 1, '2022-04-13 15:58:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_version`
--

CREATE TABLE `tbl_version` (
  `id` int(11) NOT NULL,
  `version` text COLLATE utf8_persian_ci NOT NULL,
  `releaseDate` text COLLATE utf8_persian_ci NOT NULL,
  `Changes` text COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_activation`
--
ALTER TABLE `tbl_activation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_bookmark`
--
ALTER TABLE `tbl_bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_dealtype`
--
ALTER TABLE `tbl_dealtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_property`
--
ALTER TABLE `tbl_property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_propertytype`
--
ALTER TABLE `tbl_propertytype`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbl_region`
--
ALTER TABLE `tbl_region`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`RoleId`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phoneIndex` (`phoneNumber`);

--
-- Indexes for table `tbl_version`
--
ALTER TABLE `tbl_version`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_activation`
--
ALTER TABLE `tbl_activation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_banners`
--
ALTER TABLE `tbl_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_bookmark`
--
ALTER TABLE `tbl_bookmark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_city`
--
ALTER TABLE `tbl_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_dealtype`
--
ALTER TABLE `tbl_dealtype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_property`
--
ALTER TABLE `tbl_property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_propertytype`
--
ALTER TABLE `tbl_propertytype`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_region`
--
ALTER TABLE `tbl_region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `RoleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- AUTO_INCREMENT for table `tbl_version`
--
ALTER TABLE `tbl_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
