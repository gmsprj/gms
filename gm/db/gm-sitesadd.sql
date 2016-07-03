INSERT INTO sites (
  name,
  description
) VALUES (
  '{{ name }}',
  '{{ description }}'
);
SET @site_id = LAST_INSERT_ID();

INSERT INTO images (
    url
) VALUES (
    '/img/sites/symbol.png'
);
SET @image_id = LAST_INSERT_ID();

INSERT INTO cells (
    name,
    left_id,
    right_id
) VALUES (
    'images-syms-sites',
    @image_id,
    @site_id
);

