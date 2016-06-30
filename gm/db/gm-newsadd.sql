INSERT INTO texts (
    content
) VALUES (
    '{{ content }}'
);
SET @text_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'text-news-site',
    @text_id,
    1
);

