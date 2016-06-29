INSERT INTO guilds (
    name,
    description
) VALUES (
    '{{ name }}',
    '{{ description }}'
);
SET @guild_id = LAST_INSERT_ID();

-- boards, threads, posts

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

-- guild-symbol

INSERT INTO images (
    url
) VALUES (
    '/img/guilds/symbol.png'
);
SET @image_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'guild-symbol',
    @guild_id,
    @image_id
);

-- docs

INSERT INTO docs (
    name,
    content,
    state,
    guild_id
) VALUES (
    'マニュアル',
    'これは{{ name }}のマニュアルです。',
    'published',
    @guild_id
);

