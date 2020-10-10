<?php

abstract class DropDown implements Component
{
    private $label;
    private $id;
    private $name;

    protected $options;
    protected $defaultValue;

    public function __construct($label, $id, $name)
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
    }

    public function render()
    {
?>
        <label for="<?= $this->id ?>"><?= $this->label ?></label>
        <select id="<?= $this->id ?>" name="<?= $this->name ?>" class="form-control">
            <?php if (!isset($this->defaultValue)) : ?>
                <option selected>Choose...</option>
            <?php endif; ?>
            <?php foreach ($this->options as $value => $description) : ?>
                <?php $isDefault = $value === $this->defaultValue; ?>
                <?php $selected = $isDefault ? "selected" : ""; ?>
                <option value="<?= $value ?>" <?= $selected ?>>
                    <?= $description ?>
                </option>
            <?php endforeach; ?>
        </select>
<?php
    }

    public function injectScripts()
    {
        // FIXME add the standard comment here
    }
}
