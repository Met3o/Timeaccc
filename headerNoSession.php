<?php
// Header for the top of the page, no session
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href = "bannerstyle.css">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
<div class = "links">
    <a href="schedules.php" style="float: left; margin-left: 20;">Schedule & Calendar</a>
    <a href="stopwatch.php" style="float: left; margin-left: 20;">Stopwatch</a>
    <a href="timer.php" style="float: left;margin-left: 20;">Timer</a>
    <a href="notepad.php" style="float: left;margin-left: 20;">Notepad</a>
    <a class="Login" href = "login.php" style="float: right; margin-left: 20; margin-right: 20;">Refresh Login</a>
    <a class="Logout" href = "logout.inc.php" style="float: right;margin-left: 20;">Logout</a>
    <a class="Manage" href = "accountoptions.php" style="float: right;margin-left: 20; margin-right: 20;">Manage account</a>
    <?php
    include_once('clock.php');
    $_SESSION['calendaryearsearch'] = '';
    ?>
</div>
</li>
</div>
<br><br>
</body>
