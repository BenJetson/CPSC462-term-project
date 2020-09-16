<?php

require_once 'includes/db-connect.php';
require_once 'includes/login.php';

include 'includes/header.php';

$token = AccessToken::fetchFromCookie();

$user = null;
if ($token !== null) {
    $user = get_user_by_id($db, $token->user_id);
}

?>


<div class="container py-5">
    <h1>Authentication Status</h1>
    <?php if ($user !== null) : ?>
        <div class="jumbotron my-5">
            <h2 class="display-4">You are currently <strong>signed in</strong>.</h2>
            <p class="lead">The access token presented is valid and was matched to a user.</p>
            <p>
                This access token was issued to:
                <strong><?= "$user->first_name $user->last_name ($user->email)" ?></strong>
                at <?= $token->issued_at->format("Y-m-d H:i:s") ?> for <?= $token->ip ?>.
            </p>
            <hr class="my-4">
            <p>To sign out, press the button below.</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="logout.php" role="button">Log Out</a>
            </p>
        </div>
    <?php elseif ($token !== null) : ?>
        <div class="jumbotron my-5">
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
        <div class="jumbotron my-5">
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

include 'includes/footer.php';
