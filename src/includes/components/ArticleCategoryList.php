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
                    <a class="btn mr-1 btn-secondary" href="article-list.php">
                        <i class="fa fa-list"></i>
                        All Articles
                    </a>
                    <a class="btn mr-1 btn-info" href="article-category-editor.php">
                        <i class="fa fa-archive"></i>
                        New Category
                    </a>
                    <a class="btn btn-info" href="article-editor.php">
                        <i class="fa fa-file-alt"></i>
                        New Article
                    </a>
                </div>
            <?php endif; ?>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4">
                <?php foreach ($this->categories as $category) : ?>
                    <div class="col mb-4">
                        <div class="card" style="border-color: <?= $category->color ?>">
                            <div class="card-body position-relative pb-1" style="color: <?= $category->color ?>">
                                <p class="h5 card-title">
                                    <i class="fa <?= $category->icon ?>"></i>&nbsp;
                                    <?= $category->title ?>
                                </p>
                                <a class="stretched-link" href="<?= "article-list.php?category_id=$category->article_category_id" ?>"></a>
                            </div>
                            <div class="card-body position-relative" style="border-top: 3px solid <?= $category->color ?>">
                                <p class="card-text mb-0"><?= $category->descr ?></p>
                                <a class="stretched-link" href="<?= "article-list.php?category_id=$category->article_category_id" ?>"></a>
                            </div>
                            <?php if ($this->user->is_admin) : ?>
                                <div class="card-body pt-0 d-flex justify-content-end">
                                    <a class="btn btn-sm btn-info mr-1" href="article-category-editor.php?category_id=<?= $category->article_category_id ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="">
                                        <?php // FIXME href should be form maybe ?
                                        ?>
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
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
