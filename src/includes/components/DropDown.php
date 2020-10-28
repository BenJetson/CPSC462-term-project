<?php

class DropDown implements Component
{
    private $label;
    private $id;
    private $name;

    private $options;
    private $required;
    private $defaultValue;

    public function __construct(
        $label,
        $id,
        $name,
        array $options,
        $required = false,
        $defaultValue = null
    ) {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->options = $options;
        $this->required = $required;
        $this->defaultValue = $defaultValue;
    }

    public function render()
    {
?>
        <label for="<?= $this->id ?>"><?= $this->label ?></label>
        <select id="<?= $this->id ?>" name="<?= $this->name ?>" class="form-control" <?= $this->required ? "required" : "" ?>>
            <?php if (is_null($this->defaultValue)) : ?>
                <option value="" selected>Choose...</option>
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
