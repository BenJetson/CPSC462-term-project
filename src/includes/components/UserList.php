<?php

require_once 'Component.php';
require_once __DIR__ . "/../types/User.php";
require_once __DIR__ . "/../forms/FormProcessor.php";
require_once __DIR__ . "/../forms/AdminUsersFP.php";

class UserList implements Component
{
    /** @var User[] */
    private $users;

    /**
     * @param User[] $users the list of users to display.
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function render()
    {
?>
        <div class="container">
            <?php foreach ($this->users as $user) : ?>
                <div class="card mb-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col <?= $user->is_disabled ? "text-strike" : "" ?>">
                                <h2 class="h4">
                                    <div class="d-inline-block position-relative mr-3 mb-3">
                                        <div class="avatar" aria-hidden="true">
                                            <?php if ($user->is_admin) : ?>
                                                <div class="avatar-crown">
                                                    <i class="fa fa-crown"></i>
                                                </div>
                                            <?php endif; ?>
                                            <?= $user->monogram() ?>
                                        </div>
                                    </div>
                                    <?= $user->fullName() ?>
                                </h2>
                                <p class="mb-0">
                                    <?php if ($user->is_admin) : ?>
                                        <span class="badge badge-pill badge-warning text-uppercase mr-2">
                                            Admin
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($user->is_disabled) : ?>
                                        <span class="badge badge-pill badge-danger text-uppercase mr-2">
                                            Disabled Account
                                        </span>
                                    <?php endif; ?>
                                    <i class="fa fa-fingerprint"></i>
                                    #<?= $user->user_id ?>
                                    <i class="fa fa-envelope ml-2"></i>
                                    <?= $user->email ?>
                                </p>
                                <a href="profile.php?user_id=<?= $user->user_id ?>" class="stretched-link"></a>
                            </div>
                            <div class="col-md-auto mt-3">
                                <a href="profile-editor.php?user_id=<?= $user->user_id ?>" class="btn btn-info" aria-label="Edit Profile">
                                    <i class="fa fa-pencil"></i>
                                    <span class="d-none d-md-inline">Edit Profile</span>
                                </a>
                                <?php
                                $attrMap = [
                                    "data-id" => $user->user_id,
                                    "data-name" => $user->fullName(),
                                    "data-email" => $user->email,
                                    "data-is-disabled" => $user->is_disabled ? "true" : "false",
                                    "data-is-admin" => $user->is_admin ? "true" : "false",
                                ];

                                // Cannot alter management attributes for the
                                // system owner (user ID 1), so disable button.
                                if ($user->user_id === 1) {
                                    $attrMap["disabled"] = true;
                                }

                                $attrs = "";
                                foreach ($attrMap as $key => $val) {
                                    $attrs .= "$key=\"$val\" ";
                                }
                                ?>
                                <button class="btn btn-secondary btn-manage" aria-label="Manage Account" <?= $attrs ?>>
                                    <i class="fa fa-cog"></i>
                                    <span class="d-none d-md-inline">Manage Account</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="modal d-none" id="manageModal">
            <div class="modal-dialog modal-dialog-scrollable">
                <form method="POST" action="admin-users.php">
                    <input type="hidden" name="<?= FormProcessor::OPERATION ?>" value="<?= AdminUsersFP::OP_MANAGE ?>" />
                    <input type="hidden" name="user_id" value="" id="userIdInput" />

                    <div class="modal-content">
                        <div class="modal-header">
                            <p class="h4 mb-0">
                                Manage Account
                            </p>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-dismiss">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body mb-1">
                            <div class="alert">
                                You are viewing settings for the
                                following account:
                                <br />
                                <br />
                                <i class="fa fa-fingerprint"></i>
                                #<span id="idLabel"></span>
                                <br />
                                <i class="fa fa-user"></i>
                                <span id="nameLabel"></span>
                                <br />
                                <i class="fa fa-envelope"></i>
                                <span id="emailLabel"></span>
                            </div>
                            <div class="alert alert-warning">
                                <p class="h5">
                                    <i class="fa fa-crown"></i>
                                    Administration
                                </p>
                                <p>
                                    Administrative users have full control over
                                    the system, including the ability to see all
                                    help tickets and edit the knowledge base.
                                </p>
                                <hr />
                                <div class="custom-control custom-switch">
                                    <?php /* The second hidden input of the same name will
                                           * cause the unchecked checkbox to still be POSTed,
                                           * in effect.
                                           *
                                           * PHP will receive both values but only the last value
                                           * is the one that prevails.
                                           */ ?>
                                    <input type="hidden" name="is_admin" value="off" />
                                    <input type="checkbox" class="custom-control-input" id="adminSwitch" name="is_admin" />
                                    <label class="custom-control-label" for="adminSwitch">
                                        Grant this user administrative privileges.
                                    </label>
                                </div>
                            </div>
                            <div class="alert alert-danger">
                                <p class="h5">
                                    <i class="fa fa-ban"></i>
                                    Status
                                </p>
                                <p>
                                    Users with disabled accounts cannot login or
                                    access the system.
                                </p>
                                <hr />
                                <div class="custom-control custom-switch">
                                    <?php /* The second hidden input of the same name will
                                           * cause the unchecked checkbox to still be POSTed,
                                           * in effect.
                                           *
                                           * PHP will receive both values but only the last value
                                           * is the one that prevails.
                                           */ ?>
                                    <input type="hidden" name="is_disabled" value="off" />
                                    <input type="checkbox" class="custom-control-input" id="disableSwitch" name="is_disabled" />
                                    <label class="custom-control-label" for="disableSwitch">
                                        Disable this user's account.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger btn-dismiss">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script src="assets/js/modal.js"></script>
        <script>
            window.addEventListener("load", () => {
                let manageModal = new Modal("manageModal");

                let idLabel = document.getElementById("idLabel");
                let userIdInput = document.getElementById("userIdInput");
                let nameLabel = document.getElementById("nameLabel");
                let emailLabel = document.getElementById("emailLabel");
                let adminSwitch = document.getElementById("adminSwitch");
                let disableSwitch = document.getElementById("disableSwitch");

                let switchStateHandler = () => {
                    // Ensure that the state of the switches is valid.
                    if (disableSwitch.checked) {
                        adminSwitch.setAttribute("disabled", true);
                        adminSwitch.checked = false;
                    } else {
                        adminSwitch.removeAttribute("disabled");
                    }
                };

                disableSwitch.addEventListener("change", switchStateHandler);

                /** @param {Event} event */
                let manageHandler = (event) => {
                    /** @type {Element} */
                    let btn = event.target;

                    // Sometimes the user may click on the span or the i element
                    // inside the button. When this happens, we must traverse
                    // backwards up the DOM tree to find the parent button.
                    while (!btn.classList.contains("btn-manage")) {
                        btn = btn.parentNode;
                    }

                    // The parent button has the user data attached to it via
                    // attributes. Fetch this data and store it in an object.
                    let user = {
                        id: btn.getAttribute("data-id"),
                        name: btn.getAttribute("data-name"),
                        email: btn.getAttribute("data-email"),
                        isAdmin: btn.getAttribute("data-is-admin"),
                        isDisabled: btn.getAttribute("data-is-disabled"),
                    };

                    // Attach the fetched user data to the modal labels.
                    idLabel.innerText = user.id;
                    userIdInput.value = user.id;
                    nameLabel.innerText = user.name;
                    emailLabel.innerText = user.email;
                    adminSwitch.checked = user.isAdmin === "true";
                    disableSwitch.checked = user.isDisabled === "true";

                    // Ensure that the switches have the correct states.
                    switchStateHandler();

                    manageModal.show();
                };

                // Attach the manage modal handler to each of the buttons.
                let manageBtns = document.querySelectorAll(".btn-manage");
                for (let btn of manageBtns) {
                    btn.addEventListener("click", manageHandler);
                }
            });
        </script>
<?php
    }
}
