<?php
// Page for the user to decide whether to delete their account or view & change details

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
// $_SESSION["loggedin"] parameter is a Boolean value that indicates whether the user has been logged in or not
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}
require_once ('config.php');
include_once ('headerInSchedules.php');
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <title>Account Options - Timeacc</title>
  <link rel = "stylesheet" href = "accountmanagementstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
</head>
<body>
  <div class = "accountoptions">
  <button onclick = "location.href = 'accountmanagement.php'" class = "featuresbutton">View and Edit Account Details</button>
  <button onclick = "location.href = 'accountdeleteverify.php'" class = "featuresbutton">Delete account</button>
  </div>
</body>
</html>
