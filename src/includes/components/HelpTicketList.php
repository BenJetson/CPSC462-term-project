<?php

require_once 'Component.php';
require_once 'DropDown.php';
require_once 'HelpTicketStatusBadge.php';
require_once __DIR__ . '/../types/HelpTicket.php';
require_once __DIR__ . '/../types/HelpTicketFilter.php';
require_once __DIR__ . '/../types/User.php';

class HelpTicketList implements Component
{
    /** @var HelpTicket[] */
    private $help_tickets;
    /** @var HelpTicketFilter */
    private $filter;
    /** @var User */
    private $user;

    public function __construct(
        array $help_tickets,
        HelpTicketFilter $filter,
        User $user
    ) {
        $this->help_tickets = $help_tickets;
        $this->filter = $filter;
        $this->user = $user;
    }

    public function render()
    {
?>
        <div class="container">
            <div class="row flex-column-reverse flex-lg-row">
                <div class="col-md-6 mb-3">
                    Found <?= count($this->help_tickets) ?> tickets.
                    <br />
                    <em>Most recently updated tickets appear at the top.</em>
                </div>
                <div class="col-md mb-3">
                    <form method="GET" action="ticket-index.php" id="filterForm">
                        <?php (new DropDown(
                            null,
                            "filterSelect",
                            HelpTicketFilter::URL_PARAM,
                            HelpTicketFilter::filterOptionNamesForUser($this->user),
                            true,
                            $this->filter->getName(),
                            true
                        ))->render(); ?>
                    </form>
                </div>
                <div class="col-md-auto mb-3 text-right">
                    <a href="ticket-editor.php" class="btn btn-info">
                        <i class="fa fa-file-alt"></i>
                        New Ticket
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <?php if (count($this->help_tickets) === 0) : ?>
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <p class="display-4">
                            <i class="fa fa-hat-wizard"></i>
                        </p>
                        <p class="card-title h5">
                            No matching tickets were found.
                        </p>
                        <p>
                            That means there's no work to be done here!
                            Have a great day.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            <?php foreach ($this->help_tickets as $help_ticket) : ?>
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Ticket #<?= $help_ticket->help_ticket_id ?></h2>
                        <div class="h5 mb-0">
                            <span class="sr-only">Status</span>
                            <?php (new HelpTicketStatusBadge(
                                $this->user,
                                $help_ticket
                            ))->render(); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col-md-9">
                                <span class="sr-only">Subject</span>
                                <p class="card-title h4"><?= $help_ticket->subject ?></p>
                            </div>
                            <div class="col-md-3 text-md-right text-muted">
                                <p class="mb-0">
                                    <i class="fa fa-user"></i>
                                    <span class="sr-only">Submitter</span>
                                    <?= $help_ticket->submitter_name ?>
                                </p>
                                <p class="mb-0">
                                    <i class="fa fa-user-tag"></i>
                                    <span class="sr-only">Assignee</span>
                                    <?= $help_ticket->assignee_name ?: "<em>Unassigned</em>" ?>
                                </p>
                                <p class="mb-0">
                                    <i class="fa fa-calendar-day"></i>
                                    <span class="sr-only">Last Updated</span>
                                    <?= $help_ticket->updated_at->format(
                                        "Y-m-d h:i:s a"
                                    ) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="ticket.php?help_ticket_id=<?= $help_ticket->help_ticket_id ?>" class="stretched-link">
                        <span class="sr-only">View Ticket #<?= $help_ticket->help_ticket_id ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script>
            window.addEventListener("load", () => {
                // Automatically navigate to the new filter page when the
                // drop down menu changes value.

                let filterForm = document.getElementById("filterForm");
                let filterSelect = document.getElementById("filterSelect");
                filterSelect.addEventListener("change", () => {
                    filterForm.submit()
                });
            });
        </script>
<?php
    }
}
