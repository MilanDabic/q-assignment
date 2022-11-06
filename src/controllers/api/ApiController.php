<?php namespace api;

class ApiController {

    public static function request(string $endpoint, ?array $data, bool $require_token = true) {
        $curl = curl_init();

        $curl_opts = [
            CURLOPT_URL => BASE_URL.$endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ];

        //if delete data element provided, the call will be treated as delete request, if just data provided as post and if the data is null, as get

        if ($data !== null) {
            if (isset($data['delete']) && $data['delete']) {
                $curl_opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                unset($data['delete']);
            } else {
                $curl_opts[CURLOPT_POST] = true;
            }
            
            $curl_opts[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        if ($require_token) {
            $token = ApiAuthController::get_token();
            $curl_opts[CURLOPT_HTTPHEADER][] = 'Authorization: Bearer ' . $token;
        }
        

        curl_setopt_array($curl, $curl_opts);
        
        $response = curl_exec($curl);

        if (curl_error($curl)) {
            return ['error' => curl_error($curl)];
        }

        $response = json_decode($response, true);
        
        if (isset($response['status']) && $response['status'] !== 200) {
            return ['error' => $response['title']];
        }

        curl_close($curl);

        return $response;
    }

}