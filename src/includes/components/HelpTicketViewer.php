<?php

require_once 'CommentSection.php';
require_once 'Component.php';
require_once 'DropDown.php';
require_once 'HelpTicketStatusBadge.php';
require_once __DIR__ . '/../types/HelpTicket.php';
require_once __DIR__ . '/../types/User.php';

class HelpTicketViewer implements Component
{
    private $user;
    private $help_ticket;

    public function __construct(User $user, HelpTicket $help_ticket)
    {
        $this->user = $user;
        $this->help_ticket = $help_ticket;
    }

    public function render()
    {
?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-7 col-lg-8 col-xl-9">
                    <div class="card mt-3">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <h1 class="h3 mb-0">Ticket #1</h1>
                            <div class="h4 mb-0">
                                <?php (new HelpTicketStatusBadge(
                                    $this->user,
                                    $this->help_ticket,
                                    true
                                ))->render(); ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-title h4"><?= $this->help_ticket->subject ?></p>
                            <?php foreach (explode("\n", $this->help_ticket->body) as $paragraph) : ?>
                                <?php if (!empty($paragraph)) : ?>
                                    <p><?= $paragraph ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-4 col-xl-3">
                    <div class="card bg-light mt-3">
                        <div class="card-body">
                            <h2 class="card-title h5">
                                Properties
                            </h2>
                            <p class="card-text">
                                <strong>Status</strong>
                                <br />
                                <?php (new HelpTicketStatusBadge(
                                    $this->user,
                                    $this->help_ticket
                                ))->render(); ?>
                            </p>
                            <p class="card-text">
                                <strong>Submitter</strong>
                                <br />
                                <?= $this->help_ticket->submitter_name ?>
                            </p>
                            <p class="card-text">
                                <strong>Submitted at</strong>
                                <br />
                                1970-01-01 12:00 AM
                                <?php // TODO
                                ?>
                            </p>
                            <div class="card-text">
                                <strong>Assignee</strong>
                                <br />
                                <?php // FIXME make this form work
                                ?>
                                <?php if ($this->user->is_admin) : ?>
                                    <div class="form-group">
                                        <?php (new DropDown(
                                            null,
                                            null,
                                            null,
                                            []
                                        ))->render(); ?>
                                    </div>
                                    <button class="btn btn-sm btn-primary">Set Assignee</button>
                                <?php else : ?>
                                    <?= $this->help_ticket->assignee_name ?: "<em>Not Assigned</em>" ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($this->help_ticket->is_closed) : ?>
                                <p class="card-text">
                                    <?php // TODO check this
                                    ?>
                                    <strong>Closed At</strong>
                                    <br />
                                    <?= $this->help_ticket->closed_at->format("Y-m-d h:i:s a") ?>
                                    by
                                    <?= $this->help_ticket->closed_by_submitter
                                        ? $this->help_ticket->submitter_name
                                        : "helpdesk staff" ?>
                                </p>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-md-center">
                        <?php // FIXME need to dynamically show/hide these
                        ?>
                        <p class="card-title h5 mb-md-0 flex-fill">
                            Actions
                        </p>
                        <a href="#" class="btn btn-outline-danger m-1 flex-fill">
                            Close
                        </a>
                        <a href="#" class="btn btn-outline-success m-1 flex-fill">
                            Reopen
                        </a>
                        <a href="#" class="btn btn-outline-primary m-1 flex-fill">
                            Claim
                        </a>
                        <a href="#" class="btn btn-outline-primary m-1 flex-fill">
                            Unassign
                        </a>
                        <a href="#" class="btn btn-outline-primary m-1 flex-fill">
                            Comment
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="card mt-3">
                <div class="card-body">
                    <?php // FIXME no comments loaded
                    ?>
                    <?php (new CommentSection([]))->render(); ?>
                </div>
            </div>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
<?php
    }
}
