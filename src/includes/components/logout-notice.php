<?php

require_once __DIR__ . "/../page.php";

class LogoutNotice implements Component
{
    public function render()
    {
?>
        <div class="container">
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading">Success</h4>
                You have been signed out of the system and your session is
                now closed.
            </div>
        </div>
        <div class="container my-4">
            <p>
                We look forward to serving you again soon.
            </p>
            <p>
                If you would like to start a new session, please sign in using
                the link below.
            </p>
            <p class="py-2">
                <a class="btn btn-primary btn-lg" href="login.php" role="button">Log In</a>
            </p>
        </div>
<?php
    }

    public function injectScripts()
    {
        // No scripts for this component. Must be present to satisfy interface.
    }
}
