-- Adminer 4.2.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `api_client`;
CREATE TABLE `api_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `public_key` (`public_key`),
  UNIQUE KEY `private_key` (`private_key`),
  KEY `rank_id` (`rank_id`),
  CONSTRAINT `api_client_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `api_rank` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `api_client` (`id`, `name`, `public_key`, `private_key`, `rank_id`) VALUES
(1,	'root',	'root',	'root',	1),
(2,	'Guest',	'',	'',	2);

DROP TABLE IF EXISTS `api_controller`;
CREATE TABLE `api_controller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `api_controller` (`id`, `uri`) VALUES
(4,	'channel'),
(1,	'home'),
(2,	'token'),
(3,	'user');

DROP TABLE IF EXISTS `api_permission`;
CREATE TABLE `api_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_id` int(11) NOT NULL,
  `action` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `rank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller_id` (`controller_id`),
  KEY `action_id` (`action`),
  KEY `rank_id` (`rank_id`),
  CONSTRAINT `api_permission_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `api_controller` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `api_permission_ibfk_3` FOREIGN KEY (`rank_id`) REFERENCES `api_rank` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `api_permission` (`id`, `controller_id`, `action`, `rank_id`) VALUES
(1,	1,	'create',	1),
(2,	1,	'fetch',	1),
(3,	1,	'exists',	1),
(4,	1,	'read',	1),
(5,	1,	'update',	1),
(6,	1,	'delete',	1),
(7,	2,	'create',	2),
(8,	2,	'delete',	1),
(9,	3,	'create',	1);

DROP TABLE IF EXISTS `api_rank`;
CREATE TABLE `api_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `api_rank` (`id`, `name`) VALUES
(2,	'guest'),
(1,	'root');

DROP TABLE IF EXISTS `api_token`;
CREATE TABLE `api_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `ttl` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `api_token_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `api_client` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `api_token` (`id`, `token`, `timestamp`, `ttl`, `client_id`) VALUES
(1,	'root',	0,	2147483647,	1);

DROP TABLE IF EXISTS `dv_annotation`;
CREATE TABLE `dv_annotation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `todo_add_reals_columns` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`),
  CONSTRAINT `dv_annotation_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_channel`;
CREATE TABLE `dv_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `background` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dv_channel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_channel_admin`;
CREATE TABLE `dv_channel_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dv_channel_admin_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_channel_admin_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_channel_subscription`;
CREATE TABLE `dv_channel_subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dv_channel_subscription_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_channel_subscription_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_comment`;
CREATE TABLE `dv_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `flagged` tinyint(1) NOT NULL,
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `video_id` (`video_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `dv_comment_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_comment_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `dv_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_conversation`;
CREATE TABLE `dv_conversation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_conversation_user`;
CREATE TABLE `dv_conversation_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conv_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `conv_id` (`conv_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dv_conversation_user_ibfk_1` FOREIGN KEY (`conv_id`) REFERENCES `dv_conversation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_conversation_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_conversion`;
CREATE TABLE `dv_conversion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `format_id` int(11) NOT NULL,
  `resolution_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`),
  KEY `format_id` (`format_id`),
  KEY `status_id` (`status_id`),
  KEY `resolution_id` (`resolution_id`),
  CONSTRAINT `dv_conversion_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `dv_conversion_ibfk_3` FOREIGN KEY (`format_id`) REFERENCES `dv_conversion_format` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `dv_conversion_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `dv_conversion_status` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `dv_conversion_ibfk_5` FOREIGN KEY (`resolution_id`) REFERENCES `dv_conversion_resolution` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `dv_conversion_format`;
CREATE TABLE `dv_conversion_format` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `dv_conversion_format` (`id`, `name`) VALUES
(1,	'webm'),
(2,	'mp4');

DROP TABLE IF EXISTS `dv_conversion_resolution`;
CREATE TABLE `dv_conversion_resolution` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `dv_conversion_resolution` (`id`, `name`) VALUES
(1,	'360p'),
(2,	'720p'),
(3,	'1080p');

DROP TABLE IF EXISTS `dv_conversion_status`;
CREATE TABLE `dv_conversion_status` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `dv_conversion_status` (`id`, `name`) VALUES
(1,	'error'),
(2,	'converting'),
(3,	'available');

DROP TABLE IF EXISTS `dv_message`;
CREATE TABLE `dv_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conv_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `conv_id` (`conv_id`),
  CONSTRAINT `dv_message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_message_ibfk_2` FOREIGN KEY (`conv_id`) REFERENCES `dv_conversation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_playlist`;
CREATE TABLE `dv_playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `channel_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  CONSTRAINT `dv_playlist_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_playlist_video`;
CREATE TABLE `dv_playlist_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `playlist_id` (`playlist_id`),
  KEY `video_id` (`video_id`),
  CONSTRAINT `dv_playlist_video_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `dv_playlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_playlist_video_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_post`;
CREATE TABLE `dv_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `flagged` tinyint(1) NOT NULL,
  `channel_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  CONSTRAINT `dv_post_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_rank`;
CREATE TABLE `dv_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_session`;
CREATE TABLE `dv_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `expiration_timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dv_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_tag`;
CREATE TABLE `dv_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(139) COLLATE utf8_unicode_ci NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`),
  CONSTRAINT `dv_tag_ibfk_1` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_user`;
CREATE TABLE `dv_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `registration_timestamp` int(11) NOT NULL,
  `registration_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `current_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rank_id` (`rank_id`),
  CONSTRAINT `dv_user_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `dv_rank` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_video`;
CREATE TABLE `dv_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `url` int(255) NOT NULL,
  `post_timestamp` int(11) NOT NULL,
  `flagged` tinyint(1) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `visibility_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `visibility_id` (`visibility_id`),
  CONSTRAINT `dv_video_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `dv_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_video_ibfk_2` FOREIGN KEY (`visibility_id`) REFERENCES `dv_visibility` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_view`;
CREATE TABLE `dv_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `video_id` (`video_id`),
  CONSTRAINT `dv_view_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dv_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dv_view_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `dv_video` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_visibility`;
CREATE TABLE `dv_visibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dv_vote`;
CREATE TABLE `dv_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` tinyint(1) NOT NULL,
  `content_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2016-04-19 19:36:49
