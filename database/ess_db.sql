-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: ess
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `action_points`
--

DROP TABLE IF EXISTS `action_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `action_points` (
  `id` int NOT NULL AUTO_INCREMENT,
  `visit_id` int DEFAULT NULL,
  `facility_id` int DEFAULT NULL,
  `question_id` int DEFAULT NULL,
  `title` varchar(121) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `due_date` date NOT NULL,
  `assign_to` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` enum('Pending','Done') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ap_visit` (`visit_id`),
  KEY `fk_ap_creator` (`created_by`),
  KEY `fk_ap_question` (`question_id`),
  KEY `index_ap_title` (`title`) USING BTREE,
  KEY `fk_action_facility` (`facility_id`),
  CONSTRAINT `fk_action_facility` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_ap_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_ap_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_ap_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `action_points`
--

LOCK TABLES `action_points` WRITE;
/*!40000 ALTER TABLE `action_points` DISABLE KEYS */;
INSERT INTO `action_points` VALUES (1,1,2,1,'This is the title','Kindly review all these and getback','2023-02-25','44,85','Pending',1,'2023-02-24 07:13:01','2023-04-03 10:27:51'),(2,1,2,4,'GDS','Conduct a gender diversity stuff','2023-02-28','13,75,101','Pending',1,'2023-02-24 10:18:52','2023-04-03 10:27:51'),(3,3,2,3,'Test title','Bugger','2023-03-17','1','Done',1,'2023-03-15 09:03:14','2023-04-03 10:27:51'),(4,3,2,3,'Test two','Bug','2023-03-18','1,4','Done',1,'2023-03-15 09:11:54','2023-04-03 10:27:51'),(5,3,2,3,'This is test title','Big bang theory','2023-03-18','1,3','Pending',1,'2023-03-15 09:47:04','2023-04-03 10:27:51'),(6,NULL,4,NULL,'Ting','The thing is...','2023-04-27','34,40','Pending',1,'2023-04-03 11:47:05','2023-04-03 11:47:05'),(7,5,NULL,NULL,'Test ','This is test','2023-04-29','11','Pending',1,'2023-04-05 08:52:21','2023-04-05 08:52:21'),(8,5,NULL,NULL,'Test2','Do this','2023-04-29','3','Pending',1,'2023-04-05 08:53:03','2023-04-05 08:53:03'),(9,5,NULL,NULL,'Baggot','Test','2023-04-29','11,22','Pending',1,'2023-04-05 08:54:32','2023-04-05 08:54:32'),(10,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 08:56:09','2023-04-05 08:56:09'),(11,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 08:56:51','2023-04-05 08:56:51'),(12,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 08:57:35','2023-04-05 08:57:35'),(13,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 08:59:08','2023-04-05 08:59:08'),(14,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 08:59:21','2023-04-05 08:59:21'),(15,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 09:02:40','2023-04-05 09:02:40'),(16,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 09:02:44','2023-04-05 09:02:44'),(17,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 09:03:52','2023-04-05 09:03:52'),(18,5,NULL,NULL,'Bagg','test','2023-04-21','2,4','Pending',1,'2023-04-05 09:04:06','2023-04-05 09:04:06'),(20,1,NULL,3,'Do That','Do this','2023-04-28','3,11','Pending',1,'2023-04-06 12:23:15','2023-04-06 12:23:15');
/*!40000 ALTER TABLE `action_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `survey_id` int NOT NULL,
  `user_id` int NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `question_id` int NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (1,1,2,'Sample Only',4,'2020-11-10 14:46:07'),(2,1,2,'[JNmhW],[zZpTE]',2,'2020-11-10 14:46:07'),(3,1,2,'dAWTD',1,'2020-11-10 14:46:07'),(4,1,3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec in tempus turpis, sed fermentum risus. Praesent vitae velit rutrum, dictum massa nec, pharetra felis. Phasellus enim augue, laoreet in accumsan dictum, mollis nec lectus. Aliquam id viverra nisl. Proin quis posuere nulla. Nullam suscipit eget leo ut suscipit.',4,'2020-11-10 15:59:43'),(5,1,3,'[qCMGO],[JNmhW]',2,'2020-11-10 15:59:43'),(6,1,3,'esNuP',1,'2020-11-10 15:59:43');
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ap_comments`
--

DROP TABLE IF EXISTS `ap_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ap_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ap_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_comment_ap` (`ap_id`),
  KEY `fk_comment_user` (`user_id`),
  CONSTRAINT `fk_comment_ap` FOREIGN KEY (`ap_id`) REFERENCES `action_points` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ap_comments`
--

LOCK TABLES `ap_comments` WRITE;
/*!40000 ALTER TABLE `ap_comments` DISABLE KEYS */;
INSERT INTO `ap_comments` VALUES (1,3,1,'Kwako mi ni kichaa mi ni kichaa\nif you want be a movie star nina kicki zisoisha','2023-03-15 12:54:27','2023-03-15 12:54:27'),(2,3,1,'Another comment by me. Kwani mtaduu 🤸','2023-03-15 13:19:34','2023-03-15 13:19:34'),(3,17,1,'Check this out','2023-04-05 13:03:39','2023-04-05 13:03:39'),(4,14,1,'Not done','2023-04-05 13:03:50','2023-04-05 13:03:50'),(5,11,1,'Bag of rice','2023-04-05 13:04:06','2023-04-05 13:04:06');
/*!40000 ALTER TABLE `ap_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart_abstraction_gaps`
--

DROP TABLE IF EXISTS `chart_abstraction_gaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chart_abstraction_gaps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `abstraction_id` int NOT NULL,
  `gap` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_gap_abstraction` (`abstraction_id`),
  CONSTRAINT `fk_gap_abstraction` FOREIGN KEY (`abstraction_id`) REFERENCES `chart_abstractions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_abstraction_gaps`
--

LOCK TABLES `chart_abstraction_gaps` WRITE;
/*!40000 ALTER TABLE `chart_abstraction_gaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `chart_abstraction_gaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chart_abstractions`
--

DROP TABLE IF EXISTS `chart_abstractions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chart_abstractions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `visit_id` int NOT NULL,
  `created_by` int NOT NULL,
  `ccc_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `age` double NOT NULL,
  `ap_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_abstraction_visit` (`visit_id`),
  KEY `fk_abstraction_creator` (`created_by`),
  CONSTRAINT `fk_abstraction_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_abstraction_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chart_abstractions`
--

LOCK TABLES `chart_abstractions` WRITE;
/*!40000 ALTER TABLE `chart_abstractions` DISABLE KEYS */;
/*!40000 ALTER TABLE `chart_abstractions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklists`
--

DROP TABLE IF EXISTS `checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `abbr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Abbreviation...',
  `description` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `created_by` int NOT NULL,
  `status` enum('draft','published','retired') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `published_by` int DEFAULT NULL,
  `retired_at` timestamp NULL DEFAULT NULL,
  `retired_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_checklist_created_by` (`created_by`),
  CONSTRAINT `fk_checklist_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklists`
--

LOCK TABLES `checklists` WRITE;
/*!40000 ALTER TABLE `checklists` DISABLE KEYS */;
INSERT INTO `checklists` VALUES (1,'Test One P','TOP','please work now',1,'published','2023-03-06 05:00:06',1,NULL,NULL,'2023-02-16 13:37:47','2023-03-06 05:00:06'),(2,'Checklist Two','TO','Desc',1,'published',NULL,NULL,NULL,NULL,'2023-02-22 13:36:55','2023-03-03 10:17:11'),(3,'HIV Testing Service','HTS','This is HTS',1,'published','2023-03-06 14:22:42',1,NULL,NULL,'2023-03-06 14:15:01','2023-03-06 14:22:42'),(4,'Care and Treatment','CT','tackle',1,'draft',NULL,1,NULL,NULL,'2023-03-07 10:27:25','2023-03-10 07:37:51');
/*!40000 ALTER TABLE `checklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counties`
--

DROP TABLE IF EXISTS `counties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `counties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` int NOT NULL,
  `capital` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counties`
--

LOCK TABLES `counties` WRITE;
/*!40000 ALTER TABLE `counties` DISABLE KEYS */;
INSERT INTO `counties` VALUES (1,'Baringo',30,'Kabarnet','2021-02-22 08:02:16','2021-02-22 08:02:16'),(2,'Bomet',36,'Bomet','2021-02-22 08:02:16','2021-02-22 08:02:16'),(3,'Bungoma',39,'Bungoma','2021-02-22 08:02:16','2021-02-22 08:02:16'),(4,'Busia',40,'Busia','2021-02-22 08:02:16','2021-02-22 08:02:16'),(5,'Elgeyo-Marakwet',28,'Iten','2021-02-22 08:02:16','2021-02-22 08:02:16'),(6,'Embu',14,'Embu','2021-02-22 08:02:16','2021-02-22 08:02:16'),(7,'Garissa',7,'Garissa','2021-02-22 08:02:16','2021-02-22 08:02:16'),(8,'Homa Bay',43,'Homa Bay','2021-02-22 08:02:16','2021-02-22 08:02:16'),(9,'Isiolo',11,'Isiolo','2021-02-22 08:02:17','2021-02-22 08:02:17'),(10,'Kajiado',34,'Kajiado','2021-02-22 08:02:17','2021-02-22 08:02:17'),(11,'Kakamega',37,'Kakamega','2021-02-22 08:02:17','2021-02-22 08:02:17'),(12,'Kericho',35,'Kericho','2021-02-22 08:02:17','2021-02-22 08:02:17'),(13,'Kiambu',22,'Kiambu','2021-02-22 08:02:17','2021-02-22 08:02:17'),(14,'Kilifi',3,'Kilifi','2021-02-22 08:02:17','2021-02-22 08:02:17'),(15,'Kirinyaga',20,'Kerugoya/Kutus','2021-02-22 08:02:17','2021-02-22 08:02:17'),(16,'Kisii',45,'Kisii','2021-02-22 08:02:17','2021-02-22 08:02:17'),(17,'Kisumu',42,'Kisumu','2021-02-22 08:02:17','2021-02-22 08:02:17'),(18,'Kitui',15,'Kitui','2021-02-22 08:02:17','2021-02-22 08:02:17'),(19,'Kwale',2,'Kwale','2021-02-22 08:02:17','2021-02-22 08:02:17'),(20,'Laikipia',31,'Rumuruti','2021-02-22 08:02:17','2021-02-22 08:02:17'),(21,'Lamu',5,'Lamu','2021-02-22 08:02:17','2021-02-22 08:02:17'),(22,'Machakos',16,'Machakos','2021-02-22 08:02:17','2021-02-22 08:02:17'),(23,'Makueni',17,'Wote','2021-02-22 08:02:18','2021-02-22 08:02:18'),(24,'Mandera',9,'Mandera','2021-02-22 08:02:18','2021-02-22 08:02:18'),(25,'Marsabit',10,'Marsabit','2021-02-22 08:02:18','2021-02-22 08:02:18'),(26,'Meru',12,'Meru','2021-02-22 08:02:18','2021-02-22 08:02:18'),(27,'Migori',44,'Migori','2021-02-22 08:02:18','2021-02-22 08:02:18'),(28,'Mombasa',1,'Mombasa City','2021-02-22 08:02:18','2021-02-22 08:02:18'),(29,'Murang\'a',21,'Murang\'a','2021-02-22 08:02:18','2021-02-22 08:02:18'),(30,'Nairobi',47,'Nairobi City','2021-02-22 08:02:18','2021-02-22 08:02:18'),(31,'Nakuru',32,'Nakuru','2021-02-22 08:02:18','2021-02-22 08:02:18'),(32,'Nandi',29,'Kapsabet','2021-02-22 08:02:18','2021-02-22 08:02:18'),(33,'Narok',33,'Narok','2021-02-22 08:02:18','2021-02-22 08:02:18'),(34,'Nyamira',46,'Nyamira','2021-02-22 08:02:18','2021-02-22 08:02:18'),(35,'Nyandarua',18,'Ol Kalou','2021-02-22 08:02:18','2021-02-22 08:02:18'),(36,'Nyeri',19,'Nyeri','2021-02-22 08:02:18','2021-02-22 08:02:18'),(37,'Samburu',25,'Maralal','2021-02-22 08:02:19','2021-02-22 08:02:19'),(38,'Siaya',41,'Siaya','2021-02-22 08:02:19','2021-02-22 08:02:19'),(39,'Taita-Taveta',6,'Voi','2021-02-22 08:02:19','2021-02-22 08:02:19'),(40,'Tana River',4,'Hola','2021-02-22 08:02:19','2021-02-22 08:02:19'),(41,'Tharaka-Nithi',13,'Chuka','2021-02-22 08:02:19','2021-02-22 08:02:19'),(42,'Trans-Nzoia',26,'Kitale','2021-02-22 08:02:19','2021-02-22 08:02:19'),(43,'Turkana',23,'Lodwar','2021-02-22 08:02:19','2021-02-22 08:02:19'),(44,'Uasin Gishu',27,'Eldoret','2021-02-22 08:02:19','2021-02-22 08:02:19'),(45,'Vihiga',38,'Vihiga','2021-02-22 08:02:19','2021-02-22 08:02:19'),(46,'Wajir',8,'Wajir','2021-02-22 08:02:19','2021-02-22 08:02:19'),(47,'West Pokot',24,'Kapenguria','2021-02-22 08:02:19','2021-02-22 08:02:19');
/*!40000 ALTER TABLE `counties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilities`
--

DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facilities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mfl_code` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `county_code` int NOT NULL,
  `team_id` int DEFAULT NULL,
  `active` tinyint DEFAULT '0',
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_mfl_code` (`mfl_code`) USING BTREE,
  KEY `FK_facility_county` (`county_code`),
  KEY `Index _name` (`name`) USING BTREE,
  KEY `fk_facility_team` (`team_id`),
  CONSTRAINT `FK_facility_county` FOREIGN KEY (`county_code`) REFERENCES `counties` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_facility_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1458 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilities`
--

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (2,'12935','Embakasi Health Centre',47,1,1,23.23,0.09,'2022-05-16 10:55:01','2023-03-28 09:09:56'),(3,'12974','Huruma Lions Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-10-12 11:44:37'),(4,'18176','Sex Workers Outreach Program (Kibra)',47,1,1,NULL,NULL,'2022-05-16 10:55:01','2023-03-28 09:10:44'),(5,'17684','Hope World Wide Kenya Mukuru Clinic',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(6,'19504','Child Doctor Kenya',47,1,1,NULL,NULL,'2022-05-16 10:55:01','2023-03-28 14:02:55'),(7,'19429','SWOP Donholm',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01',NULL),(8,'28284','LVCT Health Dreams',47,1,1,NULL,NULL,'2022-05-16 10:55:01','2023-03-28 14:02:55'),(9,'13189','SOS Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(10,'12929','Dreams Centre Dispensary (Langata)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(11,'13249','Waithaka Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(12,'13240','Umoja Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(13,'13234','Tabitha Medical Clinic',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(14,'13180','Sex Workers Operation Project (Swop)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(15,'23200','bar Hostess Empowerment & Support programme-Roysambu',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:09'),(16,'18896','Swop Thika Road',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01',NULL),(17,'19271','Swop Korogocho',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01',NULL),(18,'19719','Swop Kawangware',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01',NULL),(19,'13210','St Joseph\'s Dispensary (Dagoretti)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(20,'13188','Sokoni Arcade VCT',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01',NULL),(21,'13186','Silanga (MSF Belgium) Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(22,'13171','Ruai Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(23,'13165','Riruta Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(24,'28592','LVCT Health (Ngando Dreams Site)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:08'),(25,'13156','Pumwani Maternity Hospital',47,4,1,NULL,NULL,'2022-05-16 10:55:01','2023-04-05 06:33:59'),(26,'13155','Pumwani Majengo Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(27,'13126','Njiru  Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(28,'12893','Chandaria Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(29,'13122','Ngara Health Centre (City Council of Nairobi)',47,4,1,NULL,NULL,'2022-05-16 10:55:01','2023-04-05 06:33:59'),(30,'13113','Nairobi South Clinic',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(31,'13108','Nairobi Deaf (Liverpool)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01',NULL),(32,'13105','Mutuini Sub-District Hospital',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(33,'13076','Mathari Hospital',47,4,1,NULL,NULL,'2022-05-16 10:55:01','2023-04-05 06:33:59'),(34,'19308','Maisha House VCT (Noset)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:09'),(35,'13051','Loco Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(36,'13050','Liverpool VCT',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(37,'13041','Langata Subcounty Hospital(Mugumoini)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(38,'23414','Kware Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(39,'13028','Kibera Community Health Centre - Amref',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(40,'13023','Kenyatta National Hospital',47,4,1,NULL,NULL,'2022-05-16 10:55:01','2023-04-05 06:33:59'),(41,'13015','Kayole I Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(42,'12871','APTC Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(43,'12913','Dandora I Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(44,'12930','Eastleigh Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(45,'13029','Kibera D O Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(46,'13009','Karura Health Centre (Kiambu Rd)',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(47,'13003','Karen Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(48,'12998','Kaloleni Dispensary',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(49,'19471','Iom Wellness Clinic',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(50,'13245','Ushirika Medical Clinic',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(51,'13019','Kemri VCT',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-30 08:53:10'),(52,'12889','Cana Family Life Clinic',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(53,'12876','Babadogo Health Centre',47,NULL,1,NULL,NULL,'2022-05-16 10:55:01','2022-06-28 09:40:36'),(54,'20402','Support For Addiction Prevention & Treatment In Africa',47,NULL,1,NULL,NULL,'2022-06-13 10:03:01','2022-06-30 08:53:09'),(55,'23786','NGARA MAT CLINIC',47,NULL,1,NULL,NULL,'2022-06-13 10:05:58','2022-06-30 08:53:09'),(56,'13079','Mathari MAT',47,NULL,1,NULL,NULL,'2022-06-13 10:08:48','2022-06-13 10:08:48'),(57,'12883','Biafra Lions Clinic',47,NULL,1,NULL,NULL,'2022-06-20 12:46:18','2022-06-28 09:40:36'),(58,'12977','Patanisho Maternity and Nursing Home',47,NULL,1,NULL,NULL,'2022-06-20 12:46:57','2022-06-30 08:53:10'),(59,'12919','Dog Unit Dispensary (O P Kenya Police)',47,NULL,1,NULL,NULL,'2022-06-21 13:57:46','2022-06-30 08:53:10'),(60,'13138','Pangani Dispensary',47,NULL,1,NULL,NULL,'2022-06-22 13:07:06','2022-06-28 09:40:36'),(61,'13184','Shauri Moyo Clinic',47,NULL,1,NULL,NULL,'2022-06-22 13:07:59','2022-06-30 08:53:10'),(62,'13239','Uhuru Camp Dispensary (O P Admin Police)',47,NULL,1,NULL,NULL,'2022-06-22 13:08:42','2022-06-30 08:53:10'),(66,'13130','National Youth Service Hq Dispensary (Ruaraka)',47,NULL,1,NULL,NULL,'2022-06-22 13:59:28','2022-06-30 08:53:10'),(68,'28742','SWOP Majengo clinic',47,NULL,1,NULL,NULL,'2022-06-27 06:17:25','2022-06-27 06:17:25'),(69,'13078','Mathare Police Depot',47,NULL,1,NULL,NULL,'2022-06-27 10:28:47','2022-06-28 09:40:36'),(70,'13259','Woodley Clinic',47,NULL,1,NULL,NULL,'2022-06-28 06:57:58','2022-06-28 09:40:36'),(71,'12989','Jerusalem Clinic',47,NULL,1,NULL,NULL,'2022-06-28 07:07:58','2022-06-28 09:40:36'),(72,'12961','Gsu Dispensary (Nairobi West)',47,NULL,1,NULL,NULL,'2022-06-28 08:32:42','2022-06-28 09:40:36'),(73,'12963','Gsu Hq Dispensary (Ruaraka)',47,NULL,1,NULL,NULL,'2022-06-28 08:33:28','2022-06-28 09:40:36'),(74,'12962','GSUTraining School',47,NULL,1,NULL,NULL,'2022-06-28 08:33:54','2022-06-30 08:53:10'),(75,'18505','KEMRI Mimosa',47,NULL,1,NULL,NULL,'2022-06-28 08:37:22','2022-06-30 08:53:10'),(76,'13030','Kibera South Health Centre',47,NULL,1,NULL,NULL,'2022-06-28 08:40:14','2022-06-28 09:40:36'),(77,'26911','Noset Maisha House',47,NULL,1,NULL,NULL,'2022-06-28 08:44:32','2022-06-30 08:53:09'),(78,'13144','South B Police Band Dispensary',47,NULL,1,NULL,NULL,'2022-06-28 08:47:15','2022-06-28 09:40:36'),(346,'28885','Mama Margaret Uhuru Hospital',47,NULL,1,NULL,NULL,'2022-06-30 08:53:08','2022-06-30 08:53:08'),(383,'28552','Ngomongo Dispensary',47,NULL,1,NULL,NULL,'2022-06-30 08:53:08','2022-06-30 08:53:08'),(408,'27815','Tassia Health Centre',47,NULL,1,NULL,NULL,'2022-06-30 08:53:08','2022-06-30 08:53:08'),(409,'28373','Njenga Health Centre',47,NULL,1,NULL,NULL,'2022-06-30 08:53:08','2022-06-30 08:53:08'),(441,'27196','Gatina Dispensary(Dagoretti)',47,NULL,1,NULL,NULL,'2022-06-30 08:53:09','2022-06-30 08:53:09'),(455,'26913','Ushirika Dispensary (Dandora)',47,NULL,1,NULL,NULL,'2022-06-30 08:53:09','2022-06-30 08:53:09'),(570,'24979','Kenyatta University Teaching Refferal and Research Hospital',47,NULL,1,NULL,NULL,'2022-06-30 08:53:09','2022-06-30 08:53:09');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility_visits`
--

DROP TABLE IF EXISTS `facility_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facility_visits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `facility_id` int NOT NULL,
  `visit_date` date NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_facility_visit` (`facility_id`,`visit_date`),
  KEY `fk_visit_creator` (`created_by`),
  CONSTRAINT `fk_visit_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_facility` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility_visits`
--

LOCK TABLES `facility_visits` WRITE;
/*!40000 ALTER TABLE `facility_visits` DISABLE KEYS */;
INSERT INTO `facility_visits` VALUES (1,2,'2023-02-02',0,0,1,'2023-02-22 09:39:16','2023-03-09 11:26:24'),(3,2,'2023-02-01',NULL,NULL,1,'2023-02-22 09:57:54','2023-02-22 09:57:54'),(4,3,'2023-03-08',0,0,1,'2023-03-09 11:25:46','2023-03-09 11:25:46'),(5,3,'2023-03-09',0,0,1,'2023-03-14 12:15:49','2023-03-14 12:15:49'),(6,6,'2023-03-15',0,0,1,'2023-03-15 07:34:04','2023-03-15 07:34:04'),(7,4,'2023-03-15',0,0,1,'2023-03-15 07:34:25','2023-03-15 07:34:25');
/*!40000 ALTER TABLE `facility_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frequencies`
--

DROP TABLE IF EXISTS `frequencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `frequencies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(98) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `days` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_frequency_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frequencies`
--

LOCK TABLES `frequencies` WRITE;
/*!40000 ALTER TABLE `frequencies` DISABLE KEYS */;
INSERT INTO `frequencies` VALUES (1,'Regular',7,'2023-02-28 11:56:43','2023-02-28 11:56:43'),(2,'Monthly',30,'2023-02-28 11:56:58','2023-02-28 11:56:58'),(3,'Quarterly',90,'2023-02-28 11:57:34','2023-02-28 11:57:34'),(4,'Semi-annual',182,'2023-02-28 11:58:03','2023-02-28 11:58:03'),(5,'Annual',365,'2023-02-28 11:58:20','2023-02-28 11:58:20');
/*!40000 ALTER TABLE `frequencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT '0',
  `message` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `mail_sent` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_notification_user` (`user_id`),
  CONSTRAINT `FK_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=707 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (676,1,'You have been assigned an action point(Test title)',1,0,'2023-03-15 09:03:14','2023-04-05 06:34:34'),(677,1,'You have been assigned an action point(Test two)',0,0,'2023-03-15 09:11:54','2023-03-15 09:11:54'),(678,4,'You have been assigned an action point(Test two)',0,0,'2023-03-15 09:11:54','2023-03-15 09:11:54'),(679,1,'You have been assigned an action point(This is test title)',0,0,'2023-03-15 09:47:04','2023-03-15 09:47:04'),(680,3,'You have been assigned an action point(This is test title)',0,0,'2023-03-15 09:47:04','2023-03-15 09:47:04'),(681,34,'You have been assigned an action point( Ting - Sex Workers Outreach Program (Kibra))',0,0,'2023-04-03 11:47:05','2023-04-03 11:47:05'),(682,40,'You have been assigned an action point( Ting - Sex Workers Outreach Program (Kibra))',0,0,'2023-04-03 11:47:05','2023-04-03 11:47:05'),(683,11,'You have been assigned an action point( Test  - Huruma Lions Dispensary)',0,0,'2023-04-05 08:52:21','2023-04-05 08:52:21'),(684,3,'You have been assigned an action point( Test2 - Huruma Lions Dispensary)',0,0,'2023-04-05 08:53:03','2023-04-05 08:53:03'),(685,11,'You have been assigned an action point( Baggot - Huruma Lions Dispensary)',0,0,'2023-04-05 08:54:32','2023-04-05 08:54:32'),(686,22,'You have been assigned an action point( Baggot - Huruma Lions Dispensary)',0,0,'2023-04-05 08:54:32','2023-04-05 08:54:32'),(687,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:56:09','2023-04-05 08:56:09'),(688,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:56:09','2023-04-05 08:56:09'),(689,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:56:51','2023-04-05 08:56:51'),(690,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:56:51','2023-04-05 08:56:51'),(691,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:57:35','2023-04-05 08:57:35'),(692,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:57:35','2023-04-05 08:57:35'),(693,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:59:08','2023-04-05 08:59:08'),(694,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:59:08','2023-04-05 08:59:08'),(695,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:59:21','2023-04-05 08:59:21'),(696,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 08:59:21','2023-04-05 08:59:21'),(697,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:02:40','2023-04-05 09:02:40'),(698,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:02:40','2023-04-05 09:02:40'),(699,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:02:44','2023-04-05 09:02:44'),(700,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:02:44','2023-04-05 09:02:44'),(701,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:03:52','2023-04-05 09:03:52'),(702,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:03:52','2023-04-05 09:03:52'),(703,2,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:04:06','2023-04-05 09:04:06'),(704,4,'You have been assigned an action point( Bagg - Huruma Lions Dispensary)',0,0,'2023-04-05 09:04:06','2023-04-05 09:04:06'),(705,3,'You have been assigned an action point( Do That - Embakasi Health Centre)',0,0,'2023-04-06 12:23:15','2023-04-06 12:23:15'),(706,11,'You have been assigned an action point( Do That - Embakasi Health Centre)',0,0,'2023-04-06 12:23:15','2023-04-06 12:23:15');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` enum('individual','facility','sdp') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'facility',
  `frequency_id` int NOT NULL,
  `frm_option` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('check_opt','textfield_s','radio_opt','number_opt') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `order_by` int NOT NULL DEFAULT '0',
  `section_id` int NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_question_section` (`section_id`),
  KEY `fk_question_created_by` (`created_by`),
  KEY `fk_question_frequency` (`frequency_id`),
  CONSTRAINT `fk_question_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_question_frequency` FOREIGN KEY (`frequency_id`) REFERENCES `frequencies` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_question_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'Setc','facility',1,'{\"cZfjg\":\"One\",\"Jnajz\":\"Two\",\"GPZRB\":\"Three\"}','check_opt',0,1,'2023-02-21 17:16:08',1,'2023-02-21 14:16:08','2023-02-28 11:59:06'),(2,'Question two','facility',1,'','textfield_s',0,1,'2023-02-21 17:23:13',1,'2023-02-21 14:23:13','2023-02-28 11:59:06'),(3,'Question for section two','facility',1,'{\"MSZsi\":\"One\",\"vkbRQ\":\"twe\",\"tIVBM\":\"Tree\",\"dDMOV\":\"Fork\"}','check_opt',0,2,'2023-02-21 17:32:34',1,'2023-02-21 14:32:34','2023-02-28 11:59:06'),(4,'What is your gender','facility',1,'{\"Jhvde\":\"Male\",\"Jhryd\":\"Female\",\"BelDL\":\"LGBT\"}','radio_opt',0,1,'2023-02-23 14:53:52',1,'2023-02-23 11:53:52','2023-02-28 11:59:06'),(5,'What is the mostly user regimen type?','facility',1,'{\"Ivlyu\":\"LPV\\/r\",\"geksS\":\"DTG\",\"FDYkL\":\"NVP\",\"VhFQt\":\"ATV\\/r\"}','radio_opt',0,3,'2023-02-27 13:18:05',1,'2023-02-27 10:18:05','2023-02-28 11:59:06'),(6,'Why do you ask many questions?','facility',1,'','textfield_s',0,3,'2023-02-28 16:14:54',1,'2023-02-28 13:14:54','2023-02-28 13:14:54'),(7,'What sector is this?','facility',3,'{\"iyuTE\":\"One\",\"XHLEh\":\"Two\",\"uCevs\":\"Biggot\"}','radio_opt',0,5,'2023-03-06 17:17:22',1,'2023-03-06 14:17:22','2023-03-06 14:17:22'),(8,'How many people are there in this meeting?','sdp',1,'[]','number_opt',0,6,'2023-03-07 13:28:36',1,'2023-03-07 10:28:36','2023-03-28 11:57:14'),(9,'What is the tx curr for the facility','facility',2,'','number_opt',0,6,'2023-03-08 09:31:26',1,'2023-03-08 06:31:26','2023-03-08 06:31:26'),(10,'Select the most common regimen','facility',2,'{\"KnSYQ\":\"DTG\",\"JCupA\":\"EFV\",\"xPLvu\":\"LPV\\/r\",\"HbCnM\":\"NVPi\",\"ozbdP\":\"Bugers\"}','radio_opt',0,6,'2023-03-08 09:41:24',1,'2023-03-08 06:41:24','2023-03-28 12:14:21'),(11,'Describe the order of flow','facility',1,'','textfield_s',0,6,'2023-03-08 09:44:12',1,'2023-03-08 06:44:12','2023-03-08 06:44:12'),(12,'Leave this blank','facility',1,'','textfield_s',0,6,'2023-03-08 09:51:38',1,'2023-03-08 06:51:38','2023-03-08 06:51:38'),(13,'blank checks','facility',1,'{\"xCbOL\":\"Up\",\"lougf\":\"Down\",\"pyzLt\":\"Bootilicius\",\"UiJQv\":\"Big ban\"}','check_opt',0,6,'2023-03-08 09:52:22',1,'2023-03-08 06:52:22','2023-03-28 12:25:47'),(32,'Is there a standard training offered to site staff on PrEP provision?','facility',4,'{\"FxSik\":\"yes\",\"iVdFq\":\"no\"}','radio_opt',0,6,'2023-03-10 18:33:42',1,'2023-03-10 15:33:42','2023-03-10 15:33:42'),(33,'Who are you','individual',1,'','textfield_s',0,6,'2023-03-10 18:33:42',1,'2023-03-10 15:33:42','2023-03-10 15:33:42'),(34,'Select used regimens','facility',2,'{\"uJfzC\":\"EFV\",\"WpBFS\":\"LPV\",\"dPHEo\":\"NVP\",\"qXMCj\":\"DTG\"}','check_opt',0,6,'2023-03-10 18:33:42',1,'2023-03-10 15:33:42','2023-03-10 15:33:42'),(35,'How many are you','facility',1,'','number_opt',0,6,'2023-03-10 18:33:42',1,'2023-03-10 15:33:42','2023-03-10 15:33:42');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `visit_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_answer_visit` (`visit_id`),
  KEY `fk_answer_question` (`question_id`),
  KEY `fk_answer_created_by` (`created_by`),
  CONSTRAINT `fk_answer_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_answer_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responses`
--

LOCK TABLES `responses` WRITE;
/*!40000 ALTER TABLE `responses` DISABLE KEYS */;
INSERT INTO `responses` VALUES (17,1,1,'cZfjg,Jnajz,GPZRB',1,'2023-02-23 11:29:35','2023-02-23 11:52:32'),(18,1,2,'Teller of doom',1,'2023-02-23 11:29:35','2023-02-23 11:52:32'),(19,1,4,'Jhvde',1,'2023-02-23 11:54:29','2023-02-23 11:54:29'),(33,1,8,'76',1,'2023-03-08 07:19:42','2023-03-08 07:19:42'),(34,1,11,'Bagger',1,'2023-03-08 07:19:42','2023-03-08 07:19:42'),(35,3,2,'What about now what about today',1,'2023-03-09 06:25:55','2023-03-09 06:25:55'),(36,3,4,'Jhvde',1,'2023-03-09 06:25:55','2023-03-09 06:25:55'),(37,5,1,'GPZRB',1,'2023-03-14 12:16:25','2023-03-14 12:16:25'),(38,5,2,'Doom tell',1,'2023-03-14 12:16:25','2023-03-14 12:16:25'),(39,5,4,'Jhryd',1,'2023-03-14 12:16:25','2023-03-14 12:16:25'),(40,3,3,'vkbRQ',1,'2023-03-15 09:45:54','2023-03-15 09:45:54');
/*!40000 ALTER TABLE `responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(199) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `abbr` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Abbreviation...',
  `checklist_id` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_section_created_by` (`created_by`),
  KEY `fk_section_checklist` (`checklist_id`),
  CONSTRAINT `fk_section_checklist` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_section_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'Section Ones','SO',1,1,'2023-02-20 13:45:54','2023-02-21 07:36:09'),(2,'Section Two','ST',1,1,'2023-02-21 14:31:50','2023-02-21 14:31:50'),(3,'Constitution A','CA',2,1,'2023-02-27 10:16:44','2023-02-27 10:16:44'),(4,'Section Tally Pao','STP',2,1,'2023-02-27 10:19:58','2023-02-27 10:19:58'),(5,'Sector Curri Tie','SCT',3,1,'2023-03-06 14:15:24','2023-03-06 14:15:35'),(6,'Tug of War','TW',4,1,'2023-03-07 10:27:55','2023-03-07 10:27:55');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `team_lead` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_team_name` (`name`),
  KEY `fk_team_lead` (`team_lead`),
  CONSTRAINT `fk_team_lead` FOREIGN KEY (`team_lead`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Team One',48,'2023-03-27 10:11:25','2023-03-28 13:04:08'),(2,'Team Two',23,'2023-04-05 06:32:47','2023-04-05 06:32:47'),(3,'Team 3',58,'2023-04-05 06:33:04','2023-04-05 06:33:04'),(4,'COE',52,'2023-04-05 06:33:24','2023-04-05 06:33:24');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_categories`
--

DROP TABLE IF EXISTS `user_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_level` enum('Program','Facility') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `permissions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_category_creator` (`created_by`),
  CONSTRAINT `fk_category_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_categories`
--

LOCK TABLES `user_categories` WRITE;
/*!40000 ALTER TABLE `user_categories` DISABLE KEYS */;
INSERT INTO `user_categories` VALUES (1,'Program','System Administrator','Test','1,2,3,4,5',1,'2023-03-31 07:56:52','2023-04-01 04:25:09'),(2,'Program','Program Administrator','This is the program admin','2,3,4,5',1,'2023-03-31 10:19:10','2023-03-31 10:19:10'),(3,'Facility','Facility Admin','Admin for facility','2,4,6',1,'2023-04-01 04:02:12','2023-04-05 06:34:59');
/*!40000 ALTER TABLE `user_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_permissions`
--

LOCK TABLES `user_permissions` WRITE;
/*!40000 ALTER TABLE `user_permissions` DISABLE KEYS */;
INSERT INTO `user_permissions` VALUES (1,'System administration','Manage system. includes teams and facility creation'),(2,'Users Management','Manage users, create update delete'),(3,'Checklist management','Create checklists, sections and questions'),(4,'Access Reports','Access to reports module'),(5,'Create Visits & Action points','Able to create visits and action points'),(6,'All Action Points','Can view and comment on all action points');
/*!40000 ALTER TABLE `user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `facility_id` int DEFAULT NULL,
  `password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_email` (`email`),
  KEY `fk_user_facility_id` (`facility_id`),
  KEY `fk_user_creator` (`created_by`),
  KEY `fk_user_category` (`category_id`),
  CONSTRAINT `fk_user_category` FOREIGN KEY (`category_id`) REFERENCES `user_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'admin@admin.com','0735377609','Admin','Admin','K',1,NULL,'0192023a7bbd73250516f069df18b500','2023-04-06 01:06:21',1,'2022-05-17 13:21:00','2023-04-06 13:06:21'),(2,NULL,'kimjose@gmail.com','0789123123','hts','s','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-05-25 06:11:48',1,'2022-05-23 12:35:21','2022-08-23 05:08:29'),(3,NULL,'jnmwende693@gmail.com','0789123154','Jackline','Mwende','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-05-25 06:23:27',1,'2022-05-24 07:18:45','2022-05-25 06:23:27'),(4,NULL,'kimjose693@gmail.com','0717894567','Jamp','Jumper','',1,28,'0192023a7bbd73250516f069df18b500','2022-05-26 05:47:42',1,'2022-05-24 11:48:27','2022-06-06 03:45:40'),(11,NULL,'prarriw@cihebkenya.org','0701125676','Patricia','Rarriw','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 00:05:25',1,'2022-05-31 06:13:28','2022-09-29 12:05:25'),(12,NULL,'jorawo@cihebkenya.org','0789123123','Joshua','Orawo','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 09:02:23',1,'2022-06-06 05:54:36','2022-09-29 09:02:23'),(13,NULL,'POmwoma@cihebkenya.org','0721656327','Priscilla','Omwoma','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-22 08:39:14',1,'2022-06-06 06:06:24','2022-09-22 08:39:14'),(14,NULL,'emutiso@cihebkenya.org','0736216207','Esther','Mutiso','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-26 09:50:35',1,'2022-06-06 13:21:08','2022-09-26 09:50:35'),(15,NULL,'Omunyao@cihebkenya.org','0735111154','Oscar','Munyao','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-28 05:55:01',1,'2022-06-09 08:32:44','2022-09-28 05:55:01'),(16,NULL,'dpaul@cihebkenya.org','0725518149','Desmond','Mwania','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 09:04:14',1,'2022-06-09 08:58:27','2022-09-29 09:04:14'),(17,NULL,'eketer@cihebkenya.org','0777166267','EMILY','KETER','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:04','2022-06-11 13:13:04'),(18,NULL,'gwangari@cihebkenya.org','0788753904','GERALD','WANGARI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-27 06:47:41',1,'2022-06-11 13:13:04','2022-09-27 06:47:41'),(20,NULL,'amunyalo@cihebkenya.org','0778846071','AGNES','MUNYALO','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-28 06:45:50',1,'2022-06-11 13:13:05','2022-09-28 06:45:50'),(21,NULL,'jmashala@cihebkenya.org','0713301129','JUMA','MASHALA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-21 23:50:13',1,'2022-06-11 13:13:05','2022-09-22 11:50:13'),(22,NULL,'kowino@cihebkenya.org','0770424076','KENNEDY','OWINO','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:05','2022-06-11 13:13:05'),(23,NULL,'wkariuki@cihebkenya.org','0733777465','Wilson','Kariuki','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-28 22:32:28',1,'2022-06-11 13:13:05','2022-09-29 10:32:28'),(24,NULL,'rmotogwa@cihebkenya.org','0733012688','RICHARD','MOTOGWA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:05','2022-06-11 13:13:05'),(25,NULL,'aomanya@cihebkenya.org','0738124375','ANGELA','OMANYA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-26 06:32:15',1,'2022-06-11 13:13:05','2022-09-26 06:32:15'),(26,NULL,'claibon@cihebkenya.org','0704708626','CAROLINE','LAIBON','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-08 09:06:23',1,'2022-06-11 13:13:05','2022-09-08 09:06:23'),(27,NULL,'dmuriungi@cihebkenya.org','0769362836','DORIS','MURIUNGI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:05','2022-06-11 13:13:05'),(28,NULL,'emomanyi@cihebkenya.org','0701698084','EMMAH','MOMANYI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-08-25 08:40:28',1,'2022-06-11 13:13:05','2022-08-25 08:40:28'),(29,NULL,'ekadenge@cihebkenya.org','0742617927','EUNICE','KADENGE','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(30,NULL,'gnganga@cihebkenya.org','0764414398','GEOFREY','NGANGA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(31,NULL,'jngui@cihebkenya.org','0710820603','JAVIES','NGUI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-28 07:12:23',1,'2022-06-11 13:13:06','2022-09-28 07:12:23'),(32,NULL,'jmaingi@cihebkenya.org','0721068368','JAMES','MAINGI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-06-30 01:35:59',1,'2022-06-11 13:13:06','2022-06-30 13:35:59'),(33,NULL,'jnganga@cihebkenya.org','0762338976','JEWEL','NGANGA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(34,NULL,'jmosago@cihebkenya.org','0790606837','JOEL','MOSAGO','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(35,NULL,'jonyango@cihebkenya.org','0771456926','JOSHUA','ONYANGO','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(36,NULL,'mnjoroge@cihebkenya.org','0762996236','MATTHEW','NJOROGE','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(38,NULL,'rwangusi@cihebkenya.org','0718788820','REBECCA','WANGUSI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:06','2022-06-11 13:13:06'),(39,NULL,'yamondi@cihebkenya.org','0764061752','YUVINE','AMONDI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:07','2022-06-11 13:13:07'),(40,NULL,'botieno@cihebkenya.org','0776163174','BERNARD','OTIENO','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-09 06:52:36',1,'2022-06-11 13:13:07','2022-09-09 06:52:36'),(41,NULL,'mchege@cihebkenya.org','0765771370','MOURINE','CHEGE','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:07','2022-06-11 13:13:07'),(42,NULL,'cmunguti@cihebkenya.org','0708644379','CATHERINE','MUNGUTI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-06-28 09:13:29',1,'2022-06-11 13:13:07','2022-06-28 09:13:29'),(43,NULL,'jkarumba@cihebkenya.org','0749344531','JOSEPH','KARUMBA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:07','2022-06-11 13:13:07'),(44,NULL,'jmakau@cihebkenya.org','0760758327','JOSEPH','MAKAU','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:07','2022-06-11 13:13:07'),(45,NULL,'lkuria@cihebkenya.org','0722025612','LYDIA','KURIA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-28 09:39:19',1,'2022-06-11 13:13:07','2022-09-28 09:39:19'),(46,NULL,'nkipkorir@cihebkenya.org','0724042484','NICHOLAS','KIPKORIR','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:07','2022-06-11 13:13:07'),(47,NULL,'pmutele@cihebkenya.org','0780583512','PETER','MUTELE','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:07','2022-06-11 13:13:07'),(48,NULL,'ekaro@cihebkenya.org','0796959068','ESTHER','KARO','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 08:31:29',1,'2022-06-11 13:13:08','2022-09-29 08:31:29'),(49,NULL,'akiarie@cihebkenya.org','0758947670','ABSOLOM','KIARIE','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-21 06:35:50',1,'2022-06-11 13:13:08','2022-09-21 06:35:50'),(50,NULL,'dmutai@cihebkenya.org','0753005161','DEDAN','MUTAI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:08','2022-06-11 13:13:08'),(51,NULL,'gnduta@cihebkenya.org','0727455313','GLADYS','NDUTA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:08','2022-06-11 13:13:08'),(52,NULL,'jmwaniki@cihebkenya.org','0798134274','JOSEPH','MWANIKI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-08-30 00:15:44',1,'2022-06-11 13:13:08','2022-08-30 12:15:44'),(54,NULL,'bmuturi@cihebkenya.org','0799302672','BERNARD','MUTURI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:08','2022-06-11 13:13:08'),(55,NULL,'lwanjeri@cihebkenya.org','0718182311','LUDOVIC','WANJERI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 08:10:43',1,'2022-06-11 13:13:08','2022-09-29 08:10:43'),(56,NULL,'dpaul@cihebkenya.org','0763608212','DESMOND','PAUL','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-08-09 05:35:42',1,'2022-06-11 13:13:08','2022-08-09 17:35:42'),(57,NULL,'dodhiambo@cihebkenya.org','0721231557','DUNCAN','ODHIAMBO','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-06-20 06:39:48',1,'2022-06-11 13:13:09','2022-06-20 06:39:48'),(58,NULL,'jmuema@cihebkenya.org','0706790550','JANET','MUEMA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-21 07:19:10',1,'2022-06-11 13:13:09','2022-09-21 07:19:10'),(59,NULL,'orotich@cihebkenya.org','0751240537','OBADIAH','ROTICH','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:09','2022-06-11 13:13:09'),(60,NULL,'rrotich@cihebkenya.org','0700888344','ROBERT','ROTICH','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:09','2022-06-11 13:13:09'),(61,NULL,'sobala@cihebkenya.org','0755135638','STEPHEN','OBALA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:09','2022-06-11 13:13:09'),(62,NULL,'sthumbi@cihebkenya.org','0743763288','SUSAN','THUMBI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:09','2022-06-11 13:13:09'),(63,NULL,'dnganga@cihebkenya.org','0737409060','DAMARIS','NGANGA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-05 04:36:28',1,'2022-06-11 13:13:09','2022-09-05 04:36:28'),(64,NULL,'dtobon@cihebkenya.org','0793738767','DAMARIS','TOBON','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:09','2022-06-11 13:13:09'),(65,NULL,'gochieng@cihebkenya.org','0784854136','GADAFI','OCHIENG','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:09','2022-06-11 13:13:09'),(66,NULL,'emwangi@cihebkenya.org','0792145794','EMMAH','MWANGI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:10','2022-06-11 13:13:10'),(67,NULL,'fwachira@cihebkenya.org','0727723873','FRANCIS','WACHIRA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:10','2022-06-11 13:13:10'),(68,NULL,'pawuor@cihebkenya.org','0779040971','PATRICK','AWUOR','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-09 09:42:51',1,'2022-06-11 13:13:10','2022-09-09 09:42:51'),(69,NULL,'snzyoka@cihebkenya.org','0797273938','SARAH','NZYOKA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-15 07:23:10',1,'2022-06-11 13:13:10','2022-09-15 07:23:10'),(70,NULL,'ronsomu@cihebkenya.org','0729234631','ROSE','ONSOMU','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:10','2022-06-11 13:13:10'),(71,NULL,'cmichura@cihebkenya.org','0722444231','CHRISTINE','MICHURA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-08-26 07:24:32',1,'2022-06-11 13:13:10','2022-08-26 07:24:32'),(72,NULL,'smuhamed@cihebkenya.org','0767324587','SULEIMAN','MUHAMED','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:10','2022-06-11 13:13:10'),(73,NULL,'aolela@cihebkenya.org','0729363476','ANNETE','OLELA','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-08-04 04:37:41',1,'2022-06-11 13:13:10','2022-08-04 04:37:41'),(74,NULL,'wmungai@cihebkenya.org','0749244260','WALLACE','MUNGAI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:10','2022-06-11 13:13:10'),(75,NULL,'jkado@cihebkenya.org','0778283050','JAVAN','KADO','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 07:53:42',1,'2022-06-11 13:13:11','2022-09-29 07:53:42'),(76,NULL,'rkasivu@cihebkenya.org','0708825179','ROBERT','KASIVU','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:11','2022-06-11 13:13:11'),(77,NULL,'injagi@cihebkenya.org','0722587540','ISAAC','NJAGI','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:11','2022-06-11 13:13:11'),(78,NULL,'wouko@cihebkenya.org','0733097862','WILLIAM','OUKO','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:11','2022-06-11 13:13:11'),(79,NULL,'domiti@cihebkenya.org','0749112852','DORIS','OMITI','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-08 22:59:46',1,'2022-06-11 13:13:11','2022-09-09 10:59:46'),(80,NULL,'vachieng@cihebkenya.org','0112528491','VERITY','ACHIENG','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 06:59:50',1,'2022-06-11 13:13:11','2022-09-29 06:59:50'),(81,NULL,'awughanga@cihebkenya.org','0796316019','ANNE','WUGHANGA','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:11','2022-06-11 13:13:11'),(82,NULL,'sndaba@mgic.umaryland.edu','0772875985','Sospeter','Ndaba','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:11','2022-06-11 13:13:11'),(83,NULL,'vmakhoha@mgic.umaryland.edu','0763886425','Violet','Makhoha','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:11','2022-06-11 13:13:11'),(84,NULL,'cochola@mgic.umaryland.edu','0791542335','Cornelia','Ochola','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:12','2022-06-11 13:13:12'),(85,NULL,'fkimonye@mgic.umaryland.edu','0704959101','Francis','Kimonye','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:12','2022-06-11 13:13:12'),(86,NULL,'tmasai@mgic.umaryland.edu','0710279142','Tina','Masai','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-25 22:10:11',1,'2022-06-11 13:13:12','2022-09-26 10:10:11'),(87,NULL,'skoech@mgic.umaryland.edu','0710529985','Sylvia','Koech','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-06 22:20:58',1,'2022-06-11 13:13:12','2022-09-07 10:20:58'),(88,NULL,'cmuthamia@mgic.umaryland.edu','0726821277','Carolyne','Muthamia','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-06-15 07:02:58',1,'2022-06-11 13:13:12','2022-06-15 07:02:58'),(89,NULL,'jkirigha@mgic.umaryland.edu','0793510214','Jardine','Kirigha','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-26 02:07:50',1,'2022-06-11 13:13:12','2022-09-26 14:07:50'),(90,NULL,'jkimani@mgic.umaryland.edu','0786982307','Joseph','Kimani','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-09 06:51:49',1,'2022-06-11 13:13:12','2022-09-09 06:51:49'),(91,NULL,'ootieno.@mgic.umaryland.edu','0725714204','Oscar','Otieno','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-20 01:45:17',1,'2022-06-11 13:13:12','2022-09-20 13:45:17'),(92,NULL,'cngeno@mgic.umaryland.edu','0728773890','Caroline','Ngeno','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:12','2022-06-11 13:13:12'),(93,NULL,'egichora@mgic.umaryland.edu','0713048656','Elijah','Gichora','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-27 07:23:40',1,'2022-06-11 13:13:12','2022-09-27 19:23:40'),(94,NULL,'bawiti@mgic.umaryland.edu','0743853641','Brian','Awiti','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-19 22:39:31',1,'2022-06-11 13:13:13','2022-09-20 10:39:31'),(95,NULL,'rmwaura@mgic.umaryland.edu','0712051758','Ruth','Mwaura','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-08-03 08:31:56',1,'2022-06-11 13:13:13','2022-08-03 08:31:56'),(96,NULL,'cngomo@mgic.umaryland.edu','0782245133','Caroline','Ngomo','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:13','2022-06-11 13:13:13'),(97,NULL,'lomondi@mgic.umaryland.edu','0765085231','Linda','Omondi','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:13','2022-06-11 13:13:13'),(98,NULL,'dkaruga@mgic.umaryland.edu','0741062777','Daniel','Karuga','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-11 13:13:13','2022-06-11 13:13:13'),(99,NULL,'hngetich@cihebkenya.org','0708050965','Herbert','Ngetich','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-07-22 08:09:13',1,'2022-06-14 06:02:56','2022-07-22 08:09:13'),(100,NULL,'rmulwa@cihebkenya.org','0799086957','Robert','Mulwa','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-27 23:10:42',1,'2022-06-15 06:31:23','2022-09-28 11:10:42'),(101,NULL,'jmwangi@cihebkenya.org','0722998587','Joseph','Mwangi','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 08:12:26',1,'2022-06-15 06:33:49','2022-09-29 08:12:26'),(102,NULL,'rnyaboke@cihebkenya.org','0735100284','Rose','Nyaboke','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-27 05:31:33',1,'2022-06-15 06:37:01','2022-09-27 05:31:33'),(103,NULL,'bodhiambo@cihebkenya.org','0721952214','Benard','Odhiambo','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-26 06:16:44',1,'2022-06-20 12:03:59','2022-09-26 06:16:44'),(104,NULL,'oduncan@cihebkenya.org','0721231557','Duncan','Owino','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-11 22:48:22',1,'2022-06-27 05:53:29','2022-09-12 10:48:22'),(105,NULL,'smuli@gmail.com','0701641048','Susan','Muli','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-28 09:37:51','2022-06-28 09:37:51'),(106,NULL,'hchimaleni@gmail.com','0794132521','Hilda','Chimaleni','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-28 09:37:51','2022-06-28 09:37:51'),(107,NULL,'dnyambok@gmail.com','0710619496','Dotty','Nyambok','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-28 09:37:51','2022-06-28 09:37:51'),(108,NULL,'jiguri@gmail.com','0750805516','Jane','Iguri','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-28 09:37:51','2022-06-28 09:37:51'),(109,NULL,'lchialo@gmail.com','0779653781','Leonard','Chialo','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-28 09:37:51','2022-06-28 09:37:51'),(110,NULL,'eoino@gmail.com','0769468159','Emma','Oino','',1,NULL,'0192023a7bbd73250516f069df18b500',NULL,1,'2022-06-28 09:37:51','2022-06-28 09:37:51'),(111,NULL,'wwairimu@cihebkenya.org','0710000000','Winfred','Kariuki','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-29 08:42:36',1,'2022-07-04 06:58:18','2022-09-29 08:42:36'),(112,NULL,'dgathecha@mgic.umaryland.edu','0704254679','Dennis','Gathecha','',1,NULL,'0192023a7bbd73250516f069df18b500','2022-09-28 07:05:14',1,'2022-07-25 09:40:15','2022-09-28 07:05:14'),(126,NULL,'test@test.com','0790392945','Test','Twin','One',1,53,'e10adc3949ba59abbe56e057f20f883e',NULL,1,'2023-02-28 08:00:00','2023-02-28 08:03:19'),(128,2,'jnkimani693@gmail.com','0717890231','Joseph','Kimani','Ngima',1,NULL,'e10adc3949ba59abbe56e057f20f883e',NULL,1,'2023-04-01 04:49:47','2023-04-01 04:49:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visit_sections`
--

DROP TABLE IF EXISTS `visit_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visit_sections` (
  `visit_id` int NOT NULL,
  `section_id` int NOT NULL,
  `user_id` int NOT NULL,
  `submitted` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`visit_id`,`section_id`),
  KEY `fk_visit_section_user` (`user_id`),
  KEY `fk_visit_section_section` (`section_id`),
  CONSTRAINT `fk_visit_section_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_section_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_visit_section_visit` FOREIGN KEY (`visit_id`) REFERENCES `facility_visits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visit_sections`
--

LOCK TABLES `visit_sections` WRITE;
/*!40000 ALTER TABLE `visit_sections` DISABLE KEYS */;
INSERT INTO `visit_sections` VALUES (1,1,1,1,'2023-02-22 11:37:33','2023-02-24 08:55:26'),(1,2,1,0,'2023-02-22 11:23:34','2023-02-22 11:23:36'),(1,6,1,1,'2023-03-08 07:19:16','2023-03-08 07:19:42'),(3,1,1,1,'2023-02-22 11:39:36','2023-03-09 06:25:55'),(3,2,1,1,'2023-03-15 09:02:46','2023-03-15 09:47:19'),(5,1,1,1,'2023-03-14 12:16:00','2023-03-14 12:19:22'),(6,1,1,0,'2023-03-28 12:35:37','2023-03-28 12:35:37');
/*!40000 ALTER TABLE `visit_sections` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-05 17:01:19
