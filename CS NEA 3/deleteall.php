<?php
// Page for the user to choose to delete all of their schedules at once
// From schedules.php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
   header('location: login.php');
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
  <link rel = "stylesheet" href = "calendarstyle.css">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
</head>
<body>
   <?php
    // Database connection
    require_once ('config.php');
    // Include the navigation header
    include_once 'headerInSchedules.php';

  ?>
  <div id = "Ask" style = "width: inherit; height: 50px; font-size: 30; background-color: #f33; color: #fde;">
    Are you sure you want to delete ALL events? (CANNOT BE UNDONE)
  </div>
  <div id = "deleteholder">
    <form id = "deleteform" action = "deleteall.inc.php" method = "post">
      <div class = "schedulesdiv">
        <input type = "submit" id = "delete" name = "confirmdelete" value = "Yes, delete ALL" style = "
          height: 50px;
          width:200px;
          background-color: #f55;
          border-radius:15px;
          border-color:#f99;
          border-style: solid;
          font-family: "Montserrat", sans-serif;
        ">
        </input>
      </div>
    </form>
  <div class = "schedulesdiv">
  	<form id = "cancel" action = "schedules.php" method = "post">
      <input type = "submit" id = "canceldelete" name = "canceldelete" value = "No, go back" style = "
          height: 50px;
          width:200px;
          background-color: #5f5;
          border-radius:15px;
          border-color:#9f9;
          border-style: solid;
          font-family: "Montserrat", sans-serif;
        ">
  	</form>
  </div>
  </div>
</body>
</html>
