<?php namespace controllers;

use api\ApiAuthorController;

$root = substr(__DIR__, 0, strpos(__DIR__, '/public'));
require_once ($root.'/config.php');
require_once constant('ROOT_DIR').'/vendor/autoload.php';

session_start();
if (!isset($_SESSION['token'])) {
    header('Location: index.php?error_msg='.urlencode('Session expired!'));
    exit;
}

$user = json_decode($_SESSION['user'], true);

if (sizeof($_POST) > 0) {
    ApiAuthorController::add_author();
}

?><!DOCTYPE html>
<html lang="eng">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>q-assignment</title>
        <link rel="stylesheet" href="../assets/main.css">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <span>Hello <?php echo $user['first_name'] ?> <?php echo $user['last_name'] ?>!</span>
                <form action="./index.php" method="POST" class="logout">
                    <input type="hidden" name="logout" value="true" />
                    <button type="submit">Logout</button>
                </form>
            </div>
            <div class="content">
                <div class="errors"><?php echo $_GET['error_msg'] ?? ''; ?></div>
                <div class="success"><?php echo $_GET['ok_msg'] ?? ''; ?></div>
                <h1>Add an Author</h1>
                <form class="add-book-form" method="POST" action="./new_author.php">
                    <label for="first_name">First name:</label>
                    <input type="text" id="first_name" name="first_name" /><br /><br />
                    <label for="last_name">Last name:</label>
                    <input type="text" id="last_name" name="last_name" /><br /><br />
                    <label for="birthday">Birthday:</label>
                    <input type="date" id="birthday" name="birthday" /><br /><br />
                    <label for="biography">Biography:</label>
                    <textarea id="biography" name="biography">Enter text here</textarea><br /><br />
                    <label for="gender">Gender:</label>
                    <input type="text" id="gender" name="gender" /><br /><br />
                    <label for="place_of_birth">Place of birth:</label>
                    <input type="text" id="place_of_birth" name="place_of_birth" /><br /><br />
                    <button type="submit">Save</button>
                </form>
            </div>
            <br /><br />
            <a href="./main.php">Back</a>
        </div>
    </body>
</html>