-- phpMyAdmin SQL Dump
-- version 4.6.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 04, 2016 at 06:16 PM
-- Server version: 5.5.47-0+deb8u1
-- PHP Version: 7.0.5-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dreamvids`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_auth_token`
--

CREATE TABLE `api_auth_token` (
  `id` int(11) NOT NULL,
  `token` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `api_auth_token`
--

INSERT INTO `api_auth_token` (`id`, `token`, `redirect_url`, `expiration`, `client_id`) VALUES
(8, '0774ef26eb6247af07775b09e8ada266bf34fe96', 'http://dreamvids.fr', 1472997456, 3);

-- --------------------------------------------------------

--
-- Table structure for table `api_client`
--

CREATE TABLE `api_client` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `api_client`
--

INSERT INTO `api_client` (`id`, `name`, `domain`, `public_key`, `private_key`, `admin`) VALUES
(3, 'root', 'localhost', '9b55842ccdb4aae14fda2b22098eb9e682cb2b2c9f74d638f8292b4956af583eafb6e5567d5b1192e91ce37c08cae496d4c18fdcf8f411b304ddd8593a892475', '7746c0d84f7448c8fccc2104ee927d56c39825413296ebd30a926bcdbc70b9f2bf7081b13acdcdfc52f95019a155192d5a90d033f099f63acd207a4c1fe64852', 1);

-- --------------------------------------------------------

--
-- Table structure for table `api_permission`
--

CREATE TABLE `api_permission` (
  `id` int(11) NOT NULL,
  `permission` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `api_permission`
--

INSERT INTO `api_permission` (`id`, `permission`, `client_id`) VALUES
(1, '*', 3);

-- --------------------------------------------------------

--
-- Table structure for table `dv_annotation`
--

CREATE TABLE `dv_annotation` (
  `id` int(11) NOT NULL,
  `todo_add_reals_columns` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_channel`
--

CREATE TABLE `dv_channel` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `background` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_channel_admin`
--

CREATE TABLE `dv_channel_admin` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_channel_subscription`
--

CREATE TABLE `dv_channel_subscription` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_comment`
--

CREATE TABLE `dv_comment` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `flagged` tinyint(1) NOT NULL,
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_conversation`
--

CREATE TABLE `dv_conversation` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_conversation_user`
--

CREATE TABLE `dv_conversation_user` (
  `id` int(11) NOT NULL,
  `conv_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_message`
--

CREATE TABLE `dv_message` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_playlist`
--

CREATE TABLE `dv_playlist` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `channel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_playlist_video`
--

CREATE TABLE `dv_playlist_video` (
  `id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_post`
--

CREATE TABLE `dv_post` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `flagged` tinyint(1) NOT NULL,
  `channel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_rank`
--

CREATE TABLE `dv_rank` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dv_rank`
--

INSERT INTO `dv_rank` (`id`, `name`) VALUES
(1, 'Member'),
(2, 'Modo'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `dv_session`
--

CREATE TABLE `dv_session` (
  `id` int(11) NOT NULL,
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `expiration_timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_tag`
--

CREATE TABLE `dv_tag` (
  `id` int(11) NOT NULL,
  `tag` varchar(139) COLLATE utf8_unicode_ci NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_user`
--

CREATE TABLE `dv_user` (
  `id` int(11) NOT NULL,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reg_timestamp` int(11) NOT NULL,
  `reg_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `current_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rank_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_video`
--

CREATE TABLE `dv_video` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `url` int(255) NOT NULL,
  `post_timestamp` int(11) NOT NULL,
  `flagged` tinyint(1) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `visibility_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_view`
--

CREATE TABLE `dv_view` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_visibility`
--

CREATE TABLE `dv_visibility` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dv_vote`
--

CREATE TABLE `dv_vote` (
  `id` int(11) NOT NULL,
  `value` tinyint(1) NOT NULL,
  `content_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_auth_token`
--
ALTER TABLE `api_auth_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `clinet_id` (`client_id`);

--
-- Indexes for table `api_client`
--
ALTER TABLE `api_client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `public_key` (`public_key`),
  ADD UNIQUE KEY `private_key` (`private_key`);

--
-- Indexes for table `api_permission`
--
ALTER TABLE `api_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `dv_annotation`
--
ALTER TABLE `dv_annotation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `dv_channel`
--
ALTER TABLE `dv_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dv_channel_admin`
--
ALTER TABLE `dv_channel_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dv_channel_subscription`
--
ALTER TABLE `dv_channel_subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dv_comment`
--
ALTER TABLE `dv_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `dv_conversation`
--
ALTER TABLE `dv_conversation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dv_conversation_user`
--
ALTER TABLE `dv_conversation_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conv_id` (`conv_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dv_message`
--
ALTER TABLE `dv_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `conv_id` (`conv_id`);

--
-- Indexes for table `dv_playlist`
--
ALTER TABLE `dv_playlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `dv_playlist_video`
--
ALTER TABLE `dv_playlist_video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `playlist_id` (`playlist_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `dv_post`
--
ALTER TABLE `dv_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `dv_rank`
--
ALTER TABLE `dv_rank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dv_session`
--
ALTER TABLE `dv_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `dv_tag`
--
ALTER TABLE `dv_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `dv_user`
--
ALTER TABLE `dv_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rank_id` (`rank_id`);

--
-- Indexes for table `dv_video`
--
ALTER TABLE `dv_video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `visibility_id` (`visibility_id`);

--
-- Indexes for table `dv_view`
--
ALTER TABLE `dv_view`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `dv_visibility`
--
ALTER TABLE `dv_visibility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dv_vote`
--
ALTER TABLE `dv_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_auth_token`
--
ALTER TABLE `api_auth_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `api_client`
--
ALTER TABLE `api_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `api_permission`
--
ALTER TABLE `api_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `dv_annotation`
--
ALTER TABLE `dv_annotation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_channel`
--
ALTER TABLE `dv_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `dv_channel_admin`
--
ALTER TABLE `dv_channel_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_channel_subscription`
--
ALTER TABLE `dv_channel_subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_comment`
--
ALTER TABLE `dv_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_conversation`
--
ALTER TABLE `dv_conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_conversation_user`
--
ALTER TABLE `dv_conversation_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_message`
--
ALTER TABLE `dv_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_playlist`
--
ALTER TABLE `dv_playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_playlist_video`
--
ALTER TABLE `dv_playlist_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_post`
--
ALTER TABLE `dv_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_rank`
--
ALTER TABLE `dv_rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `dv_session`
--
ALTER TABLE `dv_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `dv_tag`
--
ALTER TABLE `dv_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_user`
--
ALTER TABLE `dv_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `dv_video`
--
ALTER TABLE `dv_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_view`
--
ALTER TABLE `dv_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_visibility`
--
ALTER TABLE `dv_visibility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dv_vote`
--
ALTER TABLE `dv_vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_auth_token`
--
ALTER TABLE `api_auth_token`
  ADD CONSTRAINT `api_auth_token_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `api_client` (`id`);

--
-- Constraints for table `api_permission`
--
ALTER TABLE `api_permission`
  ADD CONSTRAINT `api_permission_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `api_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_annotation`
--
ALTER TABLE `dv_annotation`
  ADD CONSTRAINT `dv_annotation_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_channel`
--
ALTER TABLE `dv_channel`
  ADD CONSTRAINT `dv_channel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_channel_admin`
--
ALTER TABLE `dv_channel_admin`
  ADD CONSTRAINT `dv_channel_admin_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_channel_admin_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_channel_subscription`
--
ALTER TABLE `dv_channel_subscription`
  ADD CONSTRAINT `dv_channel_subscription_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_channel_subscription_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_comment`
--
ALTER TABLE `dv_comment`
  ADD CONSTRAINT `dv_comment_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_comment_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `dv_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_conversation_user`
--
ALTER TABLE `dv_conversation_user`
  ADD CONSTRAINT `dv_conversation_user_ibfk_1` FOREIGN KEY (`conv_id`) REFERENCES `dv_conversation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_conversation_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_message`
--
ALTER TABLE `dv_message`
  ADD CONSTRAINT `dv_message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_message_ibfk_2` FOREIGN KEY (`conv_id`) REFERENCES `dv_conversation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_playlist`
--
ALTER TABLE `dv_playlist`
  ADD CONSTRAINT `dv_playlist_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_playlist_video`
--
ALTER TABLE `dv_playlist_video`
  ADD CONSTRAINT `dv_playlist_video_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `dv_playlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_playlist_video_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_post`
--
ALTER TABLE `dv_post`
  ADD CONSTRAINT `dv_post_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_session`
--
ALTER TABLE `dv_session`
  ADD CONSTRAINT `dv_session_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `api_client` (`id`),
  ADD CONSTRAINT `dv_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_tag`
--
ALTER TABLE `dv_tag`
  ADD CONSTRAINT `dv_tag_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dv_user`
--
ALTER TABLE `dv_user`
  ADD CONSTRAINT `dv_user_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `dv_rank` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `dv_video`
--
ALTER TABLE `dv_video`
  ADD CONSTRAINT `dv_video_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_video_ibfk_2` FOREIGN KEY (`visibility_id`) REFERENCES `dv_visibility` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `dv_view`
--
ALTER TABLE `dv_view`
  ADD CONSTRAINT `dv_view_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dv_view_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
