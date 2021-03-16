<?php
// Page to hold the classes for date-related classes schedules, and reminders
date_default_timezone_set('UTC');
class ScheduleEvent{
  public $schedulesID;
  public $schedulesTitle;
  public $schedulesDescription;
  public $schedulesYear;
  public $schedulesMonth;
  public $schedulesHour;
  public $schedulesMinute;
  public $schedulesDuration;
  public $schedulesUrgency;
  public $wholePostedTime;
  public $wholeCurrentTime;
  public $hideDescription;
  public $hideOptions;
  public function __construct($schedulesID, $schedulesTitle, $schedulesDescription,
  $schedulesYear, $schedulesMonth, $schedulesDay, $schedulesHour, $schedulesMinute,
  $schedulesDuration, $schedulesUrgency, $wholePostedTime, $wholeCurrentTime, $hideDescription, $hideOptions){
    $this->schedulesID = (int)($schedulesID);
    $this->schedulesTitle = $schedulesTitle;
    $this->schedulesDescription = $schedulesDescription;
    $this->schedulesYear = (int)($schedulesYear);
    $this->schedulesMonth = (int)($schedulesMonth);
    $this->schedulesDay = (int)($schedulesDay);
    $this->schedulesHour = (int)($schedulesHour);
    $this->schedulesMinute = (int)($schedulesMinute);
    $this->schedulesDuration = (int)($schedulesDuration);
    $this->schedulesUrgency = (int)($schedulesUrgency);
    $this->wholePostedTime = (int)($wholePostedTime);
    $this->wholeCurrentTime = (int)($wholeCurrentTime);
    $this->hideDescription = $hideDescription;
    $this->hideOptions = $hideOptions;

    // If wholeCurrentTime or wholePostedTime ends with 0-39 then add 40
    if ((int)(substr($this->wholePostedTime, -2)) < 40){
      $this->wholePostedTime = $this->wholePostedTime - 40;
    }
    if ((int)(substr($this->wholeCurrentTime, -2)) < 40){
      $this->wholeCurrentTime = $this->wholeCurrentTime - 40;
    }
  }
  public function show(){
    if ($this->schedulesHour >= 12) {
      $timeCulture = 'pm';
    }
    else{
      $timeCulture = 'am';
    }
    $dayPrepend = '';
    $monthPrepend = '';

    if ($this->schedulesMonth < 10){
      $monthPrepend = '0';
    }
    else {
      $monthPrepend = '';
    }
    if ($this->schedulesDay < 10){
      $dayPrepend = '0';
    }
    else{
      $dayPrepend = '';
    }

    // Format date
    $date =  date_create($this->schedulesDay . '-' . $this->schedulesMonth . '-' . $this->schedulesYear . ' ' . $this->schedulesHour . ':' . $this->schedulesMinute . ':00');
    $newDate = date_format($date, 'D, d M Y, G:i');
    // 'am', 'pm'
    $newDate .= $timeCulture;

    // Container for the schedule event
    $output = '<div class = "scheduleContainer">';
    $output .= '<div class = "scheduletitle"';
    if ($this->schedulesUrgency == 0){
      $this->schedulesUrgency = 1;
    }
    // Colours to indicate urgency

    // 3 = Red (Urgent)
    if ($this->schedulesUrgency == 3){
      $output .= 'style = "background-image:linear-gradient(to right, #f55, #f73); ';
    }
    // 2 = Yellow (Moderate)
    if ($this->schedulesUrgency == 2){
      $output .= 'style = "background-image:linear-gradient(to right, #fa0, #fd3); ';
    }
    // 1 = Green (Normal)
    if ($this->schedulesUrgency == 1){
      $output .= 'style = "background-image:linear-gradient(to right, #0f9, #0ff); ';
    }

    $titleLength = strlen($this->schedulesTitle);
    // If length longer than 16 then shrink font size to fit
    if ($titleLength > 16 && $titleLength < 24){
      $output .= 'font-size: 20px;';
    }
    if ($titleLength >= 23 && $titleLength < 31){
      $output .= 'font-size: 16px;';
    }

    $output .= '">' . $this->schedulesTitle . '</div>';
    if ($this->wholePostedTime == $this->wholeCurrentTime){
      $output .= '<div class = "scheduleheader" style = "background-image: linear-gradient (to right, #f50, #d44); font-size:35px;">NOW</div>';
    }
    else{
      if (isset($this->schedulesDuration) && $this->schedulesDuration > 0){
        $output .= '<div class = "scheduleheader" style = "font-size: 18px;">' . $newDate . '<br>' . 'For ' . $this->schedulesDuration . ' minutes';
      }
      else{
        $output .= '<div class = "scheduleheader">' . $newDate;
      }
      $output .= '</div>';
    }
    $output .= '<details class = "scheduledetails">';
    $output .= '<summary class = "schedulesummary">';

    if ($this->schedulesUrgency == 3){
      $output .= '[URGENT] Click to view' . '</summary>';
    }
    else{
      $output .= 'Click to view' . '</summary>';
    }

    if (strlen($this->schedulesDescription) < 1){
      if ($this->hideDescription != true){
        // Easter egg, print different empjis based on the time of day, if the description of the schedule is empty

        // Mood: tired.
        if (date('G') <= 7){
          $this->schedulesDescription = '⌚😩';
        }
        // Refreshed.
        if (date ('G') > 7 && date ('G') <= 14){
          $this->schedulesDescription = '⌚😃';
        }
        // Doing something productive.
        if (date ('G') > 14 && date ('G') <= 20){
          $this->schedulesDescription = '⌚🧐';
        }
        // Yawn.
        if (date ('G') > 20 && date ('G') <= 23){
          $this->schedulesDescription = '⌚🥱';
        }
      }
      else{
        $this->schedulesDescription = '[No description provided]';
      }
    }
    $output .= '<div class = "description">' . $this->schedulesDescription . '</div>';
    $output .= '</details>';
    $now = new DateTime();
    $futureDate = new DateTime($this->schedulesYear . '-' . $this->schedulesMonth .'-'. $this->schedulesDay . ' ' . $this->schedulesHour . ':' . $this->schedulesMinute . '' . ':00');
    $interval = $futureDate->diff($now);
    if ($this->wholePostedTime > $this->wholeCurrentTime){
      if ((int)($this->wholePostedTime) - (int)($this->schedulesDuration) < (int)($this->wholeCurrentTime)){
        $output .= 'Time elapsed: ' . (((int)($this->wholePostedTime) - (int)($this->wholeCurrentTime)) - (int)($this->schedulesDuration)) * -1 . '/' . (int)($this->schedulesDuration) . ' minutes.';
        $output .= '<br>Time remaining: ' . $this->schedulesDuration - ((((int)($this->wholePostedTime) - (int)($this->wholeCurrentTime)) - (int)($this->schedulesDuration)) * -1) . '/' . (int)($this->schedulesDuration) . ' minutes';
      }
      else{
        $output .= 'Time until: ' . $interval->format('%a day(s), %h hour(s), %i minute(s)');
      }
    }
    else{
      $output .= '[Finished]';
    }
    // Only exists if the options to edit or delete are enabled, which is not the case in the schedules delete/edit pages
    if ($this->hideOptions != true){
    $output .= '<form action = "delete.php" method = "post">';
    $output .= '<input name = "schedulesdelete" type = "text" value = "' . $this->schedulesID . '" readonly hidden></input>';
    $output .= '<input type = "submit" value = "Delete" name = "requestdelete" style = "
    background-color: #f55; color: #fff; width: 100px; height: 40px; position: absolute; top: 48; left: 380;
    border-color: #f99; border-style:solid; cursor: pointer; font-size: 20px; font-family: Montserrat;">';
    $output .= '</input>';
    $output .= '</form>';
    // Form for editing
    // Submits the values in the current schedule, including its unique identifier ID, to the database for editing
      $output .= '<form action = "schedules.inc.php" method = "post">';
      $output .= '<input name = "schedulesgetid" type = "text" value = ' . $this->schedulesID . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgettitle" type = "text" value = "' . $this->schedulesTitle . '" readonly hidden> </input>';
      $output .= '<input name = "schedulesgetdescription" type = "text" value = "' . $this->schedulesDescription . '" readonly hidden> </input>';
      $output .= '<input name = "schedulesgetyear" type = "text" value = ' . $this->schedulesYear . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgetmonth" type = "text" value = ' . $this->schedulesMonth . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgetday" type = "text" value = ' . $this->schedulesDay . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgethour" type = "text" value = ' . $this->schedulesHour . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgetminute" type = "text" value = ' . $this->schedulesMinute . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgetduration" type = "text" value = ' . $this->schedulesDuration . ' readonly hidden> </input>';
      $output .= '<input name = "schedulesgeturgency" type = "text" value = ' . $this->schedulesUrgency . ' readonly hidden> </input>';
      $output .= '<input type = "submit" name = "requestedit" value = "Edit" style = "
      background-color: #5f5; color: #fff; width: 100px; height: 40px; position: absolute; top: 48;
      border-color: #9f9; border-style:solid; cursor: pointer; font-size: 20px; font-family: Montserrat;">';
      $output .= '</input>';
      $output .= '</form>';
    }
    $output .= '</div>';
    echo $output;
    echo'<br>';
  }
}

