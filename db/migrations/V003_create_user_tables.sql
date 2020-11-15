
CREATE TABLE user (
    user_id      integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    is_admin     boolean NOT NULL DEFAULT false,
    is_disabled  boolean NOT NULL DEFAULT false,

    email            varchar(180) NOT NULL UNIQUE,
    email_changed_at datetime     NOT NULL,
    email_confirmed  boolean      NOT NULL DEFAULT false,

    first_name text        NOT NULL,
    last_name  text        NOT NULL,
    dob        date        NOT NULL,
    telephone  char(12)    NOT NULL,

    address_line_1 text       NOT NULL,
    address_line_2 text       NOT NULL,
    address_city   text       NOT NULL,
    address_state  char(2)    NOT NULL,
    address_zip    char(5)    NOT NULL,

    pass_hash       text     NOT NULL,
    pass_changed_at datetime NOT NULL,
    pass_attempts   integer  NOT NULL DEFAULT 0,
    pass_locked_at  datetime NOT NULL
)
COMMENT = 'user stores the data associated with each user account';

DELIMITER $$
CREATE PROCEDURE check_phone_number(IN telephone varchar(12))
BEGIN
    IF (telephone REGEXP '^[0-9]{3}-[0-9]{3}-[0-9]{4}$' = FALSE) THEN
        SIGNAL SQLSTATE '45000'
        SET message_text = 'telephone number does not match format';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER user_telephone_insert
BEFORE INSERT ON user
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        CALL check_phone_number(NEW.telephone);

        SET NEW.email_changed_at = NOW();
        SET NEW.pass_changed_at = NOW();
        SET NEW.pass_locked_at = FROM_UNIXTIME(0);
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER user_telephone_update
BEFORE UPDATE ON user
FOR EACH ROW
BEGIN
    IF (triggers_active()) THEN
        CALL check_phone_number(NEW.telephone);

        IF (NEW.email != OLD.email) THEN
            SET NEW.email_changed_at = NOW();
            SET NEW.email_confirmed = FALSE;
        END IF;

        IF (NEW.pass_hash != OLD.pass_hash) THEN
            SET NEW.pass_changed_at = NOW();
        END IF;
    END IF;
END$$
DELIMITER ;

INSERT INTO user (
    is_admin,
    email,
    email_confirmed,
    first_name,
    last_name,
    dob,
    telephone,
    address_line_1,
    address_line_2,
    address_city,
    address_state,
    address_zip,
    pass_hash
) VALUES (
    true,
    'bfgodfr@clemson.edu',
    true,
    'Ben',
    'Godfrey',
    '2000-07-23',
    '864-610-4160',
    '2275 University Station',
    'Box 99999',
    'Clemson',
    'SC',
    '29632',
    '$2y$10$.Jz6XTlb9bAAY6D/VHXAIuIP0Rj2.4XT4gkMZNpvCaLoCTSfhkTpe'
);
