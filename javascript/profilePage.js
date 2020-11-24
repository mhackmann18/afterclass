const changeImgBtn = document.getElementById("change-profile-image");
const fileForm = document.getElementById("upload-image-form");
const fileFormBtn = fileForm.querySelector("button");
const fileFormInput = fileForm.querySelector("input");
const editProfileBtn = document.getElementById("edit-profile-btn");
const userInfo = document.getElementById("profile-page-info");
const bio = document.getElementById("profile-right").querySelector("p");
const bioInput = document.querySelector("textarea");
const buttonDiv = document.getElementById("profile-left");
const cancelEditBtn = document.getElementById("cancel-edit-profile-btn");
const saveChangesBtn = document.getElementById("save-edit-profile-btn");
const inputsUl = document.getElementById("profile-page-inputs");
const usernameInput = document.getElementById("username-input");
const majorInput = document.getElementById("major-input");
const inputErrMsg = document.getElementById("input-err-msg");
const bioErrMsg = document.getElementById("bio-err-msg");

// Clear the file input field when the user navigates to the page
window.addEventListener("pageshow", () => {
  fileFormInput.removeEventListener("onchange", submitForm);
  fileFormInput.value = "";
  fileFormInput.addEventListener("change", submitForm);
});

/***********************/
/* Changing user image */
/***********************/

changeImgBtn.onclick = function(e){
  fileFormInput.click();
}

function submitForm(){
  fileFormBtn.click();
}

/************************/
/* Editing user profile */
/************************/

let usernameIsValid = true;
let bioIsValid = true;

// Edit profile
editProfileBtn.onclick = function(e){
  // Hide edit button
  editProfileBtn.style.display = "none";
  // Show cancel and edit buttons
  cancelEditBtn.style.display = "block";
  saveChangesBtn.style.display = "block";
  inputsUl.style.display = "block";
  // Hide user bio
  bio.style.display = "none";
  // Show bio textarea
  bioInput.style.display = "block";
  // If there was a bio saved previously, show it in textarea
  if(bio.id === "empty-bio"){
    bioInput.value = "";
  } else {
    bioInput.value = bio.innerHTML;
  }
  // Hide user info
  userInfo.style.display = "none";
}

// Will display an error message if chosen username is already associated with another account.
usernameInput.onkeyup = e => {
  const val = e.target.value;
  $.post('./php/PROCESS.php', { action: 'check-username', username: `${val}` }, res => {
    // Username is already taken.
    if(!res && val !== document.getElementById("username").innerHTML){
      inputErrMsg.style.display = "block";
      inputErrMsg.innerHTML = "Looks like that username is already taken.<br>Please choose a different username.";
      usernameIsValid = false;
    } 
    // Username is good to go
    else {
      inputErrMsg.style.display = "none";
      inputErrMsg.innerHTML = "";
      usernameIsValid = true;
    }
  });
}

bioInput.onkeyup = e => {
  const val = e.target.value;
  const maxLength = 250;
  if(val.length > maxLength){
    bioIsValid = false;
    bioErrMsg.style.display = "block";
    bioErrMsg.innerHTML = `Bio must be less than ${maxLength} characters`;
  } else {
    bioErrMsg.style.display = "none";
    bioIsValid = true;
  }
}

// Cancel edit without saving
cancelEditBtn.onclick = function(e){
  // Hide cancel and save buttons
  cancelEditBtn.style.display = "none";
  saveChangesBtn.style.display = "none";
  inputsUl.style.display = "none";
  inputErrMsg.style.display = "none";
  bioErrMsg.style.display = "none";
  // Show necessary elements
  editProfileBtn.style.display = "block";
  userInfo.style.display = "block";
  bio.style.display = "block";
  bioInput.style.display = "none";
  // Clear user's text from input
  usernameInput.value = document.getElementById('username').innerHTML;
  majorInput.value = document.getElementById('major').innerHTML;
}

saveChangesBtn.onclick = function(){
  let canSubmit = true;
  // Get user's input
  const usernameVal = usernameInput.value;
  const majorVal = majorInput.value;
  const bioVal = bioInput.value;
  // Check username and major
  if(!usernameVal){
    inputErrMsg.style.display = "block";
    inputErrMsg.innerHTML = "Please enter a username.";
    canSubmit = false;
  }
  // Check bio
  if(!usernameIsValid || !bioIsValid){
    canSubmit = false;
  }
  if(!majorVal){
    inputErrMsg.style.display = "block";
    inputErrMsg.innerHTML = "Please enter a major.";
    canSubmit = false;
  }
  // Submit to updateProfile.php
  if(canSubmit){
    $.post("./php/updateProfile.php", { username: usernameVal, major: majorVal, bio: bioVal }, () => window.location.replace("./profile.php"));
  }
}