class Reminder{
  public $remindersID;
  public $remindersTitle;
  public $remindersYear;
  public $remindersMonth;
  public $remindersHour;
  public $remindersMinute;
  public $remindersUrgency;
  public function __construct($remindersID, $remindersTitle,
  $remindersYear, $remindersMonth, $remindersDay, $remindersHour,
  $remindersMinute, $remindersUrgency){
    $this->remindersID = (int)($remindersID);
    $this->remindersTitle = $remindersTitle;
    $this->remindersYear = (int)($remindersYear);
    $this->remindersMonth = (int)($remindersMonth);
    $this->remindersDay = (int)($remindersDay);
    $this->remindersHour = (int)($remindersHour);
    $this->remindersMinute = (int)($remindersMinute);
    $this->remindersUrgency = (int)($remindersUrgency);
  }
  public function show(){
    $timeCulture = '';
    $date =  date_create($this->remindersDay . '-' . $this->remindersMonth . '-' . $this->remindersYear . ' ' . $this->remindersHour . ':' . $this->remindersMinute . ':00');
    $newDate = date_format($date, 'G:i');
    $newDate .= $timeCulture;

    $urgency = '';
    $output = '';
    if ($this->remindersUrgency == 0){
      $this->remindersUrgency = 1;
    }
    if ($this->remindersUrgency == 3){
      $output = 'style = "background-image:linear-gradient(to right, #f55, #f73);"';
      $urgency = 'URGENT';
    }
    if ($this->remindersUrgency == 2){
      $output = 'style = "background-image:linear-gradient(to right, #fa0, #fd3);"';
      $urgency = 'important';
    }
    if ($this->remindersUrgency == 1){
      $output = 'style = "background-image:linear-gradient(to right, #0f9, #0ff);"';
      $urgency = 'non-urgent';
    }
    // show
    $output = '<div class = "schedulesreturn"' . $output . '>' . $this->remindersTitle . '</div>';
    $output .= '<div class = "schedulesindicatetime">' . $newDate . ' | ' . $urgency;
    $output .= '<form method = "post" action = "schedulesfind.inc.php">
    <input type = "text" name = "schedulesnamesearch" value = "' . $this->remindersTitle . '" readonly hidden></input>
    <input type = "text" name = "schedulesurgencysearch" value = "' . $this->remindersUrgency . '" readonly hidden></input>
    <input type = "text" name = "schedulesyearsearch" value = "' . $this->remindersYear . '" readonly hidden></input>
    <input type = "text" name = "schedulesmonthsearch" value = "' . $this->remindersMonth . '" readonly hidden></input>
    <input type = "text" name = "schedulesdaysearch" value = "' . $this->remindersDay . '" readonly hidden></input>
    <input type = "text" name = "scheduleshoursearch" value = "' . $this->remindersHour . '" readonly hidden></input>
    <input type = "submit" name = "prevsubmit" class = "scheduleselect" style = "width:100%; height:30px;" value = "View this event"></input>';
    $output .= '</form></div><br><br>';
    echo $output;
  }
}
?>
<html>
<head>
  <link rel = "stylesheet" href = "calendarstyle.css">
</HEAD>
