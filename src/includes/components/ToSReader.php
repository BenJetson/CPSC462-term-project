<?php

require_once "Component.php";

class ToSReader implements Component
{
    private $height;

    public function __construct($height)
    {
        $this->height = $height;
    }

    public function render()
    {
        $style = isset($this->height) ? "style=\"height: $this->height;\"" : "";
?>
        <?php if (!isset($this->height)) : ?>
            <div class="container">
            <?php endif; ?>
            <div class="card mb-3 bg-light">
                <div class="card-body overflow-auto" <?= $style ?>>
                    <?php echo file_get_contents(__DIR__ . "/../assets/tos.html"); ?>
                </div>
            </div>
            <?php if (!isset($this->height)) : ?>
            </div>
        <?php endif; ?>
<?php
    }

    public function injectScripts()
    {
    }
}
