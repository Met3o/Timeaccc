<?php
// Registering/Creating an account
// Does not have a .inc.php include header

// Include config file
require_once 'config.php';
require_once ('accountfunctions.inc.php');

// Empty values
$username = $email = $password = $confirmPassword = '';
$usernameError = $emailError = $passwordError = $confirmPasswordError = '';

// Processing form data when form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  // Validate username
  if(empty(trim($_POST['username']))){
    $usernameError = 'Please enter a username.';
  }
  elseif (strlen(trim($_POST['username'])) < 3){
    $usernameError = 'Usernames must be longer than 3 characters.';
  }
  elseif (strlen(trim($_POST['username'])) > 16){
    $usernameError = 'Usernames must be shorter than 16 characters.';
  }
  else{
    // Prepare a select statement for the userID as each (unique) id corresponds to a username
    $sql = 'SELECT userID FROM users WHERE userUsername = ?';
      // Prepare the statement into the database connection
    $stmt = $con->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param('s', $postedUsername);
    // Set parameters
    $postedUsername = trim($_POST['username']);
    // Attempt to execute
    $stmt->execute();
    // Store result
    $result = $stmt->get_result();
    // Error if the username already belongs to another user, and exists in the database
    if($result->num_rows > 0){
      $usernameError = 'This username is already taken.';
    }
    // Trim the username whitespace
    else{
      $username = trim($_POST['username']);
    }

    $stmt->close();
  }

  // Validate email
  if(empty(trim($_POST['email']))){
    $emailError = 'Please enter a registry email.';
  }
  // Format check
  $pregPattern = '/^[^0-9][._a-zA-Z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
  if (preg_match($pregPattern, trim($_POST['email'])) != true){
    $emailError = 'Please enter an email in the correct format.';
  }
  else{
    // Prepare a select statement
    $sql = 'SELECT userEmail FROM users WHERE userEmail = ?';

    $stmt = $con->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param('s', $postedEmail);

    // Set parameters
    $postedEmail = trim($_POST['email']);
  	$_SESSION['email'] = trim($_POST['email']);

    // Execute the prepared statement
    $stmt->execute();
    // Get result
    $result = $stmt->get_result();

    if($result->num_rows == 1){
      $emailError = 'This email is already taken.';
    }
    else{
      $email = trim($_POST['email']);
    }
  }

  // Validate password
  if(empty(trim($_POST['password']))){
    $passwordError = 'Please enter a password.';
  }
  elseif(strlen(trim($_POST['password'])) < 8){
    $passwordError = 'Password must have at least 8 characters.';
  }
  else{
    $password = trim($_POST['password']);
  }
  // Validate confirm password
  if(empty(trim($_POST['confirmPassword']))){
      $confirmPasswordError = 'Please confirm password.';
  }
  else{
    $confirmPassword = trim($_POST['confirmPassword']);
    if(empty($passwordError) && ($password != $confirmPassword)){
      $confirmPasswordError = 'The password confirmation did not match.';
    }
  }

  // Check input errors before inserting in database
  if(empty($usernameError) && empty($passwordError) && empty($emailError) && empty($confirmPasswordError)){

    // Prepare an insert statement
    $sql = 'INSERT INTO users (userUsername, userEmail, userPassword) VALUES (?, ?, ?)';

    $stmt = $con->prepare($sql);
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param('sss', $postedUsername, $postedEmail, $postedPassword);

    // Set parameters
    $postedUsername = $username;
    $postedEmail = $email;
    $postedPassword = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

    mail(trim($email),
     ('Timeacc registration successful! (LOGIN DETAILS)'),
     ('Password: ' . $password . ', Username: ' . $username),
     ('From: timeacc.co.uk')
    );

    // Execute the prepared statement
    $stmt->execute();
    // Close
    $stmt->close();
    // Set username cookie for autofill
    setcookie('userName', $username);
    // Briefly auto fill username in session
    session_start();
    $_SESSION['filledName'] = $username;
    // Do the same for password
    $_SESSION['filledPassword'] = $password;
    // close connection
    $con->close();
    // Redirect to login page
    header('location: login.php');
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
  <title>Register Timeacc Account</title>
    <title>Sign Up</title>
    <style type="text/css">
      body{ font: 14px sans-serif; }
      .wrapper{ width: 350px; padding: 20px; }
    </style>
    <link rel = "stylesheet" href = "accountmanagementstyle.css">
    <link rel = "stylesheet" href = "notepadstyle.css">
    <link rel = "stylesheet" href = "calendarstyle.css">
</head>
<body>
  <div class="wrapper">
    <h2>Sign Up</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="inputform <?php echo (!empty($usernameError)) ? 'has-error' : ''; ?>">
        <label>Username</label>
        <input type="text" style = "cursor:text;" name="username" class = "scheduleselect"  autocomplete="off">
        <span class="help-block"><?php echo $usernameError; ?></span>
      </div>
      <br>
      <div class="inputform <?php echo (!empty($emailError)) ? 'has-error' : ''; ?>">
        <label>Email</label>
        <input type="text" style = "cursor:text;" name="email" class = "scheduleselect"autocomplete="off">
        <span class="help-block"><?php echo $emailError; ?></span>
      </div>
      <br>
      <div class="inputform <?php echo (!empty($passwordError)) ? 'has-error' : ''; ?>">
        <label>Password</label>
        <input type="password" style = "cursor:text;" name="password" class = "scheduleselect"autocomplete="off">
        <span class="help-block"><?php echo $passwordError; ?></span>
      </div>
      <br>
      <div class="inputform <?php echo (!empty($confirmPasswordError)) ? 'has-error' : ''; ?>">
        <label>Confirm Password</label>
        <input type="password" style = "cursor:text;" name="confirmPassword" class = "scheduleselect"autocomplete="off">
        <span class="help-block"><?php echo $confirmPasswordError; ?></span>
      </div>
      <br>
      <div class="formgroup">
        <input type="submit" style ="width:100px; height:25px;" class = "scheduleselect" value="Submit"></input>
        <br></br>
        <input type="reset" style ="width:100px; height:25px;" class = "scheduleselect"  value="Reset"></input>
      </div>
      <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
  </div>
</body>
</html>
