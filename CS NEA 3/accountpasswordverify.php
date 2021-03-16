<?php
// Page to require the user's password permission before they change their account details

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
// $_SESSION["loggedin"] parameter is a Boolean value that indicates whether the user has been logged in or not
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $passwordVerifyTry = $_POST['passwordverify'];
  if ($passwordVerifyTry != $_SESSION['password']){
    // The password is not verified, so even if the user finds the link to the account management page, they cannot access it
    $_SESSION['passwordVerified'] = false;
    echo'Incorrect password.';
  }
  else{
    // The password is verified and so account management can be accessed
    $_SESSION['passwordVerified'] = true;
    header('location: accountchange.php?error=none');
    exit;
  }
    // For if the password was not correct, stay on the page
}
// Include navigation header
include_once('headerInProgram.php');
// Include database configuration/connection
require_once('config.php');


?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "stylesheet" href = "accountmanagementstyle.css">
</head>
<body>
  <button onclick="location.href = 'accountoptions.php'" name = "cancel"
  style = "width:100px; height:50px; background-color: #09f; color: #fff; font-family: 'Montserrat';
  font-size: 25px; border-style:solid; border-color: #fff;
  cursor:pointer">Cancel</button>
  <div id = "Ask" style = "width: inherit; height: 50px; font-size: 30; background-color: #45a; color: #fde;">Change details</div>
  <form method = "post" autocomplete="off">
    <div class = "userinforeturn">
    <label>Please enter your password to continue.</label>
    <input type = "password" name = "passwordverify"></input>
    <input type = "submit"></input>
  </div>
  </form>
</body>
</html>
