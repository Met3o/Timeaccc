<?php
// Quick page for storing the values in any schedule as superglobals
// Redirect from schedules.php
// Values are for schedulesedit.php
// Not visible to the user

session_start();
date_default_timezone_set('UTC');
require_once('config.php');
$_SESSION['schedulesID'] = $_POST['schedulesgetid'];
$_SESSION['schedulesTitle']  = $_POST['schedulesgettitle'];

// No description
if (in_array($_POST['schedulesgetdescription'], ['⌚😩', '⌚😃', '⌚🧐', '⌚🥱'])){
  $_SESSION['schedulesDesc'] = '';
}
else{
  $_SESSION['schedulesDesc'] = $_POST['schedulesgetdescription'];
}
$_SESSION['schedulesYear'] = $_POST['schedulesgetyear'];
$_SESSION['schedulesMonth'] = $_POST['schedulesgetmonth'];
$_SESSION['schedulesDay'] = $_POST['schedulesgetday'];
$_SESSION['schedulesHour'] = $_POST['schedulesgethour'];
$_SESSION['schedulesMinute'] = (int)($_POST['schedulesgetminute']);
$_SESSION['schedulesDuration'] = (int)($_POST['schedulesgetduration']);
$_SESSION['schedulesUrgency'] = $_POST['schedulesgeturgency'];

header('location: schedulesedit.php');
