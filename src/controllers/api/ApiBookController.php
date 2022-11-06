<?php namespace api;


class ApiBookController {

    public static function delete_book($id) {
        $response = ApiController::request('api/v2/books/'.$id, ['delete' => true]);
        if (!isset($response['error'])) {
            header('Location: main.php?ok_msg='.urlencode('The book removed successfully.'));
        } else {
            header('Location: new_author.php?error_msg='.urlencode($response['error']));
        }
        exit;
    }

    public static function add_book() {
        $data = $_POST;
        $required_fields = ["author_id", "title", "release_date", "description", "isbn", "format", "number_of_pages"];
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
            header('Location: new_book.php?error_msg='.urlencode('The fields: ' . $errors . ', need to be filled!'));
            exit;
        }

        $data['author'] = ['id' => $data['author_id']];
        unset($data['author_id']);

        $data['number_of_pages'] = (int) $data['number_of_pages'];
        
        $response = ApiController::request('api/v2/books', $data);
        if (!isset($response['error'])) {
            header('Location: new_book.php?ok_msg='.urlencode('The book added successfully.'));
        } else {
            header('Location: new_author.php?error_msg='.urlencode($response['error']));
        }
        exit;
    }
}