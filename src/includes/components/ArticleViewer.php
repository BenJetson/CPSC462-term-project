<?php

require_once __DIR__ . '/../types/Article.php';
require_once 'Component.php';
require_once 'StarRating.php';

class ArticleViewer implements Component
{
    /** @var Article */
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
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
            <div>
                <h2>Comments</h2>
                <ul class="list-unstyled">
                    <li class="media">
                        <span class="h2 avatar mx-3">BG</span>
                        <div class="media-body">
                            <p class="h5 mt-0 mb-1">
                                Ben Godfrey
                            </p>
                            <p class="text-muted small mt-0 mb-1">
                                2020-03-04 11:43:23 PM
                            </p>
                            <p>
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                            </p>
                        </div>
                    </li>
                    <li class="media">
                        <span class="h2 avatar mx-3">BG</span>
                        <div class="media-body">
                            <p class="h5 mt-0 mb-1">
                                Ben Godfrey
                            </p>
                            <p class="text-muted small mt-0 mb-1">
                                2020-03-04 11:43:23 PM
                            </p>
                            <p>
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                                Some comment details
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
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
