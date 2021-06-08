<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// Use this namespace
use Routes\Route;

// Include router class
include 'Routes/Route.php';
include 'Classes/CarClass.php';

// Define a global basepath
define('BASEPATH','/api_car/');
//echo BASEPATH;

// If your script lives in a subfolder you can use the following example
// Do not forget to edit the basepath in .htaccess if you are on apache
// define('BASEPATH','/api/v1');

$car_info = new CarClass();
$car_info->read();
echo json_encode($car_info->read());