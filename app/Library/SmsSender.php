<?php
namespace App\Library;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

/**
 * The SMS sender library. 
 * 
 * It does NOT do data validation. It's the job of the controller to pass
 * correct data.
 */
class SmsSender {
    /**
     * $data is an associative array of the form:
     * [
     *      "mobile" => '9876543321',
     *      "country_code" => '91',
     *      "contents" => "You know what, go to hell!"
     * ]
     */
    public static function send($data) {
        $client = new Client();

        $customerKey = env('CUSTOMER_KEY');
        $apiKey = env('CUSTOMER_API_KEY');
        $currentTimeInMillis = round(microtime(true) * 1000);
        $stringToHash = $customerKey . $currentTimeInMillis . $apiKey;
        $hashValue = hash("sha512", $stringToHash);
        $result = $client->post(env('MINI_ORANGE_SEND_OTP_URL'), 
            [
                'json' => [
                    'customerKey' => $customerKey,
                    'phone' => '+'.$data['country_code'].$data['mobile'],  
                    'authType' => "SMS",
                    'transactionName' => "SERVOQUICK",
                ],
                'headers' => [
                    'Customer-Key' => $customerKey,
                    'Timestamp' => $currentTimeInMillis,
                    'Authorization' => $hashValue,
                    'Content-Type' => 'application/json'
                ]
            ]           
        );

        $result = json_decode($result->getBody(), true);      
        return $result['txId'];
    }

    public static function verify($data) {
        $client = new Client();

        $customerKey = env('CUSTOMER_KEY');
        $apiKey = env('CUSTOMER_API_KEY');
        $currentTimeInMillis = round(microtime(true) * 1000);
        $stringToHash = $customerKey . $currentTimeInMillis . $apiKey;
        $hashValue = hash("sha512", $stringToHash);
        $result = $client->post(env('MINI_ORANGE_VALIDATE_OTP_URL'), 
            [
                'json' => [
                    'txId' => $data['txt_id'],
                    'token' => $data['token'],  
                ],
                'headers' => [
                    'Customer-Key' => $customerKey,
                    'Timestamp' => $currentTimeInMillis,
                    'Authorization' => $hashValue,
                    'Content-Type' => 'application/json'
                ]
            ]           
        );
        $result = json_decode($result->getBody(), true); 
        return $result['status'];
    }
}