<?php
// OOP page for the calendar and calendar says
// Used by schedules.php for generating calendar months for a whole year into the future
// This is an includes page
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
// Get the current year
$dateYear = date('Y');

// British timezone
date_default_timezone_set('UTC');

// Function for getting the previous month so that the number of days in this month can be measured
function previous_month_and_year($previousMonthDays){
  // If it is currently January, then the previous month will be 12, not 0
  if ($previousMonthDays == 1){
    $JanuaryPrevious = 12;
    // Return December
    return $JanuaryPrevious;
  }

  // Otherwise the previous month is just one less than the current month, so return that
  else{
    $monthPrevious = $previousMonthDays - 1;
    // Return the previous month
    return $monthPrevious;
  }
}

// Class for calendar days, which are generated across the calendar (and returned in table cells)
class CalendarDay {
  public $dayNumber;  // The number a calendar cell stamp shows, identifying the date
  public $dayOfWeek;  // Day of the week
  public $daysArray = [];  // Store the days in an array
  public $monthNumber;
  public $yearNumber;

  // Constructor, taking two parameters
  // Day, month and year are constructed before being appended to an array
  public function __construct($dayNumber, $month, $year){

    // Current instances for day number, current month, current year, and appending to a days array
    $this->dayNumber = $dayNumber;
    $this->month = $month;
    $this->year = $year;

    // Extra instance variables in case the previous instance variables change; these are constants
    $this->realCurrentDayNum = date ('d');
    $this->realCurrentYear = date('Y');
    $this->realCurrentMonthNum = date('m');
  }

  // Function for generating the dates for the calendar as DOM objects
  public function show(){

    // Algorithm for displaying the CURRENT DATE in the calendar as a unique theme (white), not the main theme (blue)
    global $globalMonth; // Pre defined constant used to measure the month
    global $globalYear; // Pre defined constant used to measure the year

    // If it is the same year, month, and day then highlight the day in white
    // Print $this->daynumber inside the date cell
    if ((date('m')) == $globalMonth && (date('Y')) == $globalYear && (date('d')) == $this->dayNumber){
      $output = '<form method = "post" action = "schedulesfind.inc.php">';
      $output .= '
      <input type = "text" name = "schedulesyearsearch" value = ' . $this->year . ' readonly hidden></input>
      <input type = "text" name = "schedulesmonthsearch" value = ' . $this->month . ' readonly hidden></input>
      <input type = "text" name = "schedulesnamesearch" value = "" readonly hidden></input>
      <input type = "text" name = "schedulesdescriptionsearch" value = "" readonly hidden></input>
      <input type = "text" name = "scheduleshoursearch" value = "" readonly hidden></input>
      <input type = "text" name = "schedulesdurationsearch" value = "" readonly hidden></input>
      <input type = "text" name = "schedulesurgencysearch" value = "" readonly hidden></input>
      <input type = "checkbox" name = "showprevious" class = "scheduleselect" checked readonly hidden></input>
      <input type = "submit" name = "schedulesdaysearch" class = "Day" style = "
      background-color: #fff;
      font-family: Montserrat, sans-serif;
      width: 50px;
      padding-top: 6px;
      text-align: center;
      align-self: center;
      color: #0af;
      font-size: 30px;
      border-radius: 50%;
      border-style: solid;
      border-width: 2.2px;
      border-color: #9cf;
      box-shadow: 0px 0px 30px #0af;
      height: 50px;
      " value = ' . $this->dayNumber . '></input>';
      $output .= '</form>';
    }
    // Linked to the CSS for previous day numbers (displayed as navy and cyan)
    // Also output a hidden HTML form used to search for schedules based on that specific date
    // Else print the default style, which is in the css file
    else{
      $output = '<form method = "post" action = "schedulesfind.inc.php">';
      $output .= '
      <input type = "text" name = "schedulesyearsearch" value = ' . $this->year . ' readonly hidden></input>
      <input type = "text" name = "schedulesmonthsearch" value = ' . $this->month . ' readonly hidden></input>
      <input type = "text" name = "schedulesnamesearch" value = "" readonly hidden></input>
      <input type = "text" name = "schedulesdescriptionsearch" value = "" readonly hidden></input>
      <input type = "text" name = "scheduleshoursearch" value = "" readonly hidden></input>
      <input type = "text" name = "schedulesdurationsearch" value = "" readonly hidden></input>
      <input type = "text" name = "schedulesurgencysearch" value = "" readonly hidden></input>
      <input type = "checkbox" name = "showprevious" class = "scheduleselect" checked readonly hidden></input>
      <input type = "submit" name = "schedulesdaysearch" class = "Day"
      " value = ' . $this->dayNumber . '></input>';
      $output .= '</form>';
    }

    // RETURN but NOT ECHO as it will be echoed inside the Calendar later, which is a table
    // Echoing would lead to bad positioning outside the table cells
    return $output;
  }
}

