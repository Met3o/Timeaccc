<?php
// Page to create a set of notes

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}
include_once('headerInSchedules.php');
require_once('config.php');

?>
<html>
<head>
  <link rel = "stylesheet" href = "notepadstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <script type = "text/javascript" src = "notepad.js"></script>
</head>
<body>
  <div class = "notescontainer">
  <div class = "scheduleheader" style = "height: 25px; color: #446;">Note: Tags (<, >) will not be submitted.</div>
    <form method = "post" action = notepadcreator.inc.php>
      <label><span id = "titlelabel">Title: (0/50 characters)</span></label>
      <br>
      <input id = "notestitle"  onkeyup = "count_title(this.value, 50)" name = "notestitle" class = "notesinput"></input>
      <br>
      <label><span id = "textlabel">Text: (0/30000 characters)</span></label>
      <br>
      <textarea id = "notestext" onkeyup = "count_text(this.value, 30000)" name = "notestext" class = "notesinput" style = "white-space:pre-wrap;"></textarea>
      <br>
      <input type = "submit" value = "Create" class = "scheduleselect" style = "font-size:40px;"></input>
      <input type = "button" value = "Cancel" class = "scheduleselect" onclick = "location.href = 'notepad.php'" style = "font-size:40px;"></input>
    </form>
  </div>
</body>
</html>
