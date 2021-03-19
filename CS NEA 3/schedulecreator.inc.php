<?php
// Executor for the user creating their schedules
// Redirect from schedulecreator.php
// Not visible to the user

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
$minute = idate('i');
$hr = idate('H');
$day = idate('d');
$month = idate('m');
$year = idate('Y');
$wholeCurrentTime = $minute + ($hr * 100) + ($day * 10000) + ($month * 1000000) + ($year * 100000000);

if (isset($_POST['submit'])){
  $submitYear = (int)($_POST['schedulesyear']);
  $submitMonth = (int)($_POST['schedulesmonth']);
  $submitDay = (int)($_POST['schedulesday']);
  $submitHr = (int)($_POST['scheduleshour']);
  $submitMinute = (int)($_POST['schedulesminute']);
  $submitDuration = (int)($_POST['schedulesduration']);
  $wholePostedTime = $submitMinute + $submitDuration + ($submitHr * 100) + ($submitDay * 10000) + ($submitMonth * 1000000) + ($submitYear * 100000000);
  if ($wholePostedTime < $wholeCurrentTime){
    $confirmInsert = false;
  }
  else {
    $confirmInsert = true;
  }
}

// Exercise the insertion of the schedule into the user database if there is a connection AND the data submitted is greater or equal to the current date provided.
// Amended post-client testing
if ($con){
  $_SESSION['schedulesRefinedQuery'] = '';
  // Presence check for the title field
  if (empty(trim($_POST['schedulestitle'])) && strlen($_POST['schedulestitle2']) < 1){
    header('location: schedulecreator.php?error=emptytitle');
    exit;
  }
  // Range check for the date fields
  if ($wholeCurrentTime > $wholePostedTime + $submitDuration + 5){
    header('location: schedulecreator.php?error=elapseddate');
    exit;
  }
  // Length check for the description (no longer than 500 characters)
  if (strlen($_POST['schedulesdescription']) > 500){
    // Get the number of characters that were posted, so that they can be displayed to the user
    $_SESSION['descriptionTooLong'] = strlen(trim($_POST['schedulesdescription']));
    header('location: schedulecreator.php?error=descriptiontoolong');
    exit;
  }
  // Length check for the name/title (no longer than 30 characters)
  if (strlen($_POST['schedulestitle']) > 30){
    // Get the number of characters that were posted, so that they can be displayed to the user
    $_SESSION['nameTooLong'] = strlen(trim($_POST['schedulestitle']));
    header('location: schedulecreator.php?error=nametoolong');
    exit;
  }
  // or if no error messages have been shown
  else{
    $sql = ('INSERT INTO schedules (schedulesName, schedulesDescription, schedulesYear, schedulesMonth, schedulesDay, schedulesTime, schedulesMinute, schedulesDuration) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ssiiiiii', $schedulestitle, $schedulestext, $schedulesyear, $schedulesmonth, $schedulesday, $scheduleshour, $schedulesminute, $schedulesduration);
    // The values that the statement, $stmt, is looking for
    if (isset($_POST['submit'])) {
      // Assign the values submitted through the form into variables
      $schedulesuserid = $_SESSION['id'];
      // If schedules options selected without title typed in manually
      if (strlen($_POST['schedulestitle']) < 1 && strlen ($_POST['schedulestitle2']) > 0){
        $schedulestitle = $_POST['schedulestitle2'];
      }
      // Else refer to the title the user typed in manually
      else{
        $schedulestitle = $_POST['schedulestitle'];
      }
      $schedulestext = $_POST['schedulesdescription'];
      $schedulesyear = $_POST['schedulesyear'];
      $schedulesmonth = $_POST['schedulesmonth'];
      $schedulesday = $_POST['schedulesday'];
      $scheduleshour = $_POST['scheduleshour'];
      $schedulesminute = $_POST['schedulesminute'];
      $schedulesduration = (int)($_POST['schedulesduration']);
      $schedulesurgency = $_POST['schedulesurgency'];
    }
    $stmt->execute();
    $stmt->close();

    // Store the last inserted ID in a session for reuse
    $_SESSION['insertID'] = $con->insert_id;

    // Insert into the separate urgency table
    $sql1 = 'INSERT INTO schedulesurgencies(schedulesID, schedulesUrgency) VALUES (?, ?)';
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param('ii', $_SESSION['insertID'], $schedulesurgency);
    $stmt1->execute();
    $stmt1->close();

    // Insert into the schedulesusers table
    $sql2 = 'INSERT INTO schedulesusers(schedulesUserID, schedulesID) VALUES (?, ?)';
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param('ii', $schedulesuserid, $_SESSION['insertID']);
    $stmt2->execute();
    $stmt2->close();
  }
  $con->close();
}
?>
<meta http-equiv="refresh" content="0;url=schedules.php" />
