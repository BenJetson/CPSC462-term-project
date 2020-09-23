
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

CREATE TABLE help_ticket_comment (
    help_ticket_comment_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    help_ticket_id integer NOT NULL,
    author integer NOT NULL,
    posted_at datetime NOT NULL,
    body text,

    FOREIGN KEY (help_ticket_id)
        REFERENCES help_ticket(help_ticket_id)
        ON DELETE CASCADE,

    FOREIGN KEY (author)
        REFERENCES user(user_id)
        ON DELETE CASCADE
)
COMMENT = 'help_ticket_comment stores follow up comments to help tickets';
