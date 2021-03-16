// Script to constantly update the clock, clock.php, to display the current time
function update_time(){
  var daysArray = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
  var monthsArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var timeCultures = ['st', 'nd', 'rd', 'th'];
  currentTime = new Date();
  var currentMillisec = currentTime.getMilliseconds();
  var currentSec = currentTime.getSeconds();
  var currentMin = currentTime.getMinutes();
  var currentHour = currentTime.getHours();
  var currentDate = currentTime.getDate();
  var currentDayNum = currentTime.getDay();
  currentDay = daysArray[currentDayNum];
  var currentYear = currentTime.getFullYear();
  var currentMonthNum = currentTime.getMonth();
  var currentMonth = monthsArray[currentMonthNum];


  // Converting date numbers to strings to get the ordinal suffix
  var ord = currentDate.toString();
  // '1st'
  if (ord.endsWith('1') == true){
    // 11th
    if (ord == '11'){
      dateCulture = timeCultures[3];
    }
    // 1st, 21st, 31st
    else{
      dateCulture = timeCultures[0];
    }
  }
  // '2nd'
  else if (ord.endsWith('2') == true) {
    // 12th
    if (ord == '12'){
      dateCulture = timeCultures[3];
    }
    // 2nd, 22nd
    else{
      dateCulture = timeCultures[1];
    }
  }
  // '3rd'
  else if (ord.endsWith('3') == true) {
    // 13th
    if (ord == '13'){
      dateCulture = timeCultures[3];
    }
    // 3rd, 23rd
    else{
      dateCulture = timeCultures[2];
    }
  }
  // '20th', '4th'
  else{
    dateCulture = timeCultures[3];
  }

  // Prepending '0' before units lower than 10
  if (currentMillisec < 10){
    millisecZero = '00';
  }
  else if (currentMillisec < 100 && currentMillisec > 9){
    millisecZero = '0';
  }
  else{
    millisecZero = '';
  }
  if (currentSec < 10){
    secZero = '0';
  }
  else{
    secZero = '';
  }
  if (currentMin < 10){
    minZero = '0';
  }
  else{
    minZero = '';
  }
  if (currentHour < 10){
    hourZero = '0';
  }
  else{
    hourZero = '';
  }
  document.getElementById('fulltime').innerHTML = currentDay + ', ' + currentMonth + ' ' + currentDate + dateCulture + ', ' + currentYear + ' | <br>' + hourZero + currentHour + ': ' + minZero + currentMin + ': ' + secZero + currentSec + ': ' + millisecZero + currentMillisec;
}
var clock = setInterval(update_time, 1);
