const createGroupForm = document.getElementById("create-group-form");
const overlay = document.getElementById("overlay");
const nameInput = createGroupForm.querySelector("input");
const bioInput = createGroupForm.querySelector("textarea");
const createGroupErr = document.getElementById("create-group-err-msg");
const leaveGroupWindow = document.getElementById("leave-group-confirmation-window");

/***********************/
/* Group Creation Form */
/***********************/

let nameIsValid = true;
let bioIsValid = true;

document.getElementById("create-new-group").onclick = function(){
  showElement(overlay);
  showElement(createGroupForm);
}

overlay.onclick = function(){
  hideElement(overlay);
  hideElement(leaveGroupWindow);
  hideElement(createGroupForm);
  clearFields();
}

document.getElementById("cancel-create-group-btn").onclick = function(e){
  e.preventDefault();
  hideElement(overlay);
  hideElement(createGroupForm);
  clearFields();
}

nameInput.onkeyup = function(e){
  $.post("/afterclass/php/process.php", { action: 'check-group-name', name: e.target.value }, res => {
    if(!res){
      nameIsValid = false;
      showElement(createGroupErr);
      createGroupErr.innerHTML = "That group name is already taken.<br>Please choose a different group name.";
    } else {
      nameIsValid = true;
      hideElement(createGroupErr);
      createGroupErr.innerHTML = "";
    }
  });
}

bioInput.onkeyup = function(e){
  if(e.target.value.length > 500){
    showElement(createGroupErr);
    createGroupErr.innerHTML = "Description must be less than 350 characters.";
    bioIsValid = false;
  } else {
    hideElement(createGroupErr);
    createGroupErr.innerHTML = "";
    bioIsValid = true;
  }
}

createGroupForm.onsubmit = function(e){
  if(!nameInput.value && !bioInput.value){
    e.preventDefault();
    showElement(createGroupErr);
    createGroupErr.innerHTML = "Please enter a name and description for your group.";
  } else if(!nameIsValid && !bioIsValid){
    e.preventDefault();
  } else if(!nameIsValid){
    e.preventDefault();
    hideElement(createGroupErr);
    createGroupErr.innerHTML = "That group name is already taken.<br>Please choose a different group name.";
  } else if(!nameInput.value){
    e.preventDefault();
    showElement(createGroupErr);
    createGroupErr.innerHTML = "Please enter a name for your group.";
  } else if(!bioInput.value){
    e.preventDefault();
    showElement(createGroupErr);
    createGroupErr.innerHTML = "Please enter a description for your group.";
  } 
}



/********************************/
/* Load Groups from Memberships */
/********************************/

document.querySelector("body").onload = function(){
  $.get("/afterclass/php/process.php", { action: "get-membership-ids" }, res => {
    const ids = JSON.parse(res);
    if(ids[0] != 0)
      ids.forEach(id => showGroupCard(Number(id)));
  });
}

function showGroupCard(id){
  $.get("/afterclass/php/process.php", { action: "get-group-info", groupid: id }, res => {
    group = JSON.parse(res);
    const card = document.createElement("div");
    card.classList.add("group-page-info");

    card.innerHTML += 
    `<h1>${group.name}</h1>
    <h3>Description</h3>
    <div class='group-page-desc'>${group.description}</div>
    <div>
      <ul>
        <li>Members: ${group.numMembers}</li>
        <li>Created: ${group.dateCreated}</li>
        <li>Posts: ${group.numPosts}</li>
      </ul>
      <div>
        <a href='./group.php?groupid=${id}' class='btn btn-grey'>View Feed</a>
        <button class='btn-gold btn leave-group-btn'>Leave</button>
      </div>
    </div>`;

    document.getElementById("your-groups").appendChild(card);

    card.querySelector(".leave-group-btn").onclick = function(){
      hideElement(leaveGroupWindow);
      showElement(overlay);
      document.getElementById("leave-group-confirm-btn").onclick = $.post("./php/process.php", { action: "leave-group", groupid: id }, () => location.reload());
    }
  });
}

document.getElementById("leave-group-cancel-btn").onclick = function(){
  hideElement(leaveGroupWindow);
  hideElement(overlay);
}

/********************/
/* HELPER FUNCTIONS */
/********************/

function hideElement(el){
  el.style.display = "none";
}

function showElement(el){
  el.style.display = "block";
}

function clearFields(){
  nameInput.value = "";
  bioInput.value = "";
}