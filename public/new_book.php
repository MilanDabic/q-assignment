<?php namespace controllers;

use api\ApiBookController;

$root = substr(__DIR__, 0, strpos(__DIR__, '/public'));
require_once ($root.'/config.php');
require_once constant('ROOT_DIR').'/vendor/autoload.php';

session_start();
if (!isset($_SESSION['token'])) {
    header('Location: index.php?error_msg='.urlencode('Session expired!'));
    exit;
}

$user = json_decode($_SESSION['user'], true);

$list = json_decode($_SESSION['author_list'], true);

if (sizeof($_POST) > 0) {
    ApiBookController::add_book();
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
                <h1>Add a Book</h1>
                <form class="add-book-form" method="POST" action="./new_book.php">
                    <label for="author">Select an author:</label>
                    <select name="author_id" id="author">
                        <?php foreach($list as $id => $name): ?>
                        <option value="<?php echo $id; ?>"><?php echo $name; ?></option> 
                        <?php endforeach; ?>
                    </select><br /><br />
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" /><br /><br />
                    <label for="release_date">Release date:</label>
                    <input type="date" id="release_date" name="release_date" /><br /><br />
                    <label for="description">Description:</label>
                    <textarea id="description" name="description">Description</textarea><br /><br />
                    <label for="isbn">isbn:</label>
                    <input type="text" id="isbn" name="isbn" /><br /><br />
                    <label for="format">Format:</label>
                    <input type="text" id="format" name="format" /><br /><br />
                    <label for="number_of_pages">Number of pages:</label>
                    <input type="number" id="number_of_pages" name="number_of_pages" /><br /><br />
                    <button type="submit">Save</button>
                </form>
            </div>
            <br /><br />
            <a href="./main.php">Back</a>
        </div>
    </body>
</html>