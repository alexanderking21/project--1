<?php

// Include the 'httpHelper.php' file
require 'httpHelper.php';

// Define the CountryController class that extends the httpHelper class
class CountryController extends httpHelper
{

    private array $data;

    // Constructor function
    function __construct()
    {
        // Start the execution timer
        $executionStartTime = microtime(true);
        try {
            // Fetch the file data and assign it to the data property
            $this->data = $this->fetchFileData();

            // Check if the 'iso' parameter is present in the GET request
            if (!empty($_GET['iso'])) {
                // Fetch the country border data based on the ISO code
                $returnData = $this->fetchCountryBorderOnIso($_GET['iso']);
            } else {
                // Fetch all country codes and names
                $returnData = $this->fetchAllCountryCodes();
            }

            // Generate the response using the generateResponse method from the httpHelper class
            echo $this->generateResponse(['responseCode' => 200, 'data' => $returnData], $executionStartTime);
        } catch (Exception $e) {
            // If an exception occurs, generate a response with a 500 response code and the error message
            echo $this->generateResponse([
                'responseCode' => 500,
                'data'         => null,
                'message'      => $e->getMessage(),
            ], $executionStartTime);
        }
    }

    // Private function to fetch the file data
    private function fetchFileData()
    {
        // Read the JSON data from the 'countryBorders.geo.json' file and decode it into an associative array
        $data = file_get_contents('../data/countryBorders.geo.json');
        $jsonData = json_decode($data, true);
        return json_decode($data, true);
    }

    // Private function to fetch the country border data based on the ISO code
    private function fetchCountryBorderOnIso($isoCode)
    {
        $isoCode = $_GET['iso']; // Get the ISO code from the query parameter
        $jsonData = $this->data;

        foreach ($jsonData['features'] as $feature) {
            // Check if the ISO code of the feature matches the requested ISO code
            if ($feature['properties']['iso_a2'] === $isoCode) {
                // Get the border data of the feature
                $border = $feature['geometry'];
                echo json_encode($border);
                break;
            }
        }
        return null; // Return Null if no matching ISO code is found
    }

    // Private function to fetch all country codes and names
    private function fetchAllCountryCodes()
    {
        $outputData = array();

        foreach ($this->data['features'] as $feature) {
            // Get the country name, ISO code, and ISO code 3 of each feature
            $countryName = $feature['properties']['name'];
            $isoCode = $feature['properties']['iso_a2'];
            $isoCode3 = $feature['properties']['iso_a3'];
            // Add the country data to the outputData array
            $outputData[] = array('countryName' => $countryName, 'iso_a2' => $isoCode, 'iso_a3' => $isoCode3);
        }
        return $outputData; // Return the list of country codes
    }
}

// Create a new instance of the CountryController class, which triggers the execution of the code
new CountryController();