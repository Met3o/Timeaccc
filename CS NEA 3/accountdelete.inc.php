<?php
// Page to delete the user's account
// Redirect from accountdelete.php
// Not visible to the user
// This is an includes page

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

// Include database configuration/connection
require_once('config.php');

// Delete the user's schedule ids from the database tables
// Cycle through all values in the table matching the user's ID and use a nested prepared statement for each value in the table to delete it
$sql = 'DELETE FROM schedulesusers WHERE schedulesUserID = ?';
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $userID);
$userID = $_SESSION['id'];
$stmt->execute();
$stmt->close();

// Delete all schedules from the schedules table pertaining to the user's schedule ids
// Cycle through all values in the table matching the user's ID and use a nested prepared statement for each value in the table to delete it
$sql1 = 'SELECT * FROM (schedules, schedulesusers)
WHERE schedulesusers.schedulesUserID = ? AND schedules.schedulesID = schedulesusers.schedulesID';
$stmt1 = $con->prepare($sql1);
$stmt1->bind_param('i', $userID1);
$userID1 = $_SESSION['id'];
$stmt1->execute();
$result1 = $stmt1->get_result();
while ($row = $result1->fetch_assoc()){
  $schedulesDeleteID = $row['schedulesID'];
  $sql2 = 'DELETE FROM schedules WHERE schedulesID = ?';
  $stmt2 = $con->prepare($sql2);
  $stmt2->bind_param('i', $schedulesDeleteID);
  $stmt2->execute();
  $stmt2->close();
}
$stmt1->close();

// Delete all urgencies from the schedulesurgencies table pertaining to the user's schedule ids
// Cycle through all values in the table matching the user's ID and use a nested prepared statement for each value in the table to delete it
$sql2 = 'SELECT * FROM (schedulesurgencies, schedulesusers)
WHERE schedulesusers.schedulesUserID = ? AND schedulesurgencies.schedulesID = schedulesusers.schedulesID';
$stmt2 = $con->prepare($sql2);
$stmt2->bind_param('i', $userID2);
$userID2 = $_SESSION['id'];
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($row = $result2->fetch_assoc()){
  $schedulesDeleteID = $row['schedulesID'];
  $sql2 = 'DELETE FROM schedulesurgencies WHERE schedulesID = ?';
  $stmt2 = $con->prepare($sql2);
  $stmt2->bind_param('i', $schedulesDeleteID);
  $stmt2->execute();
  $stmt2->close();
}
$stmt2->close();

// Delete all notes from the notes table pertaining to the user's notepad ids
// Cycle through all values in the table matching the user's ID and use a nested prepared statement for each value in the table to delete it
$sql = 'SELECT * FROM (notes, notesusers)
WHERE notesusers.notesUserID = ? AND notes.notesID = notesusers.notesID';
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $userID);
$userID = $_SESSION['id'];
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()){
  $notesDeleteID = $row['notesID'];
  $sql2 = 'DELETE FROM notes WHERE notesID = ?';
  $stmt2 = $con->prepare($sql2);
  $stmt2->bind_param('i', $notesDeleteID);
  $stmt2->execute();
  $stmt2->close();
}
$stmt->close();

// Delete the user's account data from the database tables
// Cycle through all values in the table matching the user's ID and use a nested prepared statement for each value in the table to delete it
$sql = 'DELETE FROM users WHERE userID = ?';
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $userID);
$userID = $_SESSION['id'];
$stmt->execute();
$stmt->close();

// Close connection for security
$con->close();

// Of course, this means that the user is not logged in anymore so log them out
$_SESSION['loggedin'] = false;
header('location:logout.inc.php');
exit;

// No closing tag
