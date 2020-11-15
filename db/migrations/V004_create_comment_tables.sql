
CREATE TABLE comment (
    comment_id integer  NOT NULL AUTO_INCREMENT PRIMARY KEY,
    author     integer  NOT NULL,
    posted_at  datetime NOT NULL,
    is_reply   boolean  NOT NULL,
    body text,

    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'comment stores user comments for both articles and help tickets';

DELIMITER $$
CREATE TRIGGER comment_insert
BEFORE INSERT ON comment
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        SET NEW.posted_at = NOW();
    END IF;
END$$
DELIMITER ;
