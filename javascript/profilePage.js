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

// Edit profile
editProfileBtn.onclick = function(e){
  // Hide edit button
  editProfileBtn.style.display = "none";
  // Show cancel and edit buttons
  cancelEditBtn.style.display = "block";
  saveChangesBtn.style.display = "block";
  // Hide user bio
  bio.style.display = "none";
  // Show bio textarea
  bioInput.style.display = "block";
  // If there was a bio saved previously, show it in textarea
  if(bio.id === "empty-bio"){
    bioInput.innerHTML = "";
  } else {
    bioInput.innerHTML = bio.innerHTML;
  }
  // Hide user info
  userInfo.style.display = "none";
}

// Cancel edit without saving
cancelEditBtn.onclick = function(e){
  // Hide cancel and save buttons
  cancelEditBtn.style.display = "none";
  saveChangesBtn.style.display = "none";
  // Show necessary elements
  editProfileBtn.style.display = "block";
  userInfo.style.display = "block";
  bio.style.display = "block";
  bioInput.style.display = "none";
}