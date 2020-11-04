<?php

require_once 'Component.php';
require_once __DIR__ . '/../types/User.php';

class UserProfileViewer implements Component
{
    /** @var User */
    private $user;
    /** @var bool */
    private $mine;

    public function __construct(User $user, $mine)
    {
        $this->user = $user;
        $this->mine = $mine;
    }

    public function render()
    {
?>
        <div class="container">
            <div class="card bg-light mb-3">
                <div class="card-body text-center">
                    <div class="my-5">
                        <div class="d-inline-block position-relative">
                            <div class="avatar display-2" aria-hidden="true">
                                <?php if ($this->user->is_admin) : ?>
                                    <div class="avatar-crown">
                                        <i class="fa fa-crown"></i>
                                    </div>
                                <?php endif; ?>
                                <?= $this->user->monogram() ?>
                            </div>
                        </div>
                    </div>
                    <h2><?= $this->user->fullName() ?></h2>
                    <?php if ($this->user->is_admin) : ?>
                        <p class="h3 text-uppercase">
                            <span class="badge badge-pill badge-warning">
                                Admin
                            </span>
                        </p>
                    <?php endif; ?>
                    <?php

                    $href = "profile-editor.php";
                    if (!$this->mine) {
                        $href .= "?user_id=" . $this->user->user_id;
                    }

                    ?>
                    <a href="<?= $href ?>" class="btn btn-primary mt-2">
                        <i class="fa fa-pencil"></i>
                        Edit Profile
                    </a>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h2>Personal Info</h2>
                    <p class="lead">
                        <strong>Email</strong>
                        <br />
                        <?= $this->user->email ?>
                    </p>
                    <p class="lead">
                        <strong>Telephone</strong>
                        <br />
                        <?= $this->user->email ?>
                    </p>
                    <p class="lead">
                        <strong>Date of Birth</strong>
                        <br />
                        <?= $this->user->dob->format("m/d/Y") ?>
                    </p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h2>Address</h2>
                    <p class="lead">
                        <?= $this->user->address_line_1 ?>
                        <?php if ($this->user->address_line_2) : ?>
                            <br />
                            <?= $this->user->address_line_2 ?>
                        <?php endif ?>
                        <br />
                        <?= $this->user->address_city ?>,
                        <?= $this->user->address_state ?>
                        <?= $this->user->address_zip ?>
                    </p>
                </div>
            </div>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
