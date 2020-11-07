<?php

require_once __DIR__ . '/../types/HelpTicket.php';
require_once __DIR__ . '/../forms/FormProcessor.php';
require_once __DIR__ . '/../forms/HelpTicketEditorFP.php';
require_once 'Component.php';
require_once 'DropDown.php';

class HelpTicketEditor implements Component
{
    public function render()
    {
?>
        <div class="container mb-3">
            <div class="alert mb-3 alert-info">
                <div class="row no-gutters">
                    <div class="col-md-auto display-1 mr-md-4 text-center">
                        <i class="fa fa-life-ring"></i>
                    </div>
                    <div class="col-md">
                        <h2 class="h3">Get Help Faster</h2>
                        <p class="lead mb-0">
                            Before filing a ticket, take a quick look at our
                            <a class="alert-link" href="article-index.php">
                                <i class="fa fa-book"></i>
                                knowledge base
                            </a>
                            to see if your query has already been answered.
                        </p>
                    </div>
                </div>
            </div>
            <form action="ticket-editor.php" method="POST">
                <input type="hidden" name="<?= FormProcessor::OPERATION ?>" value="<?= HelpTicketEditorFP::OP_CREATE ?>" />
                <div class="form-group">
                    <label for="ticketSubject">Subject</label>
                    <input type="text" class="form-control form-control-lg font-weight-bold" autofocus name="subject" id="ticketSubject" placeholder="Subject" required />
                    <small class="form-text text-muted">
                        Give us a descriptive subject that summarizes your issue
                        in as few words as possible.
                    </small>
                </div>
                <div class="form-group">
                    <label for="ticketBody">Body</label>
                    <textarea class="form-control" rows="10" id="ticketBody" placeholder="Describe your issue with as much details as possible..." name="body" required></textarea>
                    <small class="form-text text-muted">
                        Provide as much information about your problem as you
                        know, as well as any steps you have already tried.
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">Create Ticket</button>
                <small class="form-text text-muted mt-2">
                    The contents of this ticket cannot be edited after submission
                    to preserve conversation history.<br />However, you may post
                    follow up comments to add more information.
                </small>
            </form>
        </div>
<?php
    }

    public function injectScripts()
    {
        // FIXME standard comment.
    }
}
