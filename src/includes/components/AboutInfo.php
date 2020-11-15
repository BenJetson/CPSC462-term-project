<?php

require_once 'Component.php';

class AboutInfo implements Component
{
    public function render()
    {
?>
        <div class="container text-center mb-4">
            <div class="card bg-light py-4">
                <div class="card-body">
                    <p class="display-1">
                        <i class="fa fa-question-circle"></i>
                    </p>
                    <p class="h1">
                        IT Helpdesk
                    </p>
                    <p class="h4 text-muted">
                        <em>Version 1.0</em>
                    </p>
                    <p class="lead mb-0 mt-4 text-muted">
                        Copyright &copy; 2020 Ben Godfrey, all rights reserved.
                    </p>
                </div>
            </div>
        </div>
        <div class="container mb-4 text-center">
            <div class="card">
                <div class="card-body">
                    <h2>Server Information</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th>Tier</th>
                                    <td><code><?= htmlspecialchars($_SERVER["TIER"]) ?></code></td>
                                </tr>
                                <tr>
                                    <th>Hostname</th>
                                    <td><code><?= htmlspecialchars($_SERVER["HTTP_HOST"]) ?></code></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge badge-success">OK</span></td>
                                </tr>
                                <tr>
                                    <th>Database</th>
                                    <td><span class="badge badge-success">CONNECTED</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-4 text-center">
            <div class="card">
                <div class="card-body">
                    <h2>Coursework Information</h2>
                    <p>
                        This application was created as part of my coursework at
                        Clemson University.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th>Student</th>
                                    <td><code>Ben Godfrey</code></td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td><code>bfgodfr</code></td>
                                </tr>
                                <tr>
                                    <th>Course</th>
                                    <td><code>CPSC 462</code></td>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <td><code>002</code></td>
                                </tr>
                                <tr>
                                    <th>Term</th>
                                    <td><code>FA20</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center">Acknowledgements</h2>
                    <p>
                        This application is made possible by the following
                        technologies:
                    </p>
                    <p>
                        <ul>
                            <li><a href="https://www.ubuntu.com">Ubuntu GNU/Linux</a></li>
                            <li><a href="https://httpd.apache.org">Apache Web Server</a></li>
                            <li><a href="https://mysql.com">mySQL Database</a></li>
                            <li><a href="https://php.net">PHP 5</a></li>
                            <li><a href="https://docker.com">Docker</a></li>
                            <li><a href="https://getbootstrap.com">Bootstrap 4</a></li>
                            <li><a href="https://fontawesome.com">FontAwesome Icons</a></li>
                            <li><a href="https://github.com/dropbox/zxcvbn">ZXCVBN Password Algorithm</a></li>
                            <li><a href="https://github.com/paragonie/sodium_compat">Libsodium Compat Crypto</a></li>
                            <li><a href="https://github.com/vlucas/phpdotenv">PHPDotEnv</a></li>
                            <li><a href="https://getcomposer.org">PHP Composer</a></li>
                        </ul>
                    </p>
                    <p class="mb-0">
                        The appearance of a name on this list does not indicate
                        an affiliation with or endorsement of this IT Helpdesk
                        application project by the mentioned party.
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
