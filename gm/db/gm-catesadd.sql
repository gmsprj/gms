-- categorys

INSERT INTO categorys (
    name
) VALUES (
    '{{ name }}'
);
SET @category_id = LAST_INSERT_ID();

