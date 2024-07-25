<?php
// Enable error reporting and display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the 'httpHelper.php' file
require 'httpHelper.php';

// Define the BankHolidayAPI class that extends the httpHelper class
class BankHolidayAPI extends httpHelper
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
        try {
            // Start the execution timer
            $executionStartTime = microtime(true);

            // Get the country code from the request parameters
            $countryCode = $_REQUEST['country'];

            // Get the current year
            $currentYear = date('Y');

            // Construct the URL for the Nager Date API
            $url = "https://date.nager.at/api/v3/publicholidays/$currentYear/$countryCode";

            // Make a request to the API using the curlRequest method from the httpHelper class
            $result = $this->curlRequest($url);

            // Decode the response JSON into an associative array
            $data = json_decode($result['response'], true);

            // Check if there was an error decoding the JSON
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

// Create a new instance of the BankHolidayAPI class, which triggers the execution of the code
new BankHolidayAPI();