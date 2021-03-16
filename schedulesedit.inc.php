<?php
// Page for inserting user schedule edits into the database
// Redirect from schedulesedit.php
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
?>
<?php
require_once ('config.php');

ini_set('display_errors', 1);
ini_set('log_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// If there is no connection then return error message and terminate the program
if (!$con){
  die('connection failed : ' . $con->connect_error);
}

// Get the ID of the current user in the session, inserted into the database in order to link users to their own data
// Used by $stmt below but declared in a global scope
$scheduleuserid = $_SESSION['id'];

// Measuring the whole time string of the current time and comparing it to the submitted time. If the current time is
// greater than the submitted time then the event has already elapsed so it is not submitted. Otherwise, it is.
$duration = $_POST['schedulesduration'];
$minute = idate('i');
$hr = date('G');
$day = date('d');
$month = date('m');
$year = date('Y');
$wholeCurrentTime = $minute + ($hr * 100) + ($day * 10000) + ($month * 1000000) + ($year * 100000000);

if (isset($_POST['submit'])){
  $submitYear = $_POST['schedulesyear'];
  $submitMonth = $_POST['schedulesmonth'];
  $submitDay = $_POST['schedulesday'];
  $submitHr = $_POST['scheduleshour'];
  $submitMinute = (int)($_POST['schedulesminute']);
  $submitDuration = (int)($_POST['schedulesduration']);
  $wholePostedTime = $submitDuration + $submitMinute + ($submitHr * 100) + ($submitDay * 10000) + ($submitMonth * 1000000) + ($submitYear * 100000000);
  if ($wholePostedTime < $wholeCurrentTime + $submitDuration){
    $confirmInsert = false;
  }
  else {
    $confirmInsert = true;
  }
}

// If wholeCurrentTime or wholePostedTime ends with 0-39 then add 40
if ((int)(substr($wholePostedTime, -2) < 40)){
  $wholePostedTime = $wholePostedTime - 40;
}
if ((int)(substr($wholeCurrentTime, -2) < 40)){
  $wholeCurrentTime = $wholeCurrentTime - 40;
}

// Exercise the insertion of the schedule into the user database if there is a connection AND the data submitted is greater or equal to the current date provided.
// Amended post-client testing
if ($con){
  $_SESSION['schedulesRefinedQuery'] = '';
  // Presence check for the title field
  if (empty(trim($_POST['schedulestitle']))){
    header('location: schedulesedit.php?error=emptytitle');
    exit;
  }
  // Range check for the date fields
  if ($wholeCurrentTime > $wholePostedTime + 5){
    header('location: schedulesedit.php?error=elapseddate');
    exit;
  }
  // Length check for the description (no longer than 500 characters)
  if (strlen($_POST['schedulesdescription']) > 500){
    // Get the number of characters that were posted, so that they can be displayed to the user
    $_SESSION['descriptionTooLong'] = strlen(trim($_POST['schedulesdescription']));
    header('location: schedulesedit.php?error=descriptiontoolong');
    exit;
  }

  // Length check for the name/title (no longer than 30 characters)
  if (strlen($_POST['schedulestitle']) > 30){
    // Get the number of characters that were posted, so that they can be displayed to the user
    $_SESSION['nameTooLong'] = strlen(trim($_POST['schedulestitle']));
    header('location: schedulesedit.php?error=nametoolong');
    exit;
  }
  // or if no error messages have been shown
  else{
    $sql = ('UPDATE schedules
    SET schedulesName = ?,
    schedulesDescription = ?,
    schedulesYear = ?,
    schedulesMonth = ?,
    schedulesDay = ?,
    schedulesTime = ?,
    schedulesMinute = ?,
    schedulesDuration = ?
    WHERE schedulesID = ?');
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssiiiiiii', $schedulesGetTitle, $schedulesGetDescription, $schedulesGetYear, $schedulesGetMonth, $schedulesGetDay, $schedulesGetHour, $schedulesGetMinute, $schedulesGetDuration, $schedulesGetID);
    // The values that the statement, $stmt, is looking for, and the statements below
    if (isset($_POST['submit'])) {
      // Assign the values submitted through the form into variables
      $schedulesGetID = $_SESSION['schedulesID'];
      $schedulesGetTitle = $_POST['schedulestitle'];
      $schedulesGetDescription = $_POST['schedulesdescription'];
      $schedulesGetYear = $_POST['schedulesyear'];
      $schedulesGetMonth = $_POST['schedulesmonth'];
      $schedulesGetDay = $_POST['schedulesday'];
      $schedulesGetHour = $_POST['scheduleshour'];
      $schedulesGetMinute = (int)($_POST['schedulesminute']);
      $schedulesGetDuration = (int)($_POST['schedulesduration']);
      $schedulesGetUrgency = $_POST['schedulesurgency'];
    }

    // Execute
    $stmt->execute();
    // Close the statement
    $stmt->close();

    // Update the schedulesurgencies table
    $sql = 'UPDATE schedulesurgencies
    SET schedulesUrgency = ? WHERE schedulesID = ?';
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $schedulesGetUrgency, $schedulesGetID);
    $stmt->execute();
    $stmt->close();
    $con->close();

    header('location: schedules.php');
  }
}
?>
 <meta http-equiv="Refresh" content="0;url=schedules.php" />
