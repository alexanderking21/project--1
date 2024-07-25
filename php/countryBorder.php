<?php
// Enable error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the 'httpHelper.php' file
require 'httpHelper.php';

// Define the CountryBorderInformation class that extends the httpHelper class
class CountryBorderInformation extends httpHelper
{

    // Constructor function
    public function __construct()
    {
        // Call the respond function
        $this->respond();
    }

    // Private function that performs the main logic
    private function respond()
    {
        // Start the execution timer
        $executionStartTime = microtime(true);
        try {
            // Get the ISO code from the query parameter
            $isoCode = $_GET['iso'];

            // Read the JSON data from the 'countryBorders.geo.json' file and decode it into an associative array
            $jsonData = json_decode(file_get_contents('../data/countryBorders.geo.json'), true);

            // Initialize an empty array to store the border data
            $borderData = [];

            // Iterate through each feature in the JSON data
            foreach ($jsonData['features'] as $feature) {
                // Check if the ISO code of the feature matches the requested ISO code
                if ($feature['properties']['iso_a2'] === $isoCode) {
                    // Add the ISO code (iso_a3) to the feature array
                    $feature['iso_a3'] = $feature['properties']['iso_a3'];
                    // Assign the feature array to the borderData variable
                    $borderData = $feature;
                    // Exit the loop since the desired border data has been found
                    break;
                }
            }

            // Check if there was an error decoding the JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Error("none valid json returned");
            }

            // Generate the response using the generateResponse method from the httpHelper class
            echo $this->generateResponse([
                'responseCode' => 200,
                'data'         => $borderData,
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

// Create a new instance of the CountryBorderInformation class, which triggers the execution of the code
new CountryBorderInformation();