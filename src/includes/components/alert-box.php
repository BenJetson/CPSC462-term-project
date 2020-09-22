<?php

require_once __DIR__ . "/../page.php";

class AlertBox implements Component
{
    const TYPE_INFO = "info";
    const TYPE_WARNING = "warning";
    const TYPE_DANGER = "danger";

    private $type;

    private $title;
    private $message;

    public function __construct($type, $title, $message)
    {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
    }

    public function render()
    {
?>
        <div class="container">
            <div class="alert alert-<?= $this->type ?>">
                <?php if (isset($this->title)) : ?>
                    <p class="h4"><?= $this->title ?></p>
                <?php endif; ?>
                <p class="mb-0"><?= $this->message ?></p>
            </div>
        </div>
<?php
    }

    public function injectScripts()
    {
        // No scripts for this component. Must be present to satisfy interface.
    }
}
