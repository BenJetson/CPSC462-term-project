<?php

class DropDown implements Component
{
    /** @var ?string */
    private $label;
    /** @var string */
    private $id;
    /** @var name */
    private $name;

    /** @var array[mixed]string */
    private $options;
    /** @var bool */
    private $required;
    /** @var mixed */
    private $defaultValue;

    /**
     * Constructs a new DropDown object.
     *
     * @param ?string $label The human readable label to display above the select
     *     input, or null if no label should be displayed.
     * @param string $id the HTML DOM identifier for this select.
     * @param string $name the name to assign to this select's value in the POST
     * @param array[mixed]string $options an associative array from the value
     *     of a particular select item to its description.
     * @param bool $required whether or not this select is required on the form.
     * @param ?mixed $defaultValue the value strictly equal to the option that
     *     should be selected when the page is loaded, or null for no default.
     *     If no default is specified, a "Choose..." default with no value
     *     is provided.
     */
    public function __construct(
        $label,
        $id,
        $name,
        $options,
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
        <?php if (!is_null($this->label)) : ?>
            <label for="<?= $this->id ?>"><?= $this->label ?></label>
        <?php endif; ?>
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
