<?php

require_once __DIR__ . '/../types/Comment.php';

define("GET_COMMENT_QUERY", "
    SELECT
        c.comment_id,
        u.user_id AS author_id,
        build_full_name(u.first_name, u.last_name) AS author_name,
        build_monogram(u.first_name, u.last_name) AS author_monogram,
        c.posted_at,
        c.body,
        c.is_reply
    FROM comment c
    INNER JOIN user u
        ON c.author = u.user_id
");

function get_comment_by_id(PDO $pdo, $comment_id)
{
    $stmt = $pdo->prepare(GET_COMMENT_QUERY . "
        WHERE c.comment_id = :comment_id
    ");

    $stmt->bindParam(":comment_id", $comment_id);
    $stmt->execute();

    return $stmt->fetchObject("Comment");
}

function get_all_comments(PDO $pdo)
{
    $stmt = $pdo->prepare(GET_COMMENT_QUERY);
    $stmt->execute();

    $comments = $stmt->fetchAll(PDO::FETCH_CLASS, "Comment");
    if (!$comments || count($comments) < 1) {
        return [];
    }
    return $comments;
}

/**
 * get_all_comments_by_id fetches all comments that are named in the given
 * array by identifier. Due to packet size constraints, this may not work as
 * expected with quantities greater than 1000 identifiers.
 *
 * Therefore, as noted below, this function is DEPRECATED.
 *
 * @deprecated use comment type specific database drivers instead.
 *
 * @pre count($comment_ids) <= 1000
 *
 * @param PDO $pdo the database connection to use.
 * @param array $comment_ids the list of comment IDs to fetch.
 *
 * @return Comment[]
 *
 * @throws PDOException when the database encounters an error.
 */
function get_all_comments_by_id(PDO $pdo, array $comment_ids)
{
    // Warn the developer that this is deprecated.
    trigger_error("Usage of " . __FUNCTION__ . " is deprecated.");

    $params = [];
    $q = GET_COMMENT_QUERY . "
        WHERE c.comment_id = ANY (
    ";

    for ($idx = 0; $idx < count($comment_ids); $idx++) {
        $paramName = ":id_$idx";
        $delimiter = $idx !== count($comment_ids) - 1 ? ',' : '';
        $q .= "$paramName$delimiter ";
        $params[$paramName] = $comment_ids[$idx];
    }

    $q .= "
        )
    ";

    $stmt = $pdo->prepare($q);

    foreach ($params as $param => &$val) {
        $stmt->bindParam($param, $val);
    }

    $stmt->execute();

    $comments = $stmt->fetchAll(PDO::FETCH_CLASS, "Comment");
    if (!$comments || count($comments) < 1) {
        return [];
    }
    return $comments;
}

function create_comment(PDO $pdo, Comment $comment)
{
    if (!$pdo->inTransaction()) {
        throw new RuntimeException("must be in transaction to create comment");
    }

    $stmt = $pdo->prepare("
        INSERT INTO comment (
            author,
            body,
            is_reply
        ) VALUES (
            :author,
            :body,
            :is_reply
        )
    ");

    $stmt->bindParam(":author", $comment->author_id);
    $stmt->bindParam(":body", $comment->body);
    $stmt->bindParam(":is_reply", $comment->is_reply);

    $stmt->execute();

    // Return the identifier of the comment just inserted.
    // This is safe because we are inside a transaction.
    return $pdo->lastInsertId();
}

function update_comment(PDO $pdo, Comment $comment)
{
    $stmt = $pdo->prepare("
        UPDATE comment
        SET body = :body
        WHERE comment_id = :comment_id
    ");

    $stmt->bindParam(":comment_id", $comment->comment_id);
    $stmt->bindParam(":body", $comment->body);

    $stmt->execute();
}

function delete_comment(PDO $pdo, $comment_id)
{
    $stmt = $pdo->prepare("
        DELETE FROM comment
        WHERE comment_id = :comment_id
    ");

    $stmt->bindParam(":comment_id", $comment_id);
    $stmt->execute();
}
