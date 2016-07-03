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
 *
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
 * 「板/スレッド/ポスト」の内、板の情報が保存される。
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
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='板のリスト';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * threads
 *
 * 「板/スレッド/ポスト」の内、スレッドの情報が保存される。
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
  CONSTRAINT board_threads_ibfk_1 FOREIGN KEY (board_id) REFERENCES boards (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='板のスレッド';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * posts
 *
 * 「板/スレッド/ポスト」の内、ポストの情報が保存される。
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
  CONSTRAINT thread_posts_ibfk_1 FOREIGN KEY (thread_id) REFERENCES threads (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='スレッドへのポスト';
/*!40101 SET character_set_client = @saved_cs_client */;

/**
 * guilds
 *
 * ギルドの情報が保存される。
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
 * 登録ユーザーの情報が保存される。
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
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='ユーザーのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/**
 * docs
 *
 * state
 * 文書の状態
 *
 * - draft
 *   名称: 提案、原案、草案
 *   30日で寿命が尽きる。
 *   条件を満たすと published へ遷移。
 *
 * - published
 *   名称: 文書、公開文書
 *   恒久的に保存される。
 *   条件を満たすと draft へ遷移。
 */

DROP TABLE IF EXISTS docs;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE docs (
  id int(11) NOT NULL AUTO_INCREMENT COMMENT '文書のID',
  name varchar(256) NOT NULL DEFAULT '' COMMENT '文書の名前',
  content text COMMENT '文書の内容',
  state varchar(32) NOT NULL DEFAULT 'closed' COMMENT '文書の状態',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT '文書の作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT '文書の更新日',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='ドキュメントのリスト';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/**
 * ここから以下は抽象化されたデータ構造と、それを表現するためのテーブル。
 */

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
  left_id int(11) NOT NULL DEFAULT 1 COMMENT '左のオブジェクトID',
  right_id int(11) NOT NULL DEFAULT 1 COMMENT '右のオブジェクトID',
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
  content text COMMENT 'テキストの内容',
  created datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'テキストの作成日',
  modified datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'テキストの更新日',
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
 * lefts-types-rights
 *
 *      cells
 *    /       \
 * lefts      rights
 *
 * cells.name: 'lefts-types-rights'
 * cells.left_id: lefts.id 
 * cells.right_id: rights.id
 */

/**
 * texts-news-xxxs
 *
 * 抽象化された構造。表現に cells, texts, ??? を使う。
 * ニュースと、そのソースになるオブジェクトを cells で繋ぐ。
 *
 *      cells
 *    /       \
 * texts     (sites, guilds, boards, posts, ...)
 *
 * cells.name: 'texts-news-xxxs' (example 'texts-news-guilds' でギルドのニュース)
 * cells.left_id: texts.id
 * cells.right_id: xxxs.id
 * texts.content: news のテキスト内容。
 */

/**
 * images-syms-xxxs
 *
 * シンボル画像とオブジェクトを cells で繋ぐ。
 *
 *      cells
 *    /       \
 * images   (sites, guilds, boards, posts, ...)
 *
 * cells.name に 'images-syms-xxxs' が保存される。
 * cells.left_id に images.id が保存される。
 * cells.right_id に xxxs.id が保存される。
 */

/**
 * xxxs-owners-xxxs
 *
 * 所属を表現する構造。
 * 左がオブジェクト、右が所属先のオブジェクト。
 *
 * 'docs-owners-guilds' で Docs のオーナーは Guilds であると言う状態。
 *
 *      cells
 *    /       \
 * xxxs       xxxs
 *
 * cells.name: 'xxxs-owners-xxxs' (example 'docs-owners-guilds')
 * cells.left_id: xxxs.id 
 * cells.right_id: xxxs.id 
 */

/**
 * xxxs-refs-xxxs
 *
 * 参照先と参照元を表現する構造。
 * 左が参照先、右が参照元。
 *
 * 'threads-refs-docs' で、Threads が Docs に参照されている状態を表す。
 *
 *      cells
 *    /       \
 * xxxs       xxxs
 *
 * cells.name: 'xxxs-refs-xxxs'
 * cells.left_id: xxxs.id 
 * cells.right_id: xxxs.id
 */

/**
 * texts-kvs-xxxs
 *
 * kv ... Key and Value
 *
 * キーと値を表す構造。
 * 左がキー、右が値。
 * 左のキー(texts)は検索等で参照される。
 *
 *      cells
 *    /       \
 * texts     xxxs
 *
 * cells.name: 'texts-kvs-xxxs' (example 'texts-kvs-texts')
 * cells.left_id: texts.id (key)
 * cells.right_id: texts.id (value)
 */

