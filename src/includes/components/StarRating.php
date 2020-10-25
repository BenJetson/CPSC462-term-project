<?php

require_once 'Component.php';

class StarRating implements Component
{
    /** @var int */
    private $rating;
    /** @var int */
    private $count;

    public function __construct($rating, $count)
    {
        $this->rating = intval($rating);
        $this->count = intval($count);
    }

    public function render()
    {
?>
        <span class="star-rating">
            <?php for ($i = 0; $i < 5; $i++) : ?>
                <span class="star <?= $i < $this->rating ? "star-bright" : "" ?>">&starf;</span>
            <?php endfor; ?>
            <span class="star-rating-desc small">
                <small>
                    (<?= $this->rating ?> stars, <?= $this->count ?> ratings)
                </small>
            </span>
        </span>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
