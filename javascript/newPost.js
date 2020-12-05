const form = document.getElementById("new-post-form");
const groupSelect = form.querySelector("select");
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
  $.get("/afterclass/php/process.php", { action: 'get-membership-ids' }, res => 
  JSON.parse(res).forEach(async id => {
    let name = await getGroupNameById(Number(id));
    groupSelect.innerHTML += `<option value='${id}'>${name}</option>`;
  }));
}

// Returns the name string of the group with the passed in id.
async function getGroupNameById(id){
  let groupName;
  await $.get("/afterclass/php/process.php", { action: 'get-group-info', groupid: id }, res => {
    groupName = JSON.parse(res).name;
  });
  return groupName;
}

// Show a preview in the UI of what the post will look like
function showPreviewWindow(){
  previewContainer.style.display = "table";
  showElement(document.getElementById("post-preview"));
  showElement(overlay);

  previewContainer.querySelector(".post-text").innerHTML = postTextarea.value;
  previewContainer.querySelector(".post-date").innerHTML = getCurrentDateString();

  if(fileInput.value){
    if(fileInput.files && fileInput.files[0]){
      let reader = new FileReader();
  
      reader.readAsDataURL(fileInput.files[0]);
  
      reader.onload = function(e){
        if(fileInput.value.slice(-3) === "pdf"){
          mediaDiv.innerHTML = `<embed src=${e.target.result} id="post-preview-file-display"/>`;
          document.getElementById("post-preview-file-display").style.height = `${document.getElementById("post-preview-file-display").offsetWidth*1.3}px`;
        } else {
          mediaDiv.innerHTML = `<img id="post-preview-file-display" src=${e.target.result} alt="post-picture"></img>`;
        }
      }
    }
  }

  if(ytLinkInput.value){
    let newLink = convertToEmbedLink(ytLinkInput.value);
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
    showPreviewWindow();
}

cancelPreviewBtn.onclick = function(){
  hideElement(previewContainer, overlay);
  mediaDiv.innerHTML = "";
}

changePostBtn.onclick = function(){
  hideElement(previewContainer, overlay);
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
  showElement(errMsgP);
  errMsgP.innerHTML = msg;
}

// Hide the error message
function hideErrMsg(){
  hideElement(errMsgP);
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

/********************/
/* HELPER FUNCTIONS */
/********************/

function showElement(el){
  el.style.display = "block";
}

function hideElement(...args){
  for(el of args)
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

async function getFileSrc(input){
  if(input.files && input.files[0]){
    let reader = new FileReader();

    reader.readAsDataURL(input.files[0]);

    reader.onload = function(e){
      return `${e.target.result}#` + new Date().getTime();
    }
  }
}

function convertToEmbedLink(link){
  let i = link.split("v=");
  let embedLink = `https://www.youtube.com/embed/${i[1]}`;
  return embedLink;
}