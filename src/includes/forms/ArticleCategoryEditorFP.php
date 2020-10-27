<?php

require_once 'FormProcessor.php';
require_once __DIR__ . "/../db/article.php";
require_once __DIR__ . "/../types/User.php";

class ArticleCategoryEditorFP extends FormProcessor
{
    const OP_EDIT = "article-category-edit";
}
