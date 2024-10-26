-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2024 at 03:29 AM
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
-- Database: `buspassdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 1234567891, 'adminuser@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2020-04-14 01:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `tblapplynewform`
--

CREATE TABLE `tblapplynewform` (
  `id` int(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `Contactnumber` int(200) NOT NULL,
  `DOB` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `identitytype` varchar(200) NOT NULL,
  `icum` varchar(200) NOT NULL,
  `catagorybus` varchar(200) NOT NULL,
  `fromdate` varchar(200) NOT NULL,
  `Todate` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `destination` varchar(200) NOT NULL,
  `amount` int(200) NOT NULL,
  `address` text NOT NULL,
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL,
  `pass_status` enum('active','expiring soon','expired','renewed') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblapplynewform`
--

INSERT INTO `tblapplynewform` (`id`, `name`, `gender`, `Contactnumber`, `DOB`, `email`, `image`, `identitytype`, `icum`, `catagorybus`, `fromdate`, `Todate`, `source`, `destination`, `amount`, `address`, `creationdate`, `status`, `rejection_reason`, `pass_status`) VALUES
(76, 'Bala Gomathi M', 'female', 2147483647, '2003-06-03', 'gomathivelu003@gmail.com', 'bus_regis.png', 'Adhar Card', '302670927832', '', '2024-10-12', '2024-11-12', 'Tirunelveli', 'Ambasamudram', 750, 'sadaya nayanar street', '2024-10-12 14:33:02', 'Accepted', NULL, 'active'),
(77, 'Bala Gomathi M', 'female', 2147483647, '2003-06-03', 'gomathivelu003@gmail.com', 'bus_regis.png', 'Adhar Card', '302670927832', '', '2024-10-12', '2024-11-12', 'Tirunelveli', 'Ambasamudram', 750, 'sadaya nayanar street', '2024-10-12 14:35:24', 'Accepted', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(10) NOT NULL,
  `CategoryName` varchar(200) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `CategoryName`, `CreationDate`) VALUES
