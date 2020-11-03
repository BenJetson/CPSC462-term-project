<?php

require_once 'Component.php';
require_once 'PasswordMeter.php';

class NewPasswordInput implements Component
{
    /** @var PasswordMeter */
    private $passMeter;

    public function __construct()
    {
        $this->passMeter = new PasswordMeter();
    }

    public function render()
    {
?>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
                <div class="invalid-feedback">
                    Must supply a password of at least acceptable strength with
                    at least one uppercase letter.
                </div>
            </div>
            <div class="form-group col-md-6">
                <?php $this->passMeter->render(); ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required />
                <div class="invalid-feedback">Passwords do not match.</div>
            </div>
        </div>
    <?php
    }

    public function injectScripts()
    {
        $this->passMeter->injectScripts();

    ?>
        <script>
            window.addEventListener("load", () => {
                let passField = document.getElementById("password");
                let confirmField = document.getElementById("confirmPassword");

                let checkMatch = (event) => {
                    let validityMsg = "";
                    if (passField.value !== confirmField.value) {
                        validityMsg = "Passwords do not match.";
                    }

                    confirmField.setCustomValidity(validityMsg);
                }

                passField.addEventListener("change", checkMatch);
                confirmField.addEventListener("change", checkMatch);
                passField.addEventListener("keyup", checkMatch);
                confirmField.addEventListener("keyup", checkMatch);
            });
        </script>
<?php
    }
}
