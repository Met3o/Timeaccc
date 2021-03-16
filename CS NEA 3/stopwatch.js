// Script used to control the stopwatch
// All functions

// Timer starts at 0: 00: 00: 00: 00 when page is opened
document.getElementById ('stopwatch1').innerHTML = '0: 00: 00: 00: 00';

// Variable responsible for determining if the stopwatch is counting or not
var isTiming = false;

// Counting the time begins at 0
// These values can be altered at any time for testing
var days = 0;
var hours = 0;
var minutes = 0;
var seconds = 0;
var centiseconds = 0;

// The value responsible for setting the interval for which to count up, can be halted and reset at any point to pause in {function pause()}
var x;

// Update the count down every 1 centisecond | 10 milliseconds | 0.01 seconds (1%)
function count_stopwatch() {

  // Separate the values with a ': ' to make the format 'd: hh: mm: ss: cs: '
  /* Each unit value has its own separation which can change to ': 0' for the next place value, e.g. if centiseconds < 10 then 'ss: 0cs' so ss: 05*/
  var sepCent = ': ';
  var sepSec = ': ';
  var sepMin = ': ';
  var sepHour = ': ';

  // Algorithm for increments, prepending values below 10 with '0', e.g. instead of '56: 5' becomes '56: 05'

  /* EXAMPLE: if (seconds is 9 or less), OR if (seconds is about to reset to 0 AND centiseconds is 99, so that minutes will increment,
  the unit values begin with '0' when they reset to 0 so ': 00' and not just '0')*/

  // Anything after the '||' means 'if it resets to 0'
  if (centiseconds < 9 || centiseconds == 99){
    sepCent = ': 0';
  }
  if (seconds <= 9 || seconds == 59 && centiseconds == 99){
    sepSec = ': 0';
  }
  if (minutes < 9 || minutes == 59 && seconds == 59 && centiseconds == 99){
    sepMin = ': 0';
  }
  if (hours < 9 || hours == 59 && minutes == 59 && seconds == 59 && centiseconds == 99){
    sepHour = ': 0';
  }

// Removing the '0' prepend once increments reach double digits
  else{
    if (centiseconds >= 10 && centiseconds != 99){
      sepCent = ': ';
    }
    if (seconds >= 10 && seconds != 59 && centiseconds == 99){
      sepSec = ': ';
    }
    if (minutes >= 10 && minutes != 59 && seconds == 59 && centiseconds == 99){
      sepMin = ': ';
    }
    if (hours >= 10 && hours != 59 && minutes == 59 && seconds == 59 && centiseconds == 99){
      sepHour = ': ';
    }
  }

  // Algorithms for counting up, this time using nested if
  // Increment each unit up once the next smallest unit reaches maximum value. E.g.once centiseconds reaches 99 and resets to 0, a whole second has passed, therefore ++.
    if (centiseconds == 99){
      centiseconds = 0;
      seconds++;
      if (seconds > 59){
        seconds = 0;
        minutes++;
        if (minutes > 59){
          minutes = 0;
          hours++;
          if (hours > 24){
            hours = 0;
            days++;
          }
        }
      }
    }
    else{
      centiseconds++;
    }

  // Separate the values by the colon, ': ', and unit validation, ': 0', in sepSec, sepCent etc.
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Function for changing whether to count up or remain paused / stopped
function change_state(){
  if (!x){
    x = setInterval(count_stopwatch, 10);
    document.getElementById('control').innerHTML = 'Pause';
    console.log ('Stopwatch started.');
  }
  else{
    pause();
    document.getElementById('control').innerHTML = 'Resume';
  }
}

// Pausing the stopwatch
function pause(){
  clearInterval(x);
  x = false;
  console.log ('Stopwatch paused.');
}

// Reset the timer to 0 and set the display to 0
// Also this resets the 'Resume function' to 'Start' if the stopwatch is already paused
function reset(){
  // Reset all the counting values to 0
  days = 0;
  hours = 0;
  minutes = 0;
  seconds = 0;
  centiseconds = 0;

  // Change the stopwatch display to appear resetted
  document.getElementById('stopwatch1').innerHTML = '0: 00: 00: 00: 00';
  if (!x){
    document.getElementById('control').innerHTML = 'Start';
  }
  console.log ('Stopwatch resetted.');
}

// Stop the stopwatch AND reset it
function pause_and_reset(){
  // Stop the stopwatch counting using the pause function already defined above
  pause();

  // Reset the stopwatch to 0: 00: 00: 00: 00
  reset();

  console.log ('Stopwatch terminated.');
}
