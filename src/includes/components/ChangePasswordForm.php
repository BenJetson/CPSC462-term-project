<?php

require_once 'Component.php';
require_once 'NewPasswordInput.php';
require_once __DIR__ . '/../forms/FormProcessor.php';
require_once __DIR__ . '/../forms/ChangePasswordFP.php';

class ChangePasswordForm implements Component
{
    private $pass_input;

    public function __construct()
    {
        $this->pass_input = new NewPasswordInput();
    }

    public function render()
    {
?>
        <div class="container mb-5">
            <form action="change-password.php" method="POST" id="changePasswordForm">
                <input type="hidden" name="<?= FormProcessor::OPERATION ?>" value="<?= ChangePasswordFP::OP_CHANGE_PASS ?>">
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="h3">
                            <i class="fa fa-lock mr-2"></i>
                            Authorization
                        </p>
                        <p>
                            For your protection, you are required to enter your
                            current password to authorize a password change.
                        </p>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_password" required />
                                <div class="invalid-feedback">Must supply current password.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="h3">
                            <i class="fa fa-key mr-2"></i>
                            New Password
                        </p>
                        <p>
                            This will be your new login password.
                        </p>

                        <?php $this->pass_input->render(); ?>
                    </div>
                </div>
                <p>
                    <i class="fa fa-info-circle"></i>
                    Upon a successful password change, all of your previous
                    login sessions will expire immediately.
                </p>
                <p>
                    <i class="fa fa-info-circle"></i>
                    You will be returned to the login page to reauthenticate.
                </p>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    <?php
    }

    public function injectScripts()
    {
        $this->pass_input->injectScripts();

    ?>
        <script>
            window.addEventListener("load", function() {
                let form = document.getElementById("changePasswordForm");

                // Since we are using custom validation, we must disable the
                // browser's default validation that happens before submit.
                form.setAttribute("novalidate", true);

                // Add event listener to form to display custom validation.
                form.addEventListener("submit", (event) => {
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
