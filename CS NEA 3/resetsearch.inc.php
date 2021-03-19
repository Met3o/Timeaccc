<?php
// Small include for resetting the user's search options for schedules
// Not visible to the user
// This is an includes page

session_start();
require_once('config.php');
// Reset showing previous events
$_SESSION['showPreviousEvents'] = false;
// Reset that checkbox
$_SESSION['selectValue'] = '';
// Reset the searched query
$_SESSION['schedulesRefinedQuery'] = '';
// Redirect
header('location: schedules.php');
exit;
