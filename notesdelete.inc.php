<?php
// Page to delete the note that the user selected
// After the user has confirmed that they want to delete it
// Redirect from notesdelete.php
// Not visible to the user
// This is an includes page

// Initialize the session
session_start();
require_once ('config.php');
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

// Always get the British timezone
date_default_timezone_set('UTC');

if(!$con){
  die('Connection failed :(');
}

if ($con){
  // Query for deleting the note
  $sql = 'DELETE FROM notes WHERE notesID = ?';
  $stmt = $con->prepare($sql);
  $stmt->bind_param('i', $notesID);
  $notesID = $_POST['notesgetdelete'];
  $stmt->execute();
  $stmt->close();

  // Query for deleting the note's user table data
  $sql = 'DELETE FROM notesusers WHERE notesID = ? AND notesUserID = ?';
  $stmt = $con->prepare($sql);
  $stmt->bind_param('ii', $notesID, $userID);
  $userID = $_SESSION['id'];
  $stmt->execute();
  $stmt->close();

  $con->close();
}
?>
<meta http-equiv="refresh" content="0;url=notepad.php" />
