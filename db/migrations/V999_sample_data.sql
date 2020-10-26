
INSERT INTO article_category(title) VALUES
    ('Random'),
    ('Computere'),
    ('Mobile Devices'),
    ('Networking'),
    ('Security'),
    ('Software'),
    ('Hardware'),
    ('Policies'),
    ('Archive'),
    ('Developers');

INSERT INTO article (article_category_id, author, title, body)
VALUES
    (1, 1, 'This is the title!', 'And some body text\nmore\nmore');
