-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 02:41 AM
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
-- Database: `rfid_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `rfid` varchar(255) DEFAULT NULL,
  `date` date NOT NULL DEFAULT curdate(),
  `auto_timeout` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rfid_scans`
--

CREATE TABLE `rfid_scans` (
  `id` int(11) NOT NULL,
  `rfid_number` varchar(50) NOT NULL,
  `scanned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_attendance`
--

CREATE TABLE `saved_attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `student_number` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `saved_time_in` datetime DEFAULT NULL,
  `saved_time_out` datetime DEFAULT NULL,
  `saved_date` date DEFAULT NULL,
  `present_days` int(11) DEFAULT 0,
  `auto_timeout` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_attendance`
--

INSERT INTO `saved_attendance` (`id`, `student_id`, `time_in`, `time_out`, `name`, `student_number`, `image`, `saved_time_in`, `saved_time_out`, `saved_date`, `present_days`, `auto_timeout`) VALUES
(7, 9, '0000-00-00 00:00:00', NULL, 'Jasper Andam', '220000', 'uploads/jasper.jpg', '2025-04-29 17:45:00', '2025-04-29 17:45:32', '2025-04-29', 0, 0),
(8, 19, '0000-00-00 00:00:00', NULL, 'Lester Sam Duremdes', '220312', 'uploads/680868ca60db6.jpg', '2025-04-29 17:45:02', '2025-04-29 17:45:31', '2025-04-29', 0, 0),
(9, 29, '0000-00-00 00:00:00', NULL, 'Jang Won-young', '220143', 'uploads/6810814143318.jpg', '2025-04-29 17:45:04', '2025-04-29 17:45:29', '2025-04-29', 0, 0),
(10, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/nash.jpg', '2025-04-29 17:45:06', '2025-04-29 17:45:26', '2025-04-29', 0, 0),
(11, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-04-29 17:45:09', '2025-04-29 17:45:24', '2025-04-29', 0, 0),
(12, 4, '0000-00-00 00:00:00', NULL, 'John Lloyd Figuracion', '220062', 'uploads/6806f31ae480a.jpg', '2025-04-29 17:45:11', '2025-04-29 17:45:18', '2025-04-29', 0, 0),
(13, 9, '0000-00-00 00:00:00', NULL, 'Jasper Andam', '220000', 'uploads/jasper.jpg', '2025-04-30 20:34:49', '2025-04-30 20:35:10', '2025-04-30', 0, 0),
(14, 19, '0000-00-00 00:00:00', NULL, 'Lester Sam Duremdes', '220312', 'uploads/680868ca60db6.jpg', '2025-04-30 20:34:51', '2025-04-30 20:35:15', '2025-04-30', 0, 0),
(15, 29, '0000-00-00 00:00:00', NULL, 'Jang Won-young', '220143', 'uploads/6810814143318.jpg', '2025-04-30 20:34:52', '2025-04-30 20:35:12', '2025-04-30', 0, 0),
(16, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/nash.jpg', '2025-04-30 20:34:56', '2025-04-30 20:35:08', '2025-04-30', 0, 0),
(17, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-04-30 20:34:57', '2025-04-30 20:35:03', '2025-04-30', 0, 0),
(26, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-05-01 17:25:28', '2025-05-01 17:25:40', '2025-05-01', 0, 0),
(27, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/Screenshot 2025-05-01 142136.png', '2025-05-01 17:25:30', '2025-05-01 17:25:41', '2025-05-01', 0, 0),
(28, 19, '0000-00-00 00:00:00', NULL, 'Mike Kevin Obumani', '220123', 'uploads/Screenshot 2025-05-01 141859.png', '2025-05-01 17:25:32', '2025-05-01 17:25:39', '2025-05-01', 0, 0),
(29, 29, '0000-00-00 00:00:00', NULL, 'Jang Won-young', '220143', 'uploads/6810814143318.jpg', '2025-05-02 09:36:41', '2025-05-02 09:42:03', '2025-05-02', 0, 0),
(30, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-05-02 09:37:57', '2025-05-02 09:42:07', '2025-05-02', 0, 0),
(31, 9, '0000-00-00 00:00:00', NULL, 'Jasper Andam', '220000', 'uploads/jasper.jpg', '2025-05-02 09:37:59', '2025-05-02 09:42:11', '2025-05-02', 0, 0),
(32, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/Screenshot 2025-05-01 142136.png', '2025-05-02 09:38:01', '2025-05-02 09:42:09', '2025-05-02', 0, 0),
(33, 4, '0000-00-00 00:00:00', NULL, 'John Lloyd Figuracion', '220062', 'uploads/6806f31ae480a.jpg', '2025-05-02 09:38:03', '2025-05-02 09:42:13', '2025-05-02', 0, 0),
(34, 29, '0000-00-00 00:00:00', NULL, 'Jang Won-young', '220143', 'uploads/6810814143318.jpg', '2025-05-17 01:25:26', '2025-05-17 01:28:38', '2025-05-17', 0, 0),
(35, 9, '0000-00-00 00:00:00', NULL, 'Jasper Andam', '220000', 'uploads/jasper.jpg', '2025-05-17 01:25:45', '2025-05-17 01:28:41', '2025-05-17', 0, 0),
(36, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/Screenshot 2025-05-01 142136.png', '2025-05-17 01:25:50', '2025-05-17 01:28:42', '2025-05-17', 0, 0),
(37, 19, '0000-00-00 00:00:00', NULL, 'Mike Kevin Obumani', '220123', 'uploads/Screenshot 2025-05-01 141859.png', '2025-05-17 01:25:53', '2025-05-17 01:28:44', '2025-05-17', 0, 0),
(38, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-05-17 01:25:55', '2025-05-17 01:28:45', '2025-05-17', 0, 0),
(39, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/Screenshot 2025-05-01 142136.png', '2025-09-11 02:32:59', '2025-09-11 02:33:12', '2025-09-11', 0, 0),
(40, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/Screenshot 2025-05-01 142136.png', '2025-10-12 11:57:00', '2025-10-12 11:57:06', '2025-10-12', 0, 0),
(41, 14, '0000-00-00 00:00:00', NULL, 'Nashria Macalatas', '220353', 'uploads/Screenshot 2025-05-01 142136.png', '2025-10-12 11:58:35', '2025-10-12 11:58:42', '2025-10-12', 0, 0),
(79, 19, '0000-00-00 00:00:00', NULL, 'Mike Kevin Obumani', '220123', 'uploads/Screenshot 2025-05-01 141859.png', '2025-10-13 14:25:57', '2025-10-13 14:26:16', '2025-10-13', 0, 0),
(80, 32, '0000-00-00 00:00:00', NULL, 'Joshua Basco', '220069', 'uploads/default-profile.png', '2025-10-13 14:25:58', '2025-10-13 14:26:17', '2025-10-13', 0, 0),
(81, 29, '0000-00-00 00:00:00', NULL, 'Jang Won-young', '220143', 'uploads/6810814143318.jpg', '2025-10-13 14:25:58', '2025-10-13 14:26:19', '2025-10-13', 0, 0),
(82, 33, '0000-00-00 00:00:00', NULL, 'Roxan Joy Avenido', '220021', 'uploads/68c1c9f7b9464.jpg', '2025-10-13 14:25:59', '2025-10-13 14:26:41', '2025-10-13', 0, 1),
(83, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-10-13 14:26:01', '2025-10-13 14:26:15', '2025-10-13', 0, 0),
(84, 9, '0000-00-00 00:00:00', NULL, 'Jasper Andam', '220000', 'uploads/jasper.jpg', '2025-10-13 14:26:02', '2025-10-13 14:26:14', '2025-10-13', 0, 0),
(85, 26, '0000-00-00 00:00:00', NULL, 'Oliver Burro', '220043', 'uploads/6808aedcb7671.jpg', '2025-10-14 12:09:55', '2025-10-14 12:12:53', '2025-10-14', 0, 1),
(86, 32, '0000-00-00 00:00:00', NULL, 'Joshua Basco', '220069', 'uploads/default-profile.png', '2025-10-14 12:10:48', '2025-10-14 12:11:36', '2025-10-14', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `rfid` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT 'assets/default-profile.png',
  `year_level` varchar(20) DEFAULT NULL,
  `strand_course` varchar(50) DEFAULT NULL,
  `section` varchar(20) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `student_number`, `rfid`, `image`, `year_level`, `strand_course`, `section`, `archived`) VALUES
(4, 'John Lloyd Figuracion', 'johnlloydfiguracion@gmail.com', '220062', '3870395556', 'uploads/6806f31ae480a.jpg', '4th Year', 'BSCS', 'BS7EA', 0),
(9, 'Jasper Andam', '', '220000', '3870909764', 'uploads/jasper.jpg', '4th Year', 'BSCS', 'BS7MA', 0),
(14, 'Nashria Macalatas', 'nashriamacalatas@gmail.com', '220353', '3871664244', 'uploads/Screenshot 2025-05-01 142136.png', '4th Year', 'BSCS', 'BS7MA', 0),
(19, 'Mike Kevin Obumani', '', '220123', '3870456948', 'uploads/Screenshot 2025-05-01 141859.png', '4th Year', 'BSCS', 'BS7MA', 0),
(26, 'Oliver Burro', '', '220043', '3870628244', 'uploads/6808aedcb7671.jpg', '4th Year', 'BSCS', 'BS7EA', 0),
(29, 'Jang Won-young', '', '220143', '3870258948', 'uploads/6810814143318.jpg', '1st Year', 'BS Entrep', 'BN1AA', 1),
(32, 'Joshua Basco', '', '220069', '3870943636', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7MA', 0),
(33, 'Roxan Joy Avenido', '', '220021', '3870266532', 'uploads/68c1c9f7b9464.jpg', '4th Year', 'BSCS', 'BS7MA', 1),
(34, 'Lester Sam Duremdes', 'lestersam@gmail.com', '220001', '3871111001', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7MA', 0),
(35, 'Ellaine Joy Santos', 'ellainejoy@gmail.com', '220002', '3871111002', 'uploads/default-profile.png', '3rd Year', 'BSCS', 'BS6EA', 1),
(36, 'Kyle Jericho Reyes', 'kylejericho@gmail.com', '220003', '3871111003', 'uploads/default-profile.png', '2nd Year', 'BSCS', 'BS5MA', 0),
(37, 'Mariel Gonzales', 'marielg@gmail.com', '220004', '3871111004', 'uploads/default-profile.png', '1st Year', 'BSIT', 'BN1BA', 0),
(38, 'Patrick Mendoza', 'patrickm@gmail.com', '220005', '3871111005', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7EA', 0),
(39, 'Rhea Bautista', 'rheabautista@gmail.com', '220006', '3871111006', 'uploads/default-profile.png', '3rd Year', 'BSIT', 'BS6MA', 0),
(40, 'James Carlo Perez', 'jamescarlo@gmail.com', '220007', '3871111007', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7MA', 0),
(41, 'Trisha Mae Lazo', 'trishalazo@gmail.com', '220008', '3871111008', 'uploads/default-profile.png', '2nd Year', 'BS Entrep', 'BN2AA', 0),
(42, 'Nathaniel Cruz', 'nathanielcruz@gmail.com', '220009', '3871111009', 'uploads/default-profile.png', '1st Year', 'BSCS', 'BN1AA', 1),
(43, 'Jasmine Lim', 'jasminelim@gmail.com', '220010', '3871111010', 'uploads/default-profile.png', '3rd Year', 'BSCS', 'BS6MA', 0),
(44, 'Kenneth Ramos', 'kennethramos@gmail.com', '220011', '3871111011', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7EA', 0),
(45, 'Andrea Cruzado', 'andreacruzado@gmail.com', '220012', '3871111012', 'uploads/default-profile.png', '3rd Year', 'BSIT', 'BS6EA', 0),
(46, 'Leo Francis Delos Reyes', 'leofrancis@gmail.com', '220013', '3871111013', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7MA', 0),
(47, 'Bianca Tolentino', 'biancatolentino@gmail.com', '220014', '3871111014', 'uploads/default-profile.png', '2nd Year', 'BS Entrep', 'BN2BA', 0),
(48, 'Francis Joseph Dela Cruz', 'francisjoseph@gmail.com', '220015', '3871111015', 'uploads/default-profile.png', '3rd Year', 'BSCS', 'BS6MA', 0),
(49, 'Janella Grace Cortez', 'janellacortez@gmail.com', '220016', '3871111016', 'uploads/default-profile.png', '4th Year', 'BSIT', 'BS7EA', 0),
(50, 'Angelo Vergara', 'angelov@gmail.com', '220017', '3871111017', 'uploads/default-profile.png', '2nd Year', 'BSCS', 'BS5MA', 0),
(51, 'Hannah Kim De Vera', 'hannahkim@gmail.com', '220018', '3871111018', 'uploads/default-profile.png', '1st Year', 'BS Entrep', 'BN1AA', 0),
(52, 'Renz Michael Santiago', 'renzsantiago@gmail.com', '220019', '3871111019', 'uploads/default-profile.png', '3rd Year', 'BSCS', 'BS6MA', 0),
(53, 'Clarisse Anne Robles', 'clarisseanne@gmail.com', '220020', '3871111020', 'uploads/default-profile.png', '4th Year', 'BSCS', 'BS7EA', 0);

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `violation` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `saved_attendance`
--
ALTER TABLE `saved_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD UNIQUE KEY `rfid` (`rfid`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `saved_attendance`
--
ALTER TABLE `saved_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_attendance`
--
ALTER TABLE `saved_attendance`
  ADD CONSTRAINT `saved_attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
