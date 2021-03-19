<?php
// Reminds the user when they are in the site, of events they have for the current day
// Not a page; is included in some headers
// Has links to schedules.php

if(!isset($_SESSION['loggedin'])){
  session_start();
}
// Include database configuration/connection
require_once ('config.php');
require_once ('scheduleClass.php');
date_default_timezone_set('UTC');

// Find how many reminders are for each value (urgent, non-urgent)
$sql = ('SELECT * FROM (schedules, schedulesusers, schedulesurgencies)
WHERE schedulesusers.schedulesUserID = ?
AND schedules.schedulesID = schedulesusers.schedulesID
AND schedulesurgencies.schedulesUrgency = ?
AND schedulesurgencies.schedulesID = schedules.schedulesID
AND schedules.schedulesYear = ?
AND schedules.schedulesMonth = ?
AND schedules.schedulesDay = ?
ORDER BY schedules.schedulesYear ASC,
schedules.schedulesMonth ASC,
schedules.schedulesDay ASC,
schedules.schedulesTime ASC,
schedules.schedulesMinute ASC');
$stmt = $con->prepare($sql);
$stmt->bind_param('iiiii', $userID, $urgent3, $year, $month, $day);

$userID = $_SESSION['id'];
$urgent3 = 3;
$year = idate('Y');
$month = idate('m');
$day = idate('d');

$stmt->execute();
$resultUrgency = $stmt->get_result();
// singular vs plural grammar
if ($resultUrgency->num_rows == 1){
  $isOrAre = 'is';
}
else{
  $isOrAre = 'are';
}
$stmt->close();
// The amount of urgent reminders has been found


// Select all reminders
$sql = ('SELECT * FROM (schedules, schedulesusers, schedulesurgencies)
WHERE schedulesusers.schedulesUserID = ?
AND schedules.schedulesID = schedulesusers.schedulesID
AND schedulesurgencies.schedulesID = schedules.schedulesID
AND schedules.schedulesYear = ?
AND schedules.schedulesMonth = ?
AND schedules.schedulesDay = ?
ORDER BY schedules.schedulesYear ASC,
schedules.schedulesMonth ASC,
schedules.schedulesDay ASC,
schedules.schedulesTime ASC,
schedules.schedulesMinute ASC,
schedulesurgencies.schedulesUrgency DESC');
// Prepare the statement to the database connection
$stmt = $con->prepare($sql);
// Bind the statement to data
$stmt->bind_param('iiii', $scheduleuserid, $currentYear, $currentMonth, $currentDay);

// The value the statement, $stmt, is looking for
// Get the ID of the current user in the session, retrieved from the database in order to link users to their own data
// Get all of the user data from the schedules table
$scheduleuserid = $_SESSION['id'];
$currentYear = idate('Y');
$currentMonth = idate('m');
$currentDay = idate('d');


// Execute the prepared statement
$stmt->execute();
// Get the result, which measures whether there are any schedules in the user account yet, if not, display that none have ben created
$result = $stmt->get_result();
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <link rel = "stylesheet" href = "bannerstyle.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
  <script type = "text/javascript" src = "reminder.js"></script>
</head>
<body>
  <div id = "remindercontroller">
  <div id = "remindersindicateholder">
      <?php
      if ($result->num_rows == 0){
      echo '<div class = "schedulesindicate">No events for today... yet!</div>';
      }
      else{
        // Singular
        if ($result->num_rows === 1){
          echo '<div class = "schedulesindicate">You have: ' . $result->num_rows . ' event for today, of which: ' . $resultUrgency->num_rows . ' ' .$isOrAre .' urgent.
            <button onclick = "hide_reminders()" id = "showhide">Show</button></div>';
        }
        // Plural
        else{
          echo '<div class = "schedulesindicate">You have: ' . $result->num_rows . ' events for today, of which: ' . $resultUrgency->num_rows . ' ' .$isOrAre .' urgent.
            <button onclick = "hide_reminders()" id = "showhide">Show</button></div>';
        }
      }
      ?>
    <div id = "remindersholderholder">
      <button class = "scheduleselect" onclick = "location.href = 'schedules.php'" style = "width: 250px; height: 30px; margin-left: auto; position:relative; left:19%;">Go to schedules</button>
  <div id = "remindersholder">
    <div class="remindersindicate" style = "text-align: center;">All schedules list</div>
<?php
// Generate visual timestamps for every schedule in the database
while ($row = $result->fetch_assoc()) {
  $timeCulture = '';
  $schedulesID = $row['schedulesID'];
  $schedulesTitle = $row['schedulesName'];
  $schedulesYear = $row['schedulesYear'];
  $schedulesMonth = $row['schedulesMonth'];
  $schedulesDay = $row['schedulesDay'];
  $schedulesHour = $row['schedulesTime'];
  $schedulesMinute = $row['schedulesMinute'];
  $schedulesUrgency = $row['schedulesUrgency'];

  $reminder = new Reminder ($schedulesID, $schedulesTitle,
  $schedulesYear, $schedulesMonth, $schedulesDay, $schedulesHour,
  $schedulesMinute, $schedulesUrgency);
  $reminder->show();
}
$stmt->close();
?>
</div>
</div>
</div>
</div>
</body>
</html>
