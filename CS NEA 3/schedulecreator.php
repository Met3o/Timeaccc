<?php
// Page for creating schedules that can be returned
// Schedules that are created for previous dates cannot be saved and will return an error

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
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
  <?php
  include_once('headerInSchedules.php');
  require_once ('config.php');

  // If there is no connection then return error message and terminate the program
  if (!$con){
    die('connection failed : ' . $con->connect_error);
  }
  ?>
  <link rel = "stylesheet" href = "calendarstyle.css">
  <link rel = "stylesheet" href = "bannerstyle.css">
  <script type = "text/javascript" src = "schedulesdayamounts.js"></script>
  <script type = "text/javascript" src = "schedulestextcharacters.js"></script>
</head>
</head>
<?php
// Duplicate names
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
$namesresult = $stmt->get_result();
?>
<body>
  <div id = "creatorholder">
    <form id = "schedulesform" action = "schedulecreator.inc.php" method = "post">
      <div class = "schedulesdiv">
        <label for="schedules"> <span id = "schedulesnamelabel">Title of Event: (0/30 characters)</span></label>
        <input class = "submitinput" name = "schedulestitle" type = "text" id = "schedulestitle" onkeyup = "count_name_characters(this.value)" autocomplete="off"></input>
        <br>
        <label>Or choose an existing name:</label>
        <select class = "scheduleselect" name = "schedulestitle2" type = "text" id = "schedulestitle">
          <option value = "">None selected</option>
          <?php
          // Append values into the names array for showing
          $namesArray = [];
          while ($row = $namesresult->fetch_assoc()){
            // Avoid repeating of names as options
            $name = $row['schedulesName'];
            $namesArray[] = $name;
            // If not already an option
          }
          $stmt->close();
           // Allow all names in the database as options
           $i = 0;
           for ($i == 0; $i < count(array_unique($namesArray)); $i++ ){
             echo '<option value = "' . array_unique($namesArray)[$i] . '">' . array_unique($namesArray)[$i] . '</option>';
           }
          ?>
        </select>
      </div>
      <br></br>
      <div class = "schedulesdiv" id = "descriptionholder">
        <label for="schedules"><span id = "schedulesdescriptionlabel">Description: (0/500 characters)</span></label>
        <textarea name = "schedulesdescription" type = "text" id = "schedulesdescription" onkeyup = "count_characters(this.value)"></textarea>
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
        <select   class = "scheduleselect" name="schedulesmonth" id="schedulesmonth">
        </select>
      </div>
      <div class = "schedulesdiv">
      <label>Select day of the month:</label>
      <select  class = "scheduleselect" name = "schedulesday"id="schedulesday"></select>
      </div>
      <div class = "schedulesdiv">
        <label>Select time of day:</label>
        <select class = "scheduleselect" name = "scheduleshour" id = "scheduleshour">
          <option value = <?php echo date('H');?>> <?php echo date('H');?></option>
          <option value = 0>0am</option>
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
        <label>Select minutes on the clock:</label>
        <select class = "scheduleselect" name = "schedulesminute" id = "schedulesminute">
          <option value = 0>:00</option>
          <option value = 5>:05</option>
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
       <input type="number" class = "submitinput" id = "schedulesduration" name = "schedulesduration"></input>
      </div>
        <div class = "schedulesdiv">
          <label for="schedules">Importance level? (1 = not important, 3 = very important):</label>
          <select  class = "scheduleselect" name="schedulesurgency" id="schedulesurgency">
            <option value=1>1 (Green)</option>
            <option value=2>2 (Amber)</option>
            <option value = 3>3 (Red)</option>
          </select>
        </div>
        <input type="submit" name = "submit"class="submitbutton" value="Create" style =
        "width:100px; height: 50px; background-image: linear-gradient(to top right, #05f, #fdd); font-family: 'Montserrat';
        font-size: 20px; color: #448; border-style: solid; border-color: #ddf;"></input>
      </div>
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
        font-family: 'Montserrat', sans-serif;
        margin:auto;
      ">
      </input>
    </form>
  </div>
</body>
  <?php
  // Displaying error messages
  if (isset($_GET['error'])){
    if ($_GET['error'] == 'emptytitle'){
      echo '<div id = "Notify" style = "width: 20%; height: 70px; font-size: 20; background-color: #f38; color: #fff; position: absolute; top:80%; float:right; ">
      Please enter a title for the schedule event.
      </div>';
    }
    elseif ($_GET['error'] == 'elapseddate'){
      echo '<div id = "Notify" style = "width: 20%; height: 100px; font-size: 20; background-color: #f38; color: #fff; position: fixed;top:80%;">
       Sorry, that date and time has already passed! Please select a future date.
      </div>';
    }
    elseif ($_GET['error'] == 'descriptiontoolong'){
      echo '<div id = "Notify" style = "width: 250; height: 150px; font-size: 25; background-color: #f38; color: #fff; position: fixed;top:75% ;">
       Sorry, that description is too long! (' . $_SESSION['descriptionTooLong'] . '/500 characters)
      </div>';
    }
    elseif ($_GET['error'] == 'nametoolong'){
      echo '<div id = "Notify" style = "width: 250; height: 150px; font-size: 25; background-color: #f38; color: #fff; position: fixed;top:75% ;">
       Sorry, that name is too long! (' . $_SESSION['nameTooLong'] . '/30 characters)
      </div>';
    }
  }
  ?>
</div>
  </body>
</html>
