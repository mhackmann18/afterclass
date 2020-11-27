const createGroup = document.getElementById("create-new-group");
const createGroupForm = document.getElementById("create-group-form");
const overlay = document.getElementById("overlay");
const closeFormBtn = document.getElementById("cancel-create-group-btn");
const nameInput = createGroupForm.querySelector("input");
const bioInput = createGroupForm.querySelector("textarea");
const createGroupErr = document.getElementById("create-group-err-msg");
const yourGroupsDiv = document.getElementById("your-groups");
const leaveGroupWindow = document.getElementById("leave-group-confirmation-window");
const confirmLeaveGroupBtn = document.getElementById("leave-group-confirm-btn");

/***********************/
/* Group Creation Form */
/***********************/

let nameIsValid = true;
let bioIsValid = true;

createGroup.onclick = function(){
  overlay.style.display = "block";
  createGroupForm.style.display = "block";
}

overlay.onclick = function(){
  overlay.style.display = "none";
  leaveGroupWindow.style.display = "none";
  createGroupForm.style.display = "none";
  clearFields();
}

closeFormBtn.onclick = function(e){
  e.preventDefault();
  overlay.style.display = "none";
  createGroupForm.style.display = "none";
  clearFields();
}

nameInput.onkeyup = function(e){
  $.post("/afterclass/php/PROCESS.php", { action: 'check-group-name', name: e.target.value }, res => {
    if(!res){
      console.log("Group name is already taken.");
      nameIsValid = false;
      createGroupErr.style.display = "block";
      createGroupErr.innerHTML = "That group name is already taken.<br>Please choose a different group name.";
    } else {
      console.log("Group name is available.");
      nameIsValid = true;
      createGroupErr.style.display = "none";
      createGroupErr.innerHTML = "";
    }
  });
}

bioInput.onkeyup = function(e){
  if(e.target.value.length > 500){
    createGroupErr.style.display = "block";
    createGroupErr.innerHTML = "Description must be less than 350 characters.";
    bioIsValid = false;
    console.log("Too long");
  } else {
    createGroupErr.style.display = "none";
    createGroupErr.innerHTML = "";
    bioIsValid = true;
    console.log("All good");
  }
}

createGroupForm.onsubmit = function(e){
  if(!nameInput.value && !bioInput.value){
    e.preventDefault();
    createGroupErr.style.display = "block";
    createGroupErr.innerHTML = "Please enter a name and description for your group.";
  } else if(!nameIsValid && !bioIsValid){
    e.preventDefault();
  } else if(!nameIsValid){
    e.preventDefault();
    createGroupErr.style.display = "block";
    createGroupErr.innerHTML = "That group name is already taken.<br>Please choose a different group name.";
  } else if(!nameInput.value){
    e.preventDefault();
    createGroupErr.style.display = "block";
    createGroupErr.innerHTML = "Please enter a name for your group.";
  } else if(!bioInput.value){
    e.preventDefault();
    createGroupErr.style.display = "block";
    createGroupErr.innerHTML = "Please enter a description for your group.";
  } 
}

function clearFields(){
  nameInput.value = "";
  bioInput.value = "";
}

/********************************/
/* Load Groups from Memberships */
/********************************/

document.querySelector("body").onload = function(){
  $.get("/afterclass/php/PROCESS.php", { action: "get-membership-ids" }, res => JSON.parse(res).forEach(id => showGroupCard(Number(id))));
}

function showGroupCard(id){
  $.get("/afterclass/php/PROCESS.php", { action: "get-group-card", groupid: id }, res => {
    const card = document.createElement("div");
    card.classList.add("group-page-info");
    card.innerHTML += res;
    yourGroupsDiv.appendChild(card);
    addButtonEvents(card, id);
  });
}

function addButtonEvents(groupDiv, id){
  const leaveBtn = groupDiv.querySelector(".leave-group-btn");
  const viewFeedBtn = groupDiv.querySelector(".view-group-feed");
  const groupTitle = groupDiv.querySelector("h1").innerHTML;

  leaveBtn.onclick = function(){
    leaveGroupWindow.querySelector('span').innerHTML = groupTitle;
    leaveGroupWindow.style.display = "block";
    overlay.style.display = "block";
    confirmLeaveGroupBtn.onclick = function(){
      $.get("./php/PROCESS.php", { action: "leave-group", groupid: id }, res => {
        console.log(res);
        location.reload();
      });
    }
  }

  viewFeedBtn.onclick = function(){
    console.log(`View feed from group ${id}`);
  }
}

document.getElementById("leave-group-cancel-btn").onclick = function(){
  leaveGroupWindow.style.display = "none";
  overlay.style.display = "none";
}

