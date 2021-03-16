<?php
// Page to insert the user data changes into the database
// Redirect from accountchange.php, if form submission is successful
// Not visible to the user

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

// Include database configuration/connection
require_once('config.php');
// Include navigation header
include_once('headerInSchedules.php');

// Password not verified, in case the user wants to change their data without verification that it is them (encryption)
$_SESSION['passwordVerified'] = false;

if($con) {
  // Update
  $sql = 'UPDATE users SET userUsername = ?, userPassword = ?, userEmail = ? WHERE userID = ?';

  // Prepare
  $stmt = $con->prepare($sql);

  // Bind to values
  $stmt->bind_param('ssss', $newUsername, $newHashedPassword, $newEmail, $updatingID);

  // The values that the statement, $stmt, is looking for

  // Get the current user ID to query the table
  $updatingID = $_SESSION['id'];

  // The form the user just submitted
  $newUserName = $_POST['usernamechange'];
  $newPassword = $_POST['passwordchange'];
  $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
  $newEmail = $_POST['useremailchange'];

  // Execute the statement, updating the user data
  $stmt->execute();

  // Close
  $stmt->close();

  // Reassign the session variables to the changed data
  $_SESSION['userName'] = $newUsername;
  $_SESSION['password'] = $newPassword;
  $_SESSION['email'] = $newEmail;

  // Close the database connection for extra security
  $con->close();

  // Redirect to the account management page, where details will have been updated
  header('location:accountmanagement.php');
}
else{
  die ('Conection failed, please try again later.');
}

// No closing tag
