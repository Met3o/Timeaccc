<?php
// Page to show the user their schedules that they have created, and a calendar

 // Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
  header('location: login.php');
  exit;
}
// Always get the British timezone
date_default_timezone_set('UTC');
  ?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <title>Schedules and Calendar - Timeacc</title>
  <?php
  include_once ('headerInSchedules.php');
  require_once ('config.php');
  include_once ('scheduleClass.php');

  if(!isset($_SESSION['selectValue'])){
    $_SESSION['selectValue'] = '';
  }

  ?>
<script src = "schedules.js"></script>
<link rel = "stylesheet" href = "calendarstyle.css">
<link rel = "stylesheet" href = "bannerstyle.css">
<script type = "text/javascript" src = "schedulesdayamounts.js"></script>
<script type = "text/javascript" src = "showhideoptions.js"></script>
</head>
<body>
  <div id = "instructions">Click on any day in the calendars to view events for that day.</div>
  <div id = "wholder">
  <div class = "holderholder">
    <div id = "createschedule">
      <form action="schedulecreator.php" method="post">
        <input id = "newevent" name = "newevent" type = "submit" value = "New Event"></input>
      </form>
      <form action = "deleteall.php" method = "post">
        <input id = "deleteall" name = "deleteall" type = "submit" value = "Delete All"></input>
      </form>
      <button onclick = "hide_options()" class = "scheduleselect">Show/hide search options</button>
      <button onclick = "location.href = 'resetsearch.inc.php'" class = "scheduleselect">Reset all search preferences</button>
      <div class = "scheduletitle"  id = "finddiv" style = "font-size: 15px; height:150px; display: none;">
        <form action = "schedulesfind.inc.php" method = "post">
          <label>Urgency:</label>
          <select name = "schedulesurgencysearch" class = "scheduleselect">
            <option value = "" checked>All</option>
            <option value = 1>1 (Green)</option>
            <option value = 2>2 (Amber)</option>
            <option value = 3>3 (Red)</option>
          </select>
          <label>Month:</label>
          <select name = "schedulesmonthsearch" class = "scheduleselect" id = "schedulesmonthsearch">
            <option value = "" checked>All</option>
            <option value = 1>January</option>
            <option value = 2>February</option>
            <option value = 3>March</option>
            <option value = 4>April</option>
            <option value = 5>May</option>
            <option value = 6>June</option>
            <option value = 7>July</option>
            <option value = 8>August</option>
            <option value = 9>September</option>
            <option value = 10>October</option>
            <option value = 11>November</option>
            <option value = 12>December</option>
          </select>
          <label>Date:</label>
          <select name = "schedulesdaysearch" class = "scheduleselect" id = "schedulesdaysearch">
            <option value = "" checked>All</option>
            <option value = 1>1</option>
            <option value = 2>2</option>
            <option value = 3>3</option>
            <option value = 4>4</option>
            <option value = 5>5</option>
            <option value = 6>6</option>
            <option value = 7>7</option>
            <option value = 8>8</option>
            <option value = 9>9</option>
            <option value = 10>10</option>
            <option value = 11>11</option>
            <option value = 12>12</option>
            <option value = 13>13</option>
            <option value = 14>14</option>
            <option value = 15>15</option>
            <option value = 16>16</option>
            <option value = 17>17</option>
            <option value = 18>18</option>
            <option value = 19>19</option>
            <option value = 20>20</option>
            <option value = 21>21</option>
            <option value = 22>22</option>
            <option value = 23>23</option>
            <option value = 24>24</option>
            <option value = 25>25</option>
            <option value = 26>26</option>
            <option value = 27>27</option>
            <option value = 28>28</option>
            <option value = 29>29</option>
            <option value = 30>30</option>
            <option value = 31>31</option>
          </select>
          <label>Inside hour:</label>
          <select class = "scheduleselect" name = "scheduleshoursearch" id = "scheduleshoursearch">
            <option value = "">All</option>
            <option value = 0>12am</option>
            <option value = 1>1am</option>
            <option value = 2>2am</option>
            <option value = 3>3am</option>
            <option value = 4>4am</option>
            <option value = 5>5am</option>
            <option value = 6>6am</option>
            <option value = 7>7am</option>
            <option value = 8>8am</option>
            <option value = 9>9am</option>
            <option value = 10>10am</option>
            <option value = 11>11am</option>
            <option value = 12>12pm</option>
            <option value = 13>13pm</option>
            <option value = 14>14pm</option>
            <option value = 15>15pm</option>
            <option value = 16>16pm</option>
            <option value = 17>17pm</option>
            <option value = 18>18pm</option>
            <option value = 19>19pm</option>
            <option value = 20>20pm</option>
            <option value = 21>21pm</option>
            <option value = 22>22pm</option>
            <option value = 23>23pm</option>
          </select>
          <label>Year:
          <select name = "schedulesyearsearch" class = "scheduleselect">
            <option value = "">All</option>
            <option value = <?php echo idate('Y');?>><?php echo idate('Y');?></option>
            <option value = <?php echo idate('Y') + 1;?>><?php echo idate('Y') + 1;?></option>
          </select>
          <input type="text" placeholder="Name: All" name="schedulesnamesearch" class = "scheduleselect"></input>
          <br><br>
          <input type = "submit" value = "Search for events"  class = "scheduleselect" style = "width: 200px; height:30px; font-size:15px; background-color: #5ef;"></input>
          <input type = "checkbox" name = "showprevious" class = "scheduleselect" <?php echo $_SESSION['selectValue'];?>>Show events from previous times</input>
        </form>
      </div>
    </div>
  <div class = "holder">
  <?php
  // If there is no connection then return error message and terminate the program
  if (!$con){
    die('connection failed : ' . $con->connect_error);
  }

  // Get the current date so that schedules can be compared to this date before being shown, and are only shown if they are confirmed to be in the future.
  $minute = idate('i');
  $hr = date('G');
  $day = date('d');
  $month = date('m');
  $year = date('Y');

  $wholeCurrentTime = $minute + ($hr * 100) + ($day * 10000) + ($month * 1000000) + ($year * 100000000);
  if ($con){
    // Any prepared select statement for the schedules is likely to be more than 45 characters long as strlen(trim('SELECT * FROM schedules WHERE schedulesID = ?')) = 45
    // So only execute if the select statement is lengthy enough
    // NOTE: This is from schedulesfind.inc.php AFTER the user has requested filtering
    if (isset($_SESSION['schedulesRefinedQuery']) && (strlen(trim($_SESSION['schedulesRefinedQuery'])) > 40)){
        $sql = $_SESSION['schedulesRefinedQuery'];

        // Prepare statement to the database
        $stmt = $con->prepare($sql);

        $userID = $_SESSION['id'];

        // Generate the bind_param() string values for each posted value
        $parameterBind = 'i';
        $i = 0;
        for ($i==0; $i < $_SESSION['paramCount']; $i++){
          $parameterBind .= $_SESSION['parameterArray'][$i];
        }

        // Bind to al parameters in the array
        $stmt->bind_param('' . $parameterBind . '',  ...$_SESSION['variableArray']);

        // call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterBind), $variableArray));

        // The value the statement, $stmt, is looking for
        // Get the ID of the current user in the session, retrieved from the database in order to link users to their own data
        // Get all of the user data from the schedules table
        $_SESSION['variableArray'] = $_SESSION['variableArray'];
        // Execute the prepared statement
        $stmt->execute();
         // Get the result, which measures whether there are any schedules in the user account yet, if not, display that none have ben created
        $result = $stmt->get_result();
        $_SESSION['result'] = $result;
      }
    else{
      // Otherwise prepare a statement for getting the data, corresponding to user accounts from the database, which would be the schedules
      $sql = ('SELECT * FROM (schedules, schedulesusers, schedulesurgencies)
      WHERE schedulesusers.schedulesUserID = ?
      AND schedules.schedulesID = schedulesusers.schedulesID
      AND schedulesurgencies.schedulesID = schedulesusers.schedulesID
      ORDER BY schedulesYear ASC,
      schedulesMonth ASC,
      schedulesDay ASC,
      schedulesTime ASC,
      schedulesMinute ASC,
      schedulesUrgency DESC,
      schedulesName ASC,
      schedulesDuration ASC');
      // Prepare the statement to the database connection
      $stmt = $con->prepare($sql);
      // Bind the statement to data
      $stmt->bind_param('s', $scheduleuserid);

      // The value the statement, $stmt, is looking for
      // Get the ID of the current user in the session, retrieved from the database in order to link users to their own data
      // Get all of the user data from the schedules table
      $scheduleuserid = $_SESSION['id'];
      // Execute the prepared statement
      $stmt->execute();
       // Get the result, which measures whether there are any schedules in the user account yet, if not, display that none have ben created
      $result = $stmt->get_result();
      $_SESSION['result'] = $result;
    }

    if ($_SESSION['result']->num_rows == 0){
      echo '<div class = "scheduletitle" style = "width:100%; height: 80; font-size:16;">
      No key Events appear to have been created, or they have but do not match your search.
      Click on "New Event" to create one, or perhaps refine your search, or reset your search preferences.
      </div>';
    }
    // Generate visual timestamps for every schedule in the database
    while ($row = $_SESSION['result']->fetch_assoc()) {
      $timeCulture = '';
      $schedulesID = $row['schedulesID'];
      $schedulesTitle = $row['schedulesName'];
      $schedulesText = $row['schedulesDescription'];
      $schedulesYear = $row['schedulesYear'];
      $schedulesMonth = $row['schedulesMonth'];
      $schedulesDay = $row['schedulesDay'];
      $schedulesHour = $row['schedulesTime'];
      $schedulesMinute = (int) ($row['schedulesMinute']);
      $schedulesDuration = (int) ($row['schedulesDuration']);
      $schedulesUrgency = $row['schedulesUrgency'];

      // if there is no set duration
      if ($schedulesDuration < 1){
        $schedulesDuration = '';
      }

      // Get the full time string of the schedule being displayed
      // If its date and time is already passed, then do not show it
      // Otherwise do show it
      $wholePostedTime = (int)($row['schedulesDuration']) + (int)($row['schedulesMinute']) + ($row['schedulesTime'] * 100)
      + ($row['schedulesDay'] * 10000) + ($row['schedulesMonth'] * 1000000) + ($row['schedulesYear'] * 100000000);
      // If wholeCurrentTime or wholePostedTime ends with 0-39 then add 40
      if ((int)(substr($wholePostedTime, -2) < 40)){
        $wholePostedTime = $wholePostedTime - 40;
      }
      if ((int)(substr($wholeCurrentTime, -2) < 40)){
        $wholeCurrentTime = $wholeCurrentTime - 40;
      }
      // DO NOT show previous events
      if (!isset($_SESSION['showPreviousEvents']) || $_SESSION['showPreviousEvents'] != true){
        if ($wholePostedTime > $wholeCurrentTime - (int)($schedulesDuration)){
          $event = new ScheduleEvent (
            $schedulesID, $schedulesTitle, $schedulesText,
            $schedulesYear, $schedulesMonth, $schedulesDay, $schedulesHour,
            $schedulesMinute, $schedulesDuration, $schedulesUrgency,
            $wholePostedTime, $wholeCurrentTime, false, false
          );
          $event->show();
          if ($_SESSION['result']->num_rows == 1){
            echo '<br><br>';
            $_SESSION['selectValue'] = '';
          }
        }
      }
      // DO show previous events
      else{
        if (isset($_SESSION['showPreviousEvents'])){
          $event = new ScheduleEvent (
            $schedulesID, $schedulesTitle, $schedulesText,
            $schedulesYear, $schedulesMonth, $schedulesDay, $schedulesHour,
            $schedulesMinute, $schedulesDuration, $schedulesUrgency,
            $wholePostedTime, $wholeCurrentTime, false, false
          );
          $event->show();
          if ($_SESSION['result']->num_rows == 1){
            echo '<br><br>';
            $_SESSION['selectValue'] = '';
          }
        }
      }
    }
    // Close the statement
    $stmt->close();
  }
  ?>
</div>
</div>
<div class = "calendarholderholder">
  <form action = "calendarsfind.inc.php" method = "post">
    <label><div class = "scheduleheader">Search for a specific year:</div></label>
    <input type = "number" name = "calendaryearsearch" class = "scheduleselect" style = "cursor:text; width:100; float:left;"></input>
    <input type = "submit" value = "Go" style = "float:left; position: relative; width: 70px; height:50px;font-size:25px;color:#fff;background-color:#5f5;border-style:solid;border-color:#7f7;font-family:'Montserrat'">
  </form>
  <form action = "calendarsresetsearch.php" method = "post">
    <input type = "submit" value = "Reset" style = "float:left; position: relative; bottom:16;width: 70px; height:50px;font-size:18px;color:#fff;background-color:#f55;border-style:solid;border-color:#f77;font-family:'Montserrat'">
  </input>
</form>
<div class = "calendarholder">
<?php
// Importing the Calendar class
require_once ('calendarClass.php');

// Generating the calendars, from calendarclass.php
if (isset($_SESSION['calendarYear'])){
  $globalYear = $_SESSION['calendarYear'];
  $month = 1;
  $globalMonth = $month;
}
// Else generate the current year calendar
else{
  $month = date('m');
  $year = date('Y');
  $globalMonth = $month;
  $globalYear = $year;
}

// Iteration for generating all calendars in a year
for ($globalMonth == 1; $globalMonth <= 12; $globalMonth++){
  $CalendarPad = new CalendarMonth ($globalMonth, $globalYear);
  $CalendarPad->show();
  if ($globalMonth == 12){
    // Generate the calendar for the next year as well
    $globalMonth2 = 1;
    for ($globalMonth2 == 1; $globalMonth2 <= 12; $globalMonth2++){
      $CalendarPad2 = new CalendarMonth ($globalMonth2, $globalYear + 1);
      $CalendarPad2->show();
    }
  }
}
?>
</div>
</div>
</div>
</body>
</html>
