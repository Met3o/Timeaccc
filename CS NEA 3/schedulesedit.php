<?php
// Page for the user to edit anything in their individual events, provided their time has not passed
// Redirect from schedules.inc.php after the request from schedules.php

 // Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
  header('location: login.php');
  exit;
}
  ?>
<html>
  <title>Create New Event - Timeacc</title>
<head>
<meta charset="UTF-8">
<meta name="description" content="Timeacc - Free scheduling and more">
<meta name="keywords" content="time, timmeacc, scheduling, reminders">
<meta name="author" content="Imole Adebayo">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <?php
  include_once('headerInSchedules.php');
  require_once ('config.php');
  include_once('scheduleClass.php');

  // If there is no connection then return error message and terminate the program
  if (!$con){
    die('connection failed : ' . $con->connect_error);
  }


  $monthsArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  // Getting the data from the schedule that was requested to be edited
  $schedulesGetID = $_SESSION['schedulesID'];
  $schedulesGetMinute = (int)($_SESSION['schedulesMinute']);
  $schedulesGetHour = $_SESSION['schedulesHour'];
  $schedulesGetDay = $_SESSION['schedulesDay'];
  $schedulesGetMonth = $_SESSION['schedulesMonth'];
  $schedulesGetYear = $_SESSION['schedulesYear'];
  $schedulesGetTitle = $_SESSION['schedulesTitle'];
  $schedulesGetDescription = $_SESSION['schedulesDesc'];
  $schedulesGetDuration = (int)($_SESSION['schedulesDuration']);
  $schedulesGetUrgency = $_SESSION['schedulesUrgency'];

  // Get the current date so that schedules can be compared to this date before being shown, and are only shown if they are confirmed to be in the future.
  $minute = idate('i');
  $hr = date('G');
  $day = date('d');
  $month = date('m');
  $year = date('Y');
  $wholeCurrentTime = $minute + ($hr * 100) + ($day * 10000) + ($month * 1000000) + ($year * 100000000);

  $wholePostedTime = $schedulesGetDuration + $schedulesGetMinute + ($schedulesGetHour * 100)
  + ($schedulesGetDay * 10000) + ($schedulesGetMonth * 1000000) + ($schedulesGetYear * 100000000);

// print 'am' or 'pm' before/after an hour value
  $timeCulture = '';
  if ($schedulesGetHour >= 12) {
    $timeCulture = 'pm';
  }
  else{
    $timeCulture = 'am';
  }

// '0' on minutes lower than 10
  $minutesPrepend = '';
  if ($schedulesGetMinute < 10){
    $minutesPrepend = '0';
  }
  ?>
  <link rel = "stylesheet" href = "calendarstyle.css">
  <link rel = "stylesheet" href = "bannerstyle.css">
  <script type = "text/javascript" src = "schedulesdayamounts.js"></script>
  <script type = "text/javascript" src = "schedulestextcharacters.js"></script>
