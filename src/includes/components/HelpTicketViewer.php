<?php

require_once 'CommentSection.php';
require_once 'Component.php';
require_once 'DropDown.php';
require_once 'HelpTicketStatusBadge.php';
require_once __DIR__ . '/../types/HelpTicket.php';
require_once __DIR__ . '/../types/User.php';

class HelpTicketViewer implements Component
{
    /** @var User */
    private $user;
    /** @var HelpTicket */
    private $help_ticket;
    /** @var array[int]string */
    private $assign_list;

    /**
     * @param User $user
     * @param HelpTicket $help_ticket
     * @param User[] $admin_users
     */
    public function __construct(
        User $user,
        HelpTicket $help_ticket,
        array $admin_users
    ) {
        $this->user = $user;
        $this->help_ticket = $help_ticket;
        $this->assign_list = [
            0 => "Unassigned",
        ];

        foreach ($admin_users as $admin) {
            $this->assign_list[$admin->user_id] = $admin->fullName();
        }
    }

    public function render()
    {
?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-7 col-lg-8 col-xl-9">
                    <div class="card mt-3">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <h1 class="h3 mb-0">Ticket #<?= $this->help_ticket->help_ticket_id ?></h1>
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
                                <?= $this->help_ticket->assignee_name ?: "<em>Not Assigned</em>" ?>
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
                        <p class="card-title h5 mb-md-0 mr-md-3 flex-shrink-1">
                            Actions
                        </p>
                        <?php if ($this->help_ticket->is_closed) : ?>
                            <button type="button" id="reopenTicketBtn" class="btn btn-outline-success m-1 flex-fill">
                                <i class="fa fa-redo"></i>
                                Reopen
                            </button>
                        <?php else : ?>
                            <button type="button" id="closeTicketBtn" class="btn btn-outline-danger m-1 flex-fill">
                                <i class="fa fa-ban"></i>
                                Close
                            </button>
                            <button type="button" id="replyBtn" class="btn btn-outline-primary m-1 flex-fill"">
                                <i class=" fa fa-reply"></i>
                                Reply
                            </button>
                            <?php if ($this->user->is_admin) : ?>
                                <button type="button" id="assignBtn" class="btn btn-outline-primary m-1 flex-fill">
                                    <i class="fa fa-user-tag"></i>
                                    Assign
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="card mt-3">
                <div class="card-body">
                    <?php // FIXME no comments loaded
                    ?>
                    <?php (new CommentSection([], "Journal"))->render(); ?>
                </div>
            </div>
        </div>
        <?php if ($this->help_ticket->is_closed) : ?>
            <div class="modal d-none" id="reopenTicketModal">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="ticket.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="h4 mb-0">Reopen Ticket</p>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-dismiss">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Please enter the reason for reopening the ticket below.</p>
                                <div class="form-group">
                                    <label for="reopenComment">Comment</label>
                                    <textarea name="comment" id="reopenComment" rows="3" class="form-control" placeholder="Comment"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger btn-dismiss">Cancel</button>
                                <button type="submit" class="btn btn-primary">Reopen Ticket</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php else : ?>
            <div class="modal d-none" id="closeTicketModal">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="ticket.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="h4 mb-0">Close Ticket</p>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-dismiss">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Please enter the reason for closing the ticket below.</p>
                                <div class="form-group">
                                    <label for="closeComment">Comment</label>
                                    <textarea name="comment" id="closeComment" rows="3" class="form-control" placeholder="Comment"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger btn-dismiss">Cancel</button>
                                <button type="submit" class="btn btn-primary">Close Ticket</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal d-none" id="replyModal">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="ticket.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="h4 mb-0">New Reply</p>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-dismiss">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Please enter your reply to the ticket, which
                                    will be added to the journal.
                                </p>
                                <div class="form-group">
                                    <label for="commentInput">Comment</label>
                                    <textarea name="comment" id="commentInput" rows="8" class="form-control" placeholder="Comment"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger btn-dismiss">Cancel</button>
                                <button type="submit" class="btn btn-primary">Send Reply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php if ($this->user->is_admin) : ?>
                <div class="modal d-none" id="assignModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="ticket.php">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="h4 mb-0">Assign Ticket</p>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-dismiss">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Who would you like to assign this ticket to?</p>
                                    <div class="form-group">
                                        <?php (new DropDown(
                                            "Assignee",
                                            "assignUser",
                                            "user",
                                            $this->assign_list,
                                            true,
                                            $this->help_ticket->assignee_id ?: 0
                                        ))->render(); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="assignComment">Comment</label>
                                        <textarea name="comment" id="assignComment" rows="3" class="form-control" placeholder="Comment"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger btn-dismiss">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Set Assignee</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script src="assets/js/modal.js"></script>
        <script>
            window.addEventListener("load", () => {
                <?php if ($this->help_ticket->is_closed) : ?>
                    let reopenTicketModal = new Modal("reopenTicketModal");
                    document
                        .getElementById("reopenTicketBtn")
                        .addEventListener("click", () => reopenTicketModal.show());
                <?php else : ?>
                    let closeTicketModal = new Modal("closeTicketModal");
                    document
                        .getElementById("closeTicketBtn")
                        .addEventListener("click", () => closeTicketModal.show());

                    let replyModal = new Modal("replyModal");
                    document
                        .getElementById("replyBtn")
                        .addEventListener("click", () => replyModal.show());

                    <?php if ($this->user->is_admin) : ?>
                        let assignModal = new Modal("assignModal");
                        document
                            .getElementById("assignBtn")
                            .addEventListener("click", () => assignModal.show());
                    <?php endif; ?>
                <?php endif; ?>
            });
        </script>
<?php
    }
}
