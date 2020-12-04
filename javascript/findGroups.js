const groupsDiv = document.getElementById("groups");

console.log("jhdjhsdjhsdjh");

document.querySelector("body").onload = function(){
  $.get("/afterclass/php/process.php", { action: "get-no-membership-ids" }, res => {
    const ids = JSON.parse(res);
    console.log(ids);
    if(ids[0] != 0){
      ids.forEach(id => showGroupCard(Number(id)));
    }
  });
}

function showGroupCard(id){
  $.get("/afterclass/php/process.php", { action: "get-group-info", groupid: id }, res => {
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
    $.post('/afterclass/php/process.php', { action: "add-new-membership", groupid: groupId }, () => window.location.replace(`/afterclass/group.php?groupid=${groupId}`));
  }
}