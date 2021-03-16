// Option to show the password in the account management page
function show_div() {
  // The holder for the user password
  var passwordField = document.getElementById('userinfopassword');
  // The ID of the user password cover
  var separateID = document.getElementById('separate');
  if (passwordField.style.display !== 'none') {
    // Hide
    passwordField.style.display = 'none';
    separateID.style.display = 'block';
  }
  else {
    passwordField.style.display = 'block';
    separateID.style.display = 'none';
  }
}
