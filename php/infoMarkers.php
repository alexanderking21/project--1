<?php
// Enable error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Require the httpHelper class
require 'httpHelper.php';

// Define the InfoMarkersAPI class which extends the httpHelper class
class InfoMarkersAPI extends httpHelper
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

            // Set the username key and country code from the request parameters
            $usernameKey = "alexanderking";
            $countryCode = $_REQUEST['country'];

            // Construct the URLs for city and airport data
            $cityUrl = "http://api.geonames.org/searchJSON?country=$countryCode&cities=cities15000&username=$usernameKey";
            $airportUrl = "http://api.geonames.org/searchJSON?q=airport&country=$countryCode&username=$usernameKey";

            // Make cURL requests to retrieve city and airport data
            $cityResult = $this->curlRequest($cityUrl);
            $airportResult = $this->curlRequest($airportUrl);

            // Decode the JSON response data
            $cityData = json_decode($cityResult['response'], true);
            $airportData = json_decode($airportResult['response'], true);

            // Check if the JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Error("none valid json returned");
            }

            // Generate the API response and echo it
            echo $this->generateResponse([
                'responseCode' => $cityResult['code'],
                'data'         => ['cities' => $cityData, 'airports' => $airportData],
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

// Create a new instance of the InfoMarkersAPI class
new InfoMarkersAPI();