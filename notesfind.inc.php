<?php
// Page to determine the SQL query for finding exact notes for the user
// Redirect from notes.php after the user specifies a certain search/sort criteria in the form
// Generates the search/sort query for the user before posting it into a superglobal and redirecting back to notepad.php with the new query
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

if(!$con){
  die('connection failed :(');
}

// Always get the British timezone
date_default_timezone_set('UTC');

$_SESSION ['notesName'] = '';

$query = 'SELECT * FROM (notes, notesusers) WHERE notesusers.notesUserID = ? AND notes.notesID = notesusers.notesID';

if (isset($_POST['notesnamesearch'])){
  $postedName = $_POST['notesnamesearch'];
  $_SESSION['postedName'] = $postedName;
}


if (strlen(trim($_POST['notesnamesearch'])) > 0){
  $_SESSION['notesName'] = $_POST['notesnamesearch'];
  $query .= ' AND notesName = ?';
}

// If any ordering/searching criteria have been set, then add this to the query
if (strlen($_POST['notesdatemodifiedsearch']) > 0 || strlen ($_POST['notesdatecreatedsearch']) > 0 || strlen($_POST['notesnameorder']) > 0){
  $query .= ' ORDER BY ';
}
if (empty(trim($_POST['notesnamesearch']))){
  if ($_POST['notesnameorder'] == 'Ascending'){
    $query .= 'notesName ASC ';
  }
  elseif ($_POST['notesnameorder'] == 'Descending'){
    $query .= 'notesName DESC ';
  }
  else {
    $query .= '';
  }
}

if ((strlen(trim($_POST['notesdatemodifiedsearch'])) > 0 || strlen(trim($_POST['notesdatecreatedsearch']))) && strlen(trim($_POST['notesnameorder'])) > 0){
  $query .= ', ';
}

if ($_POST['notesdatemodifiedsearch'] == 'Descending'){
  $query .= 'notesDateModified DESC ';
}
elseif ($_POST['notesdatemodifiedsearch'] == 'Ascending'){
  $query .= 'notesDateModified ASC ';
}
else{
  $query .= '';
}

if (strlen(trim($_POST['notesdatemodifiedsearch'])) > 0 && strlen(trim($_POST['notesdatecreatedsearch'])) > 0){
  $query .= ', ';
}

if ($_POST['notesdatecreatedsearch'] == 'Descending'){
  $query .= 'notesDateCreated DESC ';
}
elseif ($_POST['notesdatecreatedsearch'] == 'Ascending'){
  $query .= 'notesDateCreated ASC ';
}
else{
  $query .= '';
}


$_SESSION['notesQuery'] = $query;
?>

<meta http-equiv="Refresh" content="0;url=notepad.php"/>
