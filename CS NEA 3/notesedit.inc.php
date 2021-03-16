<?php
// Page to post the edits requested by the user to the database
// Redirect from notesedit.php after the user submitted the form
// Not visible to the user
// Redirects back to notepad.php, where the user will be shown their changes
// This is an includes page

// Initialize the session
session_start();
require_once ('config.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

if(!$con){
  die('connection failed :(');
}

// Always get the British timezone
date_default_timezone_set('UTC');

if($con){
  // Avoid the user changing the name to something empty by changing it to the original name, stored in a variable
  $savedName = $_SESSION['savedName'];
  // Either the title or the notes text needs to have characters, both at the same time cannot be empty
  if (!empty(trim($_POST['notestext'])) || !empty(trim($_POST['notestitle']))){
    // Check for the existence of a note already in the database with the same name, if it exists, then assign a number to the name of this one
    // Ensures duplicates are uniquely named
    $checkExistence = ('SELECT * FROM (notes, notesusers) WHERE notes.notesName = ? AND notes.notesID = notesusers.notesID AND notesusers.notesUserID = ?');
    // Prepare the statement
    $checkStatement = $con->prepare($checkExistence);
    // Bind to the name of the note being created or edited
    $checkStatement->bind_param('si', $name, $userID);
    // Bind name parameter
    $name = $_POST['notestitle'];
    $userID = $_SESSION['id'];
    // Execute the statement
    $checkStatement->execute();
    // Get result
    $checkResult = $checkStatement->get_result();
    // If there are no notes existing with the same name, then assign
    // Else add a value indicator with the same name
    $numRows = $checkResult->num_rows;
    if ($numRows == 0){
      // ($ordinal is also inserted into the database)
      $ordinal = '';
    }
    // "(1)", "(2)"...
    else{
      $ordinal = $numRows;
    }

    // Inserting notes into the database
    // SQL statement for inserting the submitted notes, including the user ID
    $sql = ('UPDATE notes SET notesName = ?, notesText = ?, notesDateModified = ? WHERE notesID = ?');
    // Prepare the statement
    $stmt = $con->prepare($sql);
    // Bind to variables
    $stmt->bind_param('sssi', $notesName, $notesText, $notesDateModified, $notesID);
    // Assign variables to the values ($ordinal is pre-assigned above)
    $notesID = $_SESSION['notesID'];
    $notesName = $_POST['notestitle'];
    $notesDate = date_create(date('d') . '-' . date('m') . '-' . date('Y') . ' ' . date('G') . ':' . date('i') . ':' . date('s'));
    $notesDateModified = date_format($notesDate, 'd/m/Y | H:i:s');
    // If title is empty, assign it to the first word text entered in the text field (presence validation)
    if (empty(trim($_POST['notestitle'])) && !empty(trim($_POST['notestext']))){
      $notesName = $savedName;
    }
    if (!empty(trim($_POST['notestitle']))){
      $notesName = $_POST['notestitle'];
    }
    // Separately, if this is a duplicated/repeated title then rename with the appropriate number of repeats
    if ($numRows > 0){
      $notesName = $notesName;
    }
    $notesText = $_POST['notestext'];
    // Execute
    $stmt->execute();
    // Close
    $stmt->close();
    // Close the previous check statement
    $checkStatement->close();
    // Close the connection
    $con->close();
  }
}

?>
<meta http-equiv="refresh" content="0;url=notepad.php" />
