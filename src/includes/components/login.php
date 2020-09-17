<?php

require_once __DIR__ . "/../page.php";

class Login implements Component
{
    private $loginAttempted;
    private $grantStatus;
    private $isRemembered;
    private $rememberedEmail;

    public function __construct(
        $loginAttempted,
        $grantStatus,
        $isRemembered,
        $rememberedEmail
    ) {
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

            <?php if ($this->loginAttempted && !$this->grantStatus) : ?>
                <div class="alert alert-danger" role="alert">
                    The email address or password provided was incorrect.
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
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
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
    }
}
