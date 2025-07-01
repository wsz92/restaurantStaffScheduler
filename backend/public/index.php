<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); # allows frontend to make requests
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS"); # allows API to use GET, POST, DELETE, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type"); # allows PHP recieve json from the frontend
header("Content-Type: application/json"); # the code within this PHP file is in json format

# define variables
define('BASE_PATH', realpath(dirname(__FILE__))); # pulls the directory name of index.php
$staffListFile = BASE_PATH . "/../data/staffList.json"; # moves up one file level to backend to find staffList.json in data file
$assignedShiftsFile = BASE_PATH . "/../data/assignedShifts.json"; # moves up one file level to backend to find assignedShifts.json in data file
$requestMethod = $_SERVER["REQUEST_METHOD"]; # pulls the request method request (GET vs POST)
$serverPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); # pulls the URL path

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { # allows the add new staff/shift pop-up to continue
    http_response_code(200);
    exit;
}

# handle requests from server/user:

# if requesting for staffList path
if ($serverPath === "/staffList") {
    if ($requestMethod === "GET") { # if request method is GET
        # retrieve json information from file and display
        $staffListInfo = json_decode(file_get_contents($staffListFile), true);
        echo json_encode($staffListInfo);

    } elseif ($requestMethod === "POST") { # if request method is POST
        $staffInput = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user
        if (!isset($staffInput["Name"], $staffInput["Role"], $staffInput["Phone"])|| empty(trim($staffInput["Name"])) ||  empty(trim($staffInput["Role"])) || empty(trim($staffInput["Phone"]))) { # checks if fields are left empty
            http_response_code(400);
            echo json_encode(["Error" => "Staff Information is Missing!"]);
            exit;
        }

        $staffListInfo = json_decode(file_get_contents($staffListFile), true); # loads the exisiting staff list to append new staff
        if (!is_array($staffListInfo)) { # if the staff list is empty to start
            $staffListInfo = [];
        }
    
        # set the new staff information
        $newStaff = [
            "ID" => uniqid(), # unique ID for the staff in case of different staff with same information
            "name" => $staffInput["Name"],
            "role" => $staffInput["Role"],
            "phone" => $staffInput["Phone"]
        ];

        $staffListInfo[] = $newStaff; # add new staff as a new entry to staff list

        file_put_contents($staffListFile, json_encode($staffListInfo, JSON_PRETTY_PRINT)); # save the entry in an easy-to-read format
        echo json_encode(['staff' => $newStaff]);
    

    } elseif ($requestMethod === "DELETE") { # if request method is DELETE
        $staffInput = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user when button is clicked
        if (!isset($staffInput["ID"])) { # program checks that there is the ID to be deleted
            http_response_code(400);
            echo json_encode(["Error" => "Missing Staff ID for Deletion!"]);
            exit;
        }

        $staffListInfo = json_decode(file_get_contents($staffListFile), true); # loads the exisiting staff list to delete chosen staff
        $updatedStaffList = [];
        foreach ($staffListInfo as $staff) { # loop through the existing staff list and add the to the updated list as long as the ID doesn't match the DELETE staff ID
            if ($staff["ID"] !== $staffInput["ID"]) {
                $updatedStaffList[] = $staff;
            }
        }
        file_put_contents($staffListFile, json_encode(array_values($updatedStaffList), JSON_PRETTY_PRINT)); # save the file in an easy-to-read format

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
        $inputShift = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user
        if (!isset($inputShift["Day"], $inputShift["StaffID"], $inputShift["Start"], $inputShift["End"], $inputShift["Role"])|| empty(trim($inputShift["Day"])) || empty(trim($inputShift["StaffID"])) || empty(trim($inputShift["Start"])) || empty(trim($inputShift["End"])) || empty(trim($inputShift["Role"]))) { # checks if fields are left empty
            http_response_code(400);
            echo json_encode(["Error" => "Shift Information is Missing!"]);
            exit;
        }

        $shifts = json_decode(file_get_contents($assignedShiftsFile), true); # loads the exisiting shifts list to append new shift
        if (!is_array($shifts)) { # if the shift list is empty to start
            $shifts = [];
        }
    
        # create new shift entry
        $newShift = [
            "ID" => uniqid(), #  unique ID for the shift
            "day" => $inputShift["Day"],
            "staffId" => $inputShift["StaffID"],
            "start" => $inputShift["Start"],
            "end" => $inputShift["End"],
            "role" => $inputShift["Role"],
        ];

        $shifts[] = $newShift; # add new staff as a new entry to staff list

        file_put_contents($assignedShiftsFile, json_encode($shifts, JSON_PRETTY_PRINT)); # save the entry in an easy-to-read format
        echo json_encode(['shift' => $newShift]);

    } elseif ($requestMethod === "DELETE") { # if request method is DELETE
        $shiftInput = json_decode(file_get_contents("php://input"), true); # retrieve the information inputted by user when button is clicked
        if (!isset($shiftInput["ID"])) { # program checks that there is the ID to be deleted
            http_response_code(400);
            echo json_encode(["Error" => "Missing Staff ID for Deletion!"]);
            exit;
        }

        $shifts = json_decode(file_get_contents($assignedShiftsFile), true); # loads the exisiting staff list to delete chosen staff
        $updatedShiftList = [];
        foreach ($shifts as $shift) { # loop through the existing staff list and add the to the updated list as long as the ID doesn't match the DELETE staff ID
            if ($shift["ID"] !== $shiftInput["ID"]) {
                $updatedShiftList[] = $shift;
            }
        }
        file_put_contents($assignedShiftsFile, json_encode(array_values($updatedShiftList), JSON_PRETTY_PRINT)); # save the file in an easy-to-read format


    } else { # if unknown method is being called, throw error
        http_response_code(405);
        echo json_encode(["Method Error" => "Unknown Action. Please Try Again."]);
    }

} else { # if unknown URL path is being called, throw error
    http_response_code(404);
    echo json_encode(["URL Path Error" => "Unknown Action. Please Try Again."]);
}
?>