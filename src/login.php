<?php

require_once('includes/header.php');
require_once 'includes/db-connect.php';
require_once 'includes/login.php';

define("REMEMBER_ME_COOKIE", "remember-me-email");

$loginAttempted = isset($_POST["email"]) && isset($_POST["password"]);
$grantStatus = false;

if ($loginAttempted) {
    $grantStatus = password_grant(
        $db,
        $_POST["email"],
        $_POST["password"]
    );

    $rememberMeEmail = "";
    if ($grantStatus && isset($_POST["remember-me"]) && $_POST["remember-me"] === "on") {
        $rememberMeEmail = $_POST["email"];
    }

    setcookie(REMEMBER_ME_COOKIE, $rememberMeEmail, 0, "", "", false, true);
    $_COOKIE[REMEMBER_ME_COOKIE] = $rememberMeEmail; // FIXME

    if ($grantStatus) {
        header('Location: whoami.php');
        exit;
    }
}

?>

<div class="container py-5" id="login-container">
    <h1 class="text-center">Login</h1>
    <p class="py-4 text-center"><em>Authentication is required to proceed.</em></p>

    <?php if ($loginAttempted && !$grantStatus) : ?>
        <div class="alert alert-danger" role="alert">
            The email address or password provided was incorrect.
        </div>
    <?php endif; ?>

    <form method="POST" action="" id="login-form" novalidate>
        <div class="form-group">
            <label for="user-email">Email</label>
            <input type="email" class="form-control" id="user-email" name="email" value="<?= $_COOKIE[REMEMBER_ME_COOKIE] ?>" required />
            <div class="invalid-feedback">
                Please enter your email address.
            </div>
        </div>
        <div class="form-group">
            <label for="user-password">Password</label>
            <input type="password" class="form-control" id="user-password" name="password" required />
            <div class="invalid-feedback">
                Password cannot be blank.
            </div>
        </div>
        <div class="form-group custom-control custom-switch">
            <input class="custom-control-input" type="checkbox" id="remember-me" name="remember-me" <?= isset($_COOKIE[REMEMBER_ME_COOKIE]) && $_COOKIE[REMEMBER_ME_COOKIE] !== "" ? "checked" : "" ?> />
            <label class="custom-control-label" for="remember-me">Remember my email address.</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    window.addEventListener("load", function() {
        let form = document.getElementById("login-form");
        form.addEventListener("submit", function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        })
    })
</script>

<?php
require_once('includes/footer.php');