// Class for calendar days in the next or previous month that are still visible in the calendar table
class CalendarNoneDay extends CalendarDay {
  public $dayNumber;  // The number a calendar cell stamp shows, identifying the date
  public $dayOfWeek;  // Day of the week
  public $daysArray = [];
  public $monthNumber;
  public $yearNumber;


  // Function for generating the calendar day as a HTML DOM element
  public function show(){

    // Algorithm for displaying the current day in the calendar as a unique theme
    global $globalMonth; // Pre defined constant
    global $globalYear; // Pre defined constant

    // Linked to the CSS for previous day numbers (displayed as navy and cyan)
    // Also output a hidden HTML form used to search for schedules based on that specific date
    $output = '<form method = "post" action = "schedulesfind.inc.php">
    <input type = "text" name = "schedulesyearsearch" value = ' . $this->year . ' readonly hidden></input>
    <input type = "text" name = "schedulesmonthsearch" value = ' . $this->month + 1 . ' readonly hidden></input>
    ';
    // Values that must be set but are arbitrary
    $output .= '
    <input type = "text" name = "schedulesnamesearch" value = "" readonly hidden></input>
    <input type = "text" name = "schedulesdescriptionsearch" value = "" readonly hidden></input>
    <input type = "text" name = "scheduleshoursearch" value = "" readonly hidden></input>
    <input type = "text" name = "schedulesdurationsearch" value = "" readonly hidden></input>
    <input type = "text" name = "schedulesurgencysearch" value = "" readonly hidden></input>
    ';
    // Date number to click on
    $output .= '
    <input type = "submit" name = "schedulesdaysearch" class = "PrevDay" value = ' . $this->dayNumber . '></input>
    </form>';

    // RETURN but NOT ECHO as it will be echoed inside the Calendar later, which is a table
    // Echoing would lead to bad positioning outside the table cells
    return $output;
  }
}

// Class for a single calendar month
class CalendarMonth {
  // Main attributes of month, year, days, week, day of the week, weekday names, and days array
  // Public as may be required in case calendars are being showed inside a group, like a table cell
  public $month;
  public $year;
  public $days;
  public $week;
  public $dayOfWeek;
  public $weekdays;
  public $daysArray = [];

