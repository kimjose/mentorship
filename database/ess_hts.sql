-- Adminer 4.8.1 MySQL 10.4.27-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `ess` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `ess`;

DROP TABLE IF EXISTS `action_points`;
CREATE TABLE `action_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_id` int(11) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `title` varchar(121) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `due_date` date NOT NULL,
  `assign_to` text DEFAULT NULL,
  `status` enum('Pending','Done') NOT NULL DEFAULT 'Pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_ap_visit` (`visit_id`),
  KEY `fk_ap_creator` (`created_by`),
  KEY `fk_ap_question` (`question_id`),
  KEY `index_ap_title` (`title`) USING BTREE,
  KEY `fk_action_facility` (`facility_id`),
  CONSTRAINT `fk_action_facility` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_ap_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_ap_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_ap_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `question_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `answers` (`id`, `survey_id`, `user_id`, `answer`, `question_id`, `date_created`) VALUES
(1,	1,	2,	'Sample Only',	4,	'2020-11-10 14:46:07'),
(2,	1,	2,	'[JNmhW],[zZpTE]',	2,	'2020-11-10 14:46:07'),
(3,	1,	2,	'dAWTD',	1,	'2020-11-10 14:46:07'),
(4,	1,	3,	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec in tempus turpis, sed fermentum risus. Praesent vitae velit rutrum, dictum massa nec, pharetra felis. Phasellus enim augue, laoreet in accumsan dictum, mollis nec lectus. Aliquam id viverra nisl. Proin quis posuere nulla. Nullam suscipit eget leo ut suscipit.',	4,	'2020-11-10 15:59:43'),
(5,	1,	3,	'[qCMGO],[JNmhW]',	2,	'2020-11-10 15:59:43'),
(6,	1,	3,	'esNuP',	1,	'2020-11-10 15:59:43');

DROP TABLE IF EXISTS `ap_comments`;
CREATE TABLE `ap_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ap_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(200) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_comment_ap` (`ap_id`),
  KEY `fk_comment_user` (`user_id`),
  CONSTRAINT `fk_comment_ap` FOREIGN KEY (`ap_id`) REFERENCES `action_points` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `chart_abstractions`;
CREATE TABLE `chart_abstractions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `ccc_number` varchar(10) NOT NULL DEFAULT '',
  `age` double NOT NULL,
  `ap_ids` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_abstraction_visit` (`visit_id`),
  KEY `fk_abstraction_creator` (`created_by`),
  CONSTRAINT `fk_abstraction_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_abstraction_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `chart_abstraction_gaps`;
CREATE TABLE `chart_abstraction_gaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abstraction_id` int(11) NOT NULL,
  `gap` varchar(199) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_gap_abstraction` (`abstraction_id`),
  CONSTRAINT `fk_gap_abstraction` FOREIGN KEY (`abstraction_id`) REFERENCES `chart_abstractions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `checklists`;
CREATE TABLE `checklists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(199) NOT NULL,
  `abbr` varchar(10) NOT NULL COMMENT 'Abbreviation...',
  `description` varchar(250) NOT NULL DEFAULT '',
  `created_by` int(11) NOT NULL,
  `status` enum('draft','published','retired') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `published_by` int(11) DEFAULT NULL,
  `retired_at` timestamp NULL DEFAULT NULL,
  `retired_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_checklist_created_by` (`created_by`),
  CONSTRAINT `fk_checklist_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `checklists` (`id`, `title`, `abbr`, `description`, `created_by`, `status`, `published_at`, `published_by`, `retired_at`, `retired_by`, `created_at`, `updated_at`) VALUES
(5,	'HIV TESTING SERVICES',	'HTS',	'HTS Electronic and/or Paper',	1,	'draft',	NULL,	NULL,	NULL,	NULL,	'2023-04-07 11:28:44',	'2023-04-07 11:28:44');

