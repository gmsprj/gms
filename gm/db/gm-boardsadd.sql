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
    '今だ！1ゲットォォｫｫ！！ 
￣￣￣￣￣∨￣￣￣　　　　　　　(´´ 
　　　　 ∧∧　　　）　　　　　　(´⌒(´ 
　　⊂（ﾟДﾟ⊂⌒｀つ≡≡≡(´⌒;;;≡≡≡ 
　　　　　　 ￣￣　 (´⌒(´⌒;; 
　　　　　　ｽﾞｻﾞｰｰｰｰｰｯ 
',
    @thread_id
);

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'boards-owners-sites',
    @board_id,
    1
);

-- text-news-board

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
    'texts-news-boards',
    @text_id,
    @board_id
);

