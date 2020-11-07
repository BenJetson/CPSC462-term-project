<?php

require_once 'Component.php';
require_once 'DropDown.php';
require_once 'NewPasswordInput.php';
require_once 'ToSReader.php';
require_once __DIR__ . '/../forms/FormProcessor.php';
require_once __DIR__ . '/../types/User.php';

class UserProfileForm implements Component
{
    private $action;
    private $operation;

    /** @var ?User */
    private $user;

    private $isRegistration;
    private $passInput;

    public function __construct($action, $operation, $user)
    {
        $this->action = $action;
        $this->operation = $operation;

        $this->user = $user;

        $this->isRegistration = !isset($this->user)
            || !isset($this->user->user_id);
        $this->passInput = $this->isRegistration ? new NewPasswordInput() : null;
    }

    public function render()
    {
?>
        <div class="container mb-5">
            <form action="<?= $this->action ?>" method="POST" id="profile-form">
                <input type="hidden" name="<?= FormProcessor::OPERATION ?>" value="<?= $this->operation ?>" />
                <?php if (!$this->isRegistration) : ?>
                    <input type="hidden" name="user_id" value="<?= $this->user->user_id ?>" />
                <?php endif; ?>
                <p class="h3">Personal Info</p>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="first-name">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first_name" value="<?= $this->user ? $this->user->first_name : "" ?>" required />
                        <div class="invalid-feedback">Must supply a first name.</div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last-name">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last_name" value="<?= $this->user ? $this->user->last_name : "" ?>" required />
                        <div class="invalid-feedback">Must supply a last name.</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $this->user ? $this->user->email : "" ?>" required />
                        <div class="invalid-feedback">Must supply a valid email address.</div>
                        <small class="form-text text-muted">
                            A message will be sent to this address to
                            confirm. Email must be confirmed before you can
                            comment or send help tickets.
                        </small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Telephone</label>
                        <input type="text" class="form-control" id="phone" name="telephone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?= $this->user ? $this->user->telephone : "" ?>" required />
                        <div class="invalid-feedback">
                            Telephone number must be of the form: XXX-XXX-XXXX
                            with dashes included.
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" value="<?= $this->user ? $this->user->dob->format("m/d/Y") : "" ?>" required />
                        <div class="invalid-feedback">
                            Date of birth must be of the form MM/DD/YYYY with
                            leading zeroes and dashes present.
                        </div>
                    </div>
                </div>
                <p class="h3">Address</p>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="address-line-1">Address Line 1</label>
                        <input type="text" class="form-control" id="address-line-1" name="address_line_1" value="<?= $this->user ? $this->user->address_line_1 : "" ?>" required />
                        <div class="invalid-feedback">Must supply an address line 1.</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="address-line-2">Address Line 2</label>
                        <input type="text" class="form-control" id="address-line-2" name="address_line_2" value="<?= $this->user ? $this->user->address_line_2 : "" ?>" />
                        <div class="valid-feedback">
                            Address line 2 is only required if your address
                            has a second line.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?= $this->user ? $this->user->address_city : "" ?>" required />
                        <div class="invalid-feedback">Must supply a city.</div>
                    </div>
                    <div class="form-group col-md-4">
                        <?php
                        $raw_states = file_get_contents(__DIR__ . "/../assets/states.json");
                        $states = json_decode($raw_states, true);

                        (new DropDown(
                            "State",
                            "state",
                            "state",
                            $states,
                            true,
                            $this->user ? $this->user->address_state : null
                        ))->render();
                        ?>
                        <div class="invalid-feedback">Must select a state.</div>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="zip">ZIP Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" pattern="[0-9]{5}" value="<?= $this->user ? $this->user->address_zip : "" ?>" required />
                        <div class="invalid-feedback">ZIP Code must be 5 digits.</div>
                    </div>
                </div>
                <?php if ($this->isRegistration) : ?>
                    <p class="h3">Password</p>
                    <?php $this->passInput->render(); ?>
                    <p class="h3">Terms of Service</p>
                    <?php (new ToSReader("100px"))->render(); ?>
                    <div class="form-group custom-control custom-switch">
                        <input class="custom-control-input" type="checkbox" id="tos-accept" name="tos_accept" required />
                        <label class="custom-control-label" for="tos-accept">
                            I have read the terms of service and agree to be
                            bound by them.
                        </label>
                        <div class="invalid-feedback">
                            You must agree to the terms and conditions to register.
                        </div>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    <?php
    }

    public function injectScripts()
    {
        !$this->isRegistration ?: $this->passInput->injectScripts();

    ?>
        <script>
            window.addEventListener("load", function() {
                let form = document.getElementById("profile-form");

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
