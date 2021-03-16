<?php
// Page to edit a note

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}
include_once('headerInSchedules.php');
require_once('config.php');

// Always get the British timezone
date_default_timezone_set('UTC');

// Store the current title in a session variable, so that if the user tries to submit an entry title, it defaults to the original
$_SESSION['savedName'] = $_POST['notesgetname'];

// Store the ID of the current note being edited in a global variable
$_SESSION['notesID'] = $_POST['notesgetid'];
?>
<html>
<head>
  <link rel = "stylesheet" href = "notepadstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <script type = "text/javascript" src = "notepad.js"></script>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,700;1,800&display=swap" rel="stylesheet">
  <style>
  textarea{white-space:pre-wrap;
  }
  </style>
<div class="output"></div>
</script>
</head>
<body>
  <div class = "notescontainer">
    <form method = "post" action = notesedit.inc.php>
      <label><span id = "titlelabel">Title: (0/50 characters)</span></label>
      <br>
      <input id = "notestitle"  onkeyup = "count_title(this.value, 50)" name = "notestitle" class = "notesinput" value = "<?php echo $_POST['notesgetname'];?>"></input>
      <br>
      <label><span id = "textlabel">Text: (0/30000 characters)</span></label>
      <br>
      <textarea id = "notestext" class = "notestext" onkeyup = "count_text(this.value, 30000)" name = "notestext" class = "notesinput"><?php echo $_POST['notesgettext'];?></textarea>
      <br>
      <input id = "submit" type = "submit" value = "Save" class = "scheduleselect" style = "font-size:40px;"></input>
      <input type = "button" value = "Cancel" class = "scheduleselect" onclick = "location.href = 'notepad.php'" style = "font-size:40px;"></input>
    </form>
  </div>
</body>
</html>
