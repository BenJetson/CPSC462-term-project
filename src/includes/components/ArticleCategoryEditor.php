<?php

require_once __DIR__ . '/../types/ArticleCategory.php';
require_once __DIR__ . '/../forms/FormProcessor.php';
require_once __DIR__ . '/../forms/ArticleCategoryEditorFP.php';
require_once 'Component.php';

class ArticleCategoryEditor implements Component
{
    /** @var ArticleCategory */
    private $category;

    public function __construct(ArticleCategory $category)
    {
        $this->category = $category;
    }

    public function render()
    {
?>
        <div class="container">
            <form method="POST" action="article-category-editor.php" id="category-form" novalidate>
                <input type="hidden" name="<?= FormProcessor::OPERATION ?>" value="<?= ArticleCategoryEditorFP::OP_EDIT ?>" />
                <div class="row">
                    <div class="col-md-8">
                        <?php if ($this->category->article_category_id) : ?>
                            <input type="hidden" name="category_id" value="<?= $this->category->article_category_id ?>" />
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="categoryTitle">Title</label>
                            <input type="text" class="form-control form-control-lg font-weight-bold" autofocus name="title" id="categoryTitle" placeholder="Title" value="<?= $this->category->title ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="categoryIcon">Icon</label>
                            <input type="text" class="form-control text-monospace" autofocus name="icon" id="categoryIcon" placeholder="fa-icon-name-here" value="<?= $this->category->icon ?>" pattern="fa-[a-z\-]+" required />
                            <div class="invalid-feedback">
                                Icon name must be a valid Font Awesome icon name, all lowercase
                                with a leading <code>fa-</code>.
                                <br />For example, <code>fa-mobile-alt</code> is valid.
                            </div>
                            <p>
                                <small>
                                    You can see a list of all available icons and their associated names over at
                                    <a href="https://fontawesome.com/icons?d=gallery&m=free">Font Awesome</a>.
                                </small>
                            </p>
                        </div>
                        <div class="form-group">
                            <label for="categoryColor">Color</label>
                            <input type="text" class="form-control text-monospace" autofocus name="color" id="categoryColor" placeholder="Color" value="<?= $this->category->color ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="categoryDescr">Description</label>
                            <textarea class="form-control" rows="3" id="categoryDescr" placeholder="Write a description of the category here..." name="descr" required><?= $this->category->descr ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="h5">Preview</p>
                        <div class="card mb-3" id="previewCard">
                            <div class="card-body pb-1" id="previewCardHeader">
                                <p class="h5 card-title">
                                    <i id="previewIcon" class="fa <?= $this->category->icon ?>"></i>&nbsp;
                                    <span id="previewTitle"><?= $this->category->title ?></span>
                                </p>
                            </div>
                            <div class="card-body" id="previewBody" style="border-top-width: 3px; border-top-style: solid">
                                <p class="card-text" id="previewDescr"><?= $this->category->descr ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $btnText = $this->category->article_category_id ? "Update Category" : "Create Category"; ?>
                <button type="submit" class="btn btn-primary"><?= $btnText ?></button>
            </form>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script>
            window.addEventListener("load", () => {
                let title = document.getElementById("categoryTitle");
                let icon = document.getElementById("categoryIcon");
                let color = document.getElementById("categoryColor");
                let descr = document.getElementById("categoryDescr");

                let previewCard = document.getElementById("previewCard");
                let previewCardHeader = document.getElementById("previewCardHeader");
                let previewIcon = document.getElementById("previewIcon");
                let previewTitle = document.getElementById("previewTitle");
                let previewBody = document.getElementById("previewBody");
                let previewDescr = document.getElementById("previewDescr");

                let previewHandler = () => {
                    // Set the color to blank so that if an invalid color is
                    // given by the user, the last valid color will NOT persist.
                    previewCard.style.borderColor = "";
                    previewCardHeader.style.color = "";
                    previewBody.style.borderTopColor = "";

                    // Set the preview card colors.
                    previewCard.style.borderColor = color.value;
                    previewCardHeader.style.color = color.value;
                    previewBody.style.borderTopColor = color.value;

                    // Set the preview icon.
                    previewIcon.classList.remove(...previewIcon.classList);
                    previewIcon.classList.add("fa");
                    if (icon.value) previewIcon.classList.add(icon.value);

                    // Set preview text.
                    previewTitle.innerText = title.value;
                    previewDescr.innerText = descr.value;
                }

                previewHandler();

                // Add event listeners so that the preview will update when
                // any form field is modified.
                title.addEventListener("change", previewHandler);
                title.addEventListener("keyup", previewHandler);
                color.addEventListener("change", previewHandler);
                color.addEventListener("keyup", previewHandler);
                icon.addEventListener("change", previewHandler);
                icon.addEventListener("keyup", previewHandler);
                descr.addEventListener("change", previewHandler);
                descr.addEventListener("keyup", previewHandler);

                // Add event listener to form to display custom validation.
                let form = document.getElementById("category-form");
                form.addEventListener("submit", function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                });
            })
        </script>
<?php
    }
}