(8, 'AC Bus', '2022-03-04 14:27:53'),
(9, 'Non AC Bus', '2022-03-04 14:28:32'),
(10, 'Volvo Bus', '2022-03-04 14:28:47'),
(11, 'Delux Bus', '2022-03-04 14:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontact`
--

CREATE TABLE `tblcontact` (
  `ID` int(10) NOT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Message` mediumtext DEFAULT NULL,
  `EnquiryDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsRead` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcontact`
--

INSERT INTO `tblcontact` (`ID`, `Name`, `Email`, `Message`, `EnquiryDate`, `IsRead`) VALUES
(1, 'Kiran', 'kran@gmail.com', 'cost of volvo ', '2022-05-05 07:26:24', 1),
(2, 'Anuj', 'AKKK@GMAIL.COM', 'This is for testing.', '2022-05-11 08:55:16', 1),
(3, 'chellapandian', 'chellapandiaan@gmail.com', 'please update soon', '2022-05-25 13:07:04', 1),
(4, 'pradeepraj', 'pradeepraj@gmail.com', 'please fast ', '2022-05-25 13:07:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcost`
--

CREATE TABLE `tblcost` (
  `id` int(11) NOT NULL,
  `source` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `distance_km` decimal(5,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `type` enum('Inside City','Nearby District') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcost`
--

INSERT INTO `tblcost` (`id`, `source`, `destination`, `distance_km`, `cost`, `type`) VALUES
(1, 'Tirunelveli', 'Palayamkottai', 5.00, 200.00, 'Inside City'),
(2, 'Tirunelveli', 'Pettai', 7.00, 250.00, 'Inside City'),
(3, 'Tirunelveli', 'Melapalayam', 8.00, 300.00, 'Inside City'),
(4, 'Tirunelveli', 'Valliyur', 30.00, 600.00, 'Nearby District'),
(5, 'Tirunelveli', 'Tenkasi', 55.00, 1000.00, 'Nearby District'),
(6, 'Tirunelveli', 'Sankarankovil', 47.00, 900.00, 'Nearby District'),
(7, 'Tirunelveli', 'Alangulam', 42.00, 850.00, 'Nearby District'),
(8, 'Tirunelveli', 'Kadayam', 45.00, 900.00, 'Nearby District'),
(9, 'Tirunelveli', 'Ambasamudram', 37.00, 750.00, 'Nearby District'),
(10, 'Tirunelveli', 'Kalakad', 40.00, 800.00, 'Nearby District'),
(11, 'Tirunelveli', 'Papanasam', 35.00, 700.00, 'Nearby District'),
(12, 'Tirunelveli', 'Nanguneri', 38.00, 780.00, 'Nearby District'),
(13, 'Tirunelveli', 'Cheranmahadevi', 22.00, 500.00, 'Nearby District'),
(14, 'Tirunelveli', 'Kallidaikurichi', 25.00, 550.00, 'Nearby District');

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` varchar(200) DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `MobileNumber`, `UpdationDate`) VALUES
(1, 'aboutus', 'About us', '<h2 class=\"mb-2 mt-6 text-lg first:mt-3\" style=\"border: 0px solid rgb(229, 231, 235); scrollbar-color: auto; scrollbar-width: auto; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgba(59,130,246,.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; margin: 0.75rem 0px 0.5rem; color: oklch(0.304 0.04 213.681); line-height: 1.75rem; font-family: var(--font-fk-grotesk); background-color: oklch(0.99 0.004 106.471);\"><font size=\"4\"><b>About Us</b></font></h2><span style=\"border: 0px solid rgb(229, 231, 235); scrollbar-color: auto; scrollbar-width: auto; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgba(59,130,246,.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; margin-top: 0px; color: oklch(0.304 0.04 213.681); font-family: __fkGroteskNeue_598ab8, __fkGroteskNeue_Fallback_598ab8, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; background-color: oklch(0.99 0.004 106.471);\">Our Bus Pass Booking and Management System is a comprehensive web-based platform designed to streamline the process of acquiring and managing bus passes. By leveraging the power of PHP and MySQL, we have created a secure, user-friendly, and efficient system that caters to the needs of both passengers and transit authorities.</span><span style=\"color: oklch(0.304 0.04 213.681); font-family: __fkGroteskNeue_598ab8, __fkGroteskNeue_Fallback_598ab8, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; background-color: oklch(0.99 0.004 106.471);\"></span><h2 class=\"mb-2 mt-6 text-lg first:mt-3\" style=\"border: 0px solid rgb(229, 231, 235); scrollbar-color: auto; scrollbar-width: auto; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgba(59,130,246,.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; margin: 1.5rem 0px 0.5rem; color: oklch(0.304 0.04 213.681); line-height: 1.75rem; font-family: var(--font-fk-grotesk); background-color: oklch(0.99 0.004 106.471);\"><font size=\"4\"><b>Our Mission</b></font></h2><span style=\"border: 0px solid rgb(229, 231, 235); scrollbar-color: auto; scrollbar-width: auto; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgba(59,130,246,.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; margin-top: 0px; color: oklch(0.304 0.04 213.681); font-family: __fkGroteskNeue_598ab8, __fkGroteskNeue_Fallback_598ab8, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; background-color: oklch(0.99 0.004 106.471);\">Our mission is to revolutionize the traditional method of obtaining bus passes by introducing an online system that is convenient, accessible, and reliable. We aim to provide a seamless experience for passengers while enabling transit authorities to manage bus pass operations more efficiently.</span><br>', NULL, NULL, '2024-09-11 07:16:22'),
(2, 'contactus', 'Contact Us', ' PLOT NO :2 ; PANDIAN NAGAR 3RD STREET\r\nYAGAPPA NAGAR; TIRUNELVELI-625002.', 'gomathimbala003@gmail.com', 9965857942, '2024-09-11 07:08:16');

-- --------------------------------------------------------

--
-- Table structure for table `tblpass`
--

CREATE TABLE `tblpass` (
  `ID` int(10) NOT NULL,
  `PassNumber` varchar(255) NOT NULL,
  `FullName` varchar(200) DEFAULT NULL,
  `ProfileImage` varchar(200) DEFAULT NULL,
  `ContactNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `IdentityType` varchar(200) DEFAULT NULL,
  `IdentityCardno` varchar(200) DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL,
  `Source` varchar(200) DEFAULT NULL,
  `Destination` varchar(200) DEFAULT NULL,
  `FromDate` varchar(200) DEFAULT NULL,
  `ToDate` varchar(200) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `PasscreationDate` timestamp NULL DEFAULT current_timestamp(),
  `Status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `payment_status` enum('Not Paid','Paid') DEFAULT 'Not Paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpass`
--

INSERT INTO `tblpass` (`ID`, `PassNumber`, `FullName`, `ProfileImage`, `ContactNumber`, `Email`, `IdentityType`, `IdentityCardno`, `Category`, `Source`, `Destination`, `FromDate`, `ToDate`, `amount`, `PasscreationDate`, `Status`, `payment_status`) VALUES
(55, 'BALA7832', 'Bala Gomathi M', NULL, 2147483647, 'gomathivelu003@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1000, '2024-10-05 15:38:15', 'Accepted', 'Not Paid');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment`
--

CREATE TABLE `tblpayment` (
  `PaymentID` int(11) NOT NULL,
  `PassNumber` varchar(255) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `PaymentStatus` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TransactionID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblrenewal`
--

CREATE TABLE `tblrenewal` (
  `id` int(200) NOT NULL,
  `FIRSTNAME` varchar(200) NOT NULL,
  `GMAIL` varchar(200) NOT NULL,
  `CONTACTNUMBER` int(200) NOT NULL,
  `PASSNUMBER` int(200) NOT NULL,
  `fromdate` varchar(200) NOT NULL,
  `todate` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `destination` varchar(200) NOT NULL,
  `ANYCOMMAND` varchar(200) NOT NULL,
  `creationdate` timestamp(6) NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblrenewal`
--

INSERT INTO `tblrenewal` (`id`, `FIRSTNAME`, `GMAIL`, `CONTACTNUMBER`, `PASSNUMBER`, `fromdate`, `todate`, `source`, `destination`, `ANYCOMMAND`, `creationdate`) VALUES
(16, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 16:57:49.300425'),
(17, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:14:24.012466'),
(18, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:18:52.762982'),
(19, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:20:54.262519'),
(20, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:26:10.546550'),
(21, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:26:24.734397'),
(22, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:35:24.171157'),
(23, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:37:23.954662'),
(24, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:37:43.326573'),
(25, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:38:48.383887'),
(26, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:42:08.762597'),
(27, 'Bala Gomathi', 'gomathivelu003@gmail.com', 2147483647, 0, '2024-10-08', '2024-11-08', 'Tirunelveli', 'Tenkasi', 'Thank you', '2024-10-08 17:43:16.253629');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `mobile`, `password`, `created_at`) VALUES
(1, 'Bala Gomathi', 'balagomathi003@gmail.com', '9876543210', '$2y$10$mVuIOUpYEJiTxPTQYLYFKOLw/wK0gqgWuHFQFiag.1vMkPVDuBwR.', '2024-07-23 12:27:50'),
(0, 'Bala Gomathi', 'gomathivelu003@gmail.com', '9965857942', '$2y$10$BGjZxXJcx82cf28/s1Vhz.06Yfvo9xkxbjsT4tPINql0ofVbxiMr.', '2024-10-05 15:36:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblapplynewform`
--
ALTER TABLE `tblapplynewform`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcontact`
--
ALTER TABLE `tblcontact`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcost`
--
ALTER TABLE `tblcost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpass`
--
ALTER TABLE `tblpass`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `PassNumber` (`PassNumber`);

--
-- Indexes for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `PassNumber` (`PassNumber`);

--
-- Indexes for table `tblrenewal`
--
ALTER TABLE `tblrenewal`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblapplynewform`
--
ALTER TABLE `tblapplynewform`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblcontact`
--
ALTER TABLE `tblcontact`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblcost`
--
ALTER TABLE `tblcost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpass`
--
ALTER TABLE `tblpass`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `tblpayment`
--
ALTER TABLE `tblpayment`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrenewal`
--
ALTER TABLE `tblrenewal`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD CONSTRAINT `tblpayment_ibfk_1` FOREIGN KEY (`PassNumber`) REFERENCES `tblpass` (`PassNumber`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
