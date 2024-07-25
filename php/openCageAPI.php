<?php
// Enable error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Require the httpHelper class
require 'httpHelper.php';

// Define the OpenCageAPI class which extends the httpHelper class
class OpenCageAPI extends httpHelper
{

    // Constructor method
    public function __construct()
    {
        // Call the respond method
        $this->respond();
    }

    // Private method to handle the API response
    private function respond()
    {
        try {
            // Get the start time of the execution
            $executionStartTime = microtime(true);

            // Get the place name from the request parameters
            $placeName = $_REQUEST["placeName"];

            // Set the API key
            $apiKey = "5b142ecf798f4429aa0129faf0515972";

            // Construct the URL for geocoding the place name
            $url = "https://api.opencagedata.com/geocode/v1/json?q=$placeName&key=$apiKey";

            // Make a cURL request to retrieve the geocoding data
            $result = $this->curlRequest($url);

            // Decode the JSON response data
            $data = json_decode($result['response'], true);

            // Check if the JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Error("none valid json returned");
            }

            // Generate the API response and echo it
            echo $this->generateResponse([
                'responseCode' => $result['code'],
                'data'         => $data,
                'message'      => '',
            ], $executionStartTime);
        } catch (Exception $e) {
            // Handle any exceptions and generate an error response
            echo $this->generateResponse([
                'responseCode' => 500,
                'data'         => null,
                'message'      => $e->getMessage(),
            ], $executionStartTime);
        }
    }
}

// Create a new instance of the OpenCageAPI class
new OpenCageAPI();