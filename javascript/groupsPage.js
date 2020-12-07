import { userGroupsPageElements } from './modules/elements.js';
import { displayBlock, displayNone } from './modules/utilities.js';
import { getGroupDataById, getGroupIdsOfLoggedInUser, leaveGroupById } from './modules/dbController.js';
const { createGroupForm, overlay, nameInput, bioInput, createGroupErr, leaveGroupWindow, spinner } = userGroupsPageElements;

// Load all of the user's groups into UI
document.querySelector("body").onload = async function(){
  // Show loading gif
  displayBlock(spinner);

  // Get the ids of all the groups the logged in user is a member of
  const groupIds = await getGroupIdsOfLoggedInUser();

  // Show all of the user's groups in the UI
  groupIds.forEach(id => showGroupCard(Number(id)));
  
  // Show a message in the UI if the user isn't in any groups
  !groupIds.length && showNoGroupsMsg();

  // Hide loading gif
  displayNone(spinner);
}

async function showGroupCard(id){
  const groupData = await getGroupDataById(id);
  const card = document.createElement("div");
  card.classList.add("group-page-info");

  card.innerHTML += 
  `<h1>${groupData.name}</h1>
  <h3>Description</h3>
  <div class='group-page-desc'>${groupData.description}</div>
  <div>
    <ul>
      <li>Members: ${groupData.numMembers}</li>
      <li>Created: ${groupData.dateCreated}</li>
      <li>Posts: ${groupData.numPosts}</li>
    </ul>
    <div>
      <a href='./group.php?groupid=${id}' class='btn btn-grey'>View Feed</a>
      <button class='btn-gold btn leave-group-btn'>Leave</button>
    </div>
  </div>`;

  document.getElementById("your-groups").appendChild(card);

  card.querySelector(".leave-group-btn").onclick = () => displayBlock(leaveGroupWindow, overlay);
  document.getElementById("leave-group-confirm-btn").onclick = async () => {
    leaveGroupById(id);
    document.getElementById("your-groups").removeChild(card);
    displayNone(leaveGroupWindow, overlay);
  }
}

function showNoGroupsMsg(){
  const p = document.createElement("p");
  p.id = "no-groups-msg";
  p.innerHTML = "You aren't in any groups";
  document.getElementById("your-groups").appendChild(p);
}

// Close leave group popup
document.getElementById("leave-group-cancel-btn").onclick = function(){
  displayNone(leaveGroupWindow, overlay);
}

/***********************/
/* Group Creation Form */
/***********************/

let nameIsValid = true;
let bioIsValid = true;

// Show create group form
document.getElementById("create-new-group").onclick = () => displayBlock(overlay, createGroupForm);

// Close create group form
document.getElementById("cancel-create-group-btn").onclick = function(e){
  e.preventDefault();
  displayNone(overlay, createGroupForm);
  nameInput.value = "";
  bioInput.value = "";
}

// Check that group name isn't taken
nameInput.onkeyup = function(e){
  $.post("/afterclass/php/process.php", { action: 'check-group-name', name: e.target.value }, res => {
    if(!res){
      nameIsValid = false;
      displayBlock(createGroupErr);
      createGroupErr.innerHTML = "That group name is already taken.<br>Please choose a different group name.";
    } else {
      nameIsValid = true;
      displayNone(createGroupErr);
      createGroupErr.innerHTML = "";
    }
  });
}

// Make sure bio is no longer than 350 characters
bioInput.onkeyup = function(e){
  if(e.target.value.length > 500){
    displayBlock(createGroupErr);
    createGroupErr.innerHTML = "Description must be less than 350 characters.";
    bioIsValid = false;
  } else {
    displayNone(createGroupErr);
    createGroupErr.innerHTML = "";
    bioIsValid = true;
  }
}

// Check inputs then submit form if all's well
createGroupForm.onsubmit = function(e){
  if(!nameInput.value && !bioInput.value){
    e.preventDefault();
    displayBlock(createGroupErr);
    createGroupErr.innerHTML = "Please enter a name and description for your group.";
  } else if(!nameIsValid && !bioIsValid){
    e.preventDefault();
  } else if(!nameIsValid){
    e.preventDefault();
    displayNone(createGroupErr);
    createGroupErr.innerHTML = "That group name is already taken.<br>Please choose a different group name.";
  } else if(!nameInput.value){
    e.preventDefault();
    displayBlock(createGroupErr);
    createGroupErr.innerHTML = "Please enter a name for your group.";
  } else if(!bioInput.value){
    e.preventDefault();
    displayBlock(createGroupErr);
    createGroupErr.innerHTML = "Please enter a description for your group.";
  } 
}