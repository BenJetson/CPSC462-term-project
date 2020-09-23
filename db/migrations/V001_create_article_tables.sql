
CREATE TABLE article_category (
    article_category_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title text
)
COMMENT = 'article_category describes a category of knowledge articles';

CREATE TABLE article (
    article_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_category_id integer NOT NULL
        REFERENCES article_category.article_category_id
        ON DELETE CASCADE,
    author integer NOT NULL
        REFERENCES user.user_id
        ON DELETE CASCADE,
    title text NOT NULL,
    body text NOT NULL,
    updated_at datetime NOT NULL
)
COMMENT = 'article stores knowledge articles';

CREATE TABLE article_comment (
    article_comment_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_id integer NOT NULL
        REFERENCES article.article_id
        ON DELETE CASCADE,
    author integer NOT NULL
        REFERENCES user.user_id
        ON DELETE CASCADE,
    posted_at datetime NOT NULL,
    body text
)
COMMENT = 'article_comment stores user comments on knowledge articles';

CREATE TABLE article_rating (
    article_id integer NOT NULL
        REFERENCES article.article_id
        ON DELETE CASCADE,
    user_id integer NOT NULL
        REFERENCES user.user_id
        ON DELETE CASCADE,
    stars integer, /* TODO need to check with a trigger */

    UNIQUE(article_id, user_id)
)
COMMENT = 'article_comment stores user star ratings of knowledge articles';
