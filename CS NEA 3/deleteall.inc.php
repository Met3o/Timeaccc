<?php
// Page for executing the action of the user choosing to delete all of their schedules
// Redirect from deleteall.php
// Not visible to the user
// This is an includes page

 // Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
    header('location: login.php');
    exit;
}
?>
<?php
// Database connection
require_once ('config.php');

// Delete has to be requested for this to be usable
if (!isset($_POST['confirmdelete'])){
  header('location: schedules.php');
}
else{
  // Delete all urgencies from the schedulesurgencies table pertaining to the user's schedule ids
  $sql1 = 'SELECT * FROM (schedulesurgencies, schedulesusers)
  WHERE schedulesusers.schedulesUserID = ? AND schedulesurgencies.schedulesID = schedulesusers.schedulesID';
  $stmt1 = $con->prepare($sql1);
  $stmt1->bind_param('i', $userID1);
  $userID1 = $_SESSION['id'];
  $stmt1->execute();
  $result1 = $stmt1->get_result();
  while ($row = $result1->fetch_assoc()){
    $schedulesDeleteID = $row['schedulesID'];
    $sql2 = 'DELETE FROM schedulesurgencies WHERE schedulesID = ?';
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param('i', $schedulesDeleteID);
    $stmt2->execute();
    $stmt2->close();
  }
  $stmt1->close();

  $sql0 = ('DELETE FROM schedulesusers
  WHERE schedulesUserID = ?');
  // Prepare the statement
  $stmt0 = $con->prepare($sql0);
  // Bind the statement to a value, which will be the reminder ID being deleted
  $stmt0->bind_param('i', $deletable);
  $deletable = $_SESSION['id'];
  // Execute and close the statement
  $stmt0->execute();
  $stmt0->close();

  // Delete all schedules from the schedules table pertaining to the user's schedule ids
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


}
// Redirect to the original Schedules page, where the deleted reminder will no longer be shown
header('location: schedules.php');
