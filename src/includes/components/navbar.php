<?php

require_once __DIR__ . "/../types/user.php";
require_once __DIR__ . "/../page.php";

class Navbar implements Component
{
    private $user;
    private $title;

    public function __construct($user, $title)
    {
        $this->user = $user;
        $this->title = $title;
    }

    public function render()
    {
?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <?php
                // FIXME need to send users to a homepage from this link
                ?>
                <a class="navbar-brand" href="#">IT Helpdesk</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navLinks" aria-controls="navLinks" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navLinks">
                    <ul class="navbar-nav w-100 justify-content-end mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <?php if ($this->user !== null) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar bg-secondary"><?= $this->user->first_name[0] . $this->user->last_name[0] ?></div>
                                    <?= $this->user->first_name . " " . $this->user->last_name ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="whoami.php">My Account</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <?php if ($this->title !== null) : ?>
            <div class="container py-4">
                <h1><?= $this->title ?></h1>
            </div>
        <?php endif; ?>
<?php
    }

    public function injectScripts()
    {
        // No scripts for this component. Must be present to satisfy interface.
    }
}
