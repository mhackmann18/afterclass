import { postPageElements } from './modules/elements.js';
import { getGroupNameById } from './modules/dbController.js';
import { changeWatchLinkToEmbedLink, getCurrentDateString, displayNone, displayBlock } from './modules/utilities.js';

const { form, groupSelect, ytLinkInput, addImgBtn, previewBtn, postTextarea, errMsgP, overlay, previewContainer, cancelPreviewBtn, changePostBtn, fileInput, mediaDiv, postBtn } = postPageElements;

displayNone(previewContainer);

// Load the user's groups into the form select
document.querySelector("body").onload = function(){
  $.get("/afterclass/php/process.php", { action: 'get-membership-ids' }, res => 
  JSON.parse(res).forEach(async id => {
    let name = await getGroupNameById(Number(id));
    groupSelect.innerHTML += `<option value='${id}'>${name}</option>`;
  }));
}

// Show a preview popup in the UI of what the post will look like before confirmation
function showConfirmationWindow(){
  previewContainer.style.display = "block";
  displayBlock(document.getElementById("post-preview"), overlay);

  previewContainer.querySelector(".post-text").innerHTML = postTextarea.value;
  previewContainer.querySelector(".post-date").innerHTML = getCurrentDateString();

  // If the post contains a file
  if(fileInput.files && fileInput.files[0]){
    let reader = new FileReader();

    reader.readAsDataURL(fileInput.files[0]);

    reader.onload = function(e){
      if(fileInput.value.slice(-3) === "pdf"){
        mediaDiv.innerHTML = `<embed src=${e.target.result} id="post-preview-file-display"/>`;
        document.getElementById("post-preview-file-display").style.height = `${document.getElementById("post-preview-file-display").offsetWidth*1.3}px`;
      } else {
        mediaDiv.innerHTML = `<div class="flex-center"><img id="post-preview-file-display" src=${e.target.result} alt="post-picture"></img></div>`;
      }
    }
    // If the post has an embedded youtube video
  } else if(ytLinkInput.value){
    let newLink = changeWatchLinkToEmbedLink(ytLinkInput.value);
    mediaDiv.innerHTML = `<iframe id="post-preview-video"
    src=${newLink} frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
    let width = document.querySelector("iframe").offsetWidth;
    document.querySelector("iframe").height = `${width*.5}px`;
  }
}

/**********/
/* EVENTS */
/**********/

// Get rid of any youtube link input if a file is added
addImgBtn.onclick = () => ytLinkInput.value = "";

previewBtn.onclick = function(e){
  e.preventDefault();
  if(checkInputs())
    showConfirmationWindow();
}

cancelPreviewBtn.onclick = function(){
  displayNone(previewContainer, overlay);
  mediaDiv.innerHTML = "";
}

changePostBtn.onclick = function(){
  displayNone(previewContainer, overlay);
  mediaDiv.innerHTML = "";
}

ytLinkInput.onkeydown = () => fileInput.value = "";

postBtn.onclick = () => form.submit();
postTextarea.onkeydown = () => hideErrMsg();
groupSelect.onchange = () => hideErrMsg();

/***************************************/
/* INPUT VALIDATION AND ERROR HANDLING */
/***************************************/

// Show an error message to the user
function displayErrMsg(msg){
  displayBlock(errMsgP);
  errMsgP.innerHTML = msg;
}

// Hide the error message
function hideErrMsg(){
  displayNone(errMsgP);
  errMsgP.innerHTML = "";
}

// Return true if inputs are valid and false if not
function checkInputs(){
  let formValid = false;

  if(groupSelect.value === "invalid"){
    displayErrMsg("Please select a group to post to");
  } else if(!postTextarea.value){
    displayErrMsg("Please enter some text for your post");
  } else if(postTextarea.value.length > 250){
    displayErrMsg("Post text cannot be longer than 250 characters.");
  } else if(ytLinkInput.value && !/https:\/\/www.youtube.com\/watch\?v=/.test(ytLinkInput.value)){
    displayErrMsg("Make sure the link that you used is a valid youtube link");
  } else {
    formValid = true;
    hideErrMsg();
  }

  return formValid;
}