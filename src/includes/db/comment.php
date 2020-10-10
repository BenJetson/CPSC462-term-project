<?php

require_once __DIR__ . '/../types/Comment.php';

define("GET_COMMENT_QUERY", "
    SELECT
        c.comment_id,
        u.user_id AS author_id,
        build_full_name(u.first_name, u.last_name) AS author_name,
        c.posted_at,
        c.body
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

function get_all_comments_by_id(PDO $pdo, array $comment_ids)
{
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
    if (count($comments) < 1) {
        return [];
    }
    return $comments;
}

function create_comment(PDO $pdo, Comment $comment)
{
    $pdo->beginTransaction();
    $comment_id = null;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO comment (
                author,
                body
            ) VALUES (
                :author,
                :body
            )
        ");

        $stmt->bindParam(":author", $comment->author_id);
        $stmt->bindParam(":body", $comment->body);

        $stmt->execute();

        // Get the identifier of the comment just inserted.
        $stmt = $pdo->prepare("
            SELECT MAX(comment_id)
            FROM comment
        ");

        $stmt->execute();

        $comment_id = (int) $stmt->fetchColumn();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

    $pdo->commit();
    return $comment_id;
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
