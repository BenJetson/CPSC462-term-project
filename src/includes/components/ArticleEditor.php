<?php

require_once __DIR__ . '/../types/Article.php';
require_once __DIR__ . '/../forms/FormProcessor.php';
require_once __DIR__ . '/../forms/ArticleEditorFP.php';
require_once 'Component.php';

class ArticleEditor implements Component
{
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
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
                    <input type="text" class="form-control form-control-lg font-weight-bold" autofocus name="title" id="articleTitle" placeholder="Title" value="<?= $this->article->title ?>" />
                </div>
                <div class="form-group">
                    <?php
                    // TODO use the drop down thing
                    // TODO remember the default
                    ?>
                    <label for="articleCat">Category</label>
                    <select id="articleCat" class="form-control" name="category_id">
                        <option selected>Choose...</option>
                        <option value="1">Cat 1</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="articleBody">Body</label>
                    <textarea class="form-control" rows="15" id="articleBody" placeholder="Write the article contents here..." name="body"><?= $this->article->body ?></textarea>
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
