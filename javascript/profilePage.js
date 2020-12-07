import { loggedInUserProfilePageElements } from './modules/elements.js';
import { displayNone, displayBlock } from './modules/utilities.js';
const { changeImgBtn, fileForm, fileFormBtn, fileFormInput, editProfileBtn, userInfo, bio, bioInput, buttonDiv, cancelEditBtn,saveChangesBtn, inputsUl, usernameInput, majorInput, inputErrMsg, bioErrMsg } = loggedInUserProfilePageElements;


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

// Show edit inputs and buttons
editProfileBtn.onclick = function(){
  // Hide edit button
  displayNone(editProfileBtn, bio, userInfo);

  // Show cancel and edit buttons
  displayBlock(cancelEditBtn, saveChangesBtn, inputsUl, bioInput);

  // If there was a bio saved previously, show it in the textarea
  if(bio.id === "empty-bio"){
    bioInput.value = "";
  } else {
    bioInput.value = bio.innerHTML;
  }
}

// Will display an error message if chosen username is already associated with another account.
usernameInput.onkeyup = e => {
  const val = e.target.value;
  $.post('./php/process.php', { action: 'check-username', username: val }, res => {
    // If username is already taken and it's not equal to the old username
    if(!res && val !== document.getElementById("username").innerHTML){
      displayBlock(inputErrMsg);
      inputErrMsg.innerHTML = "Looks like that username is already taken.<br>Please choose a different username.";
      usernameIsValid = false;
    } else {
      displayNone(inputErrMsg);
      inputErrMsg.innerHTML = "";
      usernameIsValid = true;
    }
  });
}

// Check that bio is no longer than 250 chars
bioInput.onkeyup = e => {
  if(e.target.value.length > 250){
    bioIsValid = false;
    displayBlock(bioErrMsg);
    bioErrMsg.innerHTML = `Bio must be less than 250 characters`;
  } else {
    displayNone(bioErrMsg);
    bioIsValid = true;
  }
}

// Close edit inputs without saving
cancelEditBtn.onclick = function(){
  // Hide cancel and save buttons
  displayNone(cancelEditBtn, saveChangesBtn, inputsUl, inputErrMsg, bioErrMsg);

  // Show necessary elements
  displayBlock(editProfileBtn, userInfo, bio, bioInput);

  // Clear user's text from input and show the original values
  usernameInput.value = document.getElementById('username').innerHTML;
  majorInput.value = document.getElementById('major').innerHTML;
}

// Check that inputs are valid and upload changes to db
saveChangesBtn.onclick = function(){
  let canSubmit = true;

  // Check for username input
  if(!usernameVal){
    displayBlock(inputErrMsg);
    inputErrMsg.innerHTML = "Please enter a username.";
    canSubmit = false;
  }
  // Check that bio and username are valid
  if(!usernameIsValid || !bioIsValid){
    canSubmit = false;
  }
  // Check for major input
  if(!majorVal){
    displayBlock(inputErrMsg);
    inputErrMsg.innerHTML = "Please enter a major.";
    canSubmit = false;
  }

  if(canSubmit){
    $.post("./php/process.php", { action: "update-profile", username: usernameInput.value, major: majorInput.value, bio: bioInput.value }, res => {
      if(res !== 'logout'){
        window.location.replace("./yourProfile.php");
      } else {
        window.location.replace("./login.php");
      }
    });
  }
}