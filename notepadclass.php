<?php
class Note{
  public $notesName;
  public $notesText;
  public $notesDup;
  public $notesID;
  public $notesDateCreated;
  public $notesDateModified;

  public function __construct($notesName, $notesText, $notesDup, $notesID, $notesDateCreated, $notesDateModified){
    $this->notesName = $notesName;
    $this->notesText = $notesText;
    $this->notesDup = $notesDup;
    $this->notesID = $notesID;
    $this->notesDateCreated = $notesDateCreated;
    $this->notesDateModified = $notesDateModified;
  }

  public function show(){
    // If there are duplicate notes with the same name then get a variable for indicating the duplicate/repeat number
    if ($this->notesDup > 0){
      $notesDuplicate = ' (' . $this->notesDup . ')';
    }
    // Else there are no other notes with the same name
    else{
      $notesDuplicate = '';
    }
    // Get substring preview of the text
    if (strlen($this->notesText) > 30){
      $notesTextPreview = substr($this->notesText, 0, 30) . '...';
    }
    // Preview for the text without showing all of it
    else{
      $notesTextPreview = $this->notesText;
    }
    // Get substring preview of the name if longer thn 30
    if (strlen($this->notesName) > 30){
      $notesNamePreview = substr($this->notesName, 0, 30) . '...' . $notesDuplicate;
    }
    else{
      $notesNamePreview = $this->notesName  . $notesDuplicate;
    }
    $output = '<div class = "notescontainment">';
    // Output the notes, as well as (invisible) forms containing the data in case the user requests to edit or delete,
    // to hold the data being edited or deleted
    $output .= '
    <form action = "notesedit.php" method = "post">
    <input type = "text" name = "notesgetname" value = "' . $this->notesName . '" readonly hidden></input>
    <input type = "text" name = "notesgettext" value = "' . $this->notesText . '" readonly hidden></input>
    <input type = "text" name = "notesgetid" value = "' . $this->notesID . '" readonly hidden></input>
    <div class = "scheduleheader" style = "color: #fff;">' . $notesNamePreview . '</div>
    <div class = "scheduleheader" style = "height:25px;">
    Date created: ' . $this->notesDateCreated . '<br></div>
    <div class = "scheduleheader" style = "height:25px;">
    Date modified: ' . $this->notesDateModified . '<br></div>
    <input type = "submit" value = "Edit" style = "color: #fff; background-color: #5f5;
    width:70px; height:40px;
    font-family: Montserrat;
    border-style:solid;
    border-color:#7f7;">
    </input>
    </form>
    <form action = "notesdelete.php" method = "post">
    <input type = "text" name = "notesdeleteid" value = ' . $this->notesID . ' readonly hidden></input>
    <input type = "submit" value = "Delete" style = "color: #fff; background-color: #f55;
    width:70px; height:40px; float:right;
    font-family: Montserrat;
    border-style:solid;
    border-color:#f77;
    position:relative;
    bottom:56px;"></input>
    </form>
    <br>
    <form action = "notesviewer.php" method = "post">
    <input type = "text" name = "notesgetid" value = ' . $this->notesID . ' readonly hidden></input>
    <input type = "text" name = "notesgetname" value = "' . $this->notesName . '" readonly hidden></input>
    <input type = "submit" value = "Read/View" style = "width: 328px; margin:auto;" class = "scheduleselect"></input>
    </form>
    <div class = "notestext">' . $notesTextPreview . '</div>
    <br><br>
    </div>
    ';
    echo $output;
  }
}
