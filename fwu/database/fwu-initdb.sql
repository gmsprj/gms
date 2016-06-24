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
  'FWU（仮） 0.0.3',
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

-- フリーランス・ギルド

INSERT INTO guilds (
    name,
    description
) VALUES (
    'フリーランス',
    'フリーランス・ギルドです。'
);
SET @guild_id = LAST_INSERT_ID();

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    'フリーランスギルド専用板',
    'フリーランスギルドの専用板です。',
    'guilds',
    @guild_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    'フリーランス・雑談スレ',
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
    '雑談スレ',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    '雑談スレです。',
    @thread_id
);

