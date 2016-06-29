INSERT INTO boards (
    name,
    description,
    parent_name,
    parent_id
) VALUES (
    '{{ name }}',
    '{{ description }}',
    'null',
    0
);
SET @board_id = LAST_INSERT_ID();

INSERT INTO threads (
    name,
    board_id
) VALUES (
    '{{ name }}',
    @board_id
);
SET @thread_id = LAST_INSERT_ID();

INSERT INTO posts (
    name,
    content,
    thread_id
) VALUES (
    '名無しさん',
    'てすと。',
    @thread_id
);

