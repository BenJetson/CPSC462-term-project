<?php

require_once 'Component.php';
require_once __DIR__ . '/../types/User.php';

class UserProfileForm implements Component
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function render()
    {
?>
        <div class="container">
            <form action="register.php" method="post">
                <p class="h3">Personal Info</p>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="first-name">First Name</label>
                        <input type="text" class="form-control" id="first-name" name="first-name" required />
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="last-name">Last Name</label>
                        <input type="text" class="form-control" id="last-name" name="last-name" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="phone">Telephone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required />
                    </div>
                    <div class="form-group col-12 col-md-6">
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
                <p class="h3">Password</p>
                <div class="form-row">
                    <div class="form-group col">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
<?php
    }
}
