<?php
// Enable error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Require the httpHelper class
require 'httpHelper.php';

// Define the WeatherAPI class which extends the httpHelper class
class WeatherAPI extends httpHelper
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
        // Get the start time of the execution
        $executionStartTime = microtime(true);
        
        try {
            // Retrieve the latitude and longitude from the request parameters
            $latitude = $_REQUEST['lat'];
            $longitude = $_REQUEST['lon'];

            // Provide the API key for accessing the weather data
            $key = "2e754d4fd3d6401385f131024242407";

            // Construct the URL for retrieving the weather forecast
            $url = "https://api.weatherapi.com/v1/forecast.json?q=$latitude,$longitude&days=3&key=$key";

            // Make a cURL request to retrieve the weather forecast data
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

// Create a new instance of the WeatherAPI class
new WeatherAPI();