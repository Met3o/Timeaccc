<?php
/* Page for selecting schedule events based on the user's selection preference in the schedules.php search form
* SQL statement is generated using an algorithm based on the form submitted
* Redirect from schedules.php after selection preference is submitted OR reminders.php if the user requested to look at a specific schedule
* Not visible to the user
* Redirects to schedules.php with the new search criteria
* This is an includes page
*/

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
}


require_once ('config.php');

// Always get the British timezone
date_default_timezone_set('UTC');

// CSS styling
echo '<link rel = "stylesheet" href = "calendarstyle.css">';

// The values posted from the search form
$schedulesName = $schedulesUrgency = $schedulesHour = $schedulesDay = $schedulesMonth = $schedulesYear = '';

// SQL statement text
$postNameSearchGet = $postUrgencySearchGet = $postYearSearchGet = $postMonthSearchGet = $postDaySearchGet = $postHourSearchGet = '';

// Parameter binding
$paramName = $paramUrgency = $paramYear = $paramMonth = $paramDay = $paramHour= '';

// The values being searched for each parameter
// May or may not be empty, depending on what the user actually chose
$postName = trim ($_POST['schedulesnamesearch']);
$postUrgency = trim($_POST['schedulesurgencysearch']);
$postYear = trim($_POST['schedulesyearsearch']);
$postMonth = trim($_POST['schedulesmonthsearch']);
$postDay = trim($_POST['schedulesdaysearch']);
$postHour = trim($_POST['scheduleshoursearch']);

// Assign all of those variables to the session
$_SESSION['postName'] = trim ($_POST['schedulesnamesearch']);
$_SESSION['postUrgency'] = trim($_POST['schedulesurgencysearch']);
$_SESSION['postYear'] = trim($_POST['schedulesyearsearch']);
$_SESSION['postMonth'] = trim($_POST['schedulesmonthsearch']);
$_SESSION['postDay'] = trim($_POST['schedulesdaysearch']);
$_SESSION['postHour'] = trim($_POST['scheduleshoursearch']);

// Reset the original schedules search query if it exists, so that the new query that is about to be generated is freshly assigned
$_SESSION['schedulesRefinedQuery'] = '';

// Variable to measure the number of parameters to bind
$paramCount = 0;

// Current user's ID
$userID = $_SESSION['id'];

// SQL statement first declared
// Selecting from the normalised schedules, schedulesurgencies, and schedulesID tables
// Where all three tables correspind to the same linked record (with unique IDs)
$sql = 'SELECT * FROM (schedules, schedulesurgencies, schedulesusers)
WHERE schedulesusers.schedulesUserID = ?
AND schedules.schedulesID = schedulesusers.schedulesID
AND schedulesurgencies.schedulesID = schedulesusers.schedulesID';

// Array to store the parameters in
$parameterArray = [];

// Array to store the variables in
$variableArray = [];

// Parameter 0 has to be the user's id in any query, because of 'WHERE schedulesusers.schedulesUserID = ?'
$variableArray[0] = $userID;

// If postName is not empty, so the user searched based on name
if ($postName != ''){
  $postNameSearchGet = ' AND schedules.schedulesName = ?';
  $paramCount++;
  // Add the query value to the sql statement
  $sql .= $postNameSearchGet;

  // Therefore a parameter exists for binding
  $paramName = 's';

  // Store parameters in an array
  $parameterArray[] = $paramName;

  // Store the posted variable in in an array; this is used for the data to bind to the query
  $variableArray [] = $postName;
}

// If the user searched based on urgency
if ($postUrgency != ''){
  $postUrgencySearchGet = ' AND schedulesurgencies.schedulesUrgency = ?';
  $paramCount++;

  // Add the query value to the sql statement
  $sql .= $postUrgencySearchGet;

  // Therefore a parameter exists for binding
  $paramUrgency = 'i';

  // Store parameters in an array
  $parameterArray[] = $paramUrgency;

  // Store the posted variable in in an array; this is used for the data to bind to the query
  $variableArray [] = $postUrgency;
}

