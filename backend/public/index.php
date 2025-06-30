<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); # allows frontend to make requests
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); # allows API to use GET, POST, and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type"); # allows PHP recieve JSON from the frontend
header("Content-Type: application/json"); # the code within this PHP file is in JSON format

?>
