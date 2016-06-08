-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: localhost    Database: fwu_p2
-- ------------------------------------------------------
-- Server version	5.7.12-0ubuntu1

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
-- Table structure for table `boards`
--

DROP TABLE IF EXISTS `boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boards` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '板のID',
  `name` varchar(128) NOT NULL COMMENT '板の名前',
  `created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '板の作成日',
  `modified` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '板の更新日',
  `parent_name` varchar(32) NOT NULL DEFAULT 'plaza' COMMENT '板の親の名前（plaza, guilds等）',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '板の親のID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='板のリスト';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `guilds`
--

DROP TABLE IF EXISTS `guilds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guilds` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ギルドのID',
  `name` varchar(128) NOT NULL COMMENT 'ギルドの名前',
  `created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ギルドの作成日',
  `modified` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ギルドの更新日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='ギルドのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ポストのID',
  `name` varchar(128) NOT NULL COMMENT 'ポストの投稿者名',
  `content` text NOT NULL COMMENT 'ポストの投稿内容',
  `created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ポストの作成日',
  `modified` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ポストの更新日',
  `thread_id` int(11) NOT NULL COMMENT '所属するスレッドのID',
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='スレッドへのポスト';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `threads`
--

DROP TABLE IF EXISTS `threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'スレッドのID',
  `name` varchar(128) NOT NULL COMMENT 'スレッドの名前',
  `created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'スレッドの作成日',
  `modified` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'スレッドの更新日',
  `board_id` int(11) NOT NULL COMMENT '所属する板のID',
  PRIMARY KEY (`id`),
  KEY `board_id` (`board_id`),
  CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='板のスレッド';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ユーザーのID',
  `name` varchar(64) NOT NULL COMMENT 'ユーザーの名前',
  `email` varchar(256) NOT NULL COMMENT 'ユーザーのメールアドレス',
  `password` varchar(256) NOT NULL COMMENT 'ユーザーのログイン・パスワード',
  `created` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ユーザーの作成日',
  `modified` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ユーザーの更新日',
  `guild_id` int(11) NOT NULL COMMENT '所属ギルドの外部キー',
  PRIMARY KEY (`id`),
  KEY `guild_id` (`guild_id`),
  CONSTRAINT `guild_ibfk_1` FOREIGN KEY (`guild_id`) REFERENCES `guilds` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='ユーザーのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-07 15:29:48
