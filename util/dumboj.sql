-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: dumboj
-- ------------------------------------------------------
-- Server version	5.1.63-0ubuntu0.10.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contest_problems`
--

DROP TABLE IF EXISTS `contest_problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contest_problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `flag` char(1) NOT NULL,
  `submissions` int(11) NOT NULL DEFAULT '0',
  `solutions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_constraint_cp_pid` (`problem_id`),
  KEY `fk_constraint_cp_cid` (`contest_id`),
  CONSTRAINT `fk_constraint_cp_cid` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_constraint_cp_pid` FOREIGN KEY (`problem_id`) REFERENCES `problems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contestants`
--

DROP TABLE IF EXISTS `contestants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contestants` (
  `contest_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `solutions` int(11) NOT NULL DEFAULT '0',
  `penalty` int(11) NOT NULL DEFAULT '0',
  `json` text NOT NULL,
  PRIMARY KEY (`contest_id`,`user_id`),
  KEY `fk_constraint_ct_uid` (`user_id`),
  CONSTRAINT `fk_constraint_ct_cid` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_constraint_ct_uid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contests`
--

DROP TABLE IF EXISTS `contests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text,
  `password` varchar(32) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `announcement` text,
  PRIMARY KEY (`id`),
  KEY `fk_constraint_c_uid` (`user_id`),
  CONSTRAINT `fk_constraint_c_uid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `problem_contents`
--

DROP TABLE IF EXISTS `problem_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_contents` (
  `problem_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text,
  `input` text,
  `output` text,
  `sample_input` text,
  `sample_output` text,
  `hint` text,
  `remark` varchar(512) NOT NULL DEFAULT '',
  `votes` int(11) NOT NULL DEFAULT '0',
  `creation_time` datetime DEFAULT NULL,
  PRIMARY KEY (`problem_id`,`user_id`),
  KEY `fk_constraint_pc_uid` (`user_id`),
  CONSTRAINT `fk_constraint_pc_pid` FOREIGN KEY (`problem_id`) REFERENCES `problems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_constraint_pc_uid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `problems`
--

DROP TABLE IF EXISTS `problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL DEFAULT '',
  `source` varchar(512) NOT NULL DEFAULT '',
  `time_limit` int(11) DEFAULT NULL,
  `memory_limit` int(11) DEFAULT NULL,
  `original_site` varchar(64) NOT NULL DEFAULT '',
  `original_id` varchar(64) NOT NULL DEFAULT '',
  `original_url` varchar(512) NOT NULL DEFAULT '',
  `creation_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_problem` (`original_site`,`original_id`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_id` varchar(64) DEFAULT NULL,
  `problem_id` int(11) NOT NULL,
  `original_site` varchar(64) NOT NULL,
  `original_problem_id` varchar(64) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `language_value` varchar(128) NOT NULL,
  `language` varchar(128) NOT NULL,
  `language_key` int(11) NOT NULL,
  `time` int(11) DEFAULT NULL,
  `memory` int(11) DEFAULT NULL,
  `result` varchar(128) NOT NULL,
  `result_key` int(11) NOT NULL,
  `source_code` text NOT NULL,
  `is_shared` tinyint(4) NOT NULL DEFAULT '1',
  `additional_info` text,
  `submission_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_constraint_s_pid` (`problem_id`),
  KEY `fk_constraint_s_uid` (`user_id`),
  CONSTRAINT `fk_constraint_s_pid` FOREIGN KEY (`problem_id`) REFERENCES `problems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_constraint_s_uid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=327 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `real_name` varchar(128) DEFAULT NULL,
  `school` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `share_email` tinyint(4) NOT NULL DEFAULT '1',
  `share_code` tinyint(4) NOT NULL DEFAULT '1',
  `privilege` int(11) NOT NULL DEFAULT '0',
  `submissions` int(11) NOT NULL DEFAULT '0',
  `solutions` int(11) NOT NULL DEFAULT '0',
  `registration_time` datetime DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
INSERT INTO `users` VALUES (1,'admin',MD5('123456'),'admin',NULL,NULL,0,0,7,0,0,NULL,1);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-04 14:30:02
