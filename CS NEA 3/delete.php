<?php
// Page for the user to choose to delete an individual schedule event

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
   header('location: login.php');
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
  <link rel = "stylesheet" href = "calendarstyle.css">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
</head>
<body>
   <?php
    // Database connection
    require_once ('config.php');
    // Include the navigation header
    include_once ('headerInSchedules.php');
    // Include the schedule event classes
    include_once ('scheduleClass.php');
    // Each scheduling event has its own schedules delete form, where the value is automatically set to the id (schedulesID) of that schedule event
    // This value is stored in $postedDelete and then the session, so it is held across the pages including "delete.inc.php"
    $postedDelete = $_POST['schedulesdelete'];
    $_SESSION['deleteID'] = $postedDelete;
    // Prepare a statement for getting the data, corresponding to user accounts from the database
    // Users can only access their own schedule IDs as those are the only ones displayed on their schedules page
    $sql = ('SELECT * FROM (schedules, schedulesurgencies, schedulesusers)
    WHERE schedules.schedulesID = ?
    AND schedulesusers.schedulesID = schedules.schedulesID
    AND schedulesurgencies.schedulesID = schedules.schedulesID');
    // prepare the statement
    $stmt = $con->prepare($sql);
    // Bind the statement
    $stmt->bind_param('s', $postedDelete);
    // Execute the prepared statement
    $stmt->execute();
    // Return the result to check if there are any schedules created, if there are not, display a message
    $result = $stmt->get_result();
    if ($result->num_rows == 0){
      echo'No key dates appear to have been created, or they have but do not match your search.';
    }

    // Printing each schedule while data is being fetched
    while ($row = $result->fetch_assoc()) {
      $timeCulture = '';
      $schedulesID = $row['schedulesID'];
      $schedulesTitle = $row['schedulesName'];
      $schedulesText = $row['schedulesDescription'];
      $schedulesYear = $row['schedulesYear'];
      $schedulesMonth = $row['schedulesMonth'];
      $schedulesDay = $row['schedulesDay'];
      $schedulesHour = $row['schedulesTime'];
      $schedulesMinute = $row['schedulesMinute'];
      $schedulesDuration = $row['schedulesDuration'];
      $schedulesUrgency = $row['schedulesUrgency'];

      // Get the current date so that schedules can be compared to this date before being shown, and are only shown if they are confirmed to be in the future.
      $minute = idate('i');
      $hr = date('G');
      $day = date('d');
      $month = date('m');
      $year = date('Y');
      // Timezones
      // If it is not currently British Summer TIme, then subtract an hour for accuracy

      $wholeCurrentTime = $minute + ($hr * 100) + ($day * 10000) + ($month * 1000000) + ($year * 100000000);
      $wholePostedTime = ($schedulesMinute) + ($schedulesHour * 100) + ($schedulesDay * 10000)
      + ($schedulesMonth * 1000000) + ($schedulesYear * 100000000);

      $event = new ScheduleEvent (
        $schedulesID, $schedulesTitle, $schedulesText,
        $schedulesYear, $schedulesMonth, $schedulesDay, $schedulesHour,
        $schedulesMinute, $schedulesDuration, $schedulesUrgency,
        $wholePostedTime, $wholeCurrentTime, true, true
      );
      $event->show();
      echo'<br>';

      // Displaying validation error messages
      if (isset($_GET['error'])){
        if ($_GET['error'] == 'emptytitle'){
          echo '<div id = "Notify" style = "width: 100%; height: 35px; font-size: 25;
          background-color: #f38; color: #fff; position: relative;top:75%;">
          Please enter a title for the schedule event.
          </div>';
        }
        if ($_GET['error'] == 'elapseddate'){
          echo '<div id = "Notify" style = "width: 100%; height: 35px; font-size: 25;
          background-color: #f38; color: #fff; position: relative;top:90% ;">
           Sorry, that date and time has already passed! Please select a future date.
          </div>';
        }
      }
    }

  ?>
  <html>
  <head>
    <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
  <div id = "Ask" style = "width: inherit; height: 50px; font-size: 30; background-color: #f33; color: #fde;">
    Are you sure you want to delete this event?
  </div>
</head>
<body>
  <div id = "deleteholder">
    <form id = "deleteform" action = "delete.inc.php" method = "post">
      <div class = "schedulesdiv">
        <input type = "submit" id = "delete" name = "confirmdelete" value = "Yes, delete" style = "
          height: 50px;
          width:200px;
          background-color: #f55;
          border-radius:15px;
          border-color:#f99;
          border-style: solid;
          font-family: 'Montserrat', sans-serif;
        ">
        </input>
      </div>
    </form>
  <div class = "schedulesdiv">
  	<form id = "cancel" action = "schedules.php" method = "post">
      <input type = "submit" id = "canceldelete" name = "canceldelete" value = "No, go back" style = "
          height: 50px;
          width:200px;
          background-color: #5f5;
          border-radius:15px;
          border-color:#9f9;
          border-style: solid;
          font-family: 'Montserrat', sans-serif;
        ">
  	</form>
  </div>
  </div>
</body>
</html>
