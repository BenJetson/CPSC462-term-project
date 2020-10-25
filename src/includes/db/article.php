<?php

require_once __DIR__ . "/../types/Article.php";
require_once __DIR__ . "/../types/ArticleCategory.php";
require_once 'comment.php';


// TODO need to fetch comments as well


define("GET_ARTICLE_CATEGORY_QUERY", "
    SELECT
        article_category_id,
        title
    FROM article_category
");

function get_article_categories(PDO $pdo)
{
    $stmt = $pdo->prepare(GET_ARTICLE_CATEGORY_QUERY);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_CLASS, "ArticleCategory");

    if (count($categories) === 0) {
        return array();
    }
    return $categories;
}

function get_article_category_by_id(PDO $pdo, $category_id)
{
    $stmt = $pdo->prepare(GET_ARTICLE_CATEGORY_QUERY . "
        WHERE article_category_id = :category_id
    ");

    $stmt->bindParam(":category_id", $category_id);

    $stmt->execute();

    return $stmt->fetchObject("ArticleCategory");
}

function create_article_category(PDO $pdo, ArticleCategory $category)
{
    $stmt = $pdo->prepare("
        INSERT INTO article_category (
            title
        ) VALUES (
            :title
        )
    ");

    $stmt->bindParam(":title", $category->title);

    $stmt->execute();
}

function update_article_category(PDO $pdo, ArticleCategory $category)
{
    $stmt = $pdo->prepare("
        UPDATE article_category
        SET title = :title
        WHERE article_category_id = :category_id
    ");

    $stmt->bindParam(":title", $category->title);
    $stmt->bindParam(":category_id", $category->article_category_id);

    $stmt->execute();
}

function delete_article_category(PDO $pdo, $category_id)
{
    $stmt = $pdo->prepare("
        DELETE FROM article_category
        WHERE article_category_id = :category_id
    ");

    $stmt->bindParam(":category_id", $category_id);

    $stmt->execute();
}


define("GET_ARTICLE_QUERY", "
    SELECT
        a.article_id,
        c.article_category_id AS category_id,
        c.title AS category_title,
        u.user_id AS author_id,
        build_full_name(u.first_name, u.last_name) AS author_name,
        a.title,
        a.body,
        a.created_at,
        a.updated_at,
        ROUND(AVG(ar.stars)) AS rating,
        COUNT(ar.stars) AS rating_count
    FROM article a
    INNER JOIN article_category c
        ON a.article_category_id = c.article_category_id
    INNER JOIN user u
        ON a.author = u.user_id
    LEFT JOIN article_rating ar
        ON a.article_id = ar.article_id
    GROUP BY a.article_id
");

function get_article_by_id(PDO $pdo, $article_id)
{
    $stmt = $pdo->prepare(GET_ARTICLE_QUERY . "
        HAVING a.article_id = :article_id
    ");

    $stmt->bindParam(":article_id", $article_id);

    $stmt->execute();

    return $stmt->fetchObject("Article");
}

function get_all_articles(PDO $pdo)
{
    $stmt = $pdo->prepare(GET_ARTICLE_QUERY);

    $stmt->execute();

    $articles = $stmt->fetchAll(PDO::FETCH_CLASS, "Article");

    if (count($articles) === 0) {
        return array();
    }
    return $articles;
}

function get_articles_in_category(PDO $pdo, $category_id)
{
    $stmt = $pdo->prepare(GET_ARTICLE_QUERY . "
        HAVING c.category_id = :category_id
    ");

    $stmt->bindParam(":category_id", $category_id);

    $stmt->execute();

    $articles = $stmt->fetchAll(PDO::FETCH_CLASS, "Article");

    if (count($articles) === 0) {
        return array();
    }
    return $articles;
}


function create_article(PDO $pdo, Article $article)
{
    $stmt = $pdo->prepare("
        INSERT INTO article (
            article_category_id,
            author,
            title,
            body
        ) VALUES (
            :article_category_id,
            :author,
            :title,
            :body
        )
    ");

    $stmt->bindParam(":article_category_id", $article->article_category_id);
    $stmt->bindParam(":author", $article->author);
    $stmt->bindParam(":title", $article->title);
    $stmt->bindParam(":body", $article->body);

    $stmt->execute();
}

function update_article(PDO $pdo, Article $article)
{
    $stmt = $pdo->prepare("
        UPDATE article
        SET
            article_category_id = :article_category_id,
            author = :author,
            title = :title,
            body = :body
        WHERE article_id = :article_id
    ");

    $stmt->bindParam(":article_category_id", $article->article_category_id);
    $stmt->bindParam(":author", $article->author);
    $stmt->bindParam(":title", $article->title);
    $stmt->bindParam(":body", $article->body);
    $stmt->bindParam(":article_id", $article->article_id);

    $stmt->execute();
}

function delete_article(PDO $pdo, $article_id)
{
    $stmt = $pdo->prepare("
        DELETE FROM article
        WHERE article_id = :article_id
    ");

    $stmt->bindParam(":article_id", $article_id);

    $stmt->execute();
}

function set_article_rating(PDO $pdo, User $user, $article_id, $stars)
{
    $stmt = $pdo->prepare("
        INSERT INTO article_rating (
            user_id,
            article_id,
            stars
        ) VALUES (
            :user_id,
            :article_id,
            :stars_1
        )
        ON DUPLICATE KEY
        UPDATE stars = :stars_2
    ");

    $stmt->bindParam(":user_id", $user->user_id);
    $stmt->bindParam(":article_id", $article_id);
    $stmt->bindParam(":stars_1", $stars);
    $stmt->bindParam(":stars_2", $stars);

    $stmt->execute();
}

function create_article_comment(PDO $pdo, $article_id, Comment $comment)
{
    $comment_id = create_comment($pdo, $comment);

    $stmt = $pdo->prepare("
        INSERT INTO article_comment (
            article_id,
            comment_id
        VALUES (
            :article_id,
            :comment_id
        )
    ");

    $stmt->bindParam(":article_id", $article_id);
    $stmt->bindParam(":comment_id", $comment_id);

    $stmt->execute();
}
