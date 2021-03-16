<?php
// Page to choose to delete a note
// Redirect from notepad.php

// Initialize the session
session_start();
require_once ('config.php');
include_once ('headerInProgram.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

if(!$con){
  die('Connection failed :(');
}

// Always get the British timezone
date_default_timezone_set('UTC');

$postedDelete = $_POST['notesdeleteid'];
?>
<html>
<head>
  <link rel = "stylesheet" href = "notepadstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
  <script type = "text/javascript" src = "notepad.js"></script>
</head>
<body>
  <br><br>
  <div class = "notesdeleteholder">
    <div class = "notesholder">
      <?php
      // Retrieving the notepad names
      $sql = 'SELECT * FROM notes WHERE notesID = ?';
      $stmt = $con->prepare($sql);
      $stmt->bind_param('i', $notesID);
      $notesID = $_POST['notesdeleteid'];
      $stmt->execute();
      $result = $stmt->get_result();
      while($row = $result->fetch_assoc()){
        $notesName = $row['notesName'];
        $notesText = $row['notesText'];
        $notesDup = $row['notesDuplicate'];
        $notesID = $row['notesID'];
        // If there are duplicate notes with the same name then get a variable for indicating the duplicate/repeat number
        if ($notesDup > 0){
          $notesDuplicate = ' (' . $notesDup . ')';
        }
        // Else there are no other notes with the same name
        else{
          $notesDuplicate = '';
        }
        // Get substring preview of the text
        if (strlen($notesText) > 100){
          $notesTextPreview = substr($notesText, 0, 100) . '...';
        }
        // Preview for the text without showing all of it
        else{
          $notesTextPreview = $notesText;
        }
        // Get substring preview of the name if longer thn 30
        if (strlen($notesName) > 30){
          $notesNamePreview = substr($notesName, 0, 30) . '...' . $notesDuplicate;
        }
        else{
          $notesNamePreview = $notesName  . $notesDuplicate;
        }
        $output = '<div class = "notescontainment">';
        // Output the notes, as well as (invisible) forms containing the data in case the user requests to edit or delete,
        // to hold the data being edited or deleted
        $output .= '
        <form action = "notesedit.php" method = "post">
        <input type = "text" name = "notesgetname" value = "' . $notesName . '" readonly hidden></input>
        <input type = "text" name = "notesgettext" value = "' . $notesText . '" readonly hidden></input>
        <input type = "text" name = "notesgetid" value = "' . $notesID . '" readonly hidden></input>
        <div class = "scheduleheader" style = "color: #fff;">' . $notesNamePreview . '</div>
        <input type = "submit" value = "Edit" style = "color: #fff; background-color: #5f5;
        width:70px; height:40px;
        font-family: Montserrat;
        border-style:solid;
        border-color:#7f7;">
        </input>
        </form>
        <form action = "notesdelete.php" method = "post">
        </form>
        <br>
        <div class = "notestext" style = "height: 50px;">' . $notesTextPreview . '</div>
        <br><br>
        </div>
        ';
        echo $output;
      }
      ?>
    </div>
  </div>
  <br><br><br><br><br><br><br><br><br><br><br><br>
  <div id = "Ask" style = "width: inherit; height: 50px; font-size: 30; background-color: #f33; color: #fde;">Are you sure you want to delete this note?</div>
  <div id = "deleteholder">
    <form id = "deleteform" action = "notesdelete.inc.php" method = "post">
      <div class = "schedulesdiv">
        <input type = "text" name = "notesgetdelete" value = <?php echo $postedDelete;?> readonly hidden></input>
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
    <form id = "cancel" action = "notepad.php" method = "post">
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
