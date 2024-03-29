<?php

require_once 'Component.php';
require_once __DIR__ . '/../types/Comment.php';

class CommentSection implements Component
{
    /** @var Comment[] */
    private $comments;
    /** @var string */
    private $title;

    public function __construct(array $comments, $title = "Comments")
    {
        $this->comments = $comments;
        $this->title = $title;
    }

    public function render()
    {
?>
        <div>
            <h2><?= $this->title ?></h2>
            <ul class="list-unstyled pt-3">
                <?php if (count($this->comments) === 0) : ?>
                    <li class="media pb-3">
                        <div class="media-body">No comments.</div>
                    </li>
                <?php endif; ?>
                <?php foreach ($this->comments as $comment) : ?>
                    <li class="media">
                        <span class="h3 avatar mr-2 mx-lg-3" aria-hidden="true">
                            <?= $comment->author_monogram ?>
                        </span>
                        <div class="media-body">
                            <p class="h5 mt-0 mb-1">
                                <?= $comment->author_name ?>
                            </p>
                            <p class="text-muted small mt-0 mb-1">
                                <?= $comment->posted_at->format("Y-m-d h:i:s a"); ?>
                            </p>
                            <p>
                                <?= nl2br($comment->body) ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
