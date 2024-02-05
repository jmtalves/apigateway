<?php

// start
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", "error.log");
require_once("autoload.php");


$Start  = new Start();
$Start->init();
exit();
