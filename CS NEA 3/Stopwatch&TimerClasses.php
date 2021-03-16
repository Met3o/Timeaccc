<?php
// Page holding the classes to generate the stopwatch and timer from
// Used by stopwatch.php and timer.php
class Stopwatch{
  public function __construct(){
  }
  public function show(){
    $output = '
      <div id = "StopwatchContainer">
      <div id = "buttoncontainer">
      <button class = "controllerButtons" id = "control" onclick = "change_state()">Start/Pause</button>
      <button class = "controllerButtons" id = "reset" onclick = "reset()">Reset</button>
      <button class = "controllerButtons" id = "stop" onclick = "pause_and_reset()">End</button>
      </div>
      <div class = "timingcontainers">
      <p><span id = "stopwatch1"></span></p>
      </div>
      <p style = "text-align: center; text-shadow: 0px 5px 30px #038"><span>day: hr: min: sec: csec</span></p>
      </div>';
    echo $output;
  }
}
class Timer{
  public function __construct(){

  }
  public function show(){
    $output = '
    <div id = "StopwatchContainer">
    <div id = "buttoncontainer">
    <button class = "controllerButtons" id = "control" onclick = "change_state()">Start/Pause</button>
    <button class = "controllerButtons" id = "reset" onclick = "reset()">Reset</button>
    <button class = "controllerButtons" id = "stop" onclick = "pause_and_reset()">End</button>
    </div>
    <div class = "timingcontainers">
    <p><span id = "stopwatch1"></span></p>
    <div class = "setTimer">
    <button id = "daysTen" class = "timercontrol" onclick = "increase_days_by_ten()">+10<div class = "indicator"></div></button>
    <button id = "daysUnit" class = "timercontrol" onclick = "increase_days_by_one()">+<div class = "indicator"></div></button>
    <button id = "hoursTen" class = "timercontrol" onclick = "increase_hours_by_ten()">+10<div class = "indicator"></div></button>
    <button id = "hoursUnit" class = "timercontrol" onclick = "increase_hours_by_one()">+<div class = "indicator"></div></button>
    <button id = "minutesTen" class = "timercontrol" onclick = "increase_minutes_by_ten()">+10<div class = "indicator"></div></button>
    <button id = "minutessUnit" class = "timercontrol" onclick = "increase_minutes_by_one()">+<div class = "indicator"></div></button>
    <button id = "secondsTen" class = "timercontrol" onclick = "increase_seconds_by_ten()">+10<div class = "indicator"></div></button>
    <button id = "secondsUnit" class = "timercontrol" onclick = "increase_seconds_by_one()">+<div class = "indicator"></div></button>
    <button id = "centisecondsTen" class = "timercontrol" onclick = "increase_centiseconds_by_ten()">+10<div class = "indicator"></div></button>
    <button id = "centisecondsUnit" class = "timercontrol" onclick = "increase_centiseconds_by_one()">+<div class = "indicator"></div></button>
    </div>
    <div class = "setTimer">
    <button id = "daysTen" class = "timercontrol" onclick = "decrease_days_by_ten()">-10<div class = "indicator"></div></button>
    <button id = "daysUnit" class = "timercontrol" onclick = "decrease_days_by_one()">-<div class = "indicator"></div></button>
    <button id = "hoursTen" class = "timercontrol" onclick = "decrease_hours_by_ten()">-10<div class = "indicator"></div></button>
    <button id = "hoursUnit" class = "timercontrol" onclick = "decrease_hours_by_one()">-<div class = "indicator"></div></button>
    <button id = "minutesTen" class = "timercontrol" onclick = "decrease_minutes_by_ten()">-10<div class = "indicator"></div></button>
    <button id = "minutessUnit" class = "timercontrol" onclick = "decrease_minutes_by_one()">-<div class = "indicator"></div></button>
    <button id = "secondsTen" class = "timercontrol" onclick = "decrease_seconds_by_ten()">-10<div class = "indicator"></div></button>
    <button id = "secondsUnit" class = "timercontrol" onclick = "decrease_seconds_by_one()">-<div class = "indicator"></div></button>
    <button id = "centisecondsTen" class = "timercontrol" onclick = "decrease_centiseconds_by_ten()">-10<div class = "indicator"></div></button>
    <button id = "centisecondsUnit" class = "timercontrol" onclick = "decrease_centiseconds_by_one()">-<div class = "indicator"></div></button>
    </div>
    </div>
    </div>';
      echo $output;
  }
}
?>
