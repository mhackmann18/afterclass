async function getUsernameById(userId){
  let username; 

  await $.get("/projects/afterclass/php/process.php", { action: 'get-username-by-id', userid: userId }, res => username = res);

  return username;
}

async function getGroupNameById(id){
  let name;
  await $.get("/projects/afterclass/php/process.php", { action: 'get-group-info', groupid: id }, res => name = JSON.parse(res).name);
  return name;
}

async function hasProfileImg(userId){
  let hasImg = false;
  await $.get("/projects/afterclass/php/process.php", { action: 'check-profile-img', userid: userId }, res => {
    if(res)
      hasImg = true;
  });

  if(hasImg){
    return true;
  } else {
    return false;
  }
}

const getGroupDataById = async id => JSON.parse(await $.get("/projects/afterclass/php/process.php", { action: "get-group-info", groupid: id }));

const getGroupIdsOfLoggedInUser = async () => JSON.parse(await $.get("/projects/afterclass/php/process.php", { action: "get-membership-ids" }));

const leaveGroupById = id => $.post("./php/process.php", { action: "leave-group", groupid: id });

export { 
  getUsernameById, 
  getGroupNameById, 
  hasProfileImg, 
  getGroupDataById, 
  getGroupIdsOfLoggedInUser,
  leaveGroupById
};