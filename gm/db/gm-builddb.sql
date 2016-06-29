/**
 * プロジェクトのデータベース、テーブル構造を定義した SQL ファイル。
 *
 * サービスのデータベースは以下のテーブル名、構造で定義される。
 * 開発で使用するフレームワークは CakePHP3 だが、テーブル構造の定義は別途必要になる為この SQL ファイルが作成された。
 *
 * シェルを使える環境であれば、このファイルは以下のように使用する。
 *
 *     $ mysql -u your_name -p your_database < gm-builddb.sql
 *
 * 以上のコマンドで your_database にテーブルが構築される。
 * この時、同名のテーブルは構築前に破棄される。
 */

-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: localhost    Database: 
-- ------------------------------------------------------
-- Server version

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

/**
 * sites
 *
 * sites テーブルには Web サイトの名前や説明が保存される。
 */

DROP TABLE IF EXISTS sites;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE sites (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'サイトのID',
  name varchar(512) NOT NULL COMMENT 'サイトの名前',
  description varchar(1024) NOT NULL COMMENT 'サイトの説明',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='サイトの設定リスト';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * boards
 *
 * boards テーブルには「板/スレッド/ポスト」の内、板の情報が保存される。
 * 「板/スレッド/ポスト」の概念については 2ch を参照。
 *
 * 依存関係:
 *
 *     <- 依存方向
 *
 *     (guilds or etc...) <- boards <- threads <- posts
 *
 * parent_name には板の親を表す小文字のオブジェクト名("guilds" 等)が保存される。
 * parent_name が "guilds" であれば、parent_id は guilds.id を指す。
 * parent_name が "null" なら親は存在せず、parent_id の値も無意味になる。
 */

DROP TABLE IF EXISTS boards;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE boards (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '板のID',
  name varchar(128) NOT NULL COMMENT '板の名前',
  description text COMMENT '板の説明',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT '板の作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT '板の更新日',
  parent_name varchar(32) NOT NULL DEFAULT 'null' COMMENT '板の親の名前（guilds等）',
  parent_id int(11) NOT NULL DEFAULT 0 COMMENT '板の親のID',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='板のリスト';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * threads
 *
 * threads テーブルには「板/スレッド/ポスト」の内、スレッドの情報が保存される。
 * 依存関係は boards を参照。
 */

DROP TABLE IF EXISTS threads;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE threads (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'スレッドのID',
  name varchar(128) NOT NULL COMMENT 'スレッドの名前',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'スレッドの作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'スレッドの更新日',
  board_id int(11) NOT NULL COMMENT '所属する板のID',
  PRIMARY KEY (id),
  KEY board_id (board_id),
  CONSTRAINT threads_ibfk_1 FOREIGN KEY (board_id) REFERENCES boards (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='板のスレッド';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * posts
 *
 * posts テーブルには「板/スレッド/ポスト」の内、ポストの情報が保存される。
 * 依存関係は boards を参照。
 */

DROP TABLE IF EXISTS posts;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE posts (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'ポストのID',
  name varchar(128) NOT NULL COMMENT 'ポストの投稿者名',
  content text NOT NULL COMMENT 'ポストの投稿内容',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ポストの作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ポストの更新日',
  thread_id int(11) NOT NULL COMMENT '所属するスレッドのID',
  PRIMARY KEY (id),
  KEY thread_id (thread_id),
  CONSTRAINT posts_ibfk_1 FOREIGN KEY (thread_id) REFERENCES threads (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='スレッドへのポスト';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * guilds
 *
 * guilds テーブルにはギルドの情報が保存される。
 *
 * 依存関係:
 *
 *     <- 依存方向
 *
 *     guilds <- users
 *            <- boards
 *
 * users はユーザーの新規作成時に users.guild_id の参照先が必要になるため、サービス起動時に最低 1 つ以上のギルド(ID は 1)が必要。
 * boards は boards.parent_name が "guilds" である時、boards.parent_id が guilds.id を参照するので、ギルドの削除時などに boards の走査/削除も必要になる。
 */

DROP TABLE IF EXISTS guilds;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE guilds (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'ギルドのID',
  name varchar(128) NOT NULL COMMENT 'ギルドの名前',
  description text COMMENT '板の説明',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ギルドの作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ギルドの更新日',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='ギルドのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * users
 *
 * users テーブルには登録ユーザーの情報が保存される。
 * 依存関係は guilds を参照。
 */

DROP TABLE IF EXISTS users;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'ユーザーのID',
  name varchar(64) NOT NULL COMMENT 'ユーザーの名前',
  email varchar(256) NOT NULL COMMENT 'ユーザーのメールアドレス',
  password varchar(256) NOT NULL COMMENT 'ユーザーのログイン・パスワード',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ユーザーの作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'ユーザーの更新日',
  guild_id int(11) NOT NULL DEFAULT 1 COMMENT '所属ギルドの外部キー',
  PRIMARY KEY (id),
  KEY guild_id (guild_id),
  CONSTRAINT guild_ibfk_1 FOREIGN KEY (guild_id) REFERENCES guilds (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='ユーザーのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/**
 * cells
 *
 * cells テーブルは異なる２つのオブジェクトを繋ぐ役割を持つ。
 * セルの名前は name に格納され、この名前は検索等で利用される。
 */

DROP TABLE IF EXISTS cells;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE cells (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'セルのID',
  name varchar(32) NOT NULL COMMENT 'セルの名前',
  left_id int(11) NOT NULL DEFAULT 1 COMMENT '左のオブジェクト ID',
  right_id int(11) NOT NULL DEFAULT 1 COMMENT '右のオブジェクト ID',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='セルのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/**
 * texts
 *
 * texts テーブル。
 * 一般に cells と共に使用される。
 */

DROP TABLE IF EXISTS texts;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE texts (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT 'テキストのID',
  content text DEFAULT '' COMMENT 'テキストの内容',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'テキストの作成日',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='テキストのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/**
 * images
 *
 * images テーブル。
 * 一般に cells と共に使用される。
 */

DROP TABLE IF EXISTS images;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE images (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '画像のID',
  url varchar(256) DEFAULT '' COMMENT '画像のURL',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT '画像の作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT '画像の更新日',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='画像のリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/**
 * news
 *
 * news は抽象化された構造。
 * news の表現は cells と texts を使う。
 * texts.content に news のテキスト内容。
 * cells.left_id に texts.id が保存される。
 * cells.right_id に リンク先の id が保存される。
 */

