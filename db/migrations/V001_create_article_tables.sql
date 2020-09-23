
CREATE TABLE article_category (
    article_category_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title text
)
COMMENT = 'article_category describes a category of knowledge articles';

CREATE TABLE article (
    article_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_category_id integer NOT NULL,
    author integer NOT NULL,
    title text NOT NULL,
    body text NOT NULL,
    updated_at datetime NOT NULL,

    FOREIGN KEY (article_category_id)
        REFERENCES article_category(article_category_id)
        ON DELETE CASCADE,

    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'article stores knowledge articles';

CREATE TABLE article_comment (
    article_comment_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_id integer NOT NULL,
    author integer NOT NULL,
    posted_at datetime NOT NULL,
    body text,

    FOREIGN KEY (article_id)
        REFERENCES article(article_id)
        ON DELETE CASCADE,

    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'article_comment stores user comments on knowledge articles';

CREATE TABLE article_rating (
    article_id integer NOT NULL,
    user_id integer NOT NULL,
    stars integer, /* TODO need to check with a trigger */

    PRIMARY KEY (article_id, user_id),

    FOREIGN KEY (article_id)
        REFERENCES article(article_id)
        ON DELETE CASCADE,

    FOREIGN KEY (user_id)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'article_comment stores user star ratings of knowledge articles';
