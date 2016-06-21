-- テーブルのクリア

DELETE FROM sites;
ALTER TABLE sites AUTO_INCREMENT = 1;

DELETE FROM plazas;
ALTER TABLE plazas AUTO_INCREMENT = 1;

DELETE FROM posts;
ALTER TABLE posts AUTO_INCREMENT = 1;

DELETE FROM threads;
ALTER TABLE threads AUTO_INCREMENT = 1;

DELETE FROM boards;
ALTER TABLE boards AUTO_INCREMENT = 1;

DELETE FROM users;
ALTER TABLE users AUTO_INCREMENT = 1;

DELETE FROM guilds;
ALTER TABLE guilds AUTO_INCREMENT = 1;


-- サイトの設定

INSERT INTO sites (
  name,
  description
) VALUES (
  'FWU（仮）',
  '労働者のユニオン・サイト'
);


-- 広場の設定

INSERT INTO plazas (
  name,
  description
) VALUES (
  '広場',
  'ゆっくりしていってね！'
);
SET @plaza_id = LAST_INSERT_ID();

-- 広場用の板、スレッド、ポストを作成

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    'ロビー',
    '広場のロビー板です。',
    'plazas',
    @plaza_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    '雑談スレ',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無し',
    '雑談スレです。',
    @thread_id
);


-- Guilds と板、スレッド、ポストを作成

-- 入門者ギルド

INSERT INTO guilds (
    name,
    description
) VALUES (
    '入門用ギルド',
    '入門用のギルド（同業組合）です。'
);
SET @guild_id = LAST_INSERT_ID();

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    '入門用ギルド専用板',
    '入門用ギルドの専用板です。ゲストは書き込みできません（他のギルド員はできます）。',
    'guilds',
    @guild_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    'ギルド・雑談スレ',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    'Name Not Found',
    'ギルドの雑談スレです。',
    @thread_id
);

-- Web制作管理ギルド

INSERT INTO guilds (
    name,
    description
) VALUES (
    'web制作管理',
    'web制作管理ギルドです。'
);
SET @guild_id = LAST_INSERT_ID();

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    'web制作管理ギルド専用板',
    'web制作管理ギルドの専用板です。書き込みはギルドメンバーのみが行えます。',
    'guilds',
    @guild_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    'web制作管理・雑談スレ',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    'Name Not Found',
    'ギルドの雑談スレです。',
    @thread_id
);

