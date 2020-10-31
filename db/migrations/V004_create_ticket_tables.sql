
CREATE TABLE help_ticket (
    help_ticket_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    submitter integer NOT NULL,
    assignee integer,
    submitted_at datetime NOT NULL,
    is_closed boolean NOT NULL DEFAULT FALSE,
    closed_by_submitter boolean,
    closed_at datetime,
    subject text,
    body text,

    FOREIGN KEY (submitter)
        REFERENCES user(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (assignee)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'help_ticket stores user requests for assistance as tickets';

-- Let the help_ticket ID number start at 1000, purely for aesthetics.
ALTER TABLE help_ticket AUTO_INCREMENT = 1000;

DELIMITER $$
CREATE TRIGGER help_ticket_insert
BEFORE INSERT ON help_ticket
FOR EACH ROW
BEGIN
    SET NEW.submitted_at = NOW();
END$$
DELIMITER ;

CREATE TABLE help_ticket_comment (
    comment_id integer NOT NULL,
    help_ticket_id integer NOT NULL,

    FOREIGN KEY (help_ticket_id)
        REFERENCES help_ticket(help_ticket_id)
        ON DELETE CASCADE,

    FOREIGN KEY (comment_id)
        REFERENCES comment(comment_id)
        ON DELETE CASCADE
)
COMMENT = 'help_ticket_comment stores follow up comments to help tickets';
