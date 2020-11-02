<?php

require_once __DIR__ . '/../components/Component.php';

class Page
{
    private $components;
    private $title;

    public function __construct($title, $components)
    {
        $this->title = $title;
        $this->components = $components !== null ? $components : [];
    }

    public function append(Component $component)
    {
        array_push($components, $component);
    }

    public function render()
    {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title><?= $this->title ?> - IT Helpdesk</title>

            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

            <link rel="icon" type="image/x-icon" href="assets/icons/favicon.ico" />
            <link rel="apple-touch-icon" sizes="180x180" href="assets/icons/apple-touch-icon.png" />
            <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/favicon-32x32.png" />
            <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/favicon-16x16.png" />
            <link rel="manifest" href="assets/site.webmanifest" />

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
            <link rel="stylesheet" href="assets/style.css" />
        </head>

        <body>
            <?php
            foreach ($this->components as &$component) {
                $component->render();
            }
            ?>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
            <script src="https://kit.fontawesome.com/e22b1412b7.js" crossorigin="anonymous"></script>
            <?php
            foreach ($this->components as &$component) {
                $component->injectScripts();
            }
            ?>
        </body>

        </html>
<?php
    }
}
