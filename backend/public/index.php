<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); # allows frontend to make requests
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); # allows API to use GET, POST, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type"); # allows PHP recieve json from the frontend
header("Content-Type: application/json"); # the code within this PHP file is in json format

# define variables
define('BASE_PATH', realpath(dirname(__FILE__))); # pulls the directory name of index.php
$staffListFile = BASE_PATH . "/../data/staffList.json"; # moves up one file level to backend to find staffList.json in data file
$assignedShiftsFile = BASE_PATH . "/../data/assignedShifts.json"; # moves up one file level to backend to find assignedShifts.json in data file


$requestMethod = $_SERVER["REQUEST_METHOD"]; # pulls the request method request (GET vs POST)
$serverPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); # pulls the URL path

# handle requests from server/user

# if requesting for staffList path
if ($serverPath === "/staffList") {
    if ($requestMethod === "GET") { # if request method is GET
        # retrieve json information from file and display
        $staffListInfo = json_decode(file_get_contents($staffListFile), true);
        echo json_encode($staffListInfo);

    } elseif ($requestMethod === "POST") { # if request method is POST
        $staffInput = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user
        if (!isset($staffInput["Name"], $staffInput["Role"], $staffInput["PhoneNumber"])) { # check all information is provided
            http_response_code(400);
            echo json_encode(["Error" => "Staff Information is Missing!"]);
            exit;
        }

        $staffListInfo = json_decode(file_get_contents($staffListFile), true); # loads the exisiting staff list to append new staff
        # set the new staff information
        $newStaff = [
            "ID" => uniqid(), # unique ID for the staff in case of different staff with same information
            "Name" => $staffInput["Name"],
            "Role" => $staffInput["Role"],
            "PhoneNumber" => $staffInput["PhoneNumber"]
        ];

        $staffListInfo[] = $newStaff; # add new staff as a new entry to staff list

        file_put_contents($staffListFile, json_encode($staffListInfo, JSON_PRETTY_PRINT)); # save the entry in an easy-to-read format
        echo json_encode(["Message " => "Staff Successfully Added!", "staff" => $newStaff]); # verify successfully entry to user
    
    } else { # if unknown method is being called, throw error
        http_response_code(405);
        echo json_encode(["Method Error" => "Unknown Action. Please Try Again."]);
    }


# if requesting for shifts path
} elseif ($serverPath === "/shifts") { 
    if ($requestMethod === "GET") { # if request method is GET
        # retrieve json information from file and display
        $shifts = json_decode(file_get_contents($assignedShiftsFile), true);
        echo json_encode($shifts);

    } elseif ($requestMethod === "POST") { # if request method is POST
        $data = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user
        if (!isset($data["day"], $data["start"], $data["end"], $data["role"])) { # check all information is provided
            http_response_code(400);
            echo json_encode(["Error" => "Shift Information is Missing!"]);
            exit;
        }

        $shifts = json_decode(file_get_contents($assignedShiftsFile), true); # loads the exisiting shifts list to append new shift
        # create new shift entry
        $newShift = [
            "ID" => uniqid(), #  unique ID for the shift
            "day" => $data["day"],
            "start" => $data["start"],
            "end" => $data["end"],
            "role" => $data["role"],
            "staffID" => $data["staffId"] ?? null  # staffID will be empty if the new shift created isn't assigned yet
        ];

        $shifts[] = $newShift; # add new staff as a new entry to staff list

        file_put_contents($assignedShiftsFile, json_encode($shifts, JSON_PRETTY_PRINT)); # save the entry in an easy-to-read format
        echo json_encode(["Message" => "Shift Successfully Created!", "shift" => $newShift]); # verify successfully entry to user

    } else { # if unknown method is being called, throw error
        http_response_code(405);
        echo json_encode(["Method Error" => "Unknown Action. Please Try Again."]);
    }

} else { # if unknown URL path is being called, throw error
    http_response_code(404);
    echo json_encode(["URL Path Error" => "Unknown Action. Please Try Again."]);
}
?>