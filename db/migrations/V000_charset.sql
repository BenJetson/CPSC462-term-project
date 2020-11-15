
-- We will use the utf8mb4 character set and utf8mb4_unicodel_ci collation for
-- this database.
--
-- This allows us to save emojis and other special unicode codepoints.
--
-- Inspiration: https://mathiasbynens.be/notes/mysql-utf8mb4

ALTER DATABASE
    CHARACTER SET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
