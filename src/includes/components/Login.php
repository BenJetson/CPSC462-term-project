<?php

require_once 'Component.php';

class Login implements Component
{
    private $wasLoggedOut;
    private $loginAttempted;
    private $grantStatus;
    private $isRemembered;
    private $rememberedEmail;

    public function __construct(
        $wasLoggedOut,
        $loginAttempted,
        $grantStatus,
        $isRemembered,
        $rememberedEmail
    ) {
        $this->wasLoggedOut = $wasLoggedOut;
        $this->loginAttempted = $loginAttempted;
        $this->grantStatus = $grantStatus;
        $this->isRemembered = $isRemembered;
        $this->rememberedEmail = $rememberedEmail;
    }

    public function render()
    {
?>
        <div class="container py-5" id="login-container">
            <h1 class="text-center">Login</h1>
            <p class="py-4 text-center"><em>Authentication is required to proceed.</em></p>

            <?php if ($this->wasLoggedOut) : ?>
                <div class="alert alert-warning" role="alert">
                    <p class="h4">Attention</p>
                    <p class="mb-0">
                        Your previous session was signed out because you
                        returned to the login page.
                    </p>
                </div>
            <?php endif; ?>

            <?php if ($this->loginAttempted && !$this->grantStatus->granted) : ?>
                <div class="alert alert-danger" role="alert">
                    <p class="h4">Access Denied</p>
                    <p class="mb-0">
                        <?= $this->grantStatus->reason ?>
                    </p>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="login-form" novalidate>
                <div class="form-group">
                    <label for="user-email">Email</label>
                    <input type="email" class="form-control" id="user-email" name="email" value="<?= $this->rememberedEmail ?>" required <?= !$this->isRemembered ? "autofocus" : "" ?> />
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>
                </div>
                <div class="form-group">
                    <label for="user-password">Password</label>
                    <input type="password" class="form-control" id="user-password" name="password" required <?= $this->isRemembered ? "autofocus" : "" ?> />
                    <div class="invalid-feedback">
                        Password cannot be blank.
                    </div>
                </div>
                <div class="form-group custom-control custom-switch">
                    <input class="custom-control-input" type="checkbox" id="remember-me" name="remember-me" <?= $this->isRemembered ? "checked" : "" ?> />
                    <label class="custom-control-label" for="remember-me">Remember my email address.</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <p class="py-3">
                Don't have an account? <a href="register.php">Register</a>.
            </p>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script>
            window.addEventListener("load", function() {
                // Add event listener to form to display custom validation.
                let form = document.getElementById("login-form");
                form.addEventListener("submit", function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                });
            });
        </script>
<?php
    }
}
