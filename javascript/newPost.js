const form = document.getElementById("new-post-form");
const groupSelect = form.querySelector("select");
const ytBtn = document.getElementById("embed-yt-btn");
const ytLinkInput = document.getElementById("yt-link-input");
const addImgBtn = document.getElementById("post-img-btn");
const previewBtn = document.getElementById("preview-new-post-btn");
const postTextarea = document.getElementById("new-post-text");
const errMsgP = form.querySelector(".err-msg");
const overlay = document.getElementById("overlay");
const previewContainer = document.querySelector(".outer");
const cancelPreviewBtn = document.getElementById("cancel-preview-btn");
const changePostBtn = document.getElementById("change-post-btn");
const fileInput = document.getElementById("file-upload");
const mediaDiv = previewContainer.querySelector(".post-media");
const postBtn = document.getElementById("confirm-post-btn");

hideElement(previewContainer);

// Load the user's groups into the form select
document.querySelector("body").onload = function(){
  $.get("/afterclass/php/PROCESS.php", { action: 'get-membership-ids' }, res => {
    // Parse the JSON array of group ids, get the group name of each id, then add new options to the form select
    JSON.parse(res).forEach(async id => {
      id = Number(id);
      let name = await getGroupNameById(id);
      addGroupOption(id, name);
    });
  });
}

// Returns the name string of the group with the passed in id.
async function getGroupNameById(id){
  let groupName;
  await $.get("/afterclass/php/PROCESS.php", { action: 'get-group-info', groupid: id }, res => {
    dataArr = JSON.parse(res);
    groupName = dataArr[0];
  });
  return groupName;
}

// Show a preview in the UI of what the post will look like
function showPreviewWindow(){
  previewContainer.style.display = "table";
  showElement(overlay);

  previewContainer.querySelector(".post-text").innerHTML = postTextarea.value;
  previewContainer.querySelector(".post-date").innerHTML = getCurrentDateString();

  if(fileInput.value){
    console.log("A file is being uploaded");
    mediaDiv.innerHTML = '<img alt="post-picture"></img>';
    readURL(fileInput);
  }

  if(ytLinkInput.value){
    let newLink = convertToEmbedLink(ytLinkInput.value);
    mediaDiv.innerHTML = `<iframe id="post-preview-video"
    src=${newLink} frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
    let width = document.querySelector("iframe").offsetWidth;
    document.querySelector("iframe").height = `${width*.5}px`;
  }
}

// Add all a group to the form select
function addGroupOption(groupId, groupName){
  let option = `<option value='${groupId}'>${groupName}</option>`;
  groupSelect.innerHTML += option;
}



/**********/
/* EVENTS */
/**********/

// Show input for youtube video link
ytBtn.onclick = function(e){
  e.preventDefault();
  ytLinkInput.style.display = "inline-block";
  // Get rid of any file input if a youtube link is added
  ytLinkInput.onkeydown = () => fileInput.value = "";
}

// Get rid of any youtube link input if a file is added
addImgBtn.onclick = function(e){
  ytLinkInput.style.display = "none";
  ytLinkInput.value = "";
}

previewBtn.onclick = function(e){
  e.preventDefault();
  if(checkInputs()){
    showPreviewWindow();
  } 
}

cancelPreviewBtn.onclick = function(){
  hideElement(previewContainer);
  hideElement(overlay);
  mediaDiv.innerHTML = "";
}

changePostBtn.onclick = function(){
  hideElement(previewContainer);
  hideElement(overlay);
  mediaDiv.innerHTML = "";
}

postBtn.onclick = () => form.submit();


postTextarea.onkeydown = () => hideErrMsg();

groupSelect.onchange = () => hideErrMsg();

/***********************/
/* USER ERROR HANDLING */
/***********************/

// Show an error message to the user
function displayErrMsg(msg){
  errMsgP.style.display = "inline-block";
  errMsgP.innerHTML = msg;
}

// Hide the error message
function hideErrMsg(){
  errMsgP.innerHTML = "";
  errMsgP.style.display = "none";
}

// Check textarea and group input
function checkInputs(){
  let formValid = true;
  if(groupSelect.value === "invalid"){
    displayErrMsg("Please select a group to post to");
    formValid = false;
  } else if(!postTextarea.value){
    displayErrMsg("Please enter some text for your post");
    formValid = false;
  } else if(postTextarea.value.length > 250){
    displayErrMsg("Post text cannot be longer than 250 characters.");
    formValid = false;
  } else {
    hideErrMsg();
  }
  return formValid;
}

/********************/
/* HELPER FUNCTIONS */
/********************/

function showElement(el){
  el.style.display = "block";
}

function hideElement(el){
  el.style.display = "none";
}

function getCurrentDateString(){
  let date = new Date();
  const year = date.getFullYear();
  let month = date.getMonth() + 1;
  let day = date.getDate();
  if(day < 10){
    day = "0" + String(day);
  }
  return `${month}/${day}/${year}`;
}

function readURL(input){
  if(input.files && input.files[0]){
    let reader = new FileReader();

    reader.onload = function(e){
      document.querySelector(".post-media").querySelector("img").src = e.target.result;
    }

    reader.readAsDataURL(input.files[0]);
  }
}

function convertToEmbedLink(link){
  let i = link.split("v=");
  let embedLink = `https://www.youtube.com/embed/${i[1]}`;
  return embedLink;
}