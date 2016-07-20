-- guilds

INSERT INTO guilds (
    name,
    description,
    category_id
) VALUES (
    '{{ name }}',
    '{{ description }}',
    1
);
SET @guild_id = LAST_INSERT_ID();

-- threads

INSERT INTO threads (
    name,
    guild_id
) VALUES (
    '{{ name }}・雑談スレ',
    @guild_id
);
SET @thread_id = LAST_INSERT_ID();

-- posts

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    'ギルドの雑談スレです。',
    @thread_id
);

-- symbols

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
    'images-syms-guilds',
    @image_id,
    @guild_id
);

-- news

INSERT INTO texts (
    content
) VALUES (
    '{{ name }}が新設されました。'
);
SET @text_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'texts-news-guilds',
    @text_id,
    @guild_id
);

