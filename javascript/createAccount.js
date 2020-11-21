const emailField = document.getElementById("email");
const usernameField = document.getElementById("username");
const errorMsg = document.querySelector(".create-account-error");
const form = document.querySelector("form");

// These values are used when the form is submitted to make sure the email and username values don't already exist in the database.
let emailIsValid = true;
let usernameIsValid = true;

// Will display an error message if the email is already associated with another account.
emailField.onkeyup = e => {
  const val = e.target.value;
  $.post('./PROCESS.php', { action: 'check-email', email: `${val}` }, res => {
    // Email already exists.
    if(!res){
      errorMsg.innerHTML = "Looks like that email address is already taken.<br>Please use another email address.";
      emailIsValid = false;
    } 
    // Email is unique.
    else {
      errorMsg.innerHTML = "";
      emailIsValid = true;
    }
  });
}

// Will display an error message if chosen username is already associated with another account.
usernameField.onkeyup = e => {
  const val = e.target.value;
  $.post('./PROCESS.php', { action: 'check-username', username: `${val}` }, res => {
    // Username is already taken.
    if(!res){
      errorMsg.innerHTML = "Looks like that username is already taken.<br>Please enter a different username.";
      usernameIsValid = false;
    } 
    // Username is good to go
    else {
      errorMsg.innerHTML = "";
      usernameIsValid = true;
    }
  });
}

// Makes sure input values for username and password are valid
form.onsubmit = e => {
  // If both the username and the email have been taken
  if(!emailIsValid && !usernameIsValid){
    e.preventDefault();
    errorMsg.innerHTML = "Both your username and your email are already taken.<br>Please enter a new username and email.";
  } 
  // If email is not a valid um email address
  else if(!/\b\w{6}@umsystem.edu\b/.test(emailField.value)){
    e.preventDefault();
    errorMsg.innerHTML = "Email must be a valid um system email address.<br>Ex. mdhtf3@umsystem.edu";
  } 
  // If the email is already taken
  else if(!emailIsValid){
    e.preventDefault();
    errorMsg.innerHTML = "Looks like that email address is already taken.<br>Please use another email address.";
  } 
  // If the username is already taken
  else if(!usernameIsValid){
    e.preventDefault();
    errorMsg.innerHTML = "Looks like that username is already taken.<br>Please enter a different username.";
  } 
}