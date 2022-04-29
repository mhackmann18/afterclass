const groupsDiv = document.getElementById("groups");
const spinner = document.getElementById("spinner");
const noGroupsMsg = document.getElementById("find-groups-no-groups");

document.querySelector("body").onload = function(){
  spinner.style.display = "block";
  $.get("/projects/afterclass/php/process.php", { action: "get-no-membership-ids" }, res => {
    const ids = JSON.parse(res);
    if(ids[0] != 0){
      ids.forEach(id => showGroupCard(Number(id)));
    }
    if(!ids.length)
      noGroupsMsg.style.display = "block";
    spinner.style.display = "none";
  });
}

function showGroupCard(id){
  $.get("/projects/afterclass/php/process.php", { action: "get-group-info", groupid: id }, res => {
    const group = JSON.parse(res);
    const card = document.createElement("div");
    card.classList.add("group-page-info");
    card.innerHTML += 
      `<h1>${group.name}</h1>
      <h3>Description</h3>
      <div class="group-page-desc">${group.description}</div>
      <div>
        <ul>
          <li>Members: ${group.numMembers}</li>
          <li>Created: ${group.dateCreated}</li>
          <li>Posts: ${group.numPosts}</li>
        </ul>
        <button class="btn-gold leave-group-btn">Join</button>
      </div>`;
    groupsDiv.appendChild(card);

    addJoinEvent(card, id);
  });
}

function addJoinEvent(cardDiv, groupId){
  const joinBtn = cardDiv.querySelector('button');

  joinBtn.onclick = function(e){
    $.post('/projects/afterclass/php/process.php', { action: "add-new-membership", groupid: groupId }, () => window.location.replace(`/projects/afterclass/group.php?groupid=${groupId}`));
  }
}