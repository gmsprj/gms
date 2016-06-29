-- テーブルのクリア

DELETE FROM sites;
ALTER TABLE sites AUTO_INCREMENT = 1;

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
  'GM（仮） 0.0.4',
  '労働者のユニオン・サイトです。
ギルド（同業者組合）に参加することで議論に参加可能です。'
);


-- ギルドと板、スレッド、ポストを作成

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
    'ギルド専用板',
    'ギルドの専用板です。',
    'guilds',
    @guild_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    'ロビー',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    'ギルドのロビースレッドです。',
    @thread_id
);

-- 開発者ギルド

INSERT INTO guilds (
    name,
    description
) VALUES (
    'GMS 開発者ギルド',
    'GMS の開発者ギルドです。'
);
SET @guild_id = LAST_INSERT_ID();

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    'ギルド専用板',
    'ギルドの専用板です。',
    'guilds',
    @guild_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    'ロビー',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    'ロビースレッドです。',
    @thread_id
);


-- ロビー板

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    'ロビー板',
    'ロビー板です。',
    'null',
    0
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    'ロビー',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    'ロビーです。',
    @thread_id
);

