<?php

require_once __DIR__ . "/../page.php";

class AuthStatus implements Component
{
    private $token;
    private $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    public function render()
    {
?>
        <div class="container">
            <?php if ($this->user !== null) : ?>
                <div class="jumbotron">
                    <h2 class="display-4">You are currently <strong>signed in</strong>.</h2>
                    <p class="lead">The access token presented is valid and was matched to a user.</p>
                    <p>
                        This access token was issued to:
                        <strong><?= $this->user->first_name . " " . $this->user->last_name . " (" . $this->user->email . ")" ?></strong>
                        at <?= $this->token->issued_at->format("Y-m-d H:i:s") ?> for <?= $this->token->ip ?>.
                    </p>
                    <hr class="my-4">
                    <p>To sign out, press the button below.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="logout.php" role="button">Log Out</a>
                    </p>
                </div>
            <?php elseif ($this->token !== null) : ?>
                <div class="jumbotron">
                    <h2 class="display-4">You are currently <strong>signed out</strong>.</h2>
                    <p class="lead">
                        The access token presented is valid but does not match any known user.
                    </p>
                    <p>Perhaps the account was disabled. Contact the system administrator for assistance.</p>
                    <hr class="my-4">
                    <p>To sign in, press the button below.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="login.php" role="button">Log In</a>
                    </p>
                </div>
            <?php else : ?>
                <div class="jumbotron">
                    <h2 class="display-4">You are currently <strong>signed out</strong>.</h2>
                    <p class="lead">Either no access token was presented or the access token was invalid.</p>
                    <p>This can happen if you left your session idle for some time or if you have never signed in on this computer before.</p>
                    <hr class="my-4">
                    <p>To sign in, press the button below.</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="login.php" role="button">Log In</a>
                    </p>
                </div>
            <?php endif; ?>
        </div>
<?php
    }

    public function injectScripts()
    {
        // No scripts for this component. Must be present to satisfy interface.
    }
}
