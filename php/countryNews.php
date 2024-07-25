<?php

// Set error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors#', 1);

// Include the 'httpHelper.php' file
require 'httpHelper.php';

// Define the NewsAPI class that extends the httpHelper class
class NewsAPI extends httpHelper
{
    // Constructor function
    public function __construct()
    {
        $this->respond();
    }

    // Private function to handle the API request and generate the response
    private function respond()
    {
        try {
            // Start the execution timer
            $executionStartTime = microtime(true);

            // Get the requested country for the news
            $countryNews = strtolower($_REQUEST['country']);

            // Set the API key
            $apikey = "6bd6d050232dad35738da730f323793e";

            // Create the API URL with the requested country and the API key
            $url = "https://gnews.io/api/v4/top-headlines?category=general&country=$countryNews&lang=en&apikey=$apikey";

            // Make a curl request to the API URL
            $result = $this->curlRequest($url);

            // Decode the response data into an associative array
            $data = json_decode($result['response'], true);

            // Check if the response is a valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Error("none valid json returned");
            }

            // Generate the response using the generateResponse method from the httpHelper class
            echo $this->generateResponse([
                'responseCode' => $result['code'],
                'data'         => $data,
                'message'      => '',
            ], $executionStartTime);
        } catch (Exception $e) {
            // If an exception occurs, generate a response with a 500 response code and the error message
            echo $this->generateResponse([
                'responseCode' => 500,
                'data'         => null,
                'message'      => $e->getMessage(),
            ], $executionStartTime);
        }
    }
}

// Create a new instance of the NewsAPI class, which triggers the execution of the code
new NewsAPI();