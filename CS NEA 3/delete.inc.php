
<?php
// Page for executing the request of the user deleting their schedule event
// Not visible to the user
// This is an includes page

 // Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
    header('location: login.php');
    exit;
}
// Database connection
require_once ('config.php');

// Delete the schedule
// The current schedule being deleted will be understood by its unique schedulesID, stored in $_SESSION['id']
// Declare a delete statement

// Delete from the schedulesurgencies table
$sql = ('DELETE FROM schedulesurgencies
WHERE schedulesID = ?');
// Prepare the statement
$stmt = $con->prepare($sql);
// Bind the statement to a value, which will be the reminder ID being deleted
$stmt->bind_param('s', $deletable);

// The unique schedule that the statement, $stmt, is looking for, so that it can delete it
// Originally stored in $postedDelete and $_SESSION['deleteID']
$deletable = $_SESSION['deleteID']; // (A schedule ID AS THE ID IS THE SAME VALUE/NAME AS THE INPUT BUTTON from the schedules page, that is HIDDEN)

// Execute and close the statement
$stmt->execute();
$stmt->close();

// Delete from the schedulesusers table
$sql = ('DELETE FROM schedulesusers
WHERE schedulesID = ?');
// Prepare the statement
$stmt = $con->prepare($sql);
// Bind the statement to a value, which will be the reminder ID being deleted
$stmt->bind_param('s', $deletable);
// Execute and close the statement
$stmt->execute();
$stmt->close();


$sql = ('DELETE FROM schedules
WHERE schedulesID = ?');
// Prepare the statement
$stmt = $con->prepare($sql);
// Bind the statement to a value, which will be the reminder ID being deleted
$stmt->bind_param('s', $deletable);
// Execute and close the statement
$stmt->execute();
$stmt->close();

// Redirect to the original Schedules page, where the deleted reminder will no longer be shown
header('location: schedules.php');
?>
