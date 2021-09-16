-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2021 at 07:56 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `custom`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `id` int(20) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`id`, `username`, `password`, `email`, `fullname`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'aoclems@gmail.com', 'Clement');

-- --------------------------------------------------------

--
-- Table structure for table `bol_specific_segment`
--

CREATE TABLE `bol_specific_segment` (
  `id` int(20) NOT NULL,
  `xmlid` varchar(200) NOT NULL,
  `Line_number` varchar(200) NOT NULL,
  `Previous_document_reference` varchar(200) NOT NULL,
  `Bol_Nature` varchar(200) NOT NULL,
  `Unique_carrier_reference` varchar(200) NOT NULL,
  `Total_number_of_containers` varchar(200) NOT NULL,
  `Total_gross_mass_manifested` varchar(200) NOT NULL,
  `Volume_in_cubic_meters` varchar(200) NOT NULL,
  `Bol_type_segment_code` varchar(200) NOT NULL,
  `Exporter_segment_code` varchar(200) NOT NULL,
  `Exporter_segment_name` varchar(200) NOT NULL,
  `Exporter_segment_addr` varchar(200) NOT NULL,
  `Consignee_segment_code` varchar(200) NOT NULL,
  `Consignee_name` varchar(200) NOT NULL,
  `Consignee_segment_addr` varchar(200) NOT NULL,
  `Notify_segment_code` varchar(200) NOT NULL,
  `Notify_segment_name` varchar(200) NOT NULL,
  `Notify_segment_addr` varchar(200) NOT NULL,
  `Place_of_loading_segment_code` varchar(200) NOT NULL,
  `Place_of_unloading_segment_code` varchar(200) NOT NULL,
  `Package_type_code` varchar(200) NOT NULL,
  `Number_of_packages` varchar(200) NOT NULL,
  `Shipping_marks` varchar(200) NOT NULL,
  `Goods_description` varchar(200) NOT NULL,
  `Freight_segment_val` varchar(200) NOT NULL,
  `Freight_segment_cur` varchar(200) NOT NULL,
  `Indicator_segment_code` varchar(200) NOT NULL,
  `Customs_segment_val` varchar(200) NOT NULL,
  `Customs_segment_cur` varchar(200) NOT NULL,
  `Transport_segment_val` varchar(200) NOT NULL,
  `Transport_segment_cur` varchar(200) NOT NULL,
  `Insurance_segment_val` varchar(200) NOT NULL,
  `Insurance_segment_cur` varchar(200) NOT NULL,
  `Number_of_seals` varchar(200) NOT NULL,
  `Marks_of_seals` varchar(200) NOT NULL,
  `Sealing_party_code` varchar(200) NOT NULL,
  `Information_part_a` varchar(200) NOT NULL,
  `Location_segment_code` varchar(200) NOT NULL,
  `Location_segment_info` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `container`
--

CREATE TABLE `container` (
  `id` int(20) NOT NULL,
  `xmlid` varchar(200) NOT NULL,
  `Referencez` varchar(200) NOT NULL,
  `Numberz` varchar(200) NOT NULL,
  `Typez` varchar(200) NOT NULL,
  `Empty` varchar(200) NOT NULL,
  `Seals` varchar(200) NOT NULL,
  `Mark1` varchar(200) NOT NULL,
  `mark2` varchar(200) NOT NULL,
  `Sealing_party` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `identification_segment`
--

CREATE TABLE `identification_segment` (
  `id` int(20) NOT NULL,
  `xmlid` varchar(200) NOT NULL,
  `reg_number` varchar(200) NOT NULL,
  `dated` varchar(200) NOT NULL,
  `bol_ref` varchar(200) NOT NULL,
  `custom_seg` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uploadd`
--

CREATE TABLE `uploadd` (
  `id` int(20) NOT NULL,
  `companyname` varchar(200) NOT NULL,
  `fileext` varchar(200) NOT NULL,
  `dateofupload` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uploadd`
--

INSERT INTO `uploadd` (`id`, `companyname`, `fileext`, `dateofupload`) VALUES
(1, 'Custome', 'bol446ncs.xml', '12-09-2021'),
(2, 'z', 'bol446ncs.xml', '13-09-2021'),
(3, 'hhh', 'bol446ncs.xml', '13-09-2021'),
(4, 'Custome', 'bol446ncs.xml', '13-09-2021'),
(5, 'Custome', 'bol446ncs.xml', '13-09-2021');

-- --------------------------------------------------------

--
-- Table structure for table `xmldata`
--

CREATE TABLE `xmldata` (
  `id` int(20) NOT NULL,
  `xmlid` varchar(200) NOT NULL,
  `Registry_number` varchar(200) NOT NULL,
  `dates` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bol_specific_segment`
--
ALTER TABLE `bol_specific_segment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `container`
--
ALTER TABLE `container`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identification_segment`
--
ALTER TABLE `identification_segment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploadd`
--
ALTER TABLE `uploadd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xmldata`
--
ALTER TABLE `xmldata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bol_specific_segment`
--
ALTER TABLE `bol_specific_segment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container`
--
ALTER TABLE `container`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identification_segment`
--
ALTER TABLE `identification_segment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `uploadd`
--
ALTER TABLE `uploadd`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `xmldata`
--
ALTER TABLE `xmldata`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
