
DELIMITER $$
CREATE PROCEDURE build_full_name(IN first_name text, IN last_name text)
BEGIN
    RETURN CONCAT(first_name, CONCAT(' ', last_name));
END$$
DELIMITER ;
