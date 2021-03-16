<?php
// Page for the user to view their details

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

// If the user has clicked on this link then they no longer need to search for certain schedules, so destroy the query
$_SESSION['schedulesRefinedQuery'] = '';

// Getting the user's email from the database, so that it can be shown
if ($con){
  // Select email to display on account management
  $sql = 'SELECT userEmail FROM users WHERE userID = ?';
  // Prepare the statement
  $stmt = $con->prepare($sql);
  // Bind the statement to parameters
  $stmt->bind_param('s', $_SESSION['id']);
  // Execute the query
  $stmt->execute();
  // Get the result (an email string)
  $result = $stmt->get_result();
  // by fetching it
  while ($row = $result->fetch_assoc()) {
    $userEmail = $row['userEmail'];
    // Assign the email to a session
    $_SESSION['email'] = $userEmail;
  }
  // Close the statement
  $stmt->close();
}
$passwordForLength = $_SESSION['password'];
$passwordLength = strlen($passwordForLength);
$i = 1;
$asteriskCover = '';
for ($i == 1; $i <= $passwordLength; $i++){
  $asteriskCover .= '*';
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
  <link rel = "stylesheet" href = "calendarstyle.css">
  <script type="text/javascript" src  = "accountshowpassword.js"></script>
</head>
<body>
  <div class = "userinfo">
    <div class = "userinfolabel">Username:</div>
    <div class = "userinforeturn" id = "userinfoname"><?php echo ($_SESSION['userName']);?></div>
    <div class = "userinfolabel">Password:
      <button class = "action" onclick = "show_div()">Show Password? (Double click first)
      </button>
    </div>
    <div class = "userinforeturn"id = "userinfopassword"><?php echo ($_SESSION['password']);?></div>
    <div class = "userinforeturn"id = "separate"><?php echo $asteriskCover;?></div>
    <div class = "userinfolabel">User ID:</div>
    <div class = "userinforeturn"id = "userinfoID"><?php echo ($_SESSION['id']);?></div>
    <div class = "userinfolabel">Your email:</div>
    <div class = "userinforeturn"id = "userinfoemail"><?php echo ($_SESSION['email']);?></div>
    <div class = "userinforeturn">
      <button onclick = "location.href = 'accountpasswordverify.php'" class = "action" id = "changes">Change details</button>
    </div>
  </div>
</body>
