<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Use this namespace
use Routes\Route;

// Include router class
include 'Routes/Route.php';
include 'Classes/CarClass.php';

// Define a global basepath
define('BASEPATH','/api_car/');

// If your script lives in a subfolder you can use the following example
// Do not forget to edit the basepath in .htaccess if you are on apache
// define('BASEPATH','/api/v1');
//echo "testing";

$car_info = new CarClass();
$car_info->delete(7);
//echo json_encode($car_info->delete(7));