<?php
// Page for submitting the note that the user created
// Redirect from notepadcreator.php after the user submits the form
// Not visible to the user
// This is an includes page

// Initialize the session
session_start();
require_once ('config.php');

if(!$con){
  die('connection failed :(');
}

// Always get the British timezone
date_default_timezone_set('UTC');

if($con){
  // Either the title or the notes text needs to have characters, both at the same time cannot be empty
  if (!empty(trim(strip_tags($_POST['notestext']))) || !empty(trim(strip_tags($_POST['notestitle'])))){
    // Check for the existence of a note already in the database with the same name, if it exists, then assign a number to the name of this one
    // Ensures duplicates are uniquely named
    $checkExistence = ('SELECT * FROM (notes, notesusers) WHERE (notes.notesName = ? OR notes.notesName = ?) AND notesusers.notesUserID = ? AND notes.notesID = notesusers.notesID');
    // Prepare the statement
    $checkStatement = $con->prepare($checkExistence);
    // Bind to the name of the note being created or edited
    $checkStatement->bind_param('ssi', $name, $title, $userID);
    // Title which is the value to compare with
    $name = strip_tags($_POST['notestitle']);
    $trext = explode(' ', trim(strip_tags($_POST['notestext'])));
    $userID = $_SESSION['id'];
    $title .= $trext[0];
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
    $sql = ('INSERT INTO notes (notesName, notesDateCreated, notesDateModified, notesText, notesDuplicate) VALUES (?, ?, ?, ?, ?)');
    // Prepare the statement
    $stmt = $con->prepare($sql);
    // Bind to variables
    $stmt->bind_param('ssssi', $notesName, $notesDateCreated, $notesDateModified, $notesText, $ordinal);
    // Assign variables to the values ($ordinal is pre-assigned above)
    $userID = $_SESSION['id'];
    $notesName = strip_tags($_POST['notestitle']);
    $notesDate = date_create(date('d') . '-' . date('m') . '-' . date('Y') . ' ' . date('G') . ':' . date('i') . ':' . date('s'));
    $notesDateModified = date_format($notesDate, 'd/m/Y | H:i:s');
    $notesDateCreated = date_format($notesDate, 'd/m/Y | H:i:s');
    // If title is empty, assign it to the first word text entered in the text field (presence validation)
    if (empty(trim(strip_tags($_POST['notestitle']))) && !empty(trim(strip_tags($_POST['notestext'])))){
      // Get the first word only
      $explode = explode(' ', trim(strip_tags($_POST['notestext'])));
      // Add the ordinal number if it is a duplicate title
      $notesName .= $explode[0];
    }
    // If a title was entered
    if (!empty(trim(strip_tags($_POST['notestitle'])))){
      $notesName = strip_tags($_POST['notestitle']);
    }
    // Separately, if this is a duplicated/repeated title then rename with the appropriate number of repeats
    if ($numRows > 0){
      $notesName = $notesName;
    }
    $notesText = strip_tags($_POST['notestext']);
    // Execute
    $stmt->execute();
    // Close
    $stmt->close();
    // Close the previous check statement
    $checkStatement->close();

    // A second set of operations for inserting notes into the normalised table
    $_SESSION['notesInsert'] = $con->insert_id;
    $sql = 'INSERT INTO notesusers(notesUserID, notesID) VALUES (?, ?)';
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $userID, $_SESSION['notesInsert']);
    $stmt->execute();
    $stmt->close();

    // Close the connection
    $con->close();
  }
}
?>
<meta http-equiv="refresh" content="0;url=notepad.php" />
