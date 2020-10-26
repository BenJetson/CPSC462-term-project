<?php

require_once 'Component.php';

class ArticleCategoryList implements Component
{
    /** @var ArticleCategory[] */
    private $categories;

    public function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    public function render()
    {
?>
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4">
                <?php foreach ($this->categories as $category) : ?>
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-body">
                                <p class="h5 card-title" style="color: <?= $category->color ?>"><i class="fa <?= $category->icon ?>"></i>&nbsp;<?= $category->title ?></p>
                                <p class="card-text"><?= $category->descr ?></p>
                                <a class="stretched-link" href="<?= "article-list.php?category_id=$category->article_category_id" ?>"></a>
                            </div>
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
