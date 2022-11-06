<?php namespace api;


class ApiAuthorController {
    public static function get_authors($query, $order_by, $direction, $limit, $pages) {
        return ApiController::request('api/v2/authors?query='.$query.'&orderBy='.$order_by.'&direction='.$direction.'&limit='.$limit.'&page='.$pages, null);
    }

    public static function delete_author($id) {
        $response = ApiController::request('api/v2/authors/'.$id, ['delete' => true]);
        if (!isset($response['error'])) {
            header('Location: main.php?ok_msg='.urlencode('The autor removed successfully.'));
        } else {
            header('Location: new_author.php?error_msg='.urlencode($response['error']));
        }
        exit;
    }

    public static function get_author($id) {
        $response = ApiController::request('api/v2/authors/'.$id, null);
        if (isset($response['error'])) {
            header('Location: author.php?error_msg='.urlencode('The autor cannot be displayed.'));
            exit;
        }
        return $response;
    }

    public static function add_author() {
        $data = $_POST;
        
        $required_fields = ["first_name", "last_name", "birthday", "biography", "gender", "place_of_birth"];
        $errors = '';
        foreach ($required_fields as $field) {
            if (strlen($data[$field]) === 0) {
                if (strlen($errors) > 0) {
                    $errors .= ', ';
                }
                $errors .= $field;
            }
        }

        if (strlen($errors) > 0) {
            header('Location: new_author.php?error_msg='.urlencode('The fields: ' . $errors . ', need to be filled!'));
            exit;
        }
        
        $response = ApiController::request('api/v2/authors', $data);
        if (!isset($response['error'])) {
            header('Location: new_author.php?ok_msg='.urlencode('The author added successfully.'));
        } else {
            header('Location: new_author.php?error_msg='.urlencode($response['error']));
        }
        exit;
    }
}