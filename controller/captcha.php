<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
} 

$error = "";
define("oklogs", "OK");
require('../model/logs.php');

define ("okdb", "OK");
require('../model/dbconnect.php');
require('../model/CaptchaModel.php');

define ("okcss", "OK");
require('../public/css/image_grid.php');
require('../view/CaptchaView.php');

