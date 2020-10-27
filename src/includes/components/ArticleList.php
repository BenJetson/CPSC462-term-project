<?php

require_once 'Breadcrumb.php';
require_once 'Component.php';
require_once __DIR__ . '/../types/Article.php';
require_once __DIR__ . '/../types/ArticleCategory.php';
require_once __DIR__ . '/../types/User.php';

class ArticleList implements Component
{
    /** @var ArticleCategory */
    private $category;
    /** @var Article[] */
    private $articles;
    /** @var User */
    private $user;

    public function __construct(
        User $user,
        ArticleCategory $category,
        array $articles
    ) {
        $this->user = $user;
        $this->category = $category;
        $this->articles = $articles;
    }

    public function render()
    {
        $headerStyle = "color: " . $this->category->color . ";";
        $headerStyle .= "border-bottom: 5px solid " . $this->category->color;

?>
        <?php (new Breadcrumb([
            [
                "Knowledge Base",
                "kb-home.php"
            ],
            [
                $this->category->title,
                "article-list.php?category_id=" . $this->category->article_category_id
            ],
        ]))->render(); ?>
        <header class="mb-5 py-5" style="<?= $headerStyle ?>">
            <div class="container">
                <h1 class="display-3">
                    <i class="fa <?= $this->category->icon ?>"></i>&nbsp;
                    <?= $this->category->title ?>
                </h1>
            </div>
        </header>
        <div class="container">
            <?php $articleCount = count($this->articles); ?>
            <p>Found <?= $articleCount ?> article<?= $articleCount === 1 ? "" : "s" ?>.</p>
            <?php foreach ($this->articles as $article) : ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col-md">
                                <p class="h5 card-title mb-md-0"><?= $article->title ?></p>
                                <a href="article.php?article_id=<?= $article->article_id ?>" class="stretched-link"></a>
                            </div>
                            <?php if ($this->category->article_category_id !== $article->category_id) : ?>
                                <div class="col-auto pr-2 text-muted">
                                    <i class="fa fa-archive"></i>
                                    <?= $article->category_title ?>
                                </div>
                            <?php endif; ?>
                            <div class="col-auto pr-2 text-muted">
                                <i class="fa fa-calendar"></i>
                                <?= $article->updated_at->format("Y-m-d"); ?>
                            </div>
                            <div class="col-auto pr-2 text-muted">
                                <i class="fa fa-comments"></i>
                                0
                                <?php // FIXME need actual comment count
                                ?>
                            </div>
                            <div class="col-auto pr-2 text-muted">
                                <i class="fa fa-star"></i>
                                <?= $article->rating ?>
                            </div>
                            <?php if ($this->user->is_admin) : ?>
                                <div class="col-auto pr-2 text-muted">
                                    <a class="btn btn-sm btn-info" href="article-editor.php?article_id=<?= $article->article_id ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="col-auto pr-2 text-muted">
                                    <a class="btn btn-sm btn-danger" href="">
                                        <?php // FIXME href should be form maybe ?
                                        ?>
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
