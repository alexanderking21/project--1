<?php

// Set error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the 'httpHelper.php' file
require 'httpHelper.php';

// Define the WikiAPI class that extends the httpHelper class
class WikiAPI extends httpHelper
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

            // Get the requested country capital and country code from the request
            $countryCapital = $_REQUEST['countryCapital'];
            $countryCode = $_REQUEST['country'];

            // Create the API URL with the requested country capital, country code, and other parameters
            $url = "http://api.geonames.org/wikipediaSearchJSON?q=$countryCapital&countryCode=$countryCode&maxRows=10&username=alexanderking&style=full";

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

// Create a new instance of the WikiAPI class, which triggers the execution of the code
new WikiAPI();