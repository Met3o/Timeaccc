<?php
// Briefly auto-fill username and password IF they just registered
session_start();
// From register.php
if (isset($_SESSION['filledName'])){
  // Briefly auto fill username in session
  $userAutoFill = $_SESSION['filledName'];
}
else{
  $userAutoFill = '';
}
// From register.php
if (isset($_SESSION['filledPassword'])){
  // Do the same for password
  $passwordAutoFill = $_SESSION['filledPassword'];
}
else{
  $passwordAutoFill = '';
}
session_unset();
session_destroy();
// Login page
// Does not have a .inc.php header
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = '';
$usernameErr = $passwordErr = '';

// Processing form data when form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){

  // Check if username is empty, if it is then ask the user to enter a username
  if(empty(trim($_POST['username']))){
    $usernameErr = 'Please enter username.';
  }
  else{
    $username = trim($_POST['username']);
  }

  // Check if password is empty, if it is then ask the user to enter a password
  if(empty(trim($_POST['password']))){
      $passwordErr = 'Please enter your password.';
  }
  else{
    $password = trim($_POST['password']);
  }

  // Validate input
  if(empty($usernameErr) && empty($passwordErr)){
    // Query to retrieve user data from the database
    $sql = 'SELECT * FROM users WHERE userUsername = ?';

    // Prepare the sql into a prepared statement
    $stmt = $con->prepare($sql);
      // Bind variables to the prepared statement
      $stmt->bind_param('s', $paramUsername);
      // The values that $stmt needs (parameters)
      $paramUsername = $username;
      // Execute the prepared statement
      $stmt->execute();
      // Get the result of the executed ststement
      $result = $stmt->get_result();
      // Check if username exists, if yes then verify password
      if($result->num_rows == 1){
        while ($row = $result->fetch_assoc()){
          $hashedPassword = $row['userPassword'];
          $id = $row['userID'];
          $username = $row['userUsername'];
          $email = $row['userEmail'];
          if(password_verify($password, $hashedPassword)){
            session_start();
            // Store current user's id, username, and logged in session variables for session access
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['userName'] = $username;
            $_SESSION['password'] = $password;

            // Get user email for the session
            $sql = 'SELECT userEmail FROM users WHERE userID = ?';
            $stmt2 = $con->prepare($sql);
            $stmt2->bind_param('i', $userID);
            $stmt2->execute();
            $result = $stmt2->get_result();
            while ($row = $result->fetch_assoc()){
              $_SESSION['userEmail'] = $row['userEmail'];
            }
            $stmt2->close();

            // If the user has clicked on this link then they no longer need to search for certain schedules, so destroy the query
            $_SESSION['schedulesRefinedQuery'] = '';

            // Insert the user's ID into a unique non-primary column to be used as the foreign key as well
            // Only executes the first time the user logs in
            // Close head statement
            $stmt->close();

            // Redirect users to welcome page
            header('location: cpanel.php');
          }
          else{
            // Display an error message if password is not valid
            $passwordErr = 'The password you entered was not valid.';
          }
        }
      }
      else{
        // Display an error message if the username entered by the user doesn't exist in the database
        $usernameErr = 'No account found with that username.';
      }
    }
    // Else if the statement failed to execute, by any reason
    else{
      echo 'Something went wrong. Please try again later.';
    }
  }
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login - Timeacc</title>
    <meta charset="UTF-8">
    <meta name="description" content="Timeacc - Free scheduling and more">
    <meta name="keywords" content="time, timmeacc, scheduling, reminders">
    <meta name="author" content="Imole Adebayo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
    <title>Login</title>
    <link rel = "stylesheet" href = "accountmanagementstyle.css">
    <link rel = "stylesheet" href = "notepadstyle.css">
    <link rel = "stylesheet" href = "calendarstyle.css">
    <style type="text/css">
      body{ font: 14px sans-serif; }
      .wrapper{ width: 350px; padding: 20px; }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <h2>Login</h2>
      <p>Please fill in your details to login.</p>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
        <div class="inputform <?php echo (!empty($usernameErr)) ? 'has-error' : ''; ?>">
          <label>Username</label>
          <input type="text" name="username" class = "scheduleselect" style = "cursor:text;" value = "<?php echo $userAutoFill;?>">
          <span class="errorindicate"><?php echo $usernameErr; ?></span>
        </div>
        <br>
        <div class="inputform <?php echo (!empty($passwordErr)) ? 'has-error' : ''; ?>">
          <label>Password</label>
          <input type="password" name="password" class = "scheduleselect" style = "cursor:text;" value = "<?php echo $passwordAutoFill;?>" autocomplete="off">
          <span class="errorindicate"><?php echo $passwordErr; ?></span>
        </div>
        <br>
        <div class="inputform">
          <input type="submit" class = "scheduleselect" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Sign up now</a></p>
      </form>
    </div>
  </body>
</html>
