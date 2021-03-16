<?php
// Small include for resetting the user's search options for schedules
// Not visible to the user
// This is an includes page

session_start();
require_once('config.php');


$_SESSION['showPreviousEvents'] = false;
$_SESSION['schedulesRefinedQuery'] = '';
header('location: schedules.php');
exit;
