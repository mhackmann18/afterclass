const feedDiv = document.getElementById("feed");
const groupCard = document.getElementById("feed-page-header");
const leaveBtn = document.getElementById("feed-page-leave-btn");
const spinner = document.getElementById("spinner");

import showPost from './modules/showPost.js';
import { displayBlock, displayNone } from './modules/utilities.js';

document.querySelector('body').onload = async function(){
  displayBlock(spinner);

  showGroupCardInfo(groupId, groupCard);
  const posts = JSON.parse(await $.get("/afterclass/php/process.php", { action: 'get-posts-by-group-id', groupid: groupId }));

  for(let post of posts)
    await showPost(post, feedDiv, false);

  displayNone(spinner);

  if(!posts.length)
    showNoPostsMsg();
}

// Display the group's data into the passed in card div
function showGroupCardInfo(groupId, card){
  $.get("/afterclass/php/process.php", { action: "get-group-info", groupid: groupId }, res => {
    const { name, description, numMembers, numPosts, dateCreated } = JSON.parse(res);
    card.querySelector("h1").innerHTML = name;
    card.querySelector(".group-page-desc").innerHTML = description;
    card.querySelector("#feed-page-members").innerHTML = numMembers;
    card.querySelector("#feed-page-posts").innerHTML = numPosts;
    card.querySelector("#feed-page-date").innerHTML = dateCreated;
    document.querySelector("#feed-page-group-name").innerHTML = name;
  });
}

// Leave group and redirect to user's groups page
leaveBtn.onclick = function(){
  $.post("./php/process.php", { action: "leave-group", groupid: groupId }, () => {
    window.location.replace("./groups.php");
  });
}

function showNoPostsMsg(){
  const p = document.createElement("p");
  p.id = "feed-page-no-posts-msg";
  p.innerHTML = "No posts to show";
  feedDiv.appendChild(p);
}