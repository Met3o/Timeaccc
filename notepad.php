<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
 header('location: login.php');
 exit;
}

include_once ('headerInProgram.php');
include_once ('notepadclass.php');
?>
<html>
<head>
  <link rel = "stylesheet" href = "notepadstyle.css">
  <link rel = "stylesheet" href = "calendarstyle.css">
</head>
<body>
  <div id = "wholder">
    <div class = "notesholderholder">
      <div class = "scheduleheader">Search</div>
      <form action = "notesfind.inc.php" method = "post">
        <label>Date modified:</label>
        <select name = "notesdatemodifiedsearch" class = "scheduleselect" id = "notesdatemodifiedsearch">
          <option value = "">Default</option>
          <option value = "Descending">Recent</option>
          <option value = "Ascending">Earliest</option>
        </select>
        <br>
        <label>Date created:</label>
        <select name = "notesdatecreatedsearch" class = "scheduleselect" id = "notesdatecreatedsearch">
          <option value = "">Default</option>
          <option value = "Descending">Recent</option>
          <option value = "Ascending">Earliest</option>
        </select>
        <br>
        <label> Alphabetical order:</label>
        <select name = "notesnameorder" class = "scheduleselect" id = "notesdateorder">
          <option value = "">None</option>
          <option value = "Ascending">Alphabetical</option>
          <option value = "Descending">Reversed</option>
        </select>
        <br>
        <input type="text" placeholder="Name: Any" name="notesnamesearch" style = "cursor: text;" class = "scheduleselect"></input>
        <br><br>
        <input type = "submit" value = "Show results"  class = "scheduleselect" style = "width: 200px; height:30px; font-size:15px; background-color: #5ef;"></input>
      </form>
      <div class = "notesholder">
        <button class = "scheduleselect" onclick = "location.href = 'notepadcreator.php'">Create new note</button>
        <br></br>
        <?php
        // Retrieving the notepad names
        if (isset($_SESSION['notesQuery'])){
          if (strlen($_SESSION['notesQuery']) > 10){
            $sql = $_SESSION['notesQuery'];
          }
        }
        else{
          $sql = 'SELECT * FROM (notes, notesusers) WHERE notesusers.notesUserID = ? AND notes.notesID = notesusers.notesID';
        }

        // Avoid an error if $_SESSION['notesName'] was not set
        // Which means that the user did not request a name search
        // So assign an empty but existent value to it
        if (!isset($_SESSION['notesName'])){
          $_SESSION['notesName'] = ' ';
        }

        $stmt = $con->prepare($sql);
        // If the user requested a name search, then bind the note ID AND the name search
        if (strlen(trim($_SESSION['notesName'])) > 0){
          $stmt->bind_param('is', $userID, $notesName);
          $notesName = $_SESSION['notesName'];
          $userID = $_SESSION['id'];
        }
        // Else just bind the ID
        else{
          $stmt->bind_param('i', $userID);
          $userID = $_SESSION['id'];
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0){
          echo '<div class = "scheduletitle" style = "width:100%; height: 80; font-size:16; color: #446;">
          No notes appear to have been created, or they have but do not match your search. Click on "Create new note" to create one, or perhaps refine your search, or reset your search preferences.
          </div>';
        }
        while($row = $result->fetch_assoc()){
          $notesName = $row['notesName'];
          $notesText = $row['notesText'];
          $notesDup = $row['notesDuplicate'];
          $notesID = $row['notesID'];
          $notesDateCreated = $row['notesDateCreated'];
          $notesDateModified = $row['notesDateModified'];
          $note = new Note($notesName, $notesText, $notesDup, $notesID, $notesDateCreated, $notesDateModified);
          $note->show();
        }
        $stmt->close();
        ?>
      </div>
    </div>
  </div>
</body>
</html>
