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
                <?php foreach ($this->comments as $comment) : ?>
                    <li class="media">
                        <span class="h2 avatar mx-3" aria-hidden="true">
                            <?= $comment->author_monogram ?>
                        </span>
                        <div class="media-body">
                            <p class="h5 mt-0 mb-1">
                                <?= $comment->author_name // FIXME names escaping too
                                ?>
                            </p>
                            <p class="text-muted small mt-0 mb-1">
                                <?= $comment->posted_at->format("Y-m-d h:i:s a"); ?>
                            </p>
                            <p>
                                <?= $comment->body // FIXME escape this
                                ?>
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
