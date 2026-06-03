-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2026 at 11:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ozone_rms`
--
CREATE DATABASE IF NOT EXISTS `ozone_rms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ozone_rms`;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--
-- Creation: Jun 02, 2026 at 08:13 PM
--

DROP TABLE IF EXISTS `calendar_events`;
CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `title` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'School Event',
  `details` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--
-- Creation: May 30, 2026 at 10:13 AM
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('New','Read','Responded') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT DELAYED INTO `contact_messages` (`id`, `fullname`, `email`, `subject`, `message`, `created_at`, `status`) VALUES
(4, 'Andualem Deblake', 'andex9690@gmail.com', 'About the result', 'Results should be good', '2026-05-30 11:11:26', 'Responded'),
(5, 'Andualem Deblake', 'andex9690@gmail.com', 'About the result', 'You message has been accepted successfully!', '2026-05-30 12:18:23', 'Responded'),
(6, 'Andualem Deblake', 'andex9690@gmail.com', 'About the result', 'hey there', '2026-05-30 12:35:49', 'Responded'),
(7, 'Andualem Deblake', 'andex9690@gmail.com', 'About the result', 'hey there', '2026-05-30 14:05:57', 'New'),
(8, 'Andualem Deblake', 'andex9690@gmail.com', 'About the result', 'Hey admin', '2026-05-30 15:48:13', 'Responded');

-- --------------------------------------------------------

--
-- Table structure for table `contact_responses`
--
-- Creation: May 30, 2026 at 11:20 AM
--

DROP TABLE IF EXISTS `contact_responses`;
CREATE TABLE `contact_responses` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `response_text` text NOT NULL,
  `responded_by` varchar(100) NOT NULL,
  `responded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_responses`
--

INSERT DELAYED INTO `contact_responses` (`id`, `contact_id`, `response_text`, `responded_by`, `responded_at`, `email_sent`) VALUES
(1, 4, 'Hey dear, your question has been accepted.', 'admin', '2026-05-30 11:20:34', 0),
(2, 5, 'I Have accepted your request!', 'admin', '2026-05-30 12:19:26', 0),
(3, 6, 'Hey dears', 'admin', '2026-05-30 12:36:22', 0),
(4, 8, 'hey dears!', 'admin', '2026-05-30 15:49:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--
-- Creation: May 23, 2026 at 07:14 PM
--

DROP TABLE IF EXISTS `parent`;
CREATE TABLE `parent` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent`
--

INSERT DELAYED INTO `parent` (`id`, `name`, `username`, `password`, `role`) VALUES
(1, 'Almaz', 'alm123', '123456', 'parent'),
(2, 'Bereket', 'bek12', '234561', 'parent'),
(3, 'Lema ', 'lema12', '345612', 'parent'),
(4, 'Mulu', 'mulu12', '456123', 'parent'),
(5, 'Ayele', 'ayele12', '561234', 'parent');

-- --------------------------------------------------------

--
-- Table structure for table `parent_messages`
--
-- Creation: May 23, 2026 at 08:57 PM
--

