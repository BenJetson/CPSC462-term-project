<?php

require_once 'Component.php';

class Breadcrumb implements Component
{
    /** @var array[string]string */
    private $crumbs;

    public function __construct(array $crumbs)
    {
        foreach ($crumbs as $piece) {
            if (!is_array($piece) || count($piece) != 2) {
                throw new RuntimeException(
                    "breadcrumb contains invalid pieces"
                );
            }
        }

        $this->crumbs = $crumbs;
    }

    public function render()
    {
?>
        <div class="container my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php for ($idx = 0; $idx < count($this->crumbs); $idx++) : ?>
                        <?php $name = $this->crumbs[$idx][0] ?>
                        <?php $href = $this->crumbs[$idx][1] ?>
                        <?php $active = $idx === count($this->crumbs) - 1 ?>
                        <li class="breadcrumb-item <?= $active ? "active" : "" ?>">
                            <a href="<?= $href ?>"><?= $name ?></a>
                        </li>
                    <?php endfor; ?>
                </ol>
            </nav>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
