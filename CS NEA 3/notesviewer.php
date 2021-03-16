<?php
// Page for viewing a note as read-only

// Initialize the session
session_start();
require_once ('config.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

if(!$con){
  die('connection failed :(');
}

// Header/banner
include_once('headerInSchedules.php');
// Always get the British timezone
date_default_timezone_set('UTC');#
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Timeacc - Free scheduling and more">
  <meta name="keywords" content="time, timmeacc, scheduling, reminders">
  <meta name="author" content="Imole Adebayo">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel = "icon" href = "https://previews.123rf.com/images/mcklog/mcklog1105/mcklog110500002/9616471-a-render-of-a-vintage-alarm-clock.jpg">
  <link rel = "stylesheet" href = "notepadstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <title><?php echo $_POST['notesgetname'] . ' - Timeacc';?></title>
</head>
<body>
  <div class = "notesviewerholder" style = "white-space:pre-wrap;">
  <?php
  if ($con){
    // Select the note the user just requested to view
    $sql = 'SELECT * FROM notes WHERE notesID = ?';
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $notesID);
    $notesID = $_POST['notesgetid'];
    $stmt->execute();
    $result = $stmt->get_result();
    // Print the note out
    while ($row = $result->fetch_assoc()){
      $notesTitle = $row['notesName'];
      $notesText = $row['notesText'];
      $notesDateCreated = $row['notesDateCreated'];
      $notesDateModified = $row['notesDateModified'];
      $output = '<div class = "notesviewer">
      <div class = "scheduleheader" style = "font-size: 30px;"> ' . $notesTitle . '</div>
      <div class = "notestext">' . $notesText . '</div>
      </div>';
      echo $output;
    }
  }
  ?>
  </div>
  <div class = "notesinfo">
    <?php
    $notepad = "'notepad.php'";
    $output = '
    <form action = "notesedit.php" method = "post">
    <input type = "text" name = "notesgetname" value = "' . $notesTitle . '" readonly hidden></input>
    <input type = "text" name = "notesgettext" value = "' . $notesText . '" readonly hidden></input>
    <input type = "text" name = "notesgetid" value = "' . $notesID . '" readonly hidden></input>
    <div class = "scheduleheader" style = "height:25px;">
    Date created: ' . $notesDateCreated . '<br></div>
    <div class = "scheduleheader" style = "height:25px;">
    Last modified: ' . $notesDateModified . '<br></div>
    <input type = "submit" value = "Edit" style = "color: #fff; background-color: #5f5;
    width:33.3333%; height:40px;
    font-family: Montserrat;
    border-style:solid;
    border-color:#7f7 float:left; position:relative; font-size: 30px; text-shadow: 5px 5px 9px #888; left:33.3333%;">
    </input>
    </form>
    <form action = "notesdelete.php" method = "post">
    <input type = "text" name = "notesdeleteid" value = ' . $notesID . ' readonly hidden></input>
    <input type = "submit" value = "Delete" style = "color: #fff; background-color: #f55;
    width:33.3333%; height:40px; float:left; font-size: 30px; text-shadow: 5px 5px 9px #888; position:relative;
    font-family: Montserrat;
    border-style:solid;
    border-color:#f77;
    position:relative;
    bottom:56px;"></input>
    </form>
    <button onclick = "location.href = ' . $notepad . '" style = "color: #fff; background-color: #9cf;
    width:33.3333%; height:40px; float:right; font-size: 30px; text-shadow: 5px 5px 9px #888; position:relative;
    font-family: Montserrat;
    border-style:solid;
    border-color:#aef;
    bottom:56px;">Back to all notes</button>
  ';
  echo $output;
  ?>
</div>
</body>
</html>
