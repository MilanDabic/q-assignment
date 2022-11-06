<?php namespace api;

use api\ApiController;

class ApiAuthController {

    private static function save_user($response) {
        $_SESSION['token'] = $response['token_key'];
        $_SESSION['refresh_token_key'] = $response['refresh_token_key'];
        $_SESSION['token_receival_time'] = time();
        $_SESSION['user'] = json_encode($response['user']);
    }

    public static function login($email, $pass) {
        
        $response = ApiController::request('api/v2/token', ['email' => $email, 'password' => $pass], false);

        if (isset($response['error'])) {
            return $response['error'];
        }

        self::save_user((array) $response);
        return true;
    }

    public static function get_token() {

        //if token expired, a refreshed one will be requested.
        //if the token din't expire, it will be returned from the session
        //if an error occured, the session will be destroyed

        if (isset($_SESSION['token_receival_time']) && isset($_SESSION['refresh_token_key'])) {
            if ((time() - $_SESSION['token_receival_time']) > 3600) {
                $response = ApiController::request('api/v2/token/refresh?refreshToken=' . $_SESSION['refresh_token_key'], null);

                if ($response['error']) {
                    session_destroy();
                    return false;
                }

                self::save_user($response);
            }

        } else {
            header('location: index.php');
        }

        return $_SESSION['token'] ?? false;
    }

}