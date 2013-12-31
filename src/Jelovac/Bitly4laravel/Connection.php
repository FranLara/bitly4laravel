<?php

namespace Jelovac\Bitly4laravel;

class Connection {

    /**
     * Default 443 for https, and 80 for http
     * @var integer port 
     */
    private static $port = 443;

    /**
     *
     * @var integer timeout
     */
    private static $timeout = 10;

    public function __construct() {
        if (!function_exists('curl_init')) {
            $message = "Sorry, But you need to have the CURL extension enabled in order to be able to use this class.";
            Log::critical($message);
            throw new Exception($message);
        }
    }

    public static function make($url, $port = null, $timeout = null) {
        if ($port !== null) {
            static::$port = $port;
        }
        if ($timeout !== null) {
            static::$timeout = $timeout;
        }
        // Initiate cURL
        $curl = curl_init();

        // Set parameters
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_PORT] = static::$port;
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_TIMEOUT] = static::$timeout;


        // Execute
        $response = curl_exec($curl);
        $headers = curl_getinfo($curl);

        // Fetch Errors
        $errorNumber = curl_errno($curl);
        $errorMessage = curl_error($curl);

        curl_close($curl);

        return array(
            'response' => array(
                'headers' => $headers,
                'content' => $response
            ),
            'error' => array(
                'number' => $errorNumber,
                'message' => $errorMessage
            )
        );
    }

}
