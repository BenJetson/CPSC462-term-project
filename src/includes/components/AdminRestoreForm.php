<?php

require_once 'Component.php';

class AdminRestoreForm implements Component
{
    public function render()
    {
?>
        <div class="container">
            <form method="POST" action="admin-restore.php" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="restore_file" id="restoreFile" accept="application/zip" required>
                        <label class="custom-file-label" for="restoreFile" id="restoreFileLabel">
                            Loading...
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Start Restore</button>
            </form>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script>
            // We are using Bootstrap's custom file picker. Unfortunately, it
            // requires that we add some of our own JavaScript if we would
            // like for the file name to be visible on the file picker.
            window.addEventListener("load", () => {
                let fileInput = document.getElementById("restoreFile");
                let fileLabel = document.getElementById("restoreFileLabel");

                let fileChangeHandler = () => {
                    let filename = fileInput.files[0] ?
                        fileInput.files[0].name :
                        "Select file.";

                    fileLabel.innerText = filename;
                }

                fileInput.addEventListener("change", fileChangeHandler);

                // Fire this on page load as well to set the name.
                fileChangeHandler();
            });
        </script>
<?php
    }
}
