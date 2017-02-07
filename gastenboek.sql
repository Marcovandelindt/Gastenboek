-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2017 at 03:55 PM
-- Server version: 5.6.31
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gastenboek`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL,
  `replied_to` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `replied_to`, `username`, `message`, `date`) VALUES
(1, 1, 'Marco', 'Hallo', '2017-02-06 14:45:33'),
(2, 8, 'Marco', 'This is a reply.', '2017-02-06 15:53:26');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL,
  `username` varchar(154) NOT NULL,
  `image` varchar(254) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` mediumint(9) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `username`, `image`, `message`, `date`, `likes`) VALUES
(2, 'Marco', 'placeholdermale.jpg', 'Hello, this is a message from Marco van de Lindt.', '2017-02-03 08:46:05', 1),
(3, 'Henk', 'placeholderdog.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', '2017-02-03 08:47:11', 0),
(6, 'Marco', 'placeholdermale.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', '2017-02-03 14:29:39', 0),
(8, 'Marco', 'placeholdermale.jpg', 'Hello, this is a test message. ', '2017-02-06 12:29:39', 0),
(15, 'Marco', 'placeholdermale.jpg', 'Hello, my name is Marco van de Lindt and this is a message sent from the homepage. ', '2017-02-07 09:34:54', 0),
(16, 'Berend', 'placeholdercat.jpg', 'Hello, this is a message written by Berend!', '2017-02-07 16:26:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `email` varchar(254) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(16) NOT NULL,
  `ip_address` varchar(254) NOT NULL,
  `user_agent` varchar(254) NOT NULL,
  `logins` varchar(100) NOT NULL DEFAULT '0',
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `country` varchar(150) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `bio` text NOT NULL,
  `status` enum('Online','Offline','','') NOT NULL DEFAULT 'Offline',
  `last_online` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `joined_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `salt`, `ip_address`, `user_agent`, `logins`, `first_name`, `last_name`, `birth_date`, `country`, `nickname`, `bio`, `status`, `last_online`, `joined_date`) VALUES
(5, 'marcovandelindt@live.nl', 'Marco', '689c2bc001ca076d64118eab38d3e21fd2a41c3ff3754c380722d14cba6b37c3', '3e6fa5242bf6ff93', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '33', 'Marco', 'van de Lindt', '1998-02-16', 'Netherlands', 'Marchoofd', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'Online', '2017-02-07 16:42:00', '2017-02-06 08:49:27'),
(14, 'henk@test.nl', 'Henk', '634425451eae3430717f29213674837650a3cb276eb07f2cc8786535ca1bab37', '7a49ac7c1ba67068', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '2', 'Henk', 'van Veen', '1975-06-17', 'Netherlands', 'Henkie', 'Hoi, ik ben Henk en ik ben 41 jaar oud. Ik heb  drie dochters, vier katten en twee honden.', 'Offline', '2017-02-06 11:52:00', '2017-02-06 08:49:27'),
(17, 'berend@hotmail.com', 'Berend', 'd399fa3cb0efd77b7c6798635c98926ba1ee00595f8fe657ba87ce4b2e6fa846', '5062a6c558822f80', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '1', 'Berend', 'de Man', '1987-02-19', 'Netherlands', 'Beer', '', 'Offline', '2017-02-03 16:16:56', '2017-02-06 08:49:27'),
(18, 'frederik@test.nl', 'Frederik', '5a9ef95c1cfd17eb77e3fd0f62a0f61ad45065d880bd6b759132ed8beea4edc1', '61ce58eb662a0267', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', '1', '', '', '0000-00-00', '', '', '', 'Offline', '2017-02-06 11:58:00', '2017-02-06 11:57:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
