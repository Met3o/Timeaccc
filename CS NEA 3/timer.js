// Script for controlling the timer
// Used by timer.php

spanText = document.querySelector('stopwatch1');

// Timer starts at 0: 00: 00: 00: 00 when page is opened
document.getElementById ('stopwatch1').innerHTML = '0: 00: 00: 00: 00';

// Check if the timer has been set to any offset amount
timeHasBeenSet = false;

// Load audio alarm sound
var timerStopNow = new Audio('AlarmSound1.ogg');
function play_alarm(){
  if (timeHasBeenSet == true){
    timerStopNow.play();
    timerStopNow.loop = true;
  }
}

// Stop looping the alarm noise
function clear_alarm_noise(){
  timerStopNow.loop = false;
  timerStopNow.pause();
  timerStopNow.currentTime = 0;
  timeHasBeenSet = false;
}

// Variable responsible for determining if the timer is counting or not
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
function count_timer() {
  isTiming = true;
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
  if (centiseconds <= 9 || centiseconds >= 100){
    sepCent = ': 0';
  }
  if (seconds <= 9 || seconds >= 59 && centiseconds >= 100){
    sepSec = ': 0';
  }
  if (minutes <= 9 || minutes >= 59 && seconds >= 59 && centiseconds >= 100){
    sepMin = ': 0';
  }
  if (hours <= 9 || hours >= 59 && minutes >= 59 && seconds >= 59 && centiseconds >= 100){
    sepHour = ': 0';
  }

  // Removing the '0' prepend once increments reach double digits
  else{
    if (centiseconds >= 10 && centiseconds <= 99){
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

  // Algorithms for counting down, this time using nested if
  // Increment each unit up once the next smallest unit reaches maximum value. E.g.once centiseconds reaches 99 and resets to 0, a whole second has passed, therefore ++.
    if (centiseconds == 0){
      if (seconds == 0 && minutes == 0 && hours == 0 && days == 0){
        if (timeHasBeenSet == true){
          timing_is_over();
          play_alarm();
          spanText.style.color = rgb(255,0,0);
        }
      }
      else{
        centiseconds = 99;
        seconds--;
        sepCent = ': ';
        if (seconds < 0){
          seconds = 59;
          minutes--;
          sepSec = ': ';
          if (minutes < 0){
            minutes = 59;
            hours--;
            sepMin = ': ';
            if (hours < 0){
              hours = 23;
              sepHour = ': ';
              days--;
            }
          }
        }
      }
    }
    else{
      centiseconds--;
      document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
    }
  // Separate the values by the colon, ': ', and unit validation, ': 0', in sepSec, sepCent etc.
}

var sepCent = ': 0';
if (centiseconds >= 10){
  sepCent = ': ';
}
var sepSec = ': 0';
if (seconds >= 10){
  sepSec = ': ';
}
var sepMin = ': 0';
if (minutes >= 10){
  sepMin = ': ';
}
var sepHour = ': 0';
if (hours >= 10){
  sepHour = ': ';
}

// Function for changing whether to count up or remain paused / stopped
function change_state(){
  if (!x){
    if (centiseconds == 0 && seconds == 0 && minutes == 0 && hours == 0 && days == 0){
      document.getElementById('stopwatch1').innerHTML = 'Please enter some values';
      isTiming = false;
    }
    else {
      x = setInterval(count_timer, 10);
      if (timeHasBeenSet != false){
        document.getElementById('control').innerHTML = 'Pause';
      }
      console.log ('Timer started, value: ' +  days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds);

      // Allow alarm to be sounded if the timer reaches 0
      if (centiseconds != 0 || seconds != 0 || minutes != 0 || hours != 0 || days != 0){
        timeHasBeenSet = true;
      }
    }
  }
  else{
    pause();
    document.getElementById('control').innerHTML = 'Resume';
  }
}

// Pausing the timer
function pause(){
  clearInterval(x);
  x = false;
  isTiming = false;
  console.log ('Timer paused.');
}

// Reset the timer to 0 and set the display to 0
// Also this resets the 'Resume function' to 'Start' if the timer is already paused
function reset(){
  clear_alarm_noise();
  // Reset all the counting values to 0
  days = 0;
  hours = 0;
  minutes = 0;
  seconds = 0;
  centiseconds = 0;

  // Change the timer display to appear resetted
  document.getElementById('stopwatch1').innerHTML = '0: 00: 00: 00: 00';
  if (!x){
    document.getElementById('control').innerHTML = 'Start';
  }
  console.log ('Timer resetted.');
}

function timing_is_over(){
  clearInterval(x);
  x = false;
  isTiming = false;
  console.log ('Timer paused.');

  // Reset all the counting values to 0
  days = 0;
  hours = 0;
  minutes = 0;
  seconds = 0;
  centiseconds = 0;
  document.getElementById('stopwatch1').innerHTML = 'Time up! Press Reset.';
  if (!x){
    document.getElementById('control').innerHTML = 'Start';
  }
  console.log ('Timer is finished.');
}
// Stop the timer AND reset it
function pause_and_reset(){
  // Stop the timer counting using the pause function already defined above
  pause();

  // Reset the timer to 0: 00: 00: 00: 00
  reset();

  console.log ('timer terminated.');
}

function increment_place_value(){
  if (centiseconds == 99){
    centiseconds = 0;
    seconds++;
  }
  if (seconds == 59){
    seconds = 0;
    minutes++;
  }
  if (minutes > 59){
    minutes = 0;
    hours++;
  }
  if (hours > 24){
    hours = 0;
    days++;
  }
}

// Increase centiseconds by a unit
function increase_centiseconds_by_one(){
  // If the alarm is going off from a previous count then just silence it and start a new counting instance
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  centiseconds++;

  // Put a '0' before values lower than 10
  if (centiseconds < 10){
    sepCent = ': 0';
  }
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (centiseconds >= 100){
    centiseconds = centiseconds - 100;
    // Value is now < 10 so put a '0' before it
    sepCent = ': 0';
  }
  // Update the entire clock
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase centiseconds by 10
function increase_centiseconds_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +10
  centiseconds = centiseconds + 10;
  // Put a '0' before values lower than 10
  if (centiseconds < 10){
    sepCent = ': 0';
  }
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (centiseconds >= 100){
    centiseconds = centiseconds - 100;
    // Value is now < 10 so put a '0' before it
    sepCent = ': 0';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase seconds by a unit
function increase_seconds_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase + 1
  seconds++;
  // Put a '0' before values lower than 10
  if (seconds < 10){
    sepSec = ': 0';
  }
  if (seconds >= 10){
    sepSec = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (seconds >= 60){
    seconds = seconds - 60;
    // Value is now < 10 so put a '0' before it
    sepSec = ': 0';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase seconds by 10
function increase_seconds_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase + 10
  seconds = seconds + 10;
  // Put a '0' before values lower than 10
  if (seconds < 10){
    sepSec = ': 0';
  }
  if (seconds >= 10){
    sepSec = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (seconds >= 60){
    seconds = seconds - 60;
    // Value is now < 10 so put a '0' before it
    sepSec = ': 0';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase minutes by a unit
function increase_minutes_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  minutes++;
  // Put a '0' before values lower than 10
  if (minutes < 10){
    sepMin = ': 0';
  }
  if (minutes >= 10){
    sepMin = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (minutes >= 60){
    minutes = minutes - 60;
    // Value is now < 10 so put a '0' before it
    sepMin = ': 0';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase minutes by 10
function increase_minutes_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  minutes = minutes + 10;
  // Put a '0' before values lower than 10
  if (minutes < 10){
    sepMin = ': 0';
  }
  if (minutes >= 10){
    sepMin = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (minutes >= 60){
    minutes = minutes - 60;
    // Value is now < 10 so put a '0' before it
    sepMin = ': 0';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase hours by a unit
function increase_hours_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  hours++;
  // Put a '0' before values lower than 10
  if (hours < 10){
    sepMin = ': 0';
  }
  if (hours >= 10){
    sepHour = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (hours >= 24){
    hours = hours - 24;
    // Value is now < 10 so put a '0' before it
    sepHour = ': 0';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase hours by 10
function increase_hours_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  hours = hours + 10;
  // Put a '0' before values lower than 10
  if (hours < 10){
    sepMin = ': 0';
  }
  if (hours >= 10){
    sepHour = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (hours >= 24){
    hours = hours - 24;
    // Value is now < 10 so put a '0' before it
    sepHour = ': 0';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}


// Increase days by a unit
function increase_days_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  days++;

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }

  // Avoid hours prepending with a 0
  if (hours >= 10){
    sepHour = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// Increase days by a unit
function increase_days_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // Increase +1
  days = days + 10;

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }

  // Avoid hours prepending with a 0
  if (hours >= 10){
    sepHour = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease centiseconds by a unit
function decrease_centiseconds_by_one(){
  // If the alarm is going off from a previous count then just silence it and start a new counting instance
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  centiseconds--;

  // Put a '0' before values lower than 10
  if (centiseconds < 10){
    sepCent = ': 0';
  }
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (centiseconds < 0){
    centiseconds = 99 - centiseconds;
    if (centiseconds == 100){
      centiseconds = 99;
    }
    // Value is now < 10 so put a '0' before it
    sepCent = ': ';
  }
  // Update the entire clock
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease centiseconds by 10
function decrease_centiseconds_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -10
  centiseconds = centiseconds - 10;
  // Put a '0' before values lower than 10
  if (centiseconds < 10){
    sepCent = ': 0';
  }
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // If the value reaches 0 then reset to close to 100
  if (centiseconds < 0){
    centiseconds = 99 - centiseconds;
    if (centiseconds >= 100){
      // The value should end with the same unit digit
      // E.g. '06' - 10 becomes '96'
      centiseconds = 100 - (centiseconds - 99);
    }
    // Value is now < 10 so put a '0' before it
    sepCent = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease seconds by a unit
function decrease_seconds_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  seconds--;

  // Put a '0' before values lower than 10
  if (seconds < 10){
    sepSec = ': 0';
  }
  if (seconds >= 10){
    sepSec = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (seconds < 0){
    seconds = 59 - seconds;
    if (seconds == 60){
      seconds = 59;
    }
    // Value is now < 10 so put a '0' before it
    sepSec = ': ';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease seconds by 10
function decrease_seconds_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -10
  seconds = seconds - 10;
  // Put a '0' before values lower than 10
  if (seconds < 10){
    sepSec = ': 0';
  }
  if (seconds >= 10){
    sepSec = ': ';
  }

  // If the value reaches 0 then reset to close to 60
  if (seconds < 0){
    seconds = 59 - seconds;
    if (seconds >= 60){
      // The value should end with the same unit digit
      // E.g. '06' - 10 becomes '96'
      seconds = 60 - (seconds - 59);
    }
    // Value is now < 10 so put a '0' before it
    sepSec = ': ';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease minutes by a unit
function decrease_minutes_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  minutes--;

  // Put a '0' before values lower than 10
  if (minutes < 10){
    sepMin = ': 0';
  }
  if (minutes >= 10){
    sepMin = ': ';
  }

  // If the value reaches 100 then reset to 0
  if (minutes < 0){
    minutes = 59 - minutes;
    if (minutes == 60){
      minutes = 59;
    }
    // Value is now < 10 so put a '0' before it
    sepMin = ': ';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease minutes by 10
function decrease_minutes_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -10
  minutes = minutes - 10;
  // Put a '0' before values lower than 10
  if (minutes < 10){
    sepMin = ': 0';
  }
  if (minutes >= 10){
    sepMin = ': ';
  }

  // If the value reaches 0 then reset to close to 100
  if (minutes < 0){
    minutes = 59 - minutes;
    if (minutes >= 60){
      // The value should end with the same unit digit
      // E.g. '06' - 10 becomes '96'
      minutes = 60 - (minutes - 59);
    }
    // Value is now < 10 so put a '0' before it
    sepMin = ': ';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease hours by a unit
function decrease_hours_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  hours--;

  // Put a '0' before values lower than 10
  if (hours < 10){
    sepHour = ': 0';
  }
  if (hours >= 10){
    sepHour = ': ';
  }

  // If the value reaches 0 then reset to close to 100
  if (hours < 0){
    hours = 23 - hours;
    if (hours >= 24){
      // The value should end with the same unit digit
      // E.g. '06' - 10 becomes '96'
      hours = 24 - (hours - 23);
    }
    // Value is now < 10 so put a '0' before it
    sepHour = ': ';
  }


  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }
  if (centiseconds <= 9){
    sepCent = ': 0';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease hours by 10
function decrease_hours_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  hours = hours - 10;
  // Put a '0' before values lower than 10
  if (hours < 10){
    sepHour = ': 0';
  }
  if (hours >= 10){
    sepHour = ': ';
  }

  // If the value reaches 0 then reset to close to 100
  if (hours < 0){
    hours = 23 - hours;
    if (hours >= 24){
      // The value should end with the same unit digit
      // E.g. '06' - 10 becomes '96'
      hours = 24 - (hours - 23);
    }
    // Value is now < 10 so put a '0' before it
    sepHour = ': ';
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}


// decrease days by a unit
function decrease_days_by_one(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  days--;

  if (days <= 0){
    days = 0;
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }

  // Avoid hours prepending with a 0
  if (hours >= 10){
    sepHour = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}

// decrease days by a unit
function decrease_days_by_ten(){
  clear_alarm_noise();
  // The timer has been set to any offset amount
  timeHasBeenSet = true;

  // decrease -1
  days = days - 10;

  if (days <= 0){
    days = 0;
  }

  // Avoid centiseconds prepending with a 0
  if (centiseconds >= 10){
    sepCent = ': ';
  }

  // Avoid seconds prepending with a 0
  if (seconds >= 10){
    sepSec = ': ';
  }

  // Avoid minutes prepending with a 0
  if (minutes >= 10){
    sepMin = ': ';
  }

  // Avoid hours prepending with a 0
  if (hours >= 10){
    sepHour = ': ';
  }
  document.getElementById('stopwatch1').innerHTML = days + sepHour + hours + sepMin + minutes + sepSec + seconds + sepCent + centiseconds;
}
