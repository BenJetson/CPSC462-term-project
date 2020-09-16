<?php

require_once 'includes/db-connect.php';
require_once 'includes/login.php';

AccessToken::destroyCookie();

include 'includes/header.php';

?>

<div class="container py-5">
    <h1>Logout Success</h1>
    <p>Your session is now closed.</p>
</div>

<?php

include 'includes/footer.php';
