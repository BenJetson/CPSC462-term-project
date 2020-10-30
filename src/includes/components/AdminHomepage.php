<?php

require_once 'Component.php';

class AdminHomepage implements Component
{
    public function __construct()
    {
    }

    public function render()
    {
?>
        <div class="container">
            <a href="index.php" class="btn btn-outline-primary mb-4">
                <i class="fa fa-arrow-left"></i>
                Back to Home
            </a>
            <p class="lead">What would you like to do?</p>
            <div class="card-deck">
                <div class="card py-5 px-3 bg-danger text-light">
                    <a href="admin-users.php" class="stretched-link"></a>
                    <div class="card-text text-center display-1">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="card-title text-center h2">
                        Manage Users
                    </div>
                    <p class="card-text lead text-center">
                        Edit the record for any user registered with the
                        helpdesk.
                    </p>
                </div>
                <div class="card py-5 px-3 bg-dark text-light">
                    <a href="admin-backup.php" class="stretched-link"></a>
                    <div class="card-text text-center display-1">
                        <i class="fa fa-download"></i>
                    </div>
                    <div class="card-title text-center h2">
                        Backup
                    </div>
                    <p class="card-text lead text-center">
                        Download an archive of all records from the helpdesk
                        to your local computer.
                    </p>
                </div>
                <div class="card py-5 px-3 bg-success text-light">
                    <a href="admin-restore.php" class="stretched-link"></a>
                    <div class="card-text text-center display-1">
                        <i class="fa fa-cloud-upload-alt"></i>
                    </div>
                    <div class="card-title text-center h2">
                        Restore
                    </div>
                    <p class="card-text lead text-center">
                        Restore the helpdesk from a previously created backup
                        archive.
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
