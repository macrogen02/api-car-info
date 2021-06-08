<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
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

//:car_id,:car_brand,:car_plate,:'',:car_owner,:is_lost,:created_by,:created_date,:'',:''
$car_info = new CarClass();
$car_info->create(9,'Toyota Fortuner','HGL 7825','Danny Green',false,2,'2017-08-12',0,'2017-08-12');
//echo json_encode($car_info->create(1));