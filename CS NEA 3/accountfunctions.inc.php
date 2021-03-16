<?php
// Page for holding functions for user account change validation
// Not visible to the user
// Not a redirect
// This is an includes page

// Require database connection
require_once ('config.php');

// Validation Check types: Length, Format, Presence, Type, and lookup values
// Checks present below: Length, Presence

// Length
// Usernames have to be 3 characters longer or more.
function username_less_than_three($input){
  if (strlen($input) < 3){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header('location: accountchange.php?error=usernameshort');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}

// Usernames have to be no longer than 16 characters.
function username_more_than_sixteen($input){
  if (strlen($input) > 16){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header('location: accountchange.php?error=usernamelong');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}

// Passwords have to have eight or more characters.
function password_less_than_eight($input){
  if (strlen($input) < 8){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header('location:accountchange.php?error=passwordshort');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}

// Passwords have to be no longer than 16 characters.
function password_more_than_sixteen($input){
  if (strlen($input) > 16){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header('location: accountchange.php?error=passwordlong');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}

// Presence

// Checking for an empty field; can be applied to any enterable field
function empty_input($input){
  if (strlen($input) == 0){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header ('location: accountchange.php?error=emptyfield');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}

// Check if email and username already exist
function username_exists($input){
  require ('config.php');
  // Get an error if the username is already taken, but NOT if it is already taken by the user
  $sql = 'SELECT * FROM users WHERE userUsername = ? AND userUsername != ?';
  $stmt = $con->prepare($sql);
  $paramInput = $input;
  $paramUsername = $_SESSION['userName'];
  $stmt->bind_param('ss', $paramInput, $paramUsername);
  $stmt->execute();
  $getResult = $stmt->get_result();
  if ($getResult->num_rows > 0){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header('location:accountchange.php?error=usernameexists');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}

// Check if valid email format
function is_email($input){
  // Regular expression
  $pregPattern = '/^[^0-9][._a-zA-Z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
  // Match email input to regular expression
  $result = preg_match($pregPattern, $input);
  // If it is a valid email type it returns true else resutns false
  if ($result != true){
    $_SESSION['passwordVerified'] = true;
    header('location:accountchange.php?error=notanemail');
    exit;
  }
  else{
    $_SESSION['passwordVerified'] = true;
  }
}

function email_exists($input){
  require ('config.php');
  // Get an error if the email is already taken, but NOT if it is already taken by the user
  $sql = 'SELECT * FROM users WHERE userEmail = ? AND userEmail != ?';
  $stmt = $con->prepare($sql);
  $paramInput = $input;
  $paramEmail = $_SESSION['email'];
  $stmt->bind_param('ss', $paramInput, $paramEmail);
  $stmt->execute();
  $getResult = $stmt->get_result();
  if ($getResult->num_rows > 0){
    $result = true;
    $_SESSION['passwordVerified'] = true;
    header('location:accountchange.php?error=emailexists');
    exit;
  }
  else {
    $result = false;
  }
  return $result;
}
