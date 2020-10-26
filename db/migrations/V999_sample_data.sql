
INSERT INTO article_category (
    title,
    descr,
    color,
    icon
) VALUES
    ('Random', 'Anything and everything, a little of both.', 'orange', 'fa-random'),
    ('Computere', 'About computeres in general.', 'aqua', 'fa-desktop'),
    ('Mobile Devices', 'Get help with mobile devices like phones and tablets.', 'dodgerblue', 'fa-mobile-alt'),
    ('Networking', 'Get help with wireless and wired network access.', 'darkgreen', 'fa-wifi'),
    ('Security', 'Find out how to make your devices secure to keep you safe.', 'deeppink', 'fa-shield-alt'),
    ('Software', 'Learn about the software programs we license.', 'goldenrod', 'fa-rocket'),
    ('Hardware', 'Explore what hardware resources we have available.', 'lightcoral', 'fa-tools'),
    ('Policies', 'Our internal policies for working with our products and services.', 'lightslategray', 'fa-file-alt'),
    ('Archive', 'Articles that are no longer maintained.', 'gray', 'fa-archive'),
    ('Developers', 'API documentation resources.', 'purple', 'fa-code');

INSERT INTO article (article_category_id, author, title, body)
VALUES
    (1, 1, 'This is the title!', 'And some body text\nmore\nmore');
