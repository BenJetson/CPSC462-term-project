<?php

class DropDown implements Component
{
    private $label;
    private $id;
    private $name;

    private $options;
    private $defaultValue;

    public function __construct(
        $label,
        $id,
        $name,
        array $options,
        $defaultValue = null
    ) {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->options = $options;
        $this->defaultValue = $defaultValue;
    }

    public function render()
    {
?>
        <label for="<?= $this->id ?>"><?= $this->label ?></label>
        <select id="<?= $this->id ?>" name="<?= $this->name ?>" class="form-control">
            <?php if (is_null($this->defaultValue)) : ?>
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
