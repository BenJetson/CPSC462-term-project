<?php

require_once 'Component.php';
require_once __DIR__ . "/../types/User.php";

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
                            <div class="col-auto">
                                <div class="d-inline-block position-relative">
                                    <div class="avatar h3" aria-hidden="true">
                                        <?php if ($user->is_admin) : ?>
                                            <div class="avatar-crown">
                                                <i class="fa fa-crown"></i>
                                            </div>
                                        <?php endif; ?>
                                        <?= $user->monogram() ?>
                                    </div>
                                </div>
                                <a href="profile.php?user_id=<?= $user->user_id ?>" class="stretched-link"></a>
                            </div>
                            <div class="col">
                                <h2>
                                    <?= $user->fullName() ?>
                                    <?php if ($user->is_admin) : ?>
                                        <small>
                                            <span class="badge badge-pill badge-warning text-uppercase ml-2">
                                                Admin
                                            </span>
                                        </small>
                                    <?php endif; ?>
                                </h2>
                                <p><?= $user->email ?></p>
                                <a href="profile.php?user_id=<?= $user->user_id ?>" class="stretched-link"></a>
                            </div>
                            <div class="col-md-auto">
                                <a href="profile.php?user_id=<?= $user->user_id ?>" class="btn btn-info">
                                    <i class="fa fa-eye"></i>
                                    View
                                </a>
                                <a href="profile-editor.php?user_id=<?= $user->user_id ?>" class="btn btn-info">
                                    <i class="fa fa-pencil"></i>
                                    Edit
                                </a>
                                <?php if ($user->is_admin) : ?>
                                    <button class="btn btn-secondary">
                                        <i class="fa fa-arrow-circle-down"></i>
                                        Demote Admin
                                    </button>
                                <?php else : ?>
                                    <button class="btn btn-danger">
                                        <i class="fa fa-crown"></i>
                                        Make Admin
                                    </button>
                                <?php endif; ?>
                                <?php if ($user->is_disabled) : ?>
                                    <button class="btn btn-secondary">
                                        <i class="fa fa-user-check"></i>
                                        Re-enable
                                    </button>
                                <?php else : ?>
                                    <button class="btn btn-danger">
                                        <i class="fa fa-ban"></i>
                                        Disable
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
