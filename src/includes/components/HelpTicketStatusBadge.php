<?php

require_once 'Component.php';
require_once __DIR__ . '/../types/HelpTicket.php';
require_once __DIR__ . '/../types/User.php';

class HelpTicketStatusBadge implements Component
{
    private $user;
    private $help_ticket;
    private $show_icon;

    public function __construct(
        User $user,
        HelpTicket $help_ticket,
        $show_icon = false
    ) {
        $this->user = $user;
        $this->help_ticket = $help_ticket;
        $this->show_icon = boolval($show_icon);
    }

    public function render()
    {
?>
        <?php if ($this->help_ticket->is_closed) : ?>
            <span class="badge badge-danger text-uppercase">
                <?php if ($this->show_icon) : ?>
                    <i class="fa fa-ban"></i>
                <?php endif; ?>
                closed
            </span>
        <?php elseif (
            is_null($this->help_ticket->assignee_id)
            && $this->user->is_admin
            && $this->help_ticket->submitter_id !== $this->user->user_id
        ) : ?>
            <span class="badge badge-warning text-uppercase">
                <?php if ($this->show_icon) : ?>
                    <i class="fa fa-warning"></i>
                <?php endif; ?>
                needs assignee
            </span>
        <?php elseif ($this->help_ticket->last_reply_author_id !== $this->user->user_id) : ?>
            <span class="badge badge-warning text-uppercase">
                <?php if ($this->show_icon) : ?>
                    <i class="fa fa-warning"></i>
                <?php endif; ?>
                your reply needed
            </span>
        <?php else : ?>
            <span class="badge badge-success text-uppercase">
                <?php if ($this->show_icon) : ?>
                    <i class="fa fa-check-circle"></i>
                <?php endif; ?>
                open
            </span>
        <?php endif; ?>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment
    }
}
