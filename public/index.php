<?php

use controllers\LoginController;

$root = substr(__DIR__, 0, strpos(__DIR__, '/public'));
require_once ($root.'/config.php');
require_once constant('ROOT_DIR').'/vendor/autoload.php';

if (isset($_POST['logout']) && $_POST['logout']) {
    LoginController::logout();
}

?><!DOCTYPE html>
<html lang="eng">
    <body>
        <div class="container">
            <div class="errors"><?php echo $_GET['error_msg'] ?? ''; ?></div>
            <div class="success"><?php echo $_GET['ok_msg'] ?? ''; ?></div>
            <form action="./main.php" method="POST">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" /><br /><br />
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" /><br /><br />
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
</html>