</head>
<body>
</div>
  <div id = "editholder">
    <form id = "schedulesform" action = "schedulesedit.inc.php" method = "post">
      <div class = "schedulesdiv">
        <label for="schedules"> <span id = "schedulesnamelabel">Title of Event: (0/30 characters)</span></label>
        <input class = "submitinput" name = "schedulestitle" type = "text" id = "schedulestitle" onkeyup = "count_name_characters(this.value)" value = "<?php echo $schedulesGetTitle;?>" autocomplete="off"></input>
      </div>
      <div class = "schedulesdiv" id = "descriptionholder">
        <span id = "schedulesdescriptionlabel">Description: (0/500 characters)</span>
        <textarea name = "schedulesdescription" type = "text" id = "schedulesdescription" onkeyup = "count_characters(this.value)"><?php echo $schedulesGetDescription;?></textarea>
      </div>
      <div class = "schedulesdiv">
        <label for="schedules">Choose a year:</label>
        <select  class = "scheduleselect" name="schedulesyear" id="schedulesyear">
          <option value=<?php echo date('Y');?>><?php echo date('Y');?></option>
          <option value=<?php echo date('Y') + 1;?>><?php echo date('Y') + 1;?></option>
        </select>
      </div>
      <div class = "schedulesdiv">
        <label for="schedules">Choose a month:</label>
        <select class = "scheduleselect" name="schedulesmonth" id="schedulesmonth">
          <option value = <?php echo $schedulesGetMonth?> ><?php echo $monthsArray[$schedulesGetMonth - 1] . '  ';?></option>
          </select>
      </div>
      <div class = "schedulesdiv">
      <label>Select day of the month:</label>
      <select  class = "scheduleselect" name = "schedulesday"id="schedulesday">
      <option  value = <?php echo $schedulesGetDay;?>><?php echo $schedulesGetDay . '  ';?></option>
      </select>
      </div>
      <div class = "schedulesdiv">
        <label>Select hour of day:</label>
        <select class = "scheduleselect" name = "scheduleshour" id = "scheduleshour">
          <option value = <?php echo $schedulesGetHour?> ><?php echo $schedulesGetHour . $timeCulture . '  ';?></option>
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
      </div>
      <div class = "schedulesdiv">
        <label>Select minutes past the clock:</label>
        <select class = "scheduleselect" name = "schedulesminute" id = "schedulesminute">
          <option value = <?php echo $schedulesGetMinute?> ><?php echo ':' . $minutesPrepend . $schedulesGetMinute . '  ';?></option>
          <option value = 00>:00</option>
          <option value = 05>:05</option>
          <option value = 10>:10</option>
          <option value = 15>:15</option>
          <option value = 20>:20</option>
          <option value = 25>:25</option>
          <option value = 30>:30</option>
          <option value = 35>:35</option>
          <option value = 40>:40</option>
          <option value = 45>:45</option>
          <option value = 50>:50</option>
          <option value = 55>:55</option>
        </select>
      </div>
      <div class = "schedulesdiv">
       <label> Duration of the event (minutes) (optional):</label>
       <input type="number" class = "submitinput" id = "schedulesduration" name = "schedulesduration" value = <?php echo $schedulesGetDuration;?>></input>
      </div>
      <div class = "schedulesdiv">
        <label for="schedules">Importance level? (1 = not important, 3 = very important):</label>
        <select  class = "scheduleselect" name="schedulesurgency" id="schedulesurgency">
          <option value = <?php echo $schedulesGetUrgency;?>><?php echo $schedulesGetUrgency;?></option>
          <option value=1>1 (Green)</option>
          <option value=2>2 (Amber)</option>
          <option value = 3>3 (Red)</option>
        </select>
      </div>
      <input type="submit" name = "submit"class="submitbutton" value="Confirm Edits" style =
      "width:100px; height: 50px; background-image: linear-gradient(to top right, #05f, #fdd); font-family: 'Montserrat';
      font-size: 20px; color: #448; border-style: solid; border-color: #ddf;"></input>
      <input type="button" name = "cancel" class="submitbutton" value="Cancel" style =
      "width:100px; height: 50px; background-image: linear-gradient(to top right, #05f, #fdd); font-family: 'Montserrat';
      font-size: 20px; color: #448; border-style: solid; border-color: #ddf; cursor:pointer;" onclick = "location.href = 'schedules.php'"></input>
    </form>
    </div>
  <div id = "creatingmenu">
    <form id = "changemindform" action = "schedules.php" method = "post">
      <input type = "submit" id = "delete" name = "changemind" value = "Cancel" style = "
        height: 50px;
        width:150px;
        position:relative;
        background-color: #f5f;
        border-radius:15px;
        border-color:#f99;
        border-style: solid;
        font-family: "Montserrat", sans-serif;
        margin:auto;
      ">
      </input>
    </form>
  </div>
  <div id = "editshow">
    <?php
    // Show the event that the user requested to edit
    // Pass the values into creating it, using the correct variable data types
    $timeCulture = '';
    $schedulesID = (int)($schedulesGetID);
    $schedulesTitle = $schedulesGetTitle;
    $schedulesText = $schedulesGetDescription;
    $schedulesYear = (int)($schedulesGetYear);
    $schedulesMonth = (int)($schedulesGetMonth);
    $schedulesDay = (int)($schedulesGetDay);
    $schedulesHour = (int)($schedulesGetHour);
    $schedulesMinute = (int)($schedulesGetMinute);
    $schedulesDuration = (int)($schedulesGetDuration);
    $schedulesUrgency = (int)($schedulesGetUrgency);

    $event = new ScheduleEvent (
      $schedulesID, $schedulesTitle, $schedulesText,
      $schedulesYear, $schedulesMonth, $schedulesDay, $schedulesHour,
      $schedulesMinute, $schedulesDuration, $schedulesUrgency,
      $wholePostedTime, $wholeCurrentTime, true, true
    );
    $event->show();
    // Displaying validation error messages
    if (isset($_GET['error'])){
      if ($_GET['error'] == 'emptytitle'){
        echo '<div id = "Notify" style = "width: 250px  ; height: 100px; font-size: 25; background-color: #f38; color: #fff; position: relative;top:75%;">
        Please enter a title for the schedule event.
        </div>';
      }
      if ($_GET['error'] == 'elapseddate'){
        echo '<div id = "Notify" style = "width: 250; height: 160px; font-size: 25; background-color: #f38; color: #fff; position: relative;top:90% ;">
         Sorry, that date and time has already passed! Please select a future date.
        </div>';
      }
      if ($_GET['error'] == 'descriptiontoolong'){
        echo '<div id = "Notify" style = "width: 250; height: 150px; font-size: 25; background-color: #f38; color: #fff; position: relative;top:80% ;">
         Sorry, that description is too long! (' . $_SESSION['descriptionTooLong'] . '/500 characters)
        </div>';
      }
      if ($_GET['error'] == 'nametoolong'){
        echo '<div id = "Notify" style = "width: 250; height: 150px; font-size: 25; background-color: #f38; color: #fff; position: relative;top:80% ;">
         Sorry, that name is too long! (' . $_SESSION['nameTooLong'] . '/30 characters)
        </div>';
      }
    }
?>
</div>
</div>
  </body>
</html>
