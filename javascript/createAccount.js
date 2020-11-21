const emailField = document.getElementById("email");
const usernameField = document.getElementById("username");
const errorMsg = document.querySelector(".create-account-error");
const form = document.querySelector("form");

let emailIsValid = true;
let usernameIsValid = true;

emailField.onkeyup = e => {
  const val = e.target.value;
  $.post('./process.php', { action: 'check-email', email: `${val}` }, res => {
    if(!res){
      errorMsg.innerHTML = "Looks like that email address is already taken.<br>Please use another email address.";
      emailIsValid = false;
    } else {
      errorMsg.innerHTML = "";
      emailIsValid = true;
    }
  });
}

usernameField.onkeyup = e => {
  const val = e.target.value;
  $.post('./process.php', { action: 'check-username', username: `${val}` }, res => {
    if(!res){
      errorMsg.innerHTML = "Looks like that username is already taken.<br>Please enter a different username.";
      usernameIsValid = false;
    } else {
      errorMsg.innerHTML = "";
      usernameIsValid = true;
    }
  });
}

form.onsubmit = e => {
  if(!emailIsValid && !usernameIsValid){
    e.preventDefault();
    errorMsg.innerHTML = "Both your username and your email are already taken.<br>Please enter a new username and email.";
  } else if(!emailIsValid){
    e.preventDefault();
    errorMsg.innerHTML = "Looks like that email address is already taken.<br>Please use another email address.";
  } else if(!usernameIsValid){
    e.preventDefault();
    errorMsg.innerHTML = "Looks like that username is already taken.<br>Please enter a different username.";
  }
}