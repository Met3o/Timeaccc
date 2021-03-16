<?php
// Page for reetting calendars search
// Redirect from schedules.php
// Not visible to the user
// This is an includes page

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}
// Always get the British timezone
date_default_timezone_set('UTC');

// database connection
require_once ('config.php');

// Reset
$_SESSION['calendarYear'] = '';
unset($_SESSION['calendarYear']);
header('location:schedules.php');
