<?php
// Page for the user to choose the option of deleting their account or cancelling
// Visible to the user

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
// $_SESSION["loggedin"] parameter is a Boolean value that indicates whether the user has been logged in or not
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}
// Include database configuration/connection
require_once('config.php');

// The header
require_once('headerNoSession.php');

// Encrypt the sensitive data handling pages; they need this value to be true to be accessed, for security
if ($_SESSION['passwordVerified'] !== true){
  header('location: accountdeleteverify.php');
  exit;
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "stylesheet" href = "accountmanagementstyle.css">
  <link rel = "stylesheet" href = "bannerstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <title> Timeacc - Delete account?</title>
</head>
<body>
  <button class = "action" style = "font-family: 'Montserrat', sans-serif; font-size:30px;" onclick = "location.href = 'accountdelete.inc.php'">Delete</button>
  <button onclick="location.href = 'accountoptions.php'" name = "cancel"
  style = "width:100px; height:50px; background-color: #09f; color: #fff; font-family: 'Montserrat'; font-size: 25px; border-style:solid; border-color: #fff;
  cursor:pointer">Cancel</button>
  <div id = "Ask" style = "width: inherit; height: 50px; font-size: 30; background-color: #f33;
  border-color: #faa; border-style: solid; color: #fde;">
  Are you sure you want to delete your Timeacc account? (ALL DATA WILL BE LOST)
</div>
</body>
</html>
