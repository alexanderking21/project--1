<?php

// Set error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the 'httpHelper.php' file
require 'httpHelper.php';

// Define the GeoJson class that extends the httpHelper class
class GeoJson extends httpHelper
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

            // Read the content of the 'countryBorders.geo.json' file
            $result = file_get_contents('../data/countryBorders.geo.json');

            // Decode the content of the file into an associative array
            $data = json_decode($result['response'], true);

            // Check if the content is a valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Error("none valid json returned");
            }

            // Generate the response using the generateResponse method from the httpHelper class
            echo $this->generateResponse([
                'responseCode' => 200,
                'data'         => $data['features'],
                'message'      => 'Success',
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

// Create a new instance of the GeoJson class, which triggers the execution of the code
new GeoJson();