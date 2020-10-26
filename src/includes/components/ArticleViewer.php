<?php

require_once __DIR__ . '/../types/Article.php';
require_once 'Component.php';
require_once 'StarRating.php';
require_once 'CommentSection.php';

class ArticleViewer implements Component
{
    /** @var Article */
    private $article;
    /** @var Comment[] */
    private $comments;

    public function __construct(Article $article, array $comments)
    {
        $this->article = $article;
        $this->comments = $comments;
    }

    public function render()
    {
?>
        <div class="container">
            <p class="lead mb-4">
                Last updated on
                <?= $this->article->updated_at->format("Y-m-d h:i:s a") ?>
                by <?= $this->article->author_name ?>.
                <br>
                Rating: <?php (new StarRating($this->article->rating, $this->article->rating_count))->render(); ?>
            </p>

            <?php foreach (explode("\n", $this->article->body) as $paragraph) : ?>
                <?php if (!empty($paragraph)) : ?>
                    <p><?= $paragraph ?></p>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="border-top" role="separator">
                <h2>Rate this Article</h2>
                <form method="POST" action="article.php">
                    <input type="hidden" name="op" value="rate" />
                    <input type="hidden" name="article_id" value="<?= $this->article->article_id ?>" />
                    <span>Rating:</span>
                    <input type="range" min="1" max="5" name="stars" class="custom-range" />
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <?php (new CommentSection($this->comments))->render(); ?>
            <div>
                <h2>Write a comment</h2>
                <form method="POST" action="article.php">
                    <input type="hidden" name="op" value="comment" />
                    <input type="hidden" name="article_id" value="<?= $this->article->article_id ?>" />
                    <div class="form-group">
                        <label for="commentInput" class="sr-only">Comment</label>
                        <textarea name="comment" id="commentInput" rows="3" class="form-control" placeholder="Comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
