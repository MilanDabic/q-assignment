<?php namespace controllers;

use api\ApiAuthorController;
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

$author = ApiAuthorController::get_author($_GET['id']);

if (isset($_POST['delete_book']) && $_POST['delete_book'] === 'true') {
    ApiBookController::delete_book($_POST['id']);
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
                <table>
                    <tr>
                        <?php foreach($author as $key => $value): ?>
                        <?php if ($key === 'books') continue; ?>
                        <th><?php echo $key; ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php foreach($author as $key => $value): ?>
                        <?php if ($key === 'books') continue; ?>
                        <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                    </tr>
                </table>
                <?php if (sizeof($author['books']) > 0): ?>
                <table>
                    <tr>
                        <?php foreach($author['books'][0] as $key => $value): ?>
                        <th><?php echo $key; ?></th>
                        <?php endforeach; ?>
                        <th>Delete</th>
                    </tr>
                    <?php foreach($author['books'] as $book): ?>
                    <tr>
                        <?php foreach($book as $key => $value): ?>
                        <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                        <td>
                            <form action="./author.php" method="POST">
                                <input type="hidden" name="delete_book" value="true" />
                                <input type="hidden" name="id" value="<?php echo $book['id']; ?>" />
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else:
                    $no_books_authors = json_decode($_SESSION['no_books_authors'], true);
                    $no_books_authors[] = $author['id'];
                    $_SESSION['no_books_authors'] = json_encode($no_books_authors);
                ?>
                <?php endif; ?>
            </div>
            <a href="./main.php">Back</a>
        </div>
    </body>
</html>