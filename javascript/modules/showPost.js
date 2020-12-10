import { getUIDateStringFromDBDate } from './utilities.js';
import { getUsernameById, getGroupNameById, hasProfileImg } from './dbController.js';

async function showPost(post, div, showGroup){
  const { groupId, text, dateCreated, userId } = post;

  // Get information about the post
  const dateString = getUIDateStringFromDBDate(dateCreated);
  const media = getPostMediaDivInnerHTML(post);
  const usernameATag = await getProfileLinkElement(userId);
  const profileImgSrc = await getProfileImgSrc(userId);

  if(showGroup)
    var groupName = await getGroupNameById(groupId);

  // Create the post container div
  const postDiv = document.createElement("div");
  postDiv.classList.add("post", "margin-top-mid");

  postDiv.innerHTML += 
  `<div class="post-head">
    <div class="profile-img-container fill">
      <img class="profile-img" src="${profileImgSrc}?t=${new Date().getTime()}" alt="profile image"></img>
    </div>
    <p class="who-when">
      ${usernameATag}
      posted 
      ${showGroup ? `in <a href="./group.php?groupid=${groupId}" class="txt-darkgrey">${groupName}</a>`: ""}
      on ${dateString}
    </p>
  </div>
  <p class="post-text">${text}</p>
  <div class="post-media">
    ${media}
  </div>`;

  div.appendChild(postDiv);
}

function getPostMediaType({ link, fileName }){
  if(link){
    return "video";
  } else if(fileName && fileName.slice(-3) === "pdf"){
    return "pdf";
  } else if(fileName){
    return "img";
  } else {
    return "none";
  }
}

function getPostMediaDivInnerHTML(post){
  const type = getPostMediaType(post);
  const { link, fileName } = post;

  if(type === "video"){
    return `<div class="bootstrap-iso">
      <div class="embed-responsive embed-responsive-16by9">
        <iframe src=${link} class="embed-responsive-item" frameborder="0" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
    </div>`;
  } else if(type === "pdf"){
    return `<div class="padding-sides-mid"><div class="pdf-container"><embed src="./uploads/${fileName}"/></div></div>`;
  } else if(type === "img"){
    return `<div class="post-img-container flex-center"><img src="/afterclass/uploads/${fileName}" alt="posted image"></div>`;
  } else {
    return "";
  }
}

async function getProfileLinkElement(userId){
  // Link to logged in user's page if the post was created by them
  const username = await getUsernameById(userId);
  // if(username === "JSJNJUDIUJIJIUJI"){
  //   return `<a href="./yourProfile.php" class="txt-darkgrey">You</a>`
  // } else {
  return `<a href="./profile.php?userid=${userId}" class="txt-darkgrey">${username}</a>`;
  // }
}

async function getProfileImgSrc(userId){
  const hasImg = await hasProfileImg(userId);
  if(hasImg){
    return `/afterclass/uploads/profile${userId}.jpg`;
  } else {
    return `/afterclass/img/blank-profile.jpg`;
  }
}

export default showPost;