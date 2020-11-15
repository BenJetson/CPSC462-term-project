<?php

require_once __DIR__ . '/../types/Article.php';
require_once __DIR__ . '/../types/ArticleCategory.php';
require_once __DIR__ . '/../types/Backup.php';
require_once __DIR__ . '/../types/Comment.php';
require_once __DIR__ . '/../types/HelpTicket.php';
require_once __DIR__ . '/../types/User.php';

/**
 * restore_clear_db will destroy ALL records stored in the database  to prepare
 * for further restore operations.
 *
 * @param PDO $pdo the database connection to use.
 */
function restore_clear_db(PDO $pdo)
{
    $pdo->exec("DELETE FROM article_rating");
    $pdo->exec("DELETE FROM article_comment");
    $pdo->exec("DELETE FROM article");
    $pdo->exec("DELETE FROM article_category");
    $pdo->exec("DELETE FROM help_ticket_comment");
    $pdo->exec("DELETE FROM help_ticket");
    $pdo->exec("DELETE FROM user");

    $pdo->exec("
        SET @DISABLE_TRIGGERS = TRUE
    ");
}

function restore_finalize_db(PDO $pdo)
{
    $pdo->exec("
        SET @DISABLE_TRIGGERS = FALSE
    ");
}

function restore_users(PDO $pdo, array $users)
{
    // To avoid hitting the maximum parameters limit, we chunk this query.
    // This also guarantees that each chunk is indexed numerically.
    $chunks = array_chunk($users, 64);

    foreach ($chunks as $chunk) {
        $query = "
            INSERT INTO USER (
                user_id,
                is_admin,
                is_disabled,

                email,
                email_changed_at,
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

                pass_hash,
                pass_changed_at,
                pass_attempts,
                pass_locked_at
            )
            VALUES
        ";
        $params = [];

        // Recall: this array was reindexed, so guaranteed that $idx is numeric.
        foreach ($chunk as $idx => $user) {
            $query .= "
                (
                    :user_id_$idx,
                    :is_admin_$idx,
                    :is_disabled_$idx,

                    :email_$idx,
                    :email_changed_at_$idx,
                    :email_confirmed_$idx,

                    :first_name_$idx,
                    :last_name_$idx,
                    :dob_$idx,
                    :telephone_$idx,

                    :address_line_1_$idx,
                    :address_line_2_$idx,
                    :address_city_$idx,
                    :address_state_$idx,
                    :address_zip_$idx,

                    :pass_hash_$idx,
                    :pass_changed_at_$idx,
                    :pass_attempts_$idx,
                    :pass_locked_at_$idx
                )
            ";

            if ($idx !== count($chunk) - 1) $query .= ",";

            $params = array_merge($params, [
                ":user_id_$idx"          => $user->user_id,
                ":is_admin_$idx"         => $user->is_admin,
                ":is_disabled_$idx"      => $user->is_disabled,
                ":email_$idx"            => $user->email,
                ":email_changed_at_$idx" => $user->email_changed_at,
                ":email_confirmed_$idx"  => $user->email_confirmed,
                ":first_name_$idx"       => $user->first_name,
                ":last_name_$idx"        => $user->last_name,
                ":dob_$idx"              => $user->dob,
                ":telephone_$idx"        => $user->telephone,
                ":address_line_1_$idx"   => $user->address_line_1,
                ":address_line_2_$idx"   => $user->address_line_2,
                ":address_city_$idx"     => $user->address_city,
                ":address_state_$idx"    => $user->address_state,
                ":address_zip_$idx"      => $user->address_zip,
                ":pass_hash_$idx"        => $user->pass_hash,
                ":pass_changed_at_$idx"  => $user->pass_changed_at,
                ":pass_attempts_$idx"    => $user->pass_attempts,
                ":pass_locked_at_$idx"   => $user->pass_locked_at,
            ]);
        }

        $stmt = $pdo->prepare($query);

        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        $stmt->execute();
    }

    // Reset the auto increment counter to the next appropriate value.
    $pdo->exec("
        ALTER TABLE user
        AUTO_INCREMENT = (
            SELECT MAX(user_id) + 1
            FROM user
        )
    ");
}

function restore_comments(PDO $pdo, array $comments)
{
    // To avoid hitting the maximum parameters limit, we chunk this query.
    // This also guarantees that each chunk is indexed numerically.
    $chunks = array_chunk($comments, 64);

    foreach ($chunks as $chunk) {
        $query = "
            INSERT INTO comment (
                comment_id,
                author,
                posted_at,
                body,
                is_reply
            )
            VALUES
        ";
        $params = [];

        foreach ($chunk as $idx => $comment) {
            $query .= "
                (
                    :comment_id_$idx,
                    :author_$idx,
                    :posted_at_$idx,
                    :body_$idx,
                    :is_reply_$idx
                )
            ";

            if ($idx !== count($chunk) - 1) $query .= ",";

            $params = array_merge($params, [
                ":comment_id_$idx" => $comment->comment_id,
                ":author_$idx"     => $comment->author,
                ":posted_at_$idx"  => $comment->posted_at,
                ":body_$idx"       => $comment->body,
                ":is_reply_$idx"   => $comment->is_reply,
            ]);
        }

        $stmt = $pdo->prepare($query);

        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        $stmt->execute();
    }

    // Reset the auto increment counter to the next appropriate value.
    $pdo->exec("
        ALTER TABLE comment
        AUTO_INCREMENT = (
            SELECT MAX(comment_id) + 1
            FROM comment
        )
    ");
}

function restore_article_categories(PDO $pdo, array $article_categories)
{
    // To avoid hitting the maximum parameters limit, we chunk this query.
    // This also guarantees that each chunk is indexed numerically.
    $chunks = array_chunk($article_categories, 64);

    foreach ($chunks as $chunk) {
        $query = "
            INSERT INTO article_category (
                article_category_id,
                title,
                descr,
                color,
                icon
            )
            VALUES
        ";
        $params = [];

        foreach ($chunk as $idx => $article_category) {
            $query .= "
                (
                    :article_category_id_$idx,
                    :title_$idx,
                    :descr_$idx,
                    :color_$idx,
                    :icon_$idx
                )
            ";

            if ($idx !== count($chunk) - 1) $query .= ",";

            $params = array_merge($params, [
                ":article_category_id_$idx" =>
                $article_category->article_category_id,

                ":title_$idx" => $article_category->title,
                ":descr_$idx" => $article_category->descr,
                ":color_$idx" => $article_category->color,
                ":icon_$idx"  => $article_category->icon,
            ]);
        }

        $stmt = $pdo->prepare($query);

        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }

        $stmt->execute();
    }

    // Reset the auto increment counter to the next appropriate value.
    $pdo->exec("
        ALTER TABLE article_category
        AUTO_INCREMENT = (
            SELECT MAX(article_category_id) + 1
            FROM article_category
        )
    ");
}

function restore_articles(PDO $pdo, array $articles)
{
}

function restore_article_comments(PDO $pdo, array $article_comments)
{
}

function restore_article_ratings(PDO $pdo, array $article_ratings)
{
}

function restore_help_tickets(PDO $pdo, array $help_tickets)
{
}

function restore_help_ticket_comments(PDO $pdo, array $help_ticket_comments)
{
}

function restore_from_file(PDO $pdo, $file_name)
{
    // Open the ZIP archive that the user uploaded.
    $zip = new ZipArchive;
    $zip->open($file_name);

    $users = json_decode(
        $zip->getFromName("users.json")
    );

    $comments = json_decode(
        $zip->getFromName("comments.json")
    );

    $article_categories = json_decode(
        $zip->getFromName("article-categories.json")
    );

    $articles = json_decode(
        $zip->getFromName("articles.json")
    );

    $article_ratings = json_decode(
        $zip->getFromName("article-ratings.json")
    );

    $article_comments = json_decode(
        $zip->getFromName("article-comments.json")
    );

    $help_tickets = json_decode(
        $zip->getFromName("help-tickets.json")
    );

    $help_ticket_comments = json_decode(
        $zip->getFromName("help-ticket-comments.json")
    );

    // Close the ZIP archive and delete it.
    $zip->close();
    unlink($file_name);
}
