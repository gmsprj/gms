INSERT INTO guilds (
    name,
    description
) VALUES (
    '{{ name }}',
    '{{ description }}'
);
SET @guild_id = LAST_INSERT_ID();

INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    '{{ name }}専用板',
    '{{ name }}の専用板です。',
    'guilds',
    @guild_id
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    '{{ name }}・雑談スレ',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    'ギルドの雑談スレです。',
    @thread_id
);

