<?php

require_once __DIR__ . "/../types/User.php";
require_once 'NoScriptWarning.php';
require_once 'Component.php';

class Navbar implements Component
{
    /** @var ?User */
    private $user;
    /** @var ?string */
    private $title;

    /**
     * @param ?User $user The user to display in the navbar.
     * @param ?string $title The title of the page to display below the navbar.
     */
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
                <a class="navbar-brand" href="index.php">🛠 IT Helpdesk</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navLinks" aria-controls="navLinks" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navLinks">
                    <ul class="navbar-nav w-100 justify-content-end mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="kb-home.php">Knowledge Base</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ticket-home.php">Help Tickets</a>
                        </li>
                        <?php if ($this->user->is_admin) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin-index.php">Administration</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->user === null) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar" aria-hidden="true">
                                        <?php if ($this->user->is_admin) : ?>
                                            <div class="avatar-crown">
                                                <i class="fa fa-crown"></i>
                                            </div>
                                        <?php endif; ?>
                                        <?= $this->user->monogram() ?></div>
                                    <?= $this->user->fullName() ?>
                                    <?php if ($this->user->is_admin) : ?>
                                        <span class="badge badge-warning badge-pill text-uppercase">Admin</span>
                                    <?php endif; ?>
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
        <?php (new NoScriptWarning())->render(); ?>
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
