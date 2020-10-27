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

            <article>
                <?php foreach (explode("\n", $this->article->body) as $paragraph) : ?>
                    <?php if (!empty($paragraph)) : ?>
                        <p><?= $paragraph ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </article>
        </div>

        <div class="container my-3">
            <h2>Rate this Article</h2>
            <form method="POST" action="article.php">
                <input type="hidden" name="op" value="rate" />
                <input type="hidden" name="article_id" value="<?= $this->article->article_id ?>" />
                <div class="row pt-2 no-gutters">
                    <div class="col mx-1">
                        <button class="btn btn-sm btn-block btn-outline-warning" type="submit" value="1" name="stars" aria-label="1 star">
                            <span class="d-lg-none">
                                <span class="text-dark">1</span>
                                <i class="fa fa-star"></i>
                            </span>
                            <span class="d-none d-lg-inline">
                                <i class="fa fa-star"></i>
                                <br>
                                <span class="badge text-dark text-uppercase">1 star</span>
                            </span>
                        </button>
                    </div>
                    <div class="col mx-1">
                        <button class="btn btn-sm btn-block btn-outline-warning" type="submit" value="2" name="stars" aria-label="2 stars">
                            <span class="d-lg-none">
                                <span class="text-dark">2</span>
                                <i class="fa fa-star"></i>
                            </span>
                            <span class="d-none d-lg-inline">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <br>
                                <span class="badge text-dark text-uppercase">2 stars</span>
                            </span>
                        </button>
                    </div>
                    <div class="col mx-1">
                        <button class="btn btn-sm btn-block btn-outline-warning" type="submit" value="3" name="stars" aria-label="3 stars">
                            <span class="d-lg-none">
                                <span class="text-dark">3</span>
                                <i class="fa fa-star"></i>
                            </span>
                            <span class="d-none d-lg-inline">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <br>
                                <span class="badge text-dark text-uppercase">3 stars</span>
                            </span>
                        </button>
                    </div>
                    <div class="col mx-1">
                        <button class="btn btn-sm btn-block btn-outline-warning" type="submit" value="4" name="stars" aria-label="4 stars">
                            <span class="d-lg-none">
                                <span class="text-dark">4</span>
                                <i class="fa fa-star"></i>
                            </span>
                            <span class="d-none d-lg-inline">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <br>
                                <span class="badge text-dark text-uppercase">4 stars</span>
                            </span>
                        </button>
                    </div>
                    <div class="col mx-1">
                        <button class="btn btn-sm btn-block btn-outline-warning" type="submit" value="5" name="stars" aria-label="5 stars">
                            <span class="d-lg-none">
                                <span class="text-dark">5</span>
                                <i class="fa fa-star"></i>
                            </span>
                            <span class="d-none d-lg-inline">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <br>
                                <span class="badge text-dark text-uppercase">5 stars</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="container my-3">
            <?php (new CommentSection($this->comments))->render(); ?>
        </div>

        <div class="container">
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
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
