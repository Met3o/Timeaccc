<?php
// This page is meant to require the user to verify their password before accessing the option to delete their account, for extra security
// Redirect from accountdelete.php

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

// Include navigation header
include_once('headerInSchedules.php');

// Avoid the user being able to access the encrypted pages without verifying their password
$_SESSION['passwordVerified'] = false;

// If a form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  // Encryption for the page
  // If the user inputs their password correctly, they can gain access to delete their account
  $passwordVerifyTry = $_POST['passwordverify'];
  if ($passwordVerifyTry != $_SESSION['password']){
    // The password is NOT verified, so even if the user finds the link to the encrypted account management page, they cannot access it
    $_SESSION['passwordVerified'] = false;
    echo'Incorrect password.';
  }
  else{
    // The password is verified and so account management can be accessed
    $_SESSION['passwordVerified'] = true;
    header('location: accountdelete.php');
  }
    // For if the password was not correct, stay on the page
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
</head>
<body>
  <button onclick="location.href = 'accountoptions.php'"
  style = "width:100px; height:50px; background-color: #09f; color: #fff; font-family: 'Montserrat';
  font-size: 25px; border-style:solid; border-color: #fff;
  cursor:pointer;">Cancel</button>
  <div id = "Ask" style = "width: inherit; height: 50px; font-size: 30; background-color: #a54; color: #fde;">Delete account</div>
  <form method = "post" autocomplete="off">
    <div class = "userinforeturn">
    <label>Please enter your password to continue.</label>
    <input type = "password" name = "passwordverify"></input>
    <input type = "submit"></input>
  </form>
</body>
