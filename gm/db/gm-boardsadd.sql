INSERT INTO boards (
    name,
    description
) VALUES (
    '{{ name }}',
    '{{ description }}'
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

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'board-owner-site',
    @board_id,
    1
);

