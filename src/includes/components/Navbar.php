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
                <?php $label = $_SERVER["TIER"] !== "prod" ? ", " . $_SERVER["TIER"] . " tier" : "" ?>
                <a class="navbar-brand" href="index.php" aria-label="IT Helpdesk<?= $label ?>">
                    🛠 IT Helpdesk
                    <?php if ($_SERVER["TIER"] !== "prod") : ?>
                        <small>
                            <span class="badge badge-pill badge-warning">
                                <?= strtoupper($_SERVER["TIER"]) ?>
                            </span>
                        </small>
                    <?php endif; ?>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navLinks" aria-controls="navLinks" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navLinks">
                    <ul class="navbar-nav w-100 justify-content-end mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="fa fa-home"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="article-index.php" aria-label="Knowledge Base">
                                <i class="fa fa-book"></i>
                                <span class="d-none d-lg-inline d-xl-none">KB</span>
                                <span class="d-lg-none d-xl-inline">Knowledge Base</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="ticket-index.php" aria-label="Help Tickets">
                                <i class="fa fa-life-ring"></i>
                                <span class="d-none d-lg-inline d-xl-none">Tickets</span>
                                <span class="d-lg-none d-xl-inline">Help Tickets</span>
                            </a>
                        </li>
                        <?php if ($this->user->is_admin) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin-index.php" aria-label="Administration">
                                    <i class="fa fa-tools"></i>
                                    <span class="d-none d-lg-inline d-xl-none">Admin</span>
                                    <span class="d-lg-none d-xl-inline">Administration</span>
                                </a>
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
                                    <a class="dropdown-item" href="profile-editor.php">
                                        <i class="fa fa-user"></i>
                                        My Profile
                                    </a>
                                    <a class="dropdown-item" href="change-password.php">
                                        <i class="fa fa-key"></i>
                                        Change Password
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="whoami.php">
                                        <i class="fa fa-info-circle"></i>
                                        Session Info
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">
                                        <i class="fa fa-sign-out-alt"></i>
                                        Logout
                                    </a>
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
