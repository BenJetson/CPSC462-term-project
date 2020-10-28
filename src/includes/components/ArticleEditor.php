<?php

require_once __DIR__ . '/../types/Article.php';
require_once __DIR__ . '/../forms/FormProcessor.php';
require_once __DIR__ . '/../forms/ArticleEditorFP.php';
require_once 'Component.php';
require_once 'DropDown.php';

class ArticleEditor implements Component
{
    /** @var Article */
    private $article;
    /** @var array */
    private $categories;


    /**
     * @param Article $article
     * @param ArticleCategory[] $categories
     */
    public function __construct(Article $article, array $categories)
    {
        $this->article = $article;
        $this->categories = [];

        foreach ($categories as $category) {
            $this->categories[$category->article_category_id] = $category->title;
        }
    }

    public function render()
    {
?>
        <div class="container">
            <form method="POST" action="article-editor.php">
                <input type="hidden" name="<?= FormProcessor::OPERATION ?>" value="<?= ArticleEditorFP::OP_EDIT ?>" />
                <?php if ($this->article->article_id) : ?>
                    <input type="hidden" name="article_id" value="<?= $this->article->article_id ?>" />
                <?php endif; ?>
                <div class="form-group">
                    <label for="articleTitle">Title</label>
                    <input type="text" class="form-control form-control-lg font-weight-bold" autofocus name="title" id="articleTitle" placeholder="Title" value="<?= $this->article->title ?>" required />
                </div>
                <div class="form-group">
                    <?php $defaultCategory =
                        $this->article->category_id ?:
                        intval($_GET["category_id"]) ?: null ?>
                    <?php (new DropDown(
                        "Category",
                        "articleCat",
                        "category_id",
                        $this->categories,
                        true,
                        $defaultCategory
                    ))->render(); ?>
                </div>
                <div class="form-group">
                    <label for="articleBody">Body</label>
                    <textarea class="form-control" rows="15" id="articleBody" placeholder="Write the article contents here..." name="body" required><?= $this->article->body ?></textarea>
                </div>
                <?php $btnText = $this->article->article_id ? "Update Article" : "Create Article"; ?>
                <button type="submit" class="btn btn-primary"><?= $btnText ?></button>
            </form>
        </div>
<?php
    }

    public function injectScripts()
    {
        // TODO standard comment
    }
}
