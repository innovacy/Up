<?php

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
$up = new \Innovacy\Up();
$output = $up->run();
// TODO: Caching for faster processing - on the other side, is this not superfast already? (Low priority)
echo $output;
