<?php

require_once 'Component.php';
require_once 'PasswordMeter.php';
require_once __DIR__ . '/../types/User.php';

class UserProfileForm implements Component
{
    private $user;
    private $isRegistration;
    private $passMeter;

    public function __construct($user)
    {
        $this->user = $user;
        $this->isRegistration = !isset($this->user)
            || !isset($this->user->user_id);
        $this->passMeter = $this->isRegistration ? new PasswordMeter() : null;
    }

    public function render()
    {
?>
        <div class="container mb-5">
            <form action="register.php" method="post">
                <p class="h3">Personal Info</p>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="first-name">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first-name" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last-name">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last-name" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                        <p>
                            <small>
                                A message will be sent to this address to
                                confirm. Email must be confirmed before you can
                                comment or send help tickets.
                            </small>
                        </p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phone">Telephone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required />
                    </div>
                </div>
                <p class="h3">Address</p>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="address-line-1">Address Line 1</label>
                        <input type="text" class="form-control" id="address-line-1" name="address-line-1" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="address-line-2">Address Line 2</label>
                        <input type="text" class="form-control" id="address-line-2" name="address-line-2" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city">
                    </div>
                    <div class="form-group col-md-4">
                        <?php // TODO use the drop down thing
                        ?>
                        <label for="state">State</label>
                        <select id="state" class="form-control" name="state">
                            <option selected>Choose...</option>
                            <option>...</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="zip">ZIP Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" required />
                    </div>
                </div>
                <?php if ($this->isRegistration) : ?>
                    <p class="h3">Password</p>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required />
                        </div>
                        <div class="form-group col-md-6">
                            <?php $this->passMeter->render(); ?>
                        </div>
                    </div>
                    <p class="h3">Terms and Conditions</p>
                    <div class="card mb-3 bg-light">
                        <div class="card-body overflow-auto" style="max-height: 150px;">
                            <?php echo file_get_contents(__DIR__ . "/../assets/tos.html"); ?>
                        </div>
                    </div>
                    <div class="form-group custom-control custom-switch">
                        <input class="custom-control-input" type="checkbox" id="tos-accept" name="tos-accept" />
                        <label class="custom-control-label" for="tos-accept">
                            I have read the terms and conditions and agree to be
                            bound by them.
                        </label>
                    </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
<?php
    }

    public function injectScripts()
    {
        !$this->isRegistration ?: $this->passMeter->injectScripts();
    }
}
