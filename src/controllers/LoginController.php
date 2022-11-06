<?php namespace controllers;

use api\ApiAuthController;

class LoginController {
    public static function login() {
        session_start();

        //avoiding more login attempts then necessary
        if (isset($_SESSION['token'])) return;

        $proceed = true;
        $error_msg = '';

        if ($_POST['email'] == '') {
            $error_msg .= 'Email not provided!';
            $proceed = false;
        }

        if ($_POST['password'] == '') {
            if (strlen($error_msg) > 0) { $error_msg .= '<br />'; }
            $error_msg .= 'Password not provided!';
            $proceed = false;
        }

        if (!$proceed) {
            header('Location: index.php?error_msg='.urlencode($error_msg));
            exit;
        }

        $login = ApiAuthController::login($_POST['email'], $_POST['password']);
        
        return $login;
    }

    public static function logout() {
        session_destroy();
        header('Location: index.php?ok_msg='.urlencode('You logged out successfully.'));
        exit;
    }
}