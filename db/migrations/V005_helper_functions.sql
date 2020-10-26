
DELIMITER $$
CREATE FUNCTION build_full_name(first_name text, last_name text)
RETURNS text
DETERMINISTIC
BEGIN
    RETURN CONCAT(first_name, CONCAT(' ', last_name));
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION build_monogram(first_name text, last_name text)
RETURNS text
DETERMINISTIC
BEGIN
    RETURN UPPER(
        CONCAT(
            LEFT(first_name, 1),
            LEFT(last_name, 1)
        )
    );
END$$
DELIMITER ;
