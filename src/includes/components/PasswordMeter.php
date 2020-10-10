<?php

require_once 'Component.php';

class PasswordMeter implements Component
{
    public function render()
    {
?>
        <div class="password-meter">
            <label for="meter">Password Strength: <em class="text-muted" id="password-meter-text">None</em></label>
            <div class="progress">
                <div class="progress-bar" id="password-meter-bar" aria-valuenow="0" aria-valuemax="4"></div>
            </div>
            <p><small id="password-meter-help"></small></p>
        </div>
    <?php
    }

    public function injectScripts()
    {
    ?>
        <script src="assets/js/zxcvbn.js"></script>
        <script>
            window.addEventListener('load', () => {
                let bar = document.getElementById("password-meter-bar");
                let text = document.getElementById("password-meter-text");
                let help = document.getElementById("password-meter-help");
                let input = document.getElementById("password");

                const scoreToText = {
                    0: "None",
                    1: "Terrible",
                    2: "Low",
                    3: "Acceptable",
                    4: "Strong"
                }

                let passMeter = () => {
                    let password = input.value;
                    let result = zxcvbn(password);
                    let score = result.score;

                    bar.setAttribute("aria-valuenow", score);
                    bar.style.width = `${score * 25}%`;

                    let className = "progress-bar ";
                    className += score > 3 ? "bg-success" :
                        score > 2 ? "bg-info" :
                        score > 1 ? "bg-warning" : "bg-danger";
                    bar.className = className;

                    console.log(result);
                    text.innerText = scoreToText[score];
                    help.innerText = result.feedback.warning;
                }

                input.addEventListener("keyup", passMeter);
                input.addEventListener("paste", passMeter);
            })
        </script>
<?php
    }
}
