const feedDiv = document.getElementById("group-feed");

document.querySelector('body').onload = function(){
  getFeedPosts();
}

function getFeedPosts(){
  $.get("./php/process.php", { action: 'get-feed-posts-by-user'}, async res => {
    let posts = JSON.parse(res);
    for(post of posts){
      await displayPost(post);
    }
    if(!posts.length)
      showNoPostsMsg();
  });
}

function showNoPostsMsg(){
  const p = document.createElement("p");
  p.id = "feed-page-no-posts-msg";
  p.innerHTML = "No posts to show";
  feedDiv.appendChild(p);
}

/* HELPER FUNCTIONS */

async function getProfileImg(userId){
  let hasImg = false;
  await $.get("/afterclass/php/process.php", { action: 'check-profile-img', userid: userId }, res => {
    if(res)
      hasImg = true;
  });

  if(hasImg){
    return `<img class="profile-img" src="/afterclass/uploads/profile${userId}.jpg" alt="profile image"></img>`;
  } else {
    return `<img class="profile-img" src="/afterclass/img/blank-profile.jpg" alt="profile image"></img>`;
  }
}

async function getUsername(userId){
  let username; 

  await $.get("/afterclass/php/process.php", { action: 'get-username-by-id', userid: userId }, res => username = res);

  return username;
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

// Takes in a post object and displays it as a post in the feed div
async function displayPost(post){
  const profileImg = await getProfileImg(post.userId);
  const groupName = await getGroupNameById(post.groupId);
  let username = await getUsername(post.userId);

  if(username === getCookie('userid')){
    username = "You";
  }

  let media;

  if(post.link){
    media = `<iframe id=${post.link} src=${post.link} frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
  } else if(post.fileName){
    media = `<img src="/afterclass/uploads/${post.fileName}" alt="posted image">`;
  }

  const newPostElement = document.createElement("div");
  newPostElement.classList.add("post", "margin-top-mid");

  let dateParts = post.dateCreated.split("-");
  let year = dateParts[0].slice(-2);
  let jsDate = `${dateParts[1]}/${dateParts[2].slice(0,2)}/${year}`;
 
  newPostElement.innerHTML = 
    `<div class="post-head">
      <div class="profile-img-container">${profileImg}</div>
      <p class="who-when"><a href="./profile.php?userid=${post.userId}" class="txt-darkgrey">${username}</a> posted in <a href="./group.php?groupid=${post.groupId}" class="txt-darkgrey">${groupName}</a> on ${jsDate}</p>
    </div>
    <p class="post-text">${post.text}</p>
    <div class="post-media">
      ${media || ""}
    </div>`;
  
  feedDiv.appendChild(newPostElement);

  if(post.link){
    const observer = new MutationObserver(function(){
      const iframe = document.getElementById(post.link);
      let width = iframe.offsetWidth;
      iframe.style.height = `${width*.5}px`;
      this.disconnect();
    });

    observer.observe(feedDiv, { childList: true });
  }
}

async function getGroupNameById(id){
  let name;
  await $.get("./php/process.php", { action: 'get-group-info', groupid: id }, res => name = JSON.parse(res).name);
  return name;
}