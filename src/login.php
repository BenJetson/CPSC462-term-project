<?php

require_once('includes/header.php');
require_once 'includes/db-connect.php';
require_once 'includes/login.php';
?>

<div class="container py-5">
    <h1>Login</h1>
    <p class="py-4"><em>Authentication is required to proceed.</em></p>
    <form method="POST" action="">
        <div class="form-group">
            <label for="user-email">Email</label>
            <input type="email" class="form-control" id="user-email" name="email" />
        </div>
        <div class="form-group">
            <label for="user-password">Password</label>
            <input type="password" class="form-control" id="user-password" name="password" />
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<div class="container">
    <h2>Received Data</h2>
    <table>
        <tr>
            <th>PostCount</th>
            <td><?= count($_POST) ?></td>
        </tr>
        <tr>
            <th>LoginReady</th>
            <td><?= count($_POST) === 2 && isset($_POST["email"]) && isset($_POST["password"]) ? "true" : "false" ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= $_POST["email"] ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?= $_POST["password"] ?></td>
        </tr>
        <tr>
            <th>Grant</th>
            <td><?= password_grant($db, $_POST["email"], $_POST["password"]) ? "true" : "false" ?></td>
        </tr>
        <tr>
            <th>Token</th>
            <td><?= print_r(AccessToken::fetchFromCookie(), true) ?></td>
        </tr>
    </table>
</div>


<?php
require_once('includes/footer.php');
