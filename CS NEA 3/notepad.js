function count_text(text, num){
  var length = text.length;
  document.getElementById('textlabel').innerHTML = 'Text: (' + length + '/' + num + ' characters)';
}
function count_title(text, num){
  var length = text.length;
  document.getElementById('titlelabel').innerHTML = 'Text: (' + length + '/' + num + ' characters)';
}
