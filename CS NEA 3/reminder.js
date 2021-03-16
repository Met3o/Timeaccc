// Script for showing/hiding the reminders on the header
// Maximise/minimise system
function hide_reminders(){
  // Hide by minimising the reminders window, decreasing the height of the elements
  var x = document.getElementById('remindersholderholder');
  var y = document.getElementById('remindersholder');
  var z = document.getElementById('remindersindicateholder');
  var a = document.getElementById('showhide');
  if (x.style.height == '300px' ){
    x.style.height = 35;
    y.style.height = auto;
    z.style.height = 35;
    document.getElementById('showhide').innerHTML = 'Show';
  }
  // Show by increasing the size
  else {
    x.style.height = 300;
    y.style.height = auto;
    z.style.height = 350;
    document.getElementById('showhide').innerHTML = 'Hide';
  }
}
