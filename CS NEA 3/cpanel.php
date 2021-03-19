<?php
// Homepage
// User is redirected here upon logging in or changing their details

 // Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
// $_SESSION["loggedin"] parameter is a Boolean value that indicates whether the user has been logged in or not
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
  header('location: login.php');
  exit;
}

?>
<head>
  <title>Welcome to Timeacc, <?php echo strip_tags($_SESSION['userName']); ?>!</title>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel ="icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <link rel = "stylesheet" href = "calendarstyle.css">
</head>
<body id = "cpanel">
  <?php
  // Database configuration
  require_once ('config.php');

  // Header for website navigation
  include_once ('header.php');

  ?>
<br>
  <?php
  // Welcome message
  echo '<div id = "Welcome" style = "width: inherit; height:
  60px; font-size: 25;
  background-image:
  linear-gradient(to right, #b9d, #9cf);
  color: #def;
  border-color: #aef;
  border-style: solid;
  ">
  Welcome to Timeacc, ' . strip_tags($_SESSION['userName']) . '! Please click on a feature to begin!</div>';
  ?>

<link rel="stylesheet" href = "bannerstyle.css">
<link rel="stylesheet" href = "stopwatchstyle.css">
<script type = "text/javascript" src = "stopwatch.js"></script>
<!-- Welcome screen navigation-->
 <div id = "options">
    <div class = "features" id = "scheduleandcalendaroption">
    <button onclick = "location.href = 'schedules.php'" class = "featuresbutton">Calendar and Events</button>
    </div>
    <div class = "features" id = "stopwatchoption">
    <button onclick = "location.href = 'stopwatch.php'" class = "featuresbutton">Stopwatch</button>
    </div>
  	<div class = "features" id = "timeroption">
    <button onclick = "location.href = 'timer.php'" class = "featuresbutton">Timer</button>
    </div>
    <div class = "features" id = "timeroption">
    <button onclick = "location.href = 'notepad.php'" class = "featuresbutton">Notepad</button>
    </div>
  </div>
</body>
