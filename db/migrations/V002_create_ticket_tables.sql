
CREATE TABLE help_ticket (
    help_ticket_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    submitter integer NOT NULL
        REFERENCES user.user_id
        ON DELETE CASCADE,
    assignee integer
        REFERENCES user.user_id
        ON DELETE CASCADE,
    submitted_at datetime NOT NULL,
    is_closed boolean NOT NULL DEFAULT FALSE,
    closed_by_submitter boolean,
    closed_at datetime,
    subject text,
    body text
)
COMMENT = 'help_ticket stores user requests for assistance as tickets';

CREATE TABLE help_ticket_comment (
    help_ticket_comment_id integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    help_ticket_id integer NOT NULL
        REFERENCES help_ticket.help_ticket_id
        ON DELETE CASCADE,
    author integer NOT NULL
        REFERENCES user.user_id
        ON DELETE CASCADE,
    posted_at datetime NOT NULL,
    body text
)
COMMENT = 'help_ticket_comment stores follow up comments to help tickets';
