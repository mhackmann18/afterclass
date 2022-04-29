const profileInfo = document.getElementById("profile-page-info");

// Get user info
document.querySelector("body").onload = () => {
  // Populate user info
  $.get("./php/process.php", { action: "get-user-info", userid: userId }, res => showUserInfo(JSON.parse(res)));
  // Populate groups list
  $.get("./php/process.php", { action: "get-membership-ids", userid: userId }, res => JSON.parse(res).forEach(groupId => {
    $.get("./php/process.php", { action: "get-group-info", groupid: groupId }, res => {
      document.getElementById("profile-page-groups").innerHTML += `<li><a href='/projects/afterclass/group.php?groupid=${groupId}'>${JSON.parse(res).name}</a></li>`;
    });
  }));
  // Load user's profile img if they have one
  $.get("./php/process.php", { action: "check-profile-img", userid: userId }, res => {
    if(res){
      document.getElementById("profile-page-img").src = `./uploads/profile${userId}.jpg?t=` + new Date().getTime();
    } else {
      document.getElementById("profile-page-img").src = "./img/blank-profile.jpg?t=" + new Date().getTime();
    }
  });
}

function showUserInfo(user){
  document.getElementById("profile-header").innerHTML = user.name;
  profileInfo.firstElementChild.innerHTML = `Email: ${user.email}`;
  document.getElementById("username").innerHTML = user.username;
  document.getElementById("major").innerHTML = user.major;
  document.getElementById("profile-bio").innerHTML = user.bio || `${user.name} hasn't added a bio yet.`;
}