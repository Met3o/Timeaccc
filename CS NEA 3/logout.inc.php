<?php
// Log out the user
// Not visible to the user

// Destroy everything about the session. So that the user cannot access the website as they are now "logged out"
$_SESSION['id'] = '';
// Ensure that a session exists that is destroyable
session_start();
// Now unset the session
session_unset();
// Destroy all the data related to the session, such as parameters (e.g. $_SESSION['id']
session_destroy();
// Redirect to the login page
header('location: login.php');
// No closing tag
