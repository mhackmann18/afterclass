const groupsDiv = document.getElementById("groups");

document.querySelector("body").onload = function(){
  $.get("/afterclass/php/PROCESS.php", { action: "get-no-membership-ids" }, res => {
    const ids = JSON.parse(res);
    if(ids[0] != 0){
      ids.forEach(id => showGroupCard(Number(id)));
    }
  });
}

function showGroupCard(id){
  $.get("/afterclass/php/PROCESS.php", { action: "get-group-info", groupid: id }, res => {
    const data = JSON.parse(res);
    const card = document.createElement("div");
    card.classList.add("group-page-info");
    card.innerHTML += 
      `<h1>${data[0]}</h1>
      <h3>Description</h3>
      <div class="group-page-desc">${data[1]}</div>
      <div>
        <ul>
          <li>Members: ${data[2]}</li>
          <li>Created: ${data[4]}</li>
          <li>Posts: ${data[3]}</li>
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
    $.get('/afterclass/php/PROCESS.php', { action: "add-new-membership", groupid: groupId }, res => {
      console.log(res);
    });
    window.location.replace(`/afterclass/group.php?groupid=${groupId}`);
  }
}