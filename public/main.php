<?php namespace controllers;

use api\ApiAuthorController;

$root = substr(__DIR__, 0, strpos(__DIR__, '/public'));
$public_root = substr(__DIR__, 0, strpos(__DIR__, 'public'));
require_once ($root.'/config.php');
require_once constant('ROOT_DIR').'/vendor/autoload.php';

LoginController::login();

if (!isset($_SESSION['token'])) {
    header('Location: index.php?error_msg='.urlencode('Session expired!'));
    exit;
}

$user = json_decode($_SESSION['user'], true);

$authors = ApiAuthorController::get_authors('', 'id', 'ASC', 20, 1);

/* 
    because number of books an author has isn't provided with the authors, and an author can be deleted only if he doesn't have any books,
    the delete button will be available after each of the authors is selected and his books listed.
    (because of loading time improving)
*/
if (!isset($_SESSION['no_books_authors'])) {
    $_SESSION['no_books_authors'] = json_encode(['62495']);
}

$no_books_authors = json_decode($_SESSION['no_books_authors'], true);

if (isset($_POST['delete']) && $_POST['delete'] === 'true') {
    ApiAuthorController::delete_author($_POST['id']);
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
                        <?php foreach($authors['items'][0] as $key => $value): ?>
                        <th><?php echo $key; ?></th>
                        <?php endforeach; ?>
                        <th>Delete</th>
                    </tr>
                    <?php $author_list = [] ?>
                    <?php foreach($authors['items'] as $author): ?>
                    <tr>
                        <?php foreach($author as $key => $value): ?>
                        <td><?php
                            $key === 'first_name'
                            ? $content = '<a href="./author.php?id='.$author['id'].'">'.$author['first_name'].'</a>'
                            : $content = $value;
                            echo $content; 
                        ?></td>
                        <?php $author_list[$author['id']] = $author['first_name'] . ' ' . $author['last_name']; ?>
                        <?php endforeach; ?>
                        <?php $_SESSION['author_list'] = json_encode($author_list); ?>
                        <td>
                            <form action="./main.php" method="POST">
                                <input type="hidden" name="delete" value="true" />
                                <input type="hidden" name="id" value="<?php echo $author['id']; ?>" />
                                <button type="submit" <?php if (!in_array($author['id'], $no_books_authors)) echo 'disabled'; ?>>Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <a class="add-book" href="./new_book.php">Add a book</a><br /><br />
                <a class="add-auth" href="./new_author.php">Add an author</a>
            </div>
        </div>
    </body>
</html>