<?php
// Page for the user to change their details as they wish
// A form exists through which users can request to post data
// Page is visible to the user
// Redirects to accountdetailschange.inc.php
// This page is encrypted due to the sensitivity of the data

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
// If not, then redirect to the login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

// Include database configuration/connection
require_once('config.php');
// If not verified in then redirect to the account pasword verification page
if ($_SESSION['passwordVerified'] !== true){
  header('location: accountpasswordverify.php');
  exit;
}

// Get input validation functions
require_once ('accountfunctions.inc.php');

// Validation checks, to ensure that the data the user submits is acceptable
// These checks return errors that then influence how the page is displayed
// No redirects but directions will be given to the user to approve their inputs
if($_SERVER['REQUEST_METHOD'] == 'POST'){

  // Presence check (applies to All INPUT fields)
  if (empty_input($_POST['usernamechange']) == true || empty_input($_POST['passwordchange']) == true || empty_input($_POST['useremailchange']) == true){
    $usernameError = 'One or more fields were left empty.';
  }

  // Validate username

  // Presence check, username
  elseif(empty_input($_POST['usernamechange']) == true){
    $usernameError = 'Please enter a username.';
    header('accountchange.php?error=emptyusername');
  }

  // Length check, username (>= 3)
  elseif (username_less_than_three(trim($_POST['usernamechange']))){
    $usernameError = 'Usernames must have at least 3 characters.';
    header('accountchange.php?error=usernameshort');
  }

  // Check if username exists
  elseif (username_exists(trim($_POST['usernamechange']))){
      $usernameError = 'That username is taken.';
      header('accountchange.php?error=usernameexists');
  }

  // Else if username too long
  elseif (username_more_than_sixteen(trim($_POST['usernamechange']))){
    $usernameError = 'That username is too long.';
    header('accountchange.php?error=usernamelong');
  }

  // Validate password

  // Presence check, password
  elseif(empty_input(trim($_POST['passwordchange'])) == true){
    $passwordError = 'Please enter a password.';
    header('accountchange.php?error=emptypassword');
  }

  // Length check, password (>= 8 characters)
  elseif(password_less_than_eight($_POST['passwordchange']) == true){
    $passwordError = 'Password must have atleast 8 characters.';
    header('accountchange.php?error=passwordshort');
  }

  // Check if email exists
  elseif (email_exists(trim($_POST['useremailchange']))){
      $usernameError = 'That email is taken.';
      header('accountchange.php?error=emailexists');
  }

  // Check if email exists
  elseif (is_email(trim($_POST['useremailchange'])) != false){
      $usernameError = 'Not a valid email.';
      header('accountchange.php?error=notanemail');
  }

  // No errors
  // All error variables are empty, so $_GET['error'] does not exist, therefore the changes can be inserted successfully
  else{
    $usernameError = $emailError = $passwordError = $confirmPasswordError = '';
  }

  // Check input errors before inserting in database
  if(empty($usernameError) && empty($passwordError) && empty($emailError)){
    // Update user data
    $sql = 'UPDATE users SET userUsername = ?, userPassword = ?, userEmail = ? WHERE userID = ?';
    // Prepare
    $stmt = $con->prepare($sql);
    // Bind to variables, assigned later
    $stmt->bind_param('sssi', $newUsername, $newHashedPassword, $newEmail, $updatingID);
    // Get the current user ID to query the table
    $updatingID = $_SESSION['id'];
    // The form the user just submitted
    // The values that the statement, $stmt, is looking for
    $newUsername = trim($_POST['usernamechange']);
    $newPassword = $_POST['passwordchange'];
    // Hash password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    // Trim username posted
    $newEmail = trim($_POST['useremailchange']);
    // Execute the statement
    $stmt->execute();
    // Close
    $stmt->close();
    // Reassign the session variables to the changed data
    $_SESSION['userName'] = $newUsername;
    $_SESSION['password'] = $newPassword;
    $_SESSION['email'] = $newEmail;

    // Mail the changes
    // (only works on domains with verified SSL certificates)
    mail($newEmail,
     ('Timeacc account details update successful! (LOGIN DETAILS)'),
     ('Password: ' . $newPassword . ', Username: ' . $newUsername),
     ('From: timeacc.co.uk')
   );
  }

  // Close the connection, for security
  $con->close();

  // Redirect back to the account information page after the update processing is finished
  header('location: accountmanagement.php');
  exit;
}

?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "stylesheet" href = "accountmanagementstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <link rel = "stylesheet" href = "bannerstyle.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
  <div id = "userchanger">
    <form method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
      <div class = "userinfolabel">User ID: </div>
      <div class = "userinforeturn"id = "userinfoID"><?php echo $_SESSION['id'];?> (Cannot be changed)</div>
      <div class = "userinfolabel">Username:</div>
      <div class = "userinforeturn" id = "userinfoname">
        <input type = "text" name = "usernamechange" value = "<?php echo ($_SESSION['userName']);?>"></input>
      </div>
      <div class = "userinfolabel">Password:</div>
      <div class = "userinforeturn"id = "userinfopassword2">
        <input type = "text" name = "passwordchange" value = "<?php echo $_SESSION['password'];?>"></input>
      </div>
      <div class = "userinfolabel">Your email:</div>
      <div class = "userinforeturn"id = "userinfoemail">
        <input type = "text" name = "useremailchange" value = "<?php echo $_SESSION['email'];?>"></input></div>
      <div class = "userinforeturn">
        <?php
        // Display input validation errors to the user
        // Echo username error if negative username validation
        // If the error exists then echo an error

        // Empty username
        if (isset($_GET['error'])){
          if ($_GET['error'] == 'emptyusername'){
            echo '<div class = "error">Please enter a username.</div>';
          }
          // Username too short
          if ($_GET['error'] == 'usernameshort'){
            echo '<div class = "error">Usernames must have at least 3 characters.</div>';
          }

          // Username too long
          if ($_GET['error'] == 'usernamelong'){
            echo '<div class = "error">Usernames must be below 16 characters.</div>';
          }

          // Username exists
          if ($_GET['error'] == 'usernameexists'){
            echo '<div class = "error">That username is already taken.</div>';
          }

          // Email exists
          if ($_GET['error'] == 'emailexists'){
            echo '<div class = "error">That email is already taken.</div>';
          }

          // Not a valid email
          if ($_GET['error'] == 'notanemail'){
            echo '<div class = "error">That is not a correct type of email.</div>';
          }

          // Echo password error if negative password validation
          // Empty password
          if ($_GET['error'] == 'emptypassword'){
            echo '<div class = "error">Please enter a password.</div>';
          }
          // Password too short (i.e. below 8 characters)
          if($_GET['error'] == 'passwordshort'){
            echo '<div class = "error">Password must have at least 8 characters.</div>';
          }

          // Any fields being left empty, so the email field
          if($_GET['error'] == 'emptyfield'){
            echo '<div class = "error">One or more fields was left empty.</div>';
          }
        }
        ?>
        <input type = "submit" class = "action" id = "changes" value = "Update details"></input>
        <button onclick = "location.href = 'accountoptions.php'" class = "action" id = "cancel">Cancel and go back</button>
      </div>
    </form>
  </div>
</body>
</html>