DROP TABLE IF EXISTS `parent_messages`;
CREATE TABLE `parent_messages` (
  `id` int(11) NOT NULL,
  `parent_username` varchar(100) NOT NULL,
  `sender_role` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_messages`
--

INSERT DELAYED INTO `parent_messages` (`id`, `parent_username`, `sender_role`, `message`, `sent_at`) VALUES
(1, 'alm123', 'Admin', 'Hello parent, this is an admin message.', '2026-05-23 20:58:07'),
(2, 'alm123', 'Admin', 'hey this is from admin', '2026-05-23 21:06:35'),
(3, 'admin', 'Parent', 'hey this is from parent', '2026-05-24 13:15:01'),
(4, 'bek12', 'Admin', 'I got your message!', '2026-05-24 13:17:45'),
(5, 'admin', 'Parent', 'Parent reply verification test', '2026-05-24 13:26:06'),
(6, 'admin', 'Parent', 'Parent reply verification test', '2026-05-24 13:26:41'),
(7, 'admin', 'Parent', 'Parent reply verification test', '2026-05-24 13:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `parent_student_links`
--
-- Creation: Jun 02, 2026 at 08:13 PM
-- Last update: Jun 03, 2026 at 08:45 PM
--

DROP TABLE IF EXISTS `parent_student_links`;
CREATE TABLE `parent_student_links` (
  `parent_user_id` int(11) NOT NULL,
  `student_user_id` int(11) NOT NULL,
  `relation` varchar(50) DEFAULT 'Child',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_student_links`
--

INSERT DELAYED INTO `parent_student_links` (`parent_user_id`, `student_user_id`, `relation`, `created_at`) VALUES
(4, 2, 'Child', '2026-06-03 20:31:12'),
(5, 3, 'Child', '2026-06-03 20:45:03');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--
-- Creation: May 30, 2026 at 12:23 PM
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT DELAYED INTO `site_settings` (`name`, `value`) VALUES
('Andualem Debalke', 'andex9690@gmail.com'),
('contact_from_email', 'andex9690@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--
-- Creation: May 24, 2026 at 07:45 PM
-- Last update: Jun 03, 2026 at 08:51 PM
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `id` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` text NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `attendance_rate` decimal(5,2) DEFAULT NULL,
  `absences` int(11) DEFAULT 0,
  `active_courses` int(11) DEFAULT 0,
  `term` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT DELAYED INTO `student` (`id`, `username`, `password`, `role`, `first_name`, `last_name`, `grade`, `gpa`, `attendance_rate`, `absences`, `active_courses`, `term`) VALUES
('1', 'mesk123', '123456\r\n', 'student', 'Meskerem ', 'Temesge', '12', 2.90, 8.50, 1, 1, 'Spring Term 2026'),
('2', 'mulu123', '23451', 'student', 'Mulugeta ', 'Begeta', '12', 2.90, 98.00, 3, 2, 'Spring Term 2026'),
('3', 'andy123', '345126', 'student', 'Andualem', 'Debalke', '11', 4.10, 98.00, 1, 4, 'Spring Term 2026'),
('4', 'kidu123', '45123', 'student', 'Kidus', 'Getachew', '11', 4.00, 89.00, 7, 10, 'Spring Term 2026'),
('5', 'sifu123', '561234', 'student', 'Sifen', 'Mendefro', '11', 4.00, 98.00, 10, 6, 'Spring Term 2026'),
('6', 'eli123', '561235', 'student', 'Eliyana', 'Yisak', '11', 4.00, 98.00, 5, 3, 'Spring Term 2026'),
('7', 'elshu123', '123456', 'student', 'Elshaday', 'Asfaw', '11', 4.10, 98.90, 5, 4, 'Spring Term 2026'),
('8', 'alex123', 'student123', 'student', 'Alex', 'Abebe', '11', 3.84, 98.00, 2, 3, 'Spring Term 2026');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--
-- Creation: Jun 03, 2026 at 03:52 PM
-- Last update: Jun 03, 2026 at 09:16 PM
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `stream` varchar(50) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `attendance_rate` decimal(5,2) DEFAULT NULL,
  `absences` int(11) DEFAULT 0,
  `active_courses` int(11) DEFAULT 6
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT DELAYED INTO `students` (`id`, `user_id`, `first_name`, `last_name`, `grade`, `stream`, `gpa`, `enrollment_date`, `attendance_rate`, `absences`, `active_courses`) VALUES
(2, 3, 'Elshaday', 'Asfaw', '11', NULL, 3.84, '0000-00-00', 89.00, 5, 6),
(6, 2, 'Andualem', 'Debalke', '11', 'Natural Science', 3.98, '0000-00-00', 79.00, 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance_records`
--
-- Creation: May 24, 2026 at 07:49 PM
--

DROP TABLE IF EXISTS `student_attendance_records`;
CREATE TABLE `student_attendance_records` (
  `id` int(11) NOT NULL,
  `student_id` varchar(25) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_attendance_records`
--

INSERT DELAYED INTO `student_attendance_records` (`id`, `student_id`, `attendance_date`, `status`, `note`) VALUES
(1, '1001', '2026-05-20', 'Present', 'On time'),
(2, '1001', '2026-05-21', 'Present', 'On time'),
(3, '1001', '2026-05-22', 'Present', 'Arrived 5 minutes late'),
(4, '1001', '2026-05-23', 'Absent', 'Excused absence'),
(5, '1001', '2026-05-24', 'Present', 'On time');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--
-- Creation: May 24, 2026 at 07:49 PM
-- Last update: Jun 03, 2026 at 03:03 PM
--

DROP TABLE IF EXISTS `student_courses`;
CREATE TABLE `student_courses` (
  `id` int(11) NOT NULL,
  `student_id` varchar(25) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL,
  `term` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_courses`
--

INSERT DELAYED INTO `student_courses` (`id`, `student_id`, `course_name`, `instructor`, `status`, `term`) VALUES
(1, '1001', 'WEB Development', 'Mr. Henok', 'Active', 'Spring Term 2026'),
(2, '1001', 'Mathematics', 'Mr. Sisay', 'Active', 'Spring Term 2026'),
(3, '1001', 'Physics', 'Mr. Sofoniyas', 'Active', 'Spring Term 2026'),
(4, '1001', 'Biology', 'Mr. Teketelew', 'Active', 'Spring Term 2026'),
(5, '2', 'History', 'Mr. Alemu', 'Active', 'Spring Term 2026'),
(6, '2', 'Geography', 'Mr. Moges', 'Active', 'Spring Term 2026'),
(7, '2', 'Amharic', 'Mrs. Alem', 'Active', 'Spring Term 2026'),
(8, '2', 'English', 'Mrs. Eliyana', 'Active', 'Spring Term 2026'),
(9, '2', 'English ', 'Mrs. Beza', 'Active', 'Spring Term 2026'),
(10, '2', 'Citizenship', 'Mr. Wegayehu', 'Active', 'Spring Term 2026');

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--
-- Creation: May 24, 2026 at 07:49 PM
--

DROP TABLE IF EXISTS `student_grades`;
CREATE TABLE `student_grades` (
  `id` int(11) NOT NULL,
  `student_id` varchar(25) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `term` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_grades`
--

INSERT DELAYED INTO `student_grades` (`id`, `student_id`, `course_name`, `instructor`, `grade`, `term`) VALUES
(1, '1001', 'Computer Science', 'Mr. Henok', '12', 'Spring Term 2026'),
(2, '1001', 'Mathematics', 'Mr. Sisay', '11', 'Spring Term 2026'),
(3, '1001', 'Physics ', 'Mr. Sofonias', '10', 'Spring Term 2026'),
(4, '1001', 'English', 'Mr. David', '9', 'Spring Term 2026');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jun 02, 2026 at 09:14 PM
-- Last update: Jun 03, 2026 at 08:53 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` int(50) NOT NULL,
  `First name` varchar(100) NOT NULL,
  `Last name` varchar(100) NOT NULL,
  `Grade` varchar(5) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(225) NOT NULL,
  `Password` int(50) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT DELAYED INTO `users` (`ID`, `First name`, `Last name`, `Grade`, `Username`, `Email`, `Password`, `Role`, `Created_at`) VALUES
(1, 'Mr. Gashaw  ', 'Taddesse', '-', 'gashaw12', 'gashaw@gmail.com', 123456, 'Admin', '2026-06-03 14:48:02'),
(2, 'Andualem', 'Debalke', '11', 'andy12', 'andy@gmail.com', 123456, 'Student', '2026-06-03 14:51:33'),
(3, 'Elshaday', 'Asfaw', '11', 'eslhu123', 'elshu@gmail.com', 123456, 'student', '2026-06-03 20:53:52'),
(4, 'Bereket', 'Tamitru', '-', 'bek123', 'Bereket@gmail.com', 123456, 'Parent', '2026-06-03 20:30:19'),
(5, 'Asfaw ', 'Ayele', '-', 'asfa123', 'asfaw@gmail.com', 123456, 'Parent', '2026-06-03 20:47:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_responses`
--
ALTER TABLE `contact_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `parent_messages`
--
ALTER TABLE `parent_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent_student_links`
--
ALTER TABLE `parent_student_links`
  ADD PRIMARY KEY (`parent_user_id`,`student_user_id`),
  ADD KEY `student_user_id` (`student_user_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `student_attendance_records`
--
ALTER TABLE `student_attendance_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_responses`
--
ALTER TABLE `contact_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `parent_messages`
--
ALTER TABLE `parent_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `student_attendance_records`
--
ALTER TABLE `student_attendance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD CONSTRAINT `calendar_events_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_responses`
--
ALTER TABLE `contact_responses`
  ADD CONSTRAINT `contact_responses_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contact_messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_student_links`
--
ALTER TABLE `parent_student_links`
  ADD CONSTRAINT `parent_student_links_ibfk_1` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parent_student_links_ibfk_2` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
