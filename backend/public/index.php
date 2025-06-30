<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); # allows frontend to make requests
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); # allows API to use GET, POST, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type"); # allows PHP recieve json from the frontend
header("Content-Type: application/json"); # the code within this PHP file is in json format

# define variables
define('BASE_PATH', realpath(dirname(__FILE__))); # pulls the directory name of index.php
$fileName = BASE_PATH . "/../data/staffList.json"; # moves up one file level to backend to find staffList.json in data file

$requestMethod = $_SERVER["REQUEST_METHOD"]; # pulls the request method request (GET vs POST)
$serverPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); # pulls the URL path

# handle requests from server/user
if ($serverPath === "/staffList") { # if requesting for staffList path
    if ($requestMethod === "GET") { # if request method is GET:
        # retrieve json information from file and display
        $staffListInfo = json_decode(file_get_contents($fileName), true);
        echo json_encode($staffListInfo);

    } elseif ($requestMethod === "POST") { # if request method is POST:
        $staffInput = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user
        if (!isset($staffInput["Name"], $staffInput["Role"], $staffInput["PhoneNumber"])) { # check all information is provided
            http_response_code(400);
            echo json_encode(["Error " => "Information is Missing!"]);
            exit;
        }

        $staffListInfo = json_decode(file_get_contents($fileName), true); # loads the exisiting staff list to append new staff
        # set the new staff information
        $newStaff = [
            "ID" => uniqid(), # unique ID for the staff in case of different staff with same information
            "Name" => $staffInput["Name"],
            "Role" => $staffInput["Role"],
            "PhoneNumber" => $staffInput["PhoneNumber"]
        ];
        $staffListInfo[] = $newStaff; # add new staff as a new entry to staff list
        file_put_contents($fileName, json_encode($staffListInfo, JSON_PRETTY_PRINT)); # save the entry in an easy-to-read format
        echo json_encode(["Message " => "Staff Successfully Added!", "staff" => $newStaff]); # verify successfully entry to user
    
    } else { # if unknown method is being called, throw error
        http_response_code(405);
        echo json_encode(["Method Error " => "Unknown Action. Please Try Again."]);
    }
} else {
    http_response_code(404); # if unknown URL path is being called, throw error
    echo json_encode(["URL Path Error " => "Unknown Action. Please Try Again."]);
}
?>