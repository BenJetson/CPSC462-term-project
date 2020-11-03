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
            <div class="row">
                <div class="col-md-6">
                    Found <?= count($this->help_tickets) ?> tickets.
                </div>
                <div class="col-md">
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
                <div class="col-md-auto">
                    <a href="ticket-editor.php" class="btn btn-info">
                        <i class="fa fa-file-alt"></i>
                        New Ticket
                    </a>
                </div>
            </div>
        </div>
        <div class="container mt-3">
            <?php foreach ($this->help_tickets as $help_ticket) : ?>
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Ticket #<?= $help_ticket->help_ticket_id ?></h2>
                        <div class="h5 mb-0">
                            <?php (new HelpTicketStatusBadge(
                                $this->user,
                                $help_ticket
                            ))->render(); ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-title h4"><?= $help_ticket->subject ?></p>
                    </div>
                    <a href="ticket.php?help_ticket_id=<?= $help_ticket->help_ticket_id ?>" class="stretched-link"></a>
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
