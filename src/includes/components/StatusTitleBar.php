<?php

require_once 'Component.php';
require_once __DIR__ . '/../types/HTTPStatus.php';


class StatusTitleBar implements Component
{
    private $statusCode;
    private $title;

    public function __construct($statusCode, $title)
    {
        $this->statusCode = $statusCode;
        $this->title = $title;
    }

    public function render()
    {
?>
        <div class="container py-4">
            <h1>
                <?php $badgeType = HTTPStatus::isError($this->statusCode) ? "danger" : "success" ?>
                <span class="text-monospace badge badge-<?= $badgeType ?> mr-2">
                    <?= $this->statusCode ?>
                </span>
                <?= $this->title ?>
            </h1>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
