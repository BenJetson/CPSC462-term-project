
CREATE TABLE article_category (
    article_category_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title text,
    descr text,
    color text,
    icon text
)
COMMENT = 'article_category describes a category of knowledge articles';

CREATE TABLE article (
    article_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    article_category_id integer NOT NULL,
    author integer NOT NULL,
    title text NOT NULL,
    body text NOT NULL,
    created_at datetime NOT NULL,
    updated_at datetime NOT NULL,

    FOREIGN KEY (article_category_id)
        REFERENCES article_category(article_category_id)
        ON DELETE CASCADE,

    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'article stores knowledge articles';

DELIMITER $$
CREATE TRIGGER article_insert
BEFORE INSERT ON article
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        SET NEW.created_at = NOW();
        SET NEW.updated_at = NOW();
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER article_update
BEFORE UPDATE ON article
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        SET NEW.updated_at = NOW();
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER article_delete
BEFORE DELETE ON article
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        DELETE FROM comment
        WHERE comment_id IN (
            SELECT comment_id
            FROM article_comment
            WHERE article_id = OLD.article_id
        );
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER article_category_delete
BEFORE DELETE ON article_category
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        DELETE FROM comment
        WHERE comment_id IN (
            SELECT ac.comment_id
            FROM article_comment ac
            INNER JOIN article a
                ON a.article_id = ac.article_id
            WHERE a.article_category_id = OLD.article_category_id
        );
    END IF;
END$$
DELIMITER ;

CREATE TABLE article_comment (
    comment_id integer NOT NULL,
    article_id integer NOT NULL,

    PRIMARY KEY (comment_id, article_id),

    FOREIGN KEY (article_id)
        REFERENCES article(article_id)
        ON DELETE CASCADE,

    FOREIGN KEY (comment_id)
        REFERENCES comment(comment_id)
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
