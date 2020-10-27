<?php

require_once 'Component.php';

class ArticleCategoryList implements Component
{
    /** @var ArticleCategory[] */
    private $categories;
    /** @var User */
    private $user;

    public function __construct(User $user, array $categories)
    {
        $this->user = $user;
        $this->categories = $categories;
    }

    public function render()
    {
?>
        <div class="container">
            <?php if ($this->user->is_admin) : ?>
                <div class="mb-4 d-flex justify-content-end">
                    <a class="btn btn-primary" href="article-editor.php">
                        <i class="fa fa-pencil"></i>
                        New Article
                    </a>
                </div>
            <?php endif; ?>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4">
                <?php foreach ($this->categories as $category) : ?>
                    <div class="col mb-4">
                        <div class="card" style="border-color: <?= $category->color ?>">
                            <div class="card-body pb-1" style="color: <?= $category->color ?>">
                                <p class="h5 card-title">
                                    <i class="fa <?= $category->icon ?>"></i>&nbsp;
                                    <?= $category->title ?>
                                </p>
                            </div>
                            <div class="card-body" style="border-top: 3px solid <?= $category->color ?>">
                                <p class="card-text"><?= $category->descr ?></p>
                            </div>
                            <a class="stretched-link" href="<?= "article-list.php?category_id=$category->article_category_id" ?>"></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
