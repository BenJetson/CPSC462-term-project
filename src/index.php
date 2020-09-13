<?php

require_once './includes/autoload.php';
require_once './includes/secrets.php';
require_once './includes/db-connect.php';

?>
<!DOCTYPE html>
<html>

<body>
    <p><?= $_SERVER["SUPER_SECRET"] ?></p>
    <!-- <p><//?= phpinfo() ?//></p> -->

    <?php

    $stmt = $db->prepare("SELECT email FROM account");
    $stmt->execute();

    echo "<p>" . $stmt->fetchColumn(0) . "</p>";
    // echo "<p>" . $stmt->fetchColumn(0) . "</p>";
    // echo "<p>" . $stmt->fetchColumn(0) . "</p>";

    ?>
</body>

</html>

<?php

// echo mail(
//     "benjetson5236@gmail.com",
//     "Test Message",
//     "This is a test to see if mail is working.\n\nThis automated message was sent to you via an application written by Ben Godfrey. If you did not request this message, please reach out to bfgodfr@clemson.edu for assistance.",
//     "From: Demo Application <noreply@clemson.edu>\r\n"
// );

?>
