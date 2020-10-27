
INSERT INTO article_category (
    title,
    descr,
    color,
    icon
) VALUES
    ('Random', 'Anything and everything, a little of both.', 'orange', 'fa-random'),
    ('Computere', 'About computeres in general.', 'mediumaquamarine', 'fa-desktop'),
    ('Mobile Devices', 'Get help with mobile devices like phones and tablets.', 'dodgerblue', 'fa-mobile-alt'),
    ('Networking', 'Get help with wireless and wired network access.', 'darkgreen', 'fa-wifi'),
    ('Security', 'Find out how to make your devices secure to keep you safe.', 'deeppink', 'fa-shield-alt'),
    ('Software', 'Learn about the software programs we license.', 'goldenrod', 'fa-rocket'),
    ('Hardware', 'Explore what hardware resources we have available.', 'lightcoral', 'fa-tools'),
    ('Policies', 'Our internal policies for working with our products and services.', 'brown', 'fa-file-alt'),
    ('Archive', 'Articles that are no longer maintained.', 'gray', 'fa-archive'),
    ('Developers', 'API documentation resources.', 'purple', 'fa-code');

INSERT INTO article (article_category_id, author, title, body)
VALUES
    (1, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (2, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (3, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (4, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (5, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (6, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (7, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (8, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (9, 1, 'This is the title!', 'And some body text\nmore\nmore'),
    (10, 1, 'This is the title!', 'And some body text\nmore\nmore');
