<?php

require_once __DIR__ . '/../types/User.php';
require_once 'Component.php';

class Homepage implements Component
{
    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
?>
        <div class="container py-5">
            <h1>Welcome, <?= $this->user->fullName() ?></h1>
        </div>
        <div class="container">
            <p class="lead">What would you like to do?</p>
            <div class="card-deck">
                <div class="card py-5 px-3 bg-info text-light">
                    <a href="kb-home.php" class="stretched-link"></a>
                    <div class="card-text text-center display-1">
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="card-title text-center h2">
                        Knowledge Base
                    </div>
                    <p class="card-text lead text-center">
                        Get answers fast by browsing our vast
                        catalogue of knowledge.
                    </p>
                </div>
                <div class="card py-5 px-3 bg-secondary text-light">
                    <a href="ticket-home.php" class="stretched-link"></a>
                    <div class="card-text text-center display-1">
                        <i class="fa fa-life-ring"></i>
                    </div>
                    <div class="card-title text-center h2">
                        Help Tickets
                    </div>
                    <p class="card-text lead text-center">
                        Request individual assistance from our staff to solve
                        your problem.
                    </p>
                </div>
            </div>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
