<?php
// Page for finding calendars based on a certain year
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

if (isset($_POST['calendaryearsearch'])){
  if ($_POST['calendaryearsearch'] > 1){
    $_SESSION['calendarYear'] = (int)($_POST['calendaryearsearch']);
  }
  elseif ($_POST['calendaryearsearch'] == 1){
    $_SESSION['calendarYear'] = (int)($_POST['calendaryearsearch']);
  }
  else{
    $_SESSION['calendarYear'] = (int)($_POST['calendaryearsearch']) + (1-(int)($_POST['calendaryearsearch']));
  }
  // Maximum
  if ($_SESSION['calendarYear'] >= 99999999999999999){
    $_SESSION['calendarYear'] = 99999999999999998;
  }
}

//redirect
header('location: schedules.php');