DROP TABLE IF EXISTS `counties`;
CREATE TABLE `counties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `code` int(11) NOT NULL,
  `capital` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `counties` (`id`, `name`, `code`, `capital`, `created_at`, `updated_at`) VALUES
(1,	'Baringo',	30,	'Kabarnet',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(2,	'Bomet',	36,	'Bomet',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(3,	'Bungoma',	39,	'Bungoma',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(4,	'Busia',	40,	'Busia',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(5,	'Elgeyo-Marakwet',	28,	'Iten',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(6,	'Embu',	14,	'Embu',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(7,	'Garissa',	7,	'Garissa',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(8,	'Homa Bay',	43,	'Homa Bay',	'2021-02-22 08:02:16',	'2021-02-22 08:02:16'),
(9,	'Isiolo',	11,	'Isiolo',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(10,	'Kajiado',	34,	'Kajiado',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(11,	'Kakamega',	37,	'Kakamega',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(12,	'Kericho',	35,	'Kericho',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(13,	'Kiambu',	22,	'Kiambu',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(14,	'Kilifi',	3,	'Kilifi',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(15,	'Kirinyaga',	20,	'Kerugoya/Kutus',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(16,	'Kisii',	45,	'Kisii',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(17,	'Kisumu',	42,	'Kisumu',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(18,	'Kitui',	15,	'Kitui',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(19,	'Kwale',	2,	'Kwale',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(20,	'Laikipia',	31,	'Rumuruti',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(21,	'Lamu',	5,	'Lamu',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(22,	'Machakos',	16,	'Machakos',	'2021-02-22 08:02:17',	'2021-02-22 08:02:17'),
(23,	'Makueni',	17,	'Wote',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(24,	'Mandera',	9,	'Mandera',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(25,	'Marsabit',	10,	'Marsabit',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(26,	'Meru',	12,	'Meru',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(27,	'Migori',	44,	'Migori',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(28,	'Mombasa',	1,	'Mombasa City',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(29,	'Murang\'a',	21,	'Murang\'a',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(30,	'Nairobi',	47,	'Nairobi City',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(31,	'Nakuru',	32,	'Nakuru',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(32,	'Nandi',	29,	'Kapsabet',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(33,	'Narok',	33,	'Narok',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(34,	'Nyamira',	46,	'Nyamira',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(35,	'Nyandarua',	18,	'Ol Kalou',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(36,	'Nyeri',	19,	'Nyeri',	'2021-02-22 08:02:18',	'2021-02-22 08:02:18'),
(37,	'Samburu',	25,	'Maralal',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(38,	'Siaya',	41,	'Siaya',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(39,	'Taita-Taveta',	6,	'Voi',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(40,	'Tana River',	4,	'Hola',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(41,	'Tharaka-Nithi',	13,	'Chuka',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(42,	'Trans-Nzoia',	26,	'Kitale',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(43,	'Turkana',	23,	'Lodwar',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(44,	'Uasin Gishu',	27,	'Eldoret',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(45,	'Vihiga',	38,	'Vihiga',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(46,	'Wajir',	8,	'Wajir',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19'),
(47,	'West Pokot',	24,	'Kapenguria',	'2021-02-22 08:02:19',	'2021-02-22 08:02:19');

DROP TABLE IF EXISTS `facilities`;
CREATE TABLE `facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mfl_code` char(50) NOT NULL DEFAULT '0',
  `name` varchar(190) NOT NULL DEFAULT '',
  `county_code` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT 0,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_mfl_code` (`mfl_code`) USING BTREE,
  KEY `FK_facility_county` (`county_code`),
  KEY `Index _name` (`name`) USING BTREE,
  KEY `fk_facility_team` (`team_id`),
  CONSTRAINT `FK_facility_county` FOREIGN KEY (`county_code`) REFERENCES `counties` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `fk_facility_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `facilities` (`id`, `mfl_code`, `name`, `county_code`, `team_id`, `active`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(2,	'12935',	'Embakasi Health Centre',	47,	1,	1,	23.23,	0.09,	'2022-05-16 10:55:01',	'2023-03-28 09:09:56'),
(3,	'12974',	'Huruma Lions Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-10-12 11:44:37'),
(4,	'18176',	'Sex Workers Outreach Program (Kibra)',	47,	1,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-03-28 09:10:44'),
(5,	'17684',	'Hope World Wide Kenya Mukuru Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(6,	'19504',	'Child Doctor Kenya',	47,	1,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-03-28 14:02:55'),
(7,	'19429',	'SWOP Donholm',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	NULL),
(8,	'28284',	'LVCT Health Dreams',	47,	1,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-03-28 14:02:55'),
(9,	'13189',	'SOS Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(10,	'12929',	'Dreams Centre Dispensary (Langata)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(11,	'13249',	'Waithaka Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(12,	'13240',	'Umoja Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(13,	'13234',	'Tabitha Medical Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(14,	'13180',	'Sex Workers Operation Project (Swop)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(15,	'23200',	'bar Hostess Empowerment & Support programme-Roysambu',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:09'),
(16,	'18896',	'Swop Thika Road',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	NULL),
(17,	'19271',	'Swop Korogocho',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	NULL),
(18,	'19719',	'Swop Kawangware',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	NULL),
(19,	'13210',	'St Joseph\'s Dispensary (Dagoretti)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(20,	'13188',	'Sokoni Arcade VCT',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	NULL),
(21,	'13186',	'Silanga (MSF Belgium) Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(22,	'13171',	'Ruai Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(23,	'13165',	'Riruta Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(24,	'28592',	'LVCT Health (Ngando Dreams Site)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:08'),
(25,	'13156',	'Pumwani Maternity Hospital',	47,	4,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-04-05 06:33:59'),
(26,	'13155',	'Pumwani Majengo Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(27,	'13126',	'Njiru  Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(28,	'12893',	'Chandaria Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(29,	'13122',	'Ngara Health Centre (City Council of Nairobi)',	47,	4,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-04-05 06:33:59'),
(30,	'13113',	'Nairobi South Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(31,	'13108',	'Nairobi Deaf (Liverpool)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	NULL),
(32,	'13105',	'Mutuini Sub-District Hospital',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(33,	'13076',	'Mathari Hospital',	47,	4,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-04-05 06:33:59'),
(34,	'19308',	'Maisha House VCT (Noset)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:09'),
(35,	'13051',	'Loco Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(36,	'13050',	'Liverpool VCT',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(37,	'13041',	'Langata Subcounty Hospital(Mugumoini)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(38,	'23414',	'Kware Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(39,	'13028',	'Kibera Community Health Centre - Amref',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(40,	'13023',	'Kenyatta National Hospital',	47,	4,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2023-04-05 06:33:59'),
(41,	'13015',	'Kayole I Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(42,	'12871',	'APTC Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(43,	'12913',	'Dandora I Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(44,	'12930',	'Eastleigh Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(45,	'13029',	'Kibera D O Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(46,	'13009',	'Karura Health Centre (Kiambu Rd)',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(47,	'13003',	'Karen Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(48,	'12998',	'Kaloleni Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(49,	'19471',	'Iom Wellness Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(50,	'13245',	'Ushirika Medical Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(51,	'13019',	'Kemri VCT',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-30 08:53:10'),
(52,	'12889',	'Cana Family Life Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(53,	'12876',	'Babadogo Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-05-16 10:55:01',	'2022-06-28 09:40:36'),
(54,	'20402',	'Support For Addiction Prevention & Treatment In Africa',	47,	NULL,	1,	NULL,	NULL,	'2022-06-13 10:03:01',	'2022-06-30 08:53:09'),
(55,	'23786',	'NGARA MAT CLINIC',	47,	NULL,	1,	NULL,	NULL,	'2022-06-13 10:05:58',	'2022-06-30 08:53:09'),
(56,	'13079',	'Mathari MAT',	47,	NULL,	1,	NULL,	NULL,	'2022-06-13 10:08:48',	'2022-06-13 10:08:48'),
(57,	'12883',	'Biafra Lions Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-06-20 12:46:18',	'2022-06-28 09:40:36'),
(58,	'12977',	'Patanisho Maternity and Nursing Home',	47,	NULL,	1,	NULL,	NULL,	'2022-06-20 12:46:57',	'2022-06-30 08:53:10'),
(59,	'12919',	'Dog Unit Dispensary (O P Kenya Police)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-21 13:57:46',	'2022-06-30 08:53:10'),
(60,	'13138',	'Pangani Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-06-22 13:07:06',	'2022-06-28 09:40:36'),
(61,	'13184',	'Shauri Moyo Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-06-22 13:07:59',	'2022-06-30 08:53:10'),
(62,	'13239',	'Uhuru Camp Dispensary (O P Admin Police)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-22 13:08:42',	'2022-06-30 08:53:10'),
(66,	'13130',	'National Youth Service Hq Dispensary (Ruaraka)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-22 13:59:28',	'2022-06-30 08:53:10'),
(68,	'28742',	'SWOP Majengo clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-06-27 06:17:25',	'2022-06-27 06:17:25'),
(69,	'13078',	'Mathare Police Depot',	47,	NULL,	1,	NULL,	NULL,	'2022-06-27 10:28:47',	'2022-06-28 09:40:36'),
(70,	'13259',	'Woodley Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 06:57:58',	'2022-06-28 09:40:36'),
(71,	'12989',	'Jerusalem Clinic',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 07:07:58',	'2022-06-28 09:40:36'),
(72,	'12961',	'Gsu Dispensary (Nairobi West)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:32:42',	'2022-06-28 09:40:36'),
(73,	'12963',	'Gsu Hq Dispensary (Ruaraka)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:33:28',	'2022-06-28 09:40:36'),
(74,	'12962',	'GSUTraining School',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:33:54',	'2022-06-30 08:53:10'),
(75,	'18505',	'KEMRI Mimosa',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:37:22',	'2022-06-30 08:53:10'),
(76,	'13030',	'Kibera South Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:40:14',	'2022-06-28 09:40:36'),
(77,	'26911',	'Noset Maisha House',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:44:32',	'2022-06-30 08:53:09'),
(78,	'13144',	'South B Police Band Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-06-28 08:47:15',	'2022-06-28 09:40:36'),
(346,	'28885',	'Mama Margaret Uhuru Hospital',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:08',	'2022-06-30 08:53:08'),
(383,	'28552',	'Ngomongo Dispensary',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:08',	'2022-06-30 08:53:08'),
(408,	'27815',	'Tassia Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:08',	'2022-06-30 08:53:08'),
(409,	'28373',	'Njenga Health Centre',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:08',	'2022-06-30 08:53:08'),
(441,	'27196',	'Gatina Dispensary(Dagoretti)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:09',	'2022-06-30 08:53:09'),
(455,	'26913',	'Ushirika Dispensary (Dandora)',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:09',	'2022-06-30 08:53:09'),
(570,	'24979',	'Kenyatta University Teaching Refferal and Research Hospital',	47,	NULL,	1,	NULL,	NULL,	'2022-06-30 08:53:09',	'2022-06-30 08:53:09');

DROP TABLE IF EXISTS `facility_visits`;
CREATE TABLE `facility_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_facility_visit` (`facility_id`,`visit_date`),
  KEY `fk_visit_creator` (`created_by`),
  CONSTRAINT `fk_visit_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_facility` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `facility_visits` (`id`, `facility_id`, `visit_date`, `latitude`, `longitude`, `created_by`, `created_at`, `updated_at`) VALUES
(9,	2,	'2023-04-06',	-1.0759715,	36.96405,	1,	'2023-04-07 12:47:08',	'2023-04-07 12:47:08');

DROP TABLE IF EXISTS `frequencies`;
CREATE TABLE `frequencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(98) NOT NULL,
  `days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_frequency_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `frequencies` (`id`, `name`, `days`, `created_at`, `updated_at`) VALUES
(1,	'Regular',	7,	'2023-02-28 11:56:43',	'2023-02-28 11:56:43'),
(2,	'Monthly',	30,	'2023-02-28 11:56:58',	'2023-02-28 11:56:58'),
(3,	'Quarterly',	90,	'2023-02-28 11:57:34',	'2023-02-28 11:57:34'),
(4,	'Semi-annual',	182,	'2023-02-28 11:58:03',	'2023-02-28 11:58:03'),
(5,	'Annual',	365,	'2023-02-28 11:58:20',	'2023-02-28 11:58:20');

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `message` varchar(200) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `mail_sent` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_notification_user` (`user_id`),
  CONSTRAINT `FK_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `notifications` (`id`, `user_id`, `message`, `read`, `mail_sent`, `created_at`, `updated_at`) VALUES
(676,	1,	'You have been assigned an action point(Test title)',	1,	0,	'2023-03-15 09:03:14',	'2023-04-05 06:34:34'),
(677,	1,	'You have been assigned an action point(Test two)',	0,	0,	'2023-03-15 09:11:54',	'2023-03-15 09:11:54'),
(678,	4,	'You have been assigned an action point(Test two)',	0,	0,	'2023-03-15 09:11:54',	'2023-03-15 09:11:54'),
(679,	1,	'You have been assigned an action point(This is test title)',	0,	0,	'2023-03-15 09:47:04',	'2023-03-15 09:47:04'),
(680,	3,	'You have been assigned an action point(This is test title)',	0,	0,	'2023-03-15 09:47:04',	'2023-03-15 09:47:04'),
(681,	34,	'You have been assigned an action point( Ting - Sex Workers Outreach Program (Kibra))',	0,	0,	'2023-04-03 11:47:05',	'2023-04-03 11:47:05'),
(682,	40,	'You have been assigned an action point( Ting - Sex Workers Outreach Program (Kibra))',	0,	0,	'2023-04-03 11:47:05',	'2023-04-03 11:47:05'),
(683,	11,	'You have been assigned an action point( Test  - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:52:21',	'2023-04-05 08:52:21'),
(684,	3,	'You have been assigned an action point( Test2 - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:53:03',	'2023-04-05 08:53:03'),
(685,	11,	'You have been assigned an action point( Baggot - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:54:32',	'2023-04-05 08:54:32'),
(686,	22,	'You have been assigned an action point( Baggot - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:54:32',	'2023-04-05 08:54:32'),
(687,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:56:09',	'2023-04-05 08:56:09'),
(688,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:56:09',	'2023-04-05 08:56:09'),
(689,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:56:51',	'2023-04-05 08:56:51'),
(690,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:56:51',	'2023-04-05 08:56:51'),
(691,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:57:35',	'2023-04-05 08:57:35'),
(692,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:57:35',	'2023-04-05 08:57:35'),
(693,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:59:08',	'2023-04-05 08:59:08'),
(694,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:59:08',	'2023-04-05 08:59:08'),
(695,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:59:21',	'2023-04-05 08:59:21'),
(696,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 08:59:21',	'2023-04-05 08:59:21'),
(697,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:02:40',	'2023-04-05 09:02:40'),
(698,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:02:40',	'2023-04-05 09:02:40'),
(699,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:02:44',	'2023-04-05 09:02:44'),
(700,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:02:44',	'2023-04-05 09:02:44'),
(701,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:03:52',	'2023-04-05 09:03:52'),
(702,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:03:52',	'2023-04-05 09:03:52'),
(703,	2,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:04:06',	'2023-04-05 09:04:06'),
(704,	4,	'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',	0,	0,	'2023-04-05 09:04:06',	'2023-04-05 09:04:06'),
(705,	3,	'You have been assigned an action point( Do That - Embakasi Health Centre)',	0,	0,	'2023-04-06 12:23:15',	'2023-04-06 12:23:15'),
(706,	11,	'You have been assigned an action point( Do That - Embakasi Health Centre)',	0,	0,	'2023-04-06 12:23:15',	'2023-04-06 12:23:15');

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `category` enum('individual','facility','sdp') NOT NULL DEFAULT 'facility',
  `frequency_id` int(11) NOT NULL,
  `frm_option` text NOT NULL,
  `type` enum('check_opt','textfield_s','radio_opt','number_opt') NOT NULL,
  `order_by` int(11) NOT NULL DEFAULT 0,
  `section_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_question_section` (`section_id`),
  KEY `fk_question_created_by` (`created_by`),
  KEY `fk_question_frequency` (`frequency_id`),
  CONSTRAINT `fk_question_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_question_frequency` FOREIGN KEY (`frequency_id`) REFERENCES `frequencies` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_question_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `questions` (`id`, `question`, `category`, `frequency_id`, `frm_option`, `type`, `order_by`, `section_id`, `date_created`, `created_by`, `created_at`, `updated_at`) VALUES
(95,	'Does this facility have the most current national HTS guidelines?',	'facility',	5,	'{\"nZaCI\":\"HTS2016\",\"juoap\":\"aPNS_HIVST\",\"mvGxe\":\"PrEP\",\"HZTAd\":\"GBV\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(96,	'Does the facility have the current HTS eligibility screening algrorithms?',	'sdp',	5,	'{\"GOyvR\":\"Paediatric\",\"ziqFa\":\"Adolescent\",\"TYHrA\":\"Adult\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(97,	'Does this facility have the most current national testing algorithm?',	'sdp',	5,	'{\"zgfnJ\":\"yes\",\"skyZL\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(98,	'Does the facility have SOP for testing?',	'facility',	5,	'{\"IvHoD\":\"Adults\",\"QLVPR\":\"Children\",\"DlFqT\":\"Adolescents\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(99,	'Does the facility have the updated HTS Protocols?',	'facility',	5,	'{\"pfAJK\":\"yes\",\"uiHrP\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(100,	'Does the facility have HTC referral and treatment linkage job aid/Algorithm?',	'sdp',	5,	'{\"klySX\":\"yes\",\"nCcvG\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(101,	'Does the Facility have the following Index testing SOPs?',	'facility',	5,	'{\"LBNdH\":\"6Cs\",\"IKmbD\":\"Principles of aPNS\",\"AwKWP\":\"aPNS algorithm\",\"IWoTL\":\"SEIT SOP\",\"YbemH\":\"IPV Screening & response\",\"gDoHw\":\"Adverse Events reporting and response\",\"KkzXq\":\"Reporting confidentiality Violation\",\"zfrJV\":\"Data sharing SOP\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(102,	'Does the facility have waste segragation job aids?',	'sdp',	5,	'{\"MXQPl\":\"yes\",\"VbFSZ\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(103,	'Does the facility have PITC protocal?',	'facility',	5,	'{\"SNQji\":\"yes\",\"tLcpX\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(104,	'Does the facility have PEP protocal?',	'sdp',	3,	'{\"AOCbq\":\"yes\",\"axKpf\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(105,	'Does the facilty have condom demonstration job aids?',	'sdp',	5,	'{\"Yrpaz\":\"Female Condom\",\"JOaSp\":\"Male Condom\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(106,	'Does the facility have demonstration Models for Condoms?',	'sdp',	5,	'{\"UdXaP\":\"Female\",\"nWObv\":\"Male\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(107,	'Does the facilty have safety SOP/Manual?',	'facility',	5,	'{\"hIFgK\":\"yes\",\"gAUzw\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(108,	'Are there Test Kits interpretation Charts in this facility?',	'sdp',	5,	'{\"mWrqK\":\"Determine\",\"ZECGA\":\"First Response\",\"lDagJ\":\"Dual Kits\",\"aJKyL\":\"HIVST\"}',	'check_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(109,	'Does this site have confidentaiality Policy?',	'facility',	5,	'{\"HPdCa\":\"yes\",\"BqAbI\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(110,	'Does this site have Stigma and Discrimination Policy?',	'sdp',	5,	'{\"ZnksH\":\"yes\",\"AVKei\":\"no\"}',	'radio_opt',	0,	7,	'2023-04-07 16:17:51',	1,	'2023-04-07 12:17:51',	'2023-04-07 12:17:51'),
(111,	'Is the HTS eligibility screening conducted in a space which provide the required confidentiality?',	'sdp',	5,	'{\"iNsyu\":\"yes\",\"pbozY\":\"no\"}',	'radio_opt',	0,	8,	'2023-04-07 16:22:46',	1,	'2023-04-07 12:22:46',	'2023-04-07 12:22:46'),
(112,	'Has the SDP intergrated eligibility screening for HTS, TB and Covid 19',	'sdp',	3,	'{\"vIKlu\":\"yes\",\"qGIiu\":\"no\"}',	'radio_opt',	0,	8,	'2023-04-07 16:22:46',	1,	'2023-04-07 12:22:46',	'2023-04-07 12:22:46'),
(113,	'Does the SDP have the current OPD/IPD linelisting register which capture HTS, TB and Covid 19 Screening (Calculate %- Num: Available/Total SDPs)',	'sdp',	3,	'',	'number_opt',	0,	8,	'2023-04-07 16:22:46',	1,	'2023-04-07 12:22:46',	'2023-04-07 12:22:46'),
(114,	'Review of the uptake of HTS eligibility screening in OPD Clinics/MCH (check OPD/IPD line listing register and  OPD/MNCH registers (Workload) to determine the proportion screened in the last 1 month)  (refer to MOH 717)',	'sdp',	1,	'',	'number_opt',	0,	8,	'2023-04-07 16:22:46',	1,	'2023-04-07 12:22:46',	'2023-04-07 12:22:46'),
(115,	'Using the OPD/IPD linelisting register review 20 eligible clients and confirm if they are tested using the MOH 362 (Calculate %)',	'individual',	1,	'',	'number_opt',	0,	8,	'2023-04-07 16:22:46',	1,	'2023-04-07 12:22:46',	'2023-04-07 12:22:46'),
(116,	'Using the OPD/IPD linelisting register review 10 clients with signs and symptoms of TB and check if they are documented in the presumptive TB register (Calculate %)',	'individual',	1,	'',	'number_opt',	0,	8,	'2023-04-07 16:22:46',	1,	'2023-04-07 12:22:46',	'2023-04-07 12:22:46'),
(133,	'Is the HTS register complete and properly populated? (Review the most recent positives in last 1 months)',	'individual',	2,	'{\"MZPWT\":\"week1\",\"cYSdt\":\"week2\",\"hqfuK\":\"week3\",\"rJYmN\":\"week4\"}',	'check_opt',	0,	9,	'2023-04-07 16:34:44',	1,	'2023-04-07 12:34:44',	'2023-04-07 12:34:44'),
(134,	'Is there evidence of retesting as per national guidelines?( Check if  the clients testing  HIV positive in the last 3 months  have been documented to be retested)',	'individual',	2,	'{\"cNPyR\":\"Month1\",\"SeVWy\":\"Month2\",\"GuCFl\":\"Month3\"}',	'check_opt',	0,	9,	'2023-04-07 16:34:44',	1,	'2023-04-07 12:34:44',	'2023-04-07 12:34:44'),
(135,	'Is there evidence of strategies specific to identification of Men?',	'facility',	1,	'{\"IKnST\":\"Extended hours\",\"WJZum\":\"Community outreaches\",\"oVBlz\":\"Multi dx screening\",\"tSPpQ\":\"Male champions\",\"Ktrkp\":\"Other (specify)\"}',	'check_opt',	0,	9,	'2023-04-07 16:34:44',	1,	'2023-04-07 12:34:44',	'2023-04-07 12:34:44'),
(136,	'Is there evidence of strategies specific to identification of AYP?',	'facility',	1,	'{\"KcSsE\":\"community\",\"lXeaJ\":\"youth friendly\",\"iIAOG\":\"SNS\",\"pQJna\":\"Champions\",\"hGHbp\":\"Other(Specify) \"}',	'check_opt',	0,	9,	'2023-04-07 16:34:44',	1,	'2023-04-07 12:34:44',	'2023-04-07 12:34:44'),
(137,	'Does the Facility/Counselor have the current version of PNS registers (2022)?',	'individual',	1,	'{\"wPztl\":\"yes\",\"toLum\":\"no\"}',	'radio_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(138,	'IS the PNS register completely and Accurately documented including summaries',	'individual',	1,	'{\"NJgOW\":\"yes\",\"ZCELz\":\"no\"}',	'radio_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(139,	'IS the PNS registers reviewed regularly (atleast monthly)',	'individual',	1,	'{\"hkFTB\":\"yes\",\"oLvOQ\":\"no\"}',	'radio_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(140,	'Review uptake of Index testing services by comparing  the new HTS POS >15years and clients screened for Index testing (Calculate Screening uptake: Num: Number screened/New POS 15yrs and above)',	'individual',	1,	'',	'number_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(141,	'Is the facility offering expanded aPNS (Review evidence for Screening beyond the new HIV POS)',	'individual',	1,	'{\"Jeiqt\":\"yes\",\"wJgkI\":\"no\"}',	'radio_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(142,	'Review the index client testing and calculate the elicitation ratio for Sexual partners (Elicitation=SPs elicited:Index screened) Ecpected 1:2',	'individual',	1,	'',	'number_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(143,	'Review the last 10 Women aged 20 above and children listed in the aPNS register- Calculate Percentage of women with Children listed (Num- No. Women aged 20yrs above with children listed/Den_Women 20yrs and above screened for Index testing)',	'individual',	1,	'',	'number_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(144,	'Review the Contacts elicited in the preceding 3 months and calculate the testing uptake (Number tested/Number elicited in the last 3 months)',	'individual',	1,	'',	'number_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(145,	'Review the clients offered Index testing in the last quarter and check what proportion have adverse Event monitoring outcome documented post the service (calculate %)',	'individual',	1,	'',	'number_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(146,	'Have all the adverse Events reported in the facility have been investigated and responded and necessary tools updated?',	'individual',	1,	'',	'number_opt',	0,	10,	'2023-04-07 16:38:24',	1,	'2023-04-07 12:38:24',	'2023-04-07 12:38:24'),
(147,	'Does the Counselor/Facility have SNS register',	'individual',	1,	'{\"jlADQ\":\"yes\",\"PlDwH\":\"no\"}',	'radio_opt',	0,	11,	'2023-04-07 16:43:39',	1,	'2023-04-07 12:43:39',	'2023-04-07 12:43:39'),
(148,	'Is the SNS register completely and Accuartely documented including Monthly summaries',	'individual',	1,	'{\"rbVhU\":\"yes\",\"AYrtS\":\"no\"}',	'radio_opt',	0,	11,	'2023-04-07 16:43:39',	1,	'2023-04-07 12:43:39',	'2023-04-07 12:43:39'),
(149,	'IS the SNS registers reviewed regularly (atleast Monthly)',	'individual',	1,	'{\"boGgv\":\"yes\",\"szJeL\":\"no\"}',	'radio_opt',	0,	11,	'2023-04-07 16:43:39',	1,	'2023-04-07 12:43:39',	'2023-04-07 12:43:39'),
(150,	'Review if SNS is offered for the 3 types of Seeds- Review the last 3 months and check if it has the three seed types have  (New Positive, High risk Negative, Peer Educator)',	'individual',	1,	'',	'number_opt',	0,	11,	'2023-04-07 16:43:39',	1,	'2023-04-07 12:43:39',	'2023-04-07 12:43:39'),
(151,	'Review the SNS register and check social network members listed in the preceding 3 months and calculate testing uptake (Testing uptake= SN Members tested/SN members listed in the last 3 months X 100)',	'individual',	1,	'',	'number_opt',	0,	11,	'2023-04-07 16:43:39',	1,	'2023-04-07 12:43:39',	'2023-04-07 12:43:39'),
(152,	'Is linkage and referral register complete and all actions documented?',	'individual',	1,	'{\"iDatX\":\"yes\",\"vlKVL\":\"no\"}',	'radio_opt',	0,	12,	'2023-04-07 16:50:27',	1,	'2023-04-07 12:50:27',	'2023-04-07 12:50:27'),
(153,	'Review the last 10 clients identified as HIV positives from the HTS register to determine the percentage who were successfully linked to treatment services (Evidence of CCC Number).',	'individual',	1,	'',	'number_opt',	0,	12,	'2023-04-07 16:50:27',	1,	'2023-04-07 12:50:27',	'2023-04-07 12:50:27'),
(154,	'Check the Last 5 clients not linked on same day or Reffered outside and review for tracking as per the Linkage Algorithm. (Calculate Percentage tracked as per the algorithm=Number tracked as per algorithm/Number not linked same day+number reffered outside for Linkage)',	'individual',	1,	'',	'number_opt',	0,	12,	'2023-04-07 16:50:27',	1,	'2023-04-07 12:50:27',	'2023-04-07 12:50:27'),
(155,	'Review the clients tested HIV Negative in the last 1 month from the HTS register and check for evidence of linkage to any form of prevention services. Calculate proportion offered the services (Prevention services: PrEP, PEP, Condoms, Counselling, VMMC etc) (calculation: =Number linked to prevention services/Number tested Negative 15 years above in the last 1 month)',	'individual',	1,	'',	'number_opt',	0,	12,	'2023-04-07 16:50:27',	1,	'2023-04-07 12:50:27',	'2023-04-07 12:50:27'),
(156,	'Were the HIV Positive clients linked to case management teams? (Calculate Linkage= Number Linked to CMTs/Number tested POS in the last 1 Month)',	'individual',	1,	'',	'number_opt',	0,	12,	'2023-04-07 16:50:27',	1,	'2023-04-07 12:50:27',	'2023-04-07 12:50:27'),
(157,	'Does the facility conduct RS?',	'facility',	1,	'{\"rmNsi\":\"yes\",\"AWjdy\":\"no\"}',	'radio_opt',	0,	13,	'2023-04-07 16:56:22',	1,	'2023-04-07 12:56:22',	'2023-04-07 12:56:22'),
(158,	'Does the facility have HTS RS Master file',	'facility',	1,	'{\"UWIMi\":\"yes\",\"oKzdn\":\"no\"}',	'radio_opt',	0,	13,	'2023-04-07 16:56:22',	1,	'2023-04-07 12:56:22',	'2023-04-07 12:56:22'),
(159,	'Does the SDP have a HTS SDP RS file?',	'sdp',	1,	'{\"SXqUP\":\"yes\",\"gBGQx\":\"no\"}',	'radio_opt',	0,	13,	'2023-04-07 16:56:22',	1,	'2023-04-07 12:56:22',	'2023-04-07 12:56:22'),
(160,	'Review the last 10 RSLRFs and check for accuracy and completeness',	'individual',	1,	'',	'number_opt',	0,	13,	'2023-04-07 16:56:22',	1,	'2023-04-07 12:56:22',	'2023-04-07 12:56:22'),
(161,	'Review the Last 10 HIV Postives 15 and above and calculate the percentage tested for RS (Calulate- No. tested for HIV RS/ No. HIV POS 15yrs and above)',	'individual',	1,	'',	'number_opt',	0,	13,	'2023-04-07 16:56:22',	1,	'2023-04-07 12:56:22',	'2023-04-07 12:56:22'),
(162,	'Check for RS data transmission to national dashboard- Review the RSLRFs for the last 3 months and confirm they have sent successfully in the ODK (calculate Percentage sent successfully)',	'facility',	1,	'',	'number_opt',	0,	13,	'2023-04-07 16:56:22',	1,	'2023-04-07 12:56:22',	'2023-04-07 12:56:22'),
(173,	'Review HTS counselor files and check if all the providers have undergone NASCOP certified HTS training',	'facility',	5,	'{\"diBRr\":\"yes\",\"TmyZb\":\"no\"}',	'radio_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(174,	'Do all the HTS providers have PT results for the most recent round and filed in the facility (Calculate the % of HTS providers with Valid PT results)',	'facility',	4,	'',	'number_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(175,	'Check What proportion have satisfactory results ',	'facility',	4,	'',	'number_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(176,	'Review the PT file and check How many HTS counselors with unsatisfactory results and have/undergoing corrective actions for unsatisfactory results? ',	'facility',	3,	'',	'number_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(177,	'Check if all the HTS providers have received atleast one observed practice in the last 3 months (Determine % of providers who have been observed)',	'facility',	3,	'',	'number_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(178,	'Review the HTS register and check for complete recording test kit names, expiry date and lot numbers for the last 10 pages. Indicate Yes if documented in all pages and No if missing ',	'individual',	3,	'{\"elitq\":\"yes\",\"jsEVG\":\"no\"}',	'radio_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(179,	'Have the counselor undergone HTS supervision/Debriefing in the last 3 months? Check Counselor supervision booklet',	'individual',	3,	'{\"hzQVw\":\"yes\",\"GYrgW\":\"no\"}',	'radio_opt',	0,	14,	'2023-04-07 17:14:31',	1,	'2023-04-07 13:14:31',	'2023-04-07 13:14:31'),
(180,	'Does the facility have stocks for HIV testing commodities to last for 3 months for the following Kits (Determine, First response, HIVST, Dual Kit)',	'facility',	3,	'{\"NqJvL\":\"yes\",\"dQJYo\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(181,	'Does the SDP have Gloves for supporting HTS',	'sdp',	3,	'{\"zHFda\":\"yes\",\"wbyuG\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(182,	'Does the SDP have the 3 waste segregation Bins (Black, Yellow, Red)?',	'sdp',	4,	'{\"TIwsu\":\"yes\",\"wkPaU\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(183,	'Does the SDP have the WHO approved sharp containers?',	'sdp',	4,	'{\"PmWYs\":\"yes\",\"RYebi\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(184,	'Does the SDP have timers for Conducting HTS?',	'sdp',	4,	'{\"dTyzi\":\"yes\",\"urHjs\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(185,	'Does the SDP have Hand washing Equipment?',	'sdp',	4,	'{\"UOVCk\":\"yes\",\"nTidx\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(186,	'Does the SDP have Lockable cabinets for storing client records?',	'sdp',	4,	'{\"aZIyM\":\"yes\",\"gqnDN\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(187,	'Does the facility have a dedicated HTS Phone for patient follow up?',	'facility',	4,	'{\"LqwtR\":\"yes\",\"ziwjV\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(188,	'Are phone Logs available at this facility?',	'facility',	5,	'{\"SFdUD\":\"yes\",\"FaUHc\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(189,	'Does the facility have updated referral directories?',	'facility',	4,	'{\"YWJKu\":\"yes\",\"ixaeA\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(190,	'Does the HTS counselor have a labcoat?',	'individual',	5,	'{\"OIVMx\":\"yes\",\"TjDhH\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(191,	'Do facility staff have access to PEP at the facility?',	'facility',	4,	'{\"RbJuq\":\"yes\",\"QjTxV\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(192,	'Does the SDP have a room thermometer?',	'facility',	4,	'{\"KsETS\":\"yes\",\"tExRh\":\"no\"}',	'radio_opt',	0,	15,	'2023-04-07 17:28:55',	1,	'2023-04-07 13:28:55',	'2023-04-07 13:28:55'),
(193,	'Has the HTS counselor signed a confidentiality agreement?',	'individual',	5,	'{\"nHuYC\":\"yes\",\"WsTta\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(194,	'Has the HTS provider been trained on SEIT',	'individual',	5,	'{\"tXwgn\":\"yes\",\"FCdqf\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(195,	'Has the HTS provider been trained on GBV lives',	'individual',	5,	'{\"RzpJT\":\"yes\",\"NTYEy\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(196,	'Has the HTS provider been trained on RS',	'individual',	5,	'{\"uGnEO\":\"yes\",\"rscvl\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(197,	'Has the HTS provider been trained on Safety in HTS',	'individual',	5,	'{\"xMLJy\":\"yes\",\"ELUWI\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(198,	'Has the HTS provider been trained on RTCQI',	'individual',	5,	'{\"gleKw\":\"yes\",\"SchfX\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(199,	'Has the HTS provider received refresher training within the last 12 months',	'individual',	5,	'{\"akiWI\":\"yes\",\"EUelT\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(200,	'Has the HTS Eligibility screener trained on HTS eligibility screening',	'individual',	5,	'{\"FsPpY\":\"yes\",\"BYshp\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(201,	'Has the HTS counselor undertaken any HTS relevant trainings? ',	'individual',	3,	'{\"Ylomp\":\"yes\",\"VkdmC\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(202,	'Has the HTS counselor participated in other in service trainings? ',	'individual',	3,	'{\"wfAOZ\":\"yes\",\"ZDmEp\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(203,	'Have all the trainnings undertaken above updated in the facility trainning  log? ',	'individual',	3,	'{\"IiBWQ\":\"yes\",\"IyXuz\":\"no\"}',	'radio_opt',	0,	16,	'2023-04-07 17:31:39',	1,	'2023-04-07 13:31:39',	'2023-04-07 13:31:39'),
(204,	'Does the facility have a HTS related CQI project',	'facility',	3,	'{\"LlVTF\":\"yes\",\"IGzls\":\"no\"}',	'radio_opt',	0,	17,	'2023-04-07 17:33:47',	1,	'2023-04-07 13:33:47',	'2023-04-07 13:33:47'),
(205,	'Has the HTS CQI project been uploaded and monitored through the CQI dashboard',	'facility',	3,	'{\"WPkRm\":\"yes\",\"pisoW\":\"no\"}',	'radio_opt',	0,	17,	'2023-04-07 17:33:47',	1,	'2023-04-07 13:33:47',	'2023-04-07 13:33:47'),
(206,	'Does the facility have minutes for the HTS CQI project within the  reporting period  ',	'facility',	3,	'{\"GevWp\":\"yes\",\"sTUet\":\"no\"}',	'radio_opt',	0,	17,	'2023-04-07 17:33:47',	1,	'2023-04-07 13:33:47',	'2023-04-07 13:33:47'),
(207,	'Is the HTS CQI Project on  course based on the implemtaiom timeline (Review minutes and runchart)',	'facility',	3,	'{\"yMzST\":\"yes\",\"ayKQZ\":\"no\"}',	'radio_opt',	0,	17,	'2023-04-07 17:33:47',	1,	'2023-04-07 13:33:47',	'2023-04-07 13:33:47'),
(208,	'IS the CQI project monitored frequently as per the CQI plan (Check for updated run Chart)',	'facility',	3,	'{\"aWtZq\":\"yes\",\"FdfpC\":\"no\"}',	'radio_opt',	0,	17,	'2023-04-07 17:33:47',	1,	'2023-04-07 13:33:47',	'2023-04-07 13:33:47'),
(209,	'Has the CQI meetings conducted as per the QI plan and meetings documented',	'facility',	3,	'{\"ZEHGx\":\"yes\",\"eqobV\":\"no\"}',	'radio_opt',	0,	17,	'2023-04-07 17:33:47',	1,	'2023-04-07 13:33:47',	'2023-04-07 13:33:47');

DROP TABLE IF EXISTS `responses`;
CREATE TABLE `responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_answer_visit` (`visit_id`),
  KEY `fk_answer_question` (`question_id`),
  KEY `fk_answer_created_by` (`created_by`),
  CONSTRAINT `fk_answer_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_answer_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `sections`;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(199) NOT NULL,
  `abbr` varchar(10) NOT NULL COMMENT 'Abbreviation...',
  `checklist_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_section_created_by` (`created_by`),
  KEY `fk_section_checklist` (`checklist_id`),
  CONSTRAINT `fk_section_checklist` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_section_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sections` (`id`, `title`, `abbr`, `checklist_id`, `created_by`, `created_at`, `updated_at`) VALUES
(7,	'A: REQUIRED JOB AIDS',	'RJA',	5,	1,	'2023-04-07 11:30:07',	'2023-04-07 11:30:07'),
(8,	'B: HTS Eligibility screening',	'HES',	5,	1,	'2023-04-07 12:19:41',	'2023-04-07 12:19:41'),
(9,	'C: HTS OVERALL STRATEGY',	'HOS',	5,	1,	'2023-04-07 12:24:22',	'2023-04-07 12:24:22'),
(10,	'D: INDEX TESTING ',	'HIT',	5,	1,	'2023-04-07 12:37:09',	'2023-04-07 12:37:09'),
(11,	'E: SOCIAL NETWORK STRATEGY (SNS)',	'HSNS',	5,	1,	'2023-04-07 12:39:05',	'2023-04-07 12:39:05'),
(12,	'F: REFERRAL AND LINKAGE TO HIV TREATMENT AND PREVENTION',	'HRLHTP',	5,	1,	'2023-04-07 12:49:14',	'2023-04-07 12:49:14'),
(13,	'G: RECENCY SURVEILLANCE',	'HRS',	5,	1,	'2023-04-07 12:53:54',	'2023-04-07 12:53:54'),
(14,	'H: QUALITY OF HIV TESTING SERVICES',	'HQHTS',	5,	1,	'2023-04-07 12:57:25',	'2023-04-07 12:57:25'),
(15,	'I: HIV TESTING SERVICES COMMODITIES, EQUIPMENTS AND SAFETY',	'HTSCES',	5,	1,	'2023-04-07 13:25:21',	'2023-04-07 13:25:21'),
(16,	'J: TRAININGS',	'HT',	5,	1,	'2023-04-07 13:30:34',	'2023-04-07 13:30:34'),
(17,	'K: CONTINOUS QUALITY IMPROVEMENT',	'HCQI',	5,	1,	'2023-04-07 13:33:03',	'2023-04-07 13:33:03');

DROP TABLE IF EXISTS `survey_set`;
CREATE TABLE `survey_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `survey_set` (`id`, `title`, `description`, `user_id`, `start_date`, `end_date`, `date_created`) VALUES
(1,	'Sample Survey',	'Sample Only',	0,	'2020-11-06',	'2020-12-10',	'2020-11-10 09:57:47'),
(2,	'Survey 1',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec in tempus turpis, sed fermentum risus. Praesent vitae velit rutrum, dictum massa nec, pharetra felis. Phasellus enim augue, laoreet in accumsan dictum, mollis nec lectus. ',	0,	'2020-10-15',	'2020-12-30',	'2020-11-10 14:12:09'),
(3,	'Survey 2',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec in tempus turpis, sed fermentum risus. Praesent vitae velit rutrum, dictum massa nec, pharetra felis. ',	0,	'2020-09-01',	'2020-11-27',	'2020-11-10 14:12:33'),
(4,	'Survey 23',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec in tempus turpis, sed fermentum risus. Praesent vitae velit rutrum, dictum massa nec, pharetra felis. ',	0,	'2020-09-10',	'2020-11-27',	'2020-11-10 14:14:03'),
(5,	'Sample Survey 101',	'Sample only',	0,	'2020-10-01',	'2020-11-23',	'2020-11-10 14:14:29');

DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `team_lead` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_team_name` (`name`),
  KEY `fk_team_lead` (`team_lead`),
  CONSTRAINT `fk_team_lead` FOREIGN KEY (`team_lead`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `teams` (`id`, `name`, `team_lead`, `created_at`, `updated_at`) VALUES
(1,	'Team One',	48,	'2023-03-27 10:11:25',	'2023-03-28 13:04:08'),
(2,	'Team Two',	23,	'2023-04-05 06:32:47',	'2023-04-05 06:32:47'),
(3,	'Team 3',	58,	'2023-04-05 06:33:04',	'2023-04-05 06:33:04'),
(4,	'COE',	52,	'2023-04-05 06:33:24',	'2023-04-05 06:33:24');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` char(50) NOT NULL DEFAULT '0',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `facility_id` int(11) DEFAULT NULL,
  `password` varchar(500) NOT NULL DEFAULT '',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_email` (`email`),
  KEY `fk_user_facility_id` (`facility_id`),
  KEY `fk_user_creator` (`created_by`),
  KEY `fk_user_category` (`category_id`),
  CONSTRAINT `fk_user_category` FOREIGN KEY (`category_id`) REFERENCES `user_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `category_id`, `email`, `phone_number`, `first_name`, `last_name`, `middle_name`, `active`, `facility_id`, `password`, `last_login`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	1,	'admin@admin.com',	'0735377609',	'Admin',	'Admin',	'K',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2023-04-19 08:50:04',	1,	'2022-05-17 13:21:00',	'2023-04-19 08:50:04'),
(2,	NULL,	'kimjose@gmail.com',	'0789123123',	'hts',	's',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-05-25 06:11:48',	1,	'2022-05-23 12:35:21',	'2022-08-23 05:08:29'),
(3,	NULL,	'jnmwende693@gmail.com',	'0789123154',	'Jackline',	'Mwende',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-05-25 06:23:27',	1,	'2022-05-24 07:18:45',	'2022-05-25 06:23:27'),
(4,	NULL,	'kimjose693@gmail.com',	'0717894567',	'Jamp',	'Jumper',	'',	1,	28,	'0192023a7bbd73250516f069df18b500',	'2022-05-26 05:47:42',	1,	'2022-05-24 11:48:27',	'2022-06-06 03:45:40'),
(11,	NULL,	'prarriw@cihebkenya.org',	'0701125676',	'Patricia',	'Rarriw',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 00:05:25',	1,	'2022-05-31 06:13:28',	'2022-09-29 12:05:25'),
(12,	NULL,	'jorawo@cihebkenya.org',	'0789123123',	'Joshua',	'Orawo',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 09:02:23',	1,	'2022-06-06 05:54:36',	'2022-09-29 09:02:23'),
(13,	NULL,	'POmwoma@cihebkenya.org',	'0721656327',	'Priscilla',	'Omwoma',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-22 08:39:14',	1,	'2022-06-06 06:06:24',	'2022-09-22 08:39:14'),
(14,	NULL,	'emutiso@cihebkenya.org',	'0736216207',	'Esther',	'Mutiso',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-26 09:50:35',	1,	'2022-06-06 13:21:08',	'2022-09-26 09:50:35'),
(15,	NULL,	'Omunyao@cihebkenya.org',	'0735111154',	'Oscar',	'Munyao',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-28 05:55:01',	1,	'2022-06-09 08:32:44',	'2022-09-28 05:55:01'),
(16,	NULL,	'dpaul@cihebkenya.org',	'0725518149',	'Desmond',	'Mwania',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 09:04:14',	1,	'2022-06-09 08:58:27',	'2022-09-29 09:04:14'),
(17,	NULL,	'eketer@cihebkenya.org',	'0777166267',	'EMILY',	'KETER',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:04',	'2022-06-11 13:13:04'),
(18,	NULL,	'gwangari@cihebkenya.org',	'0788753904',	'GERALD',	'WANGARI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-27 06:47:41',	1,	'2022-06-11 13:13:04',	'2022-09-27 06:47:41'),
(20,	NULL,	'amunyalo@cihebkenya.org',	'0778846071',	'AGNES',	'MUNYALO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-28 06:45:50',	1,	'2022-06-11 13:13:05',	'2022-09-28 06:45:50'),
(21,	NULL,	'jmashala@cihebkenya.org',	'0713301129',	'JUMA',	'MASHALA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-21 23:50:13',	1,	'2022-06-11 13:13:05',	'2022-09-22 11:50:13'),
(22,	NULL,	'kowino@cihebkenya.org',	'0770424076',	'KENNEDY',	'OWINO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:05',	'2022-06-11 13:13:05'),
(23,	NULL,	'wkariuki@cihebkenya.org',	'0733777465',	'Wilson',	'Kariuki',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-28 22:32:28',	1,	'2022-06-11 13:13:05',	'2022-09-29 10:32:28'),
(24,	NULL,	'rmotogwa@cihebkenya.org',	'0733012688',	'RICHARD',	'MOTOGWA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:05',	'2022-06-11 13:13:05'),
(25,	NULL,	'aomanya@cihebkenya.org',	'0738124375',	'ANGELA',	'OMANYA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-26 06:32:15',	1,	'2022-06-11 13:13:05',	'2022-09-26 06:32:15'),
(26,	NULL,	'claibon@cihebkenya.org',	'0704708626',	'CAROLINE',	'LAIBON',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-08 09:06:23',	1,	'2022-06-11 13:13:05',	'2022-09-08 09:06:23'),
(27,	NULL,	'dmuriungi@cihebkenya.org',	'0769362836',	'DORIS',	'MURIUNGI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:05',	'2022-06-11 13:13:05'),
(28,	NULL,	'emomanyi@cihebkenya.org',	'0701698084',	'EMMAH',	'MOMANYI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-08-25 08:40:28',	1,	'2022-06-11 13:13:05',	'2022-08-25 08:40:28'),
(29,	NULL,	'ekadenge@cihebkenya.org',	'0742617927',	'EUNICE',	'KADENGE',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(30,	NULL,	'gnganga@cihebkenya.org',	'0764414398',	'GEOFREY',	'NGANGA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(31,	NULL,	'jngui@cihebkenya.org',	'0710820603',	'JAVIES',	'NGUI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-28 07:12:23',	1,	'2022-06-11 13:13:06',	'2022-09-28 07:12:23'),
(32,	NULL,	'jmaingi@cihebkenya.org',	'0721068368',	'JAMES',	'MAINGI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-06-30 01:35:59',	1,	'2022-06-11 13:13:06',	'2022-06-30 13:35:59'),
(33,	NULL,	'jnganga@cihebkenya.org',	'0762338976',	'JEWEL',	'NGANGA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(34,	NULL,	'jmosago@cihebkenya.org',	'0790606837',	'JOEL',	'MOSAGO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(35,	NULL,	'jonyango@cihebkenya.org',	'0771456926',	'JOSHUA',	'ONYANGO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(36,	NULL,	'mnjoroge@cihebkenya.org',	'0762996236',	'MATTHEW',	'NJOROGE',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(38,	NULL,	'rwangusi@cihebkenya.org',	'0718788820',	'REBECCA',	'WANGUSI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:06',	'2022-06-11 13:13:06'),
(39,	NULL,	'yamondi@cihebkenya.org',	'0764061752',	'YUVINE',	'AMONDI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:07',	'2022-06-11 13:13:07'),
(40,	NULL,	'botieno@cihebkenya.org',	'0776163174',	'BERNARD',	'OTIENO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-09 06:52:36',	1,	'2022-06-11 13:13:07',	'2022-09-09 06:52:36'),
(41,	NULL,	'mchege@cihebkenya.org',	'0765771370',	'MOURINE',	'CHEGE',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:07',	'2022-06-11 13:13:07'),
(42,	NULL,	'cmunguti@cihebkenya.org',	'0708644379',	'CATHERINE',	'MUNGUTI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-06-28 09:13:29',	1,	'2022-06-11 13:13:07',	'2022-06-28 09:13:29'),
(43,	NULL,	'jkarumba@cihebkenya.org',	'0749344531',	'JOSEPH',	'KARUMBA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:07',	'2022-06-11 13:13:07'),
(44,	NULL,	'jmakau@cihebkenya.org',	'0760758327',	'JOSEPH',	'MAKAU',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:07',	'2022-06-11 13:13:07'),
(45,	NULL,	'lkuria@cihebkenya.org',	'0722025612',	'LYDIA',	'KURIA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-28 09:39:19',	1,	'2022-06-11 13:13:07',	'2022-09-28 09:39:19'),
(46,	NULL,	'nkipkorir@cihebkenya.org',	'0724042484',	'NICHOLAS',	'KIPKORIR',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:07',	'2022-06-11 13:13:07'),
(47,	NULL,	'pmutele@cihebkenya.org',	'0780583512',	'PETER',	'MUTELE',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:07',	'2022-06-11 13:13:07'),
(48,	NULL,	'ekaro@cihebkenya.org',	'0796959068',	'ESTHER',	'KARO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 08:31:29',	1,	'2022-06-11 13:13:08',	'2022-09-29 08:31:29'),
(49,	NULL,	'akiarie@cihebkenya.org',	'0758947670',	'ABSOLOM',	'KIARIE',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-21 06:35:50',	1,	'2022-06-11 13:13:08',	'2022-09-21 06:35:50'),
(50,	NULL,	'dmutai@cihebkenya.org',	'0753005161',	'DEDAN',	'MUTAI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:08',	'2022-06-11 13:13:08'),
(51,	NULL,	'gnduta@cihebkenya.org',	'0727455313',	'GLADYS',	'NDUTA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:08',	'2022-06-11 13:13:08'),
(52,	NULL,	'jmwaniki@cihebkenya.org',	'0798134274',	'JOSEPH',	'MWANIKI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-08-30 00:15:44',	1,	'2022-06-11 13:13:08',	'2022-08-30 12:15:44'),
(54,	NULL,	'bmuturi@cihebkenya.org',	'0799302672',	'BERNARD',	'MUTURI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:08',	'2022-06-11 13:13:08'),
(55,	NULL,	'lwanjeri@cihebkenya.org',	'0718182311',	'LUDOVIC',	'WANJERI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 08:10:43',	1,	'2022-06-11 13:13:08',	'2022-09-29 08:10:43'),
(56,	NULL,	'dpaul@cihebkenya.org',	'0763608212',	'DESMOND',	'PAUL',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-08-09 05:35:42',	1,	'2022-06-11 13:13:08',	'2022-08-09 17:35:42'),
(57,	NULL,	'dodhiambo@cihebkenya.org',	'0721231557',	'DUNCAN',	'ODHIAMBO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-06-20 06:39:48',	1,	'2022-06-11 13:13:09',	'2022-06-20 06:39:48'),
(58,	NULL,	'jmuema@cihebkenya.org',	'0706790550',	'JANET',	'MUEMA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-21 07:19:10',	1,	'2022-06-11 13:13:09',	'2022-09-21 07:19:10'),
(59,	NULL,	'orotich@cihebkenya.org',	'0751240537',	'OBADIAH',	'ROTICH',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:09',	'2022-06-11 13:13:09'),
(60,	NULL,	'rrotich@cihebkenya.org',	'0700888344',	'ROBERT',	'ROTICH',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:09',	'2022-06-11 13:13:09'),
(61,	NULL,	'sobala@cihebkenya.org',	'0755135638',	'STEPHEN',	'OBALA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:09',	'2022-06-11 13:13:09'),
(62,	NULL,	'sthumbi@cihebkenya.org',	'0743763288',	'SUSAN',	'THUMBI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:09',	'2022-06-11 13:13:09'),
(63,	NULL,	'dnganga@cihebkenya.org',	'0737409060',	'DAMARIS',	'NGANGA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-05 04:36:28',	1,	'2022-06-11 13:13:09',	'2022-09-05 04:36:28'),
(64,	NULL,	'dtobon@cihebkenya.org',	'0793738767',	'DAMARIS',	'TOBON',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:09',	'2022-06-11 13:13:09'),
(65,	NULL,	'gochieng@cihebkenya.org',	'0784854136',	'GADAFI',	'OCHIENG',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:09',	'2022-06-11 13:13:09'),
(66,	NULL,	'emwangi@cihebkenya.org',	'0792145794',	'EMMAH',	'MWANGI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:10',	'2022-06-11 13:13:10'),
(67,	NULL,	'fwachira@cihebkenya.org',	'0727723873',	'FRANCIS',	'WACHIRA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:10',	'2022-06-11 13:13:10'),
(68,	NULL,	'pawuor@cihebkenya.org',	'0779040971',	'PATRICK',	'AWUOR',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-09 09:42:51',	1,	'2022-06-11 13:13:10',	'2022-09-09 09:42:51'),
(69,	NULL,	'snzyoka@cihebkenya.org',	'0797273938',	'SARAH',	'NZYOKA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-15 07:23:10',	1,	'2022-06-11 13:13:10',	'2022-09-15 07:23:10'),
(70,	NULL,	'ronsomu@cihebkenya.org',	'0729234631',	'ROSE',	'ONSOMU',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:10',	'2022-06-11 13:13:10'),
(71,	NULL,	'cmichura@cihebkenya.org',	'0722444231',	'CHRISTINE',	'MICHURA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-08-26 07:24:32',	1,	'2022-06-11 13:13:10',	'2022-08-26 07:24:32'),
(72,	NULL,	'smuhamed@cihebkenya.org',	'0767324587',	'SULEIMAN',	'MUHAMED',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:10',	'2022-06-11 13:13:10'),
(73,	NULL,	'aolela@cihebkenya.org',	'0729363476',	'ANNETE',	'OLELA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-08-04 04:37:41',	1,	'2022-06-11 13:13:10',	'2022-08-04 04:37:41'),
(74,	NULL,	'wmungai@cihebkenya.org',	'0749244260',	'WALLACE',	'MUNGAI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:10',	'2022-06-11 13:13:10'),
(75,	NULL,	'jkado@cihebkenya.org',	'0778283050',	'JAVAN',	'KADO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 07:53:42',	1,	'2022-06-11 13:13:11',	'2022-09-29 07:53:42'),
(76,	NULL,	'rkasivu@cihebkenya.org',	'0708825179',	'ROBERT',	'KASIVU',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:11',	'2022-06-11 13:13:11'),
(77,	NULL,	'injagi@cihebkenya.org',	'0722587540',	'ISAAC',	'NJAGI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:11',	'2022-06-11 13:13:11'),
(78,	NULL,	'wouko@cihebkenya.org',	'0733097862',	'WILLIAM',	'OUKO',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:11',	'2022-06-11 13:13:11'),
(79,	NULL,	'domiti@cihebkenya.org',	'0749112852',	'DORIS',	'OMITI',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-08 22:59:46',	1,	'2022-06-11 13:13:11',	'2022-09-09 10:59:46'),
(80,	NULL,	'vachieng@cihebkenya.org',	'0112528491',	'VERITY',	'ACHIENG',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 06:59:50',	1,	'2022-06-11 13:13:11',	'2022-09-29 06:59:50'),
(81,	NULL,	'awughanga@cihebkenya.org',	'0796316019',	'ANNE',	'WUGHANGA',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:11',	'2022-06-11 13:13:11'),
(82,	NULL,	'sndaba@mgic.umaryland.edu',	'0772875985',	'Sospeter',	'Ndaba',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:11',	'2022-06-11 13:13:11'),
(83,	NULL,	'vmakhoha@mgic.umaryland.edu',	'0763886425',	'Violet',	'Makhoha',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:11',	'2022-06-11 13:13:11'),
(84,	NULL,	'cochola@mgic.umaryland.edu',	'0791542335',	'Cornelia',	'Ochola',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:12',	'2022-06-11 13:13:12'),
(85,	NULL,	'fkimonye@mgic.umaryland.edu',	'0704959101',	'Francis',	'Kimonye',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:12',	'2022-06-11 13:13:12'),
(86,	NULL,	'tmasai@mgic.umaryland.edu',	'0710279142',	'Tina',	'Masai',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-25 22:10:11',	1,	'2022-06-11 13:13:12',	'2022-09-26 10:10:11'),
(87,	NULL,	'skoech@mgic.umaryland.edu',	'0710529985',	'Sylvia',	'Koech',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-06 22:20:58',	1,	'2022-06-11 13:13:12',	'2022-09-07 10:20:58'),
(88,	NULL,	'cmuthamia@mgic.umaryland.edu',	'0726821277',	'Carolyne',	'Muthamia',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-06-15 07:02:58',	1,	'2022-06-11 13:13:12',	'2022-06-15 07:02:58'),
(89,	NULL,	'jkirigha@mgic.umaryland.edu',	'0793510214',	'Jardine',	'Kirigha',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-26 02:07:50',	1,	'2022-06-11 13:13:12',	'2022-09-26 14:07:50'),
(90,	NULL,	'jkimani@mgic.umaryland.edu',	'0786982307',	'Joseph',	'Kimani',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-09 06:51:49',	1,	'2022-06-11 13:13:12',	'2022-09-09 06:51:49'),
(91,	NULL,	'ootieno.@mgic.umaryland.edu',	'0725714204',	'Oscar',	'Otieno',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-20 01:45:17',	1,	'2022-06-11 13:13:12',	'2022-09-20 13:45:17'),
(92,	NULL,	'cngeno@mgic.umaryland.edu',	'0728773890',	'Caroline',	'Ngeno',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:12',	'2022-06-11 13:13:12'),
(93,	NULL,	'egichora@mgic.umaryland.edu',	'0713048656',	'Elijah',	'Gichora',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-27 07:23:40',	1,	'2022-06-11 13:13:12',	'2022-09-27 19:23:40'),
(94,	NULL,	'bawiti@mgic.umaryland.edu',	'0743853641',	'Brian',	'Awiti',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-19 22:39:31',	1,	'2022-06-11 13:13:13',	'2022-09-20 10:39:31'),
(95,	NULL,	'rmwaura@mgic.umaryland.edu',	'0712051758',	'Ruth',	'Mwaura',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-08-03 08:31:56',	1,	'2022-06-11 13:13:13',	'2022-08-03 08:31:56'),
(96,	NULL,	'cngomo@mgic.umaryland.edu',	'0782245133',	'Caroline',	'Ngomo',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:13',	'2022-06-11 13:13:13'),
(97,	NULL,	'lomondi@mgic.umaryland.edu',	'0765085231',	'Linda',	'Omondi',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:13',	'2022-06-11 13:13:13'),
(98,	NULL,	'dkaruga@mgic.umaryland.edu',	'0741062777',	'Daniel',	'Karuga',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-11 13:13:13',	'2022-06-11 13:13:13'),
(99,	NULL,	'hngetich@cihebkenya.org',	'0708050965',	'Herbert',	'Ngetich',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-07-22 08:09:13',	1,	'2022-06-14 06:02:56',	'2022-07-22 08:09:13'),
(100,	NULL,	'rmulwa@cihebkenya.org',	'0799086957',	'Robert',	'Mulwa',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-27 23:10:42',	1,	'2022-06-15 06:31:23',	'2022-09-28 11:10:42'),
(101,	NULL,	'jmwangi@cihebkenya.org',	'0722998587',	'Joseph',	'Mwangi',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 08:12:26',	1,	'2022-06-15 06:33:49',	'2022-09-29 08:12:26'),
(102,	NULL,	'rnyaboke@cihebkenya.org',	'0735100284',	'Rose',	'Nyaboke',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-27 05:31:33',	1,	'2022-06-15 06:37:01',	'2022-09-27 05:31:33'),
(103,	NULL,	'bodhiambo@cihebkenya.org',	'0721952214',	'Benard',	'Odhiambo',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-26 06:16:44',	1,	'2022-06-20 12:03:59',	'2022-09-26 06:16:44'),
(104,	NULL,	'oduncan@cihebkenya.org',	'0721231557',	'Duncan',	'Owino',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-11 22:48:22',	1,	'2022-06-27 05:53:29',	'2022-09-12 10:48:22'),
(105,	NULL,	'smuli@gmail.com',	'0701641048',	'Susan',	'Muli',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-28 09:37:51',	'2022-06-28 09:37:51'),
(106,	NULL,	'hchimaleni@gmail.com',	'0794132521',	'Hilda',	'Chimaleni',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-28 09:37:51',	'2022-06-28 09:37:51'),
(107,	NULL,	'dnyambok@gmail.com',	'0710619496',	'Dotty',	'Nyambok',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-28 09:37:51',	'2022-06-28 09:37:51'),
(108,	NULL,	'jiguri@gmail.com',	'0750805516',	'Jane',	'Iguri',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-28 09:37:51',	'2022-06-28 09:37:51'),
(109,	NULL,	'lchialo@gmail.com',	'0779653781',	'Leonard',	'Chialo',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-28 09:37:51',	'2022-06-28 09:37:51'),
(110,	NULL,	'eoino@gmail.com',	'0769468159',	'Emma',	'Oino',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	NULL,	1,	'2022-06-28 09:37:51',	'2022-06-28 09:37:51'),
(111,	NULL,	'wwairimu@cihebkenya.org',	'0710000000',	'Winfred',	'Kariuki',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-29 08:42:36',	1,	'2022-07-04 06:58:18',	'2022-09-29 08:42:36'),
(112,	NULL,	'dgathecha@mgic.umaryland.edu',	'0704254679',	'Dennis',	'Gathecha',	'',	1,	NULL,	'0192023a7bbd73250516f069df18b500',	'2022-09-28 07:05:14',	1,	'2022-07-25 09:40:15',	'2022-09-28 07:05:14'),
(126,	NULL,	'test@test.com',	'0790392945',	'Test',	'Twin',	'One',	1,	53,	'e10adc3949ba59abbe56e057f20f883e',	NULL,	1,	'2023-02-28 08:00:00',	'2023-02-28 08:03:19'),
(128,	2,	'jnkimani693@gmail.com',	'0717890231',	'Joseph',	'Kimani',	'Ngima',	1,	NULL,	'e10adc3949ba59abbe56e057f20f883e',	NULL,	1,	'2023-04-01 04:49:47',	'2023-04-01 04:49:47');

DROP TABLE IF EXISTS `user_categories`;
CREATE TABLE `user_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_level` enum('Program','Facility') NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT '',
  `permissions` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_category_creator` (`created_by`),
  CONSTRAINT `fk_category_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_categories` (`id`, `access_level`, `name`, `description`, `permissions`, `created_by`, `created_at`, `updated_at`) VALUES
(1,	'Program',	'System Administrator',	'Test',	'1,2,3,4,5',	1,	'2023-03-31 07:56:52',	'2023-04-01 04:25:09'),
(2,	'Program',	'Program Administrator',	'This is the program admin',	'2,3,4,5',	1,	'2023-03-31 10:19:10',	'2023-03-31 10:19:10'),
(3,	'Facility',	'Facility Admin',	'Admin for facility',	'2,4,6',	1,	'2023-04-01 04:02:12',	'2023-04-05 06:34:59');

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(120) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_permissions` (`id`, `name`, `description`) VALUES
(1,	'System administration',	'Manage system. includes teams and facility creation'),
(2,	'Users Management',	'Manage users, create update delete'),
(3,	'Checklist management',	'Create checklists, sections and questions'),
(4,	'Access Reports',	'Access to reports module'),
(5,	'Create Visits & Action points',	'Able to create visits and action points'),
(6,	'All Action Points',	'Can view and comment on all action points');

DROP TABLE IF EXISTS `visit_findings`;
CREATE TABLE `visit_findings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_id` int(11) NOT NULL,
  `description` varchar(300) NOT NULL DEFAULT '',
  `ap_ids` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_finding_visit` (`visit_id`),
  KEY `fk_finding_user` (`created_by`),
  CONSTRAINT `fk_finding_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_finding_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `visit_sections`;
CREATE TABLE `visit_sections` (
  `visit_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `submitted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`visit_id`,`section_id`),
  KEY `fk_visit_section_user` (`user_id`),
  KEY `fk_visit_section_section` (`section_id`),
  CONSTRAINT `fk_visit_section_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_section_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_section_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2023-04-19 09:52:14
