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
    'board-owner-guild',
    @board_id,
    @guild_id
);

-- text-news-board

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
    'text-news-board',
    @text_id,
    @board_id
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
    'image-symbol-guild',
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
    'doc-owner-guild',
    @doc_id,
    @guild_id
);

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
    'doc-owner-guild',
    @doc_id,
    @guild_id
);

-- text-news-guild

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
    'text-news-guild',
    @text_id,
    @guild_id
);

