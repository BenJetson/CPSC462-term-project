<?php

require_once 'Component.php';

class NoScriptWarning implements Component
{
    public function render()
    {
?>
        <noscript>
            <div class="container my-5">
                <div class="alert alert-danger border-danger" id="noScriptWarning">
                    <p class="h3">Problem Detected</p>
                    <p>
                        Your browser did not allow JavaScript to run on this
                        page. This application requires JavaScript for certain
                        features to work.
                    </p>
                    <p class="mb-0">
                        <strong>Please enable JavaScript via the
                            settings panel for your browser.</strong><br />
                        Once you have enabled JavaScript, you must
                        <a class="alert-link" href="<?= $_SERVER["PHP_SELF"] ?>">reload the page</a>.
                    </p>
                </div>
            </div>
        </noscript>
<?php
    }

    public function injectScripts()
    {
        // No scripts for this component. Must be present to satisfy interface.
    }
}
