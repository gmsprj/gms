-- guilds

INSERT INTO guilds (
    name,
    description
) VALUES (
    '{{ name }}',
    '{{ description }}'
);
SET @guild_id = LAST_INSERT_ID();

-- boards, threads, posts, cells

INSERT INTO boards (
    name,
    description
) VALUES (
    '{{ name }}専用板',
    '{{ name }}の専用板です。'
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

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'boards-owners-guilds',
    @board_id,
    @guild_id
);

-- news

INSERT INTO texts (
    content
) VALUES (
    '{{ name }}専用板が新設されました。'
);
SET @text_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'texts-news-boards',
    @text_id,
    @board_id
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

-- docs

INSERT INTO docs (
    name,
    content,
    state
) VALUES (
    'ギルド利用マニュアル',
    'これは{{ name }}の利用マニュアルです。ギルドの新規参加者を対象にしています。',
    'published'
);
SET @doc_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'docs-owners-guilds',
    @doc_id,
    @guild_id
);

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'threads-refs-docs',
    @thread_id,
    @doc_id
);

-- docs

INSERT INTO docs (
    name,
    content,
    state
) VALUES (
    '{{ name }}の提案',
    'これは{{ name }}の文書化前の提案です。',
    'draft'
);
SET @doc_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'docs-owners-guilds',
    @doc_id,
    @guild_id
);

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'threads-refs-docs',
    @thread_id,
    @doc_id
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

