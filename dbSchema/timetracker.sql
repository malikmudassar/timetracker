-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2020 at 10:37 AM
-- Server version: 5.7.29-0ubuntu0.16.04.1
-- PHP Version: 7.2.23-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timetracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL,
  `parent` int(3) NOT NULL,
  `name` varchar(40) NOT NULL,
  `class` varchar(50) NOT NULL,
  `url` varchar(89) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `parent`, `name`, `class`, `url`) VALUES
(1, 0, 'Users', 'icon icon-users', ''),
(5, 1, 'Add', '', 'admin/add_user'),
(6, 1, 'Manage', '', 'admin/manage_users'),
(7, 0, 'Attendance', 'icon icon-calendar', ''),
(8, 7, 'Daily Report', '', 'admin/report_attendance');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `hours` int(2) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `date`, `check_in`, `check_out`, `hours`, `remarks`) VALUES
(1, 3, '2017-09-25', '2017-09-25 04:00:00', '2017-09-25 12:00:00', 8, ''),
(2, 3, '2017-09-26', '2017-09-26 04:00:00', '2017-09-26 13:00:00', 9, ''),
(7, 3, '2017-09-28', '2017-09-28 04:06:01', '2017-09-28 12:59:51', 7, ''),
(8, 4, '2017-09-28', '2017-09-28 04:05:00', '2017-09-28 13:16:00', 0, ''),
(9, 5, '2017-09-28', '2017-09-28 05:07:00', '2017-09-28 13:30:00', 0, ''),
(11, 2, '2017-09-28', '2017-09-27 21:22:44', '2017-09-30 13:04:00', 0, ''),
(12, 3, '2017-09-29', '2017-09-29 03:22:13', '2017-09-29 04:44:12', 1, 'Going to restroom'),
(13, 3, '2017-09-29', '2017-09-29 04:54:42', '2017-09-29 05:03:17', 0, 'Going for a smoke'),
(14, 3, '2017-09-29', '2017-09-29 05:18:41', '2017-09-29 06:30:23', 1, 'shift over'),
(16, 3, '2017-09-29', '2017-09-29 08:24:00', '2017-09-29 08:24:52', 0, 'Going for a smoke'),
(17, 3, '2020-03-05', '2020-03-04 21:18:32', NULL, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `team_menu`
--

CREATE TABLE `team_menu` (
  `id` int(5) NOT NULL,
  `parent` int(3) NOT NULL,
  `name` varchar(90) NOT NULL,
  `class` varchar(80) NOT NULL,
  `url` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team_menu`
--

INSERT INTO `team_menu` (`id`, `parent`, `name`, `class`, `url`) VALUES
(1, 0, 'Attendance', 'icon icon-calendar', ''),
(2, 1, 'Monthly Report', '', 'team'),
(3, 0, 'Account', 'icon icon-archive', ''),
(4, 3, 'Edit Profile', '', 'team/edit_profile'),
(5, 3, 'Change Password', '', 'team/change_password'),
(6, 1, 'Mark Attendance', '', 'team/mark_attendance');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `designation` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `location` varchar(90) NOT NULL,
  `mobile` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `designation`, `email`, `password`, `location`, `mobile`) VALUES
(1, 'hira', 1, 'HR Manager', 'hira@wadic.net', '496d8d37306246ef4ea37bddb7e7355e', 'Pakistan', '0300-2009000'),
(2, 'Mudassar', 2, 'PHP Developer', 'mudassar.khani@attech-ltd.com', '496d8d37306246ef4ea37bddb7e7355e', 'Pakistan', '03215491752'),
(3, 'malik', 2, 'Developer', 'malikmudassar@gmail.com', '496d8d37306246ef4ea37bddb7e7355e', 'Pakistan', '03215491752'),
(4, 'Farhan Asim', 2, 'Sr PHP Developer', 'farhan@wadic.net', '496d8d37306246ef4ea37bddb7e7355e', 'Pakistan', '1234567890'),
(5, 'Fasial Khalil', 2, 'Designer', 'faisal@wadic.net', '496d8d37306246ef4ea37bddb7e7355e', 'Pakistan', '5678945');

-- --------------------------------------------------------

--
-- Table structure for table `user_code`
--

CREATE TABLE `user_code` (
  `id` int(5) NOT NULL,
  `user_id` int(4) NOT NULL,
  `code` int(4) NOT NULL,
  `is_expire` enum('no','yes') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(3) NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_menu`
--
ALTER TABLE `admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_menu`
--
ALTER TABLE `team_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_code`
--
ALTER TABLE `user_code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_menu`
--
ALTER TABLE `admin_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `team_menu`
--
ALTER TABLE `team_menu`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_code`
--
ALTER TABLE `user_code`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
