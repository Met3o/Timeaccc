<?php
// Page to hold the database configuration
// The server names and database names differ depending on the server host (localhost for testing)

ini_set('display_errors', 1);
ini_set('log_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// ionos
/*
$dbServerName = 'db5001648107.hosting-data.io';
// Whatever our server name is
$dbUsername = 'dbu866314';
// Database default username
$dbPassword = 'Ilikemyself14!$';
// Database access password
$dbName = 'dbs1367287';
// Database name
*/
//localhost

// Whatever our server name is
$dbServerName = 'localhost';
// Database default username
$dbUsername = 'root';
// Database access password
$dbPassword = '';
// Database name
$dbName = 'timeacc';

//  Database connection
$con = mysqli_connect($dbServerName, $dbUsername, $dbPassword, $dbName);

// Check connection
if($con === false){
    die('ERROR: Could not connect. ' . mysqli_connect_error());
}
//No closing the code as this is raw included php
