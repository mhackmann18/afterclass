<?php 
  require_once './helperFunctions.php';
  require_once './readDB/readDB.php';
  require_once './changeDB/updateDB.php';
  require_once './getFromDB/getData.php';
  
  // Make sure user is logged in
  if(!isset($_COOKIE['userid'])){
    header("location: ../login.php");
    exit;
  }

  if(!empty($_POST['action'])){

    $action = $_POST['action'];

    /* POST REQUEST HANDLERS */

    if($action == 'logout'){

      setcookie('userid', '', 1, "/");

    } else if($action == 'check-email'){

      $email = empty($_POST['email']) ? '' : $_POST['email'];
      print isEmailValid($email);

    } else if($action == 'check-username'){
      isUsernameValid();
    } else if($action == 'check-group-name'){
      isGroupNameValid();
    } else if($action == "leave-group"){

      $userId = getLoggedInUserId();
      $groupId = $_POST['groupid'];
      removeUserFromGroup($userId, $groupId);

    } else if($action == "add-new-membership") {

      $groupId = $_POST['groupid'];
      $userId = getLoggedInUserId();
      addUserToGroup($userId, $groupId);

    } if($action == "create-new-group"){

      require_once "./changeDB/createGroup.php";
      $userId = getLoggedInUserId();
      createGroup($userId, $_POST['group-name'], $_POST['description']) ? header("location: ../groups.php") : print "404: An error occured";

    } if($action == "create-new-post"){

      require_once "./changeDB/createPost.php";
      $userId = getLoggedInUserId();
      createPost($userId, $_POST['group-name'], $_POST['post-body'], $_POST['yt-link'], $_FILES['file']);

    } if($action == "update-profile"){

      require_once "./changeDB/updateProfile.php";
      $userId = getLoggedInUserId();
      updateUserProfile($userId, $_POST['username'], $_POST['major'], $_POST['bio']);

    } else if($action == "upload-profile-img"){

      require_once "./changeDB/uploadProfileImg.php";
      $userId = getLoggedInUserId();
      uploadProfileImg($userId, $_FILES['file']);

    }

  } else if(!empty($_GET['action'])){

    $action = $_GET['action'];

    /* GET REQUEST HANDLERS */

    if($action == 'get-membership-ids'){

      $userId = getLoggedInUserId();
      $groupIds = getUserGroupsIds($userId);
      print json_encode($groupIds);

    } else if($action == 'get-no-membership-ids'){

      $userId = getLoggedInUserId();
      $ids = getNoMembershipIds($userId);
      print json_encode($ids);

    } else if($action == 'get-group-info'){

      $groupId = $_GET['groupid'];
      $data = getGroupDataById($groupId);
      print json_encode($data);

    } else if($action == 'get-posts-by-group-id'){

      $groupId = empty($_GET['groupid']) ? NULL : $_GET['groupid'];
      $posts = getPostsByGroupId($groupId);
      print json_encode($posts);

    } else if($action == 'check-profile-img'){

      print hasProfileImg($_GET['userid']);

    } else if($action == 'get-username-by-id'){

      print getUsernameById($_GET['userid']);

    } else if($action == 'get-feed-posts-by-user'){

      $userId = getLoggedInUserId();
      print json_encode(getFeedPostsByUserId($userId));

    } else if($action == "get-user-info"){

      $userId = $_GET['userid'];
      $userInfo = getUserDataById($userId);
      print json_encode($userInfo);

    } 
  } else {
    print "Invalid action submitted to process.php<br>\n";
  }
?>