  // Constructor function, with each weekday item being a weekday
  public function __construct($month, $year, $weekdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')){
    // Instance current month
    $this->month = $month;
    // Instance current year
    $this->year = $year;
    // Instance weekdays
    $this->weekdays = $weekdays;
    // Number of days in the current instance of the month
    $this->num_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    // First day of the month, which positions where the calendar should begin generating day cells (indent)
    $this->date_info = getdate(strtotime('first day of', mktime(0,0,0,$this->month, 1 ,$this->year)));
     // Get the info including the day of the week that <given_date> falls on
    $this->dayOfWeek = $this->date_info['wday'];
    // Previous day of the week
    $this->previousDay = getdate(strtotime('first day of', mktime (0,0,0, $this->month-1, 1, $this->year)));
    // Current instance weekday (on the first of the month)
    $this->weekDay = date('l');
    // Curent day of the month
    $this->dayOfMonth = date('d');
    // Full name of the month
    $this->fullMonthName = date('F');
  }
  public function show(){
    // Create a whole container in which to include the Calendar
    $output = '<div class = "CalendarContainer">';
    // The Calendar is a TABLE with each day as an individual table cell
    $output .= '<table class = "Calendar">';
    // Check if the calendar is displaying the current month or a different month
    $currentMonthCheck = date('m');
    $currentYearCheck = date('Y');
    // If the calendar is displaying the current month then print the whole date
    if ($this->month == $currentMonthCheck && $this->year == $currentYearCheck){
      // Print the current month and year at the top of the calendar in a caption
      $output .= '<caption class = "monthName">' . $this->weekDay . ', '  . $this->fullMonthName . '
      ' . $this->dayOfMonth . ', ' . $this->year . '</caption>';
    }
    // If the calendar is displaying a different month, and NOT the current month, then print the month name and year
    else{
      // e.g. "March 2021" instead of "Monday, January 1, 2021" where the current date is January 1 BUT the calendar is showing March
      $output .= '<caption class = "monthName">' . $monthName = date('F', mktime(0, 0, 0, $this->month, 1)) . ' ' . $this->year . '</caption>';
    }
    // New row to represent the days underneath the month header
    $output .= '<tr>';

    // Create days of the week header for each day in the array
    // For every day in the weekdays array, setting each of them AS a day ($day)
    // For each weekday in the calendar table print the day info (i.e. the date)
    foreach ( $this->weekdays as $days ){
      $output .= '<th class = "header">' . $days . '</th>';
    }
    // Separate each row of days
    $output .= '</tr><tr>';

    // IF the first day of a month doesnt fall on a SUNDAY, then use days from previous month ("prevday") to fill the beginning space
    if ($this->dayOfWeek > 0){
      // Number of days from the day the first day of the month falls on BACK TO Sunday
      $extraPrevDays = 7 - $this->dayOfWeek;
      // Get the previous month for the previous dates
      $prevMonth = previous_month_and_year($this->month);
      // If the previous month is not December then decrease the year as well
      // UNLESS the current month year is 1 in which case DO NOT SUBTRACT 1
      if ($prevMonth == 12){
        if ($this->year == 1){
          $prevMonthDays = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $this->year);
        }
        else{
          $prevMonthDays = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $this->year - 1);
        }
      }
      // Otherwise get the days in month for the current year in the calendar
      else{
        $prevMonthDays = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $this->year);
      }
      // Offset for how many previous month days there are in the table
      $prevMonthDays +=1;
      $prevMonthDays -= $this->dayOfWeek;

      // For previous month days counting backwards from 31, 30, 29 or 28 depending on how many days are in the month and in the previous month that fill the rest of the weeek gap
      // Use of a for loop until all of the previous days have been generated
      $prevCount = 1;
      for ($prevCount == 1; $prevCount <= $this->dayOfWeek; $prevCount++){
        // Output extra table cells to contain the previous days inside
        $output .= '<td class = "days">';
        // New previous calendar days counting backwards
        $prevDays = new CalendarNoneDay ($prevMonthDays, $this->month, $this->year);
        // Print the previous days in the calendar
        $output .= $prevDays->show();
        // The gap between current day and previous days becomes larger to move to the next earlier previous day
        $prevMonthDays++;
        // Close the table cell
        $output .= '</td>';
      }
    }

    // The num_days counter, starting from 1
    $current_day = 1;

    // While loop for building the whole calendar
    // While the current day is day number 0 to number of days in the month
    // Reset 'day of week' counter and clone each row if end of row
    // There are 7 days in a week.
    while ($current_day <= $this->num_days) {
      if ($this->dayOfWeek == 7){
        // reset the day numbers for each row of 7 days, so that each row goes from 1, 2... 7 (Sunday to Saturday)
        $this->dayOfWeek = 0;
        // end that row of 7 days and start a new row
        $output .= '</tr><tr>';
      }

      // Build each day cell
      // </td> tag ends a row of days once the day cells per line reaches 7 and creates a new one
      $output .= '<td class = "days">';
      // New calendar day object inside the calendar
      $dayObject = new CalendarDay($current_day, $this->month, $this->year);
      $output .= $dayObject->show();
      // Close the cell
      $output .= '</td>';


      // Increment counters
      // Increase the day
      $current_day++;
      // Increase the day
      $this->dayOfWeek++;
      // Store the newly instantiated day in an array
      $daysArray[$current_day] = $dayObject;
    }

    /*
    Once num_days counter reaches the end of the month, if day of week counter is not 7,
    then fill the remaining space on the row with the remaining days in the table
    */
    if ($this->dayOfWeek != 7) {    // Resets at 7
      $remaining_days = 7 - $this->dayOfWeek;
      $remain = 1;
      // Amount of remaining days in the week
      // Span for the remaining days in the week
      if ($remaining_days >= 1){
        // Fill the table with first 1-6 days from the next month, depending on how well the month fills the calendar
        for ($remain == 1; $remain <= (7 - $this->dayOfWeek); $remain++){
          $prevDay = new CalendarNoneDay ($remain, $this->month, $this->year);
          // Show the day in an enclosed table cell, just like normal month days and previous month days
          $output .= '<td class = "days">' . $prevDay->show() . '</td>';
        }
      }
    }

    // Close final row once all of the days have been generated, in reference to "current_day++ 11 lines up"
    if ($current_day == $this->num_days){
      $output .= '</tr>';
      $output .= '</table>';
      $output .= '</div>';
    }

    // Print the entire calendar, which is stored in $output
    echo $output;
  }
}
?>
</body>
</html>