// If the user searched based on year
if ($postYear != ''){
  $postYearSearchGet = ' AND schedules.schedulesYear = ?';
  $paramCount++;

  // Add the query value to the sql statement
  $sql .= $postYearSearchGet;

  // Therefore a parameter exists for binding
  $paramYear = 'i';

  // Store parameters in an array
  $parameterArray[] = $paramYear;

  // Store the posted variable in in an array; this is used for the data to bind to the query
  $variableArray [] = $postYear;
}

// If the user searched based on month
if ($postMonth != ''){
  $postMonthSearchGet = ' AND schedules.schedulesMonth = ?';
  $paramCount++;

  // Add the query value to the sql statement
  $sql .= $postMonthSearchGet;

  // Therefore a parameter exists for binding
  $paramMonth = 'i';

  // Store parameters in an array
  $parameterArray[] = $paramMonth;

  // Store the posted variable in in an array; this is used for the data to bind to the query
  $variableArray [] = $postMonth;
}

// If the user searched based on day
if ($postDay != ''){
  $postDaySearchGet = ' AND schedules.schedulesDay = ?';
  $paramCount++;

  // Add the query value to the sql statement
  $sql .= $postDaySearchGet;

  // Therefore a parameter exists for binding
  $paramDay = 'i';

  // Store parameters in an array
  $parameterArray[] = $paramDay;

  // Store the posted variable in in an array; this is used for the data to bind to the query
  $variableArray [] = $postDay;
}

// If the user searched based on time of day, specifically hour
if ($postHour != ''){
  $postHourSearchGet = ' AND schedules.schedulesTime = ?';
  $paramCount++;

  // Add the query value to the sql statement
  $sql .= $postHourSearchGet;

  // Therefore a parameter exists for binding
  $paramHour = 'i';

  // Store parameters in an array
  $parameterArray[] = $paramHour;

  // Store the posted variable in in an array; this is used for the data to bind to the query
  $variableArray [] = $postHour;
}

// Ordering by date, time, urgency, and then name
$sql .= ' ORDER BY schedulesYear ASC, schedulesMonth ASC, schedulesDay ASC, schedulesTime ASC, schedulesMinute ASC, schedulesUrgency DESC, schedulesName ASC';

// Store the parameter array and variables array in superglobal variables, to be queried on the schedules page
// These will be passed into the statement to be bound as parameters ('<i><s>', <variables>)
$_SESSION['paramCount'] = $paramCount;
$_SESSION['variableArray'] = $variableArray;


// Generate the bind_param() string values for each posted value
$parameterBind = 'i';
// Get each value in the parameter binds array to be bound to the statement, both in terms of placeholder data structure initials ('i', 's'), and variables
// By cycling through the entire array
$i = 0;
for ($i=0; $i < $paramCount; $i++){
  $parameterBind .= $parameterArray[$i];
}
$_SESSION['parameterArray'] = $parameterArray;

// If the checkbox for showing events was selected, store this in a superglobal to indicate whether to display previous events or not
if (isset($_POST['showprevious'])){
  $_SESSION['showPreviousEvents'] = true;
  $_SESSION['selectValue'] = 'checked';
}
// If the user came here from the reminders feature, then any reminder should be shown even if it was previous form the time
elseif (isset($_POST['prevsubmit'])){
    $_SESSION['showPreviousEvents'] = true;
    $_SESSION['selectValue'] = 'checked';
}
// Otherwise the user hasn't searched for anything so set the variable to false
else{
  $_SESSION['showPreviousEvents'] = false;
  $_SESSION['selectValue'] = ' 0 ';
}

// Bind the generated SQL statement to a superglobal
$_SESSION['schedulesRefinedQuery'] = $sql;
?>
<meta http-equiv="Refresh" content="0;url=schedules.php" />
