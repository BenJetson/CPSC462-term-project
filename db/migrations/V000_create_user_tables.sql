
CREATE TABLE user (
    user_id      integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    is_admin     boolean NOT NULL DEFAULT false,
    is_disabled  boolean NOT NULL DEFAULT false,

    email            varchar(254) NOT NULL UNIQUE,
    email_changed_at datetime     NOT NULL DEFAULT '1970-01-01 00:00:00',
    email_confirmed  boolean      NOT NULL DEFAULT false,

    first_name text        NOT NULL,
    last_name  text        NOT NULL,
    dob        date        NOT NULL,
    telephone  varchar(12) NOT NULL,

    address_line_1 text       NOT NULL,
    address_line_2 text       NOT NULL,
    address_city   text       NOT NULL,
    address_state  varchar(2) NOT NULL,
    address_zip    varchar(5) NOT NULL,

    pass_hash       text     NOT NULL,
    pass_changed_at datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
    pass_attempts   integer  NOT NULL DEFAULT 0,
    pass_locked_at  datetime NOT NULL DEFAULT '1970-01-01 00:00:00'
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
CREATE TRIGGER user_telephone_insert_check
BEFORE INSERT ON user
FOR EACH ROW
BEGIN
    CALL check_phone_number(NEW.telephone);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER user_telephone_update_check
BEFORE UPDATE ON user
FOR EACH ROW
BEGIN
    CALL check_phone_number(NEW.telephone);
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
    29632,
    '$2y$10$.Jz6XTlb9bAAY6D/VHXAIuIP0Rj2.4XT4gkMZNpvCaLoCTSfhkTpe'
);
