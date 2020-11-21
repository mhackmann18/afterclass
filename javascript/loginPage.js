const form = document.querySelector('form');
const userField = form.querySelector('input[type="text"]');
const passwordField = form.querySelector('input[type="password"]');
const submitBtn = form.querySelector('button');
const errorMsg = document.querySelector('.error-msg');


form.onsubmit = e => {
  const userString = userField.value;
  const pwString = passwordField.value;

  e.preventDefault();

  if(!userString && !pwString){
    displayError("Please enter a username and password.");
  } else if(!userString){
    displayError("Please enter a username.");
  } else if(!pwString){
    displayError("Please enter a password.");
  } else {
    checkLogin(userString, pwString);
  }
}

userField.onkeydown = () => hideError();
passwordField.onkeydown = () => hideError();

// Takes in a error message string and displays it to the user.
function displayError(msg){
  errorMsg.innerHTML = `${msg}`;
  errorMsg.style.display = 'block';
}

// Takes two strings, the first is for the username and the second is for the password. Checks if they exist in the database and returns true if they do, and false if they don't.
function checkLogin(un, pw){
  $.post('./PROCESS.php', { action: 'login', username: un, password: pw }, res => {
    if(res !== "Logged in successfully"){
      displayError(res);
    } else {
      window.location.replace("./index.php");
    }
  });
}

// Hides the error message.
function hideError(){
  errorMsg.style.display = 'none';
}