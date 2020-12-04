<?php 

  if(!empty($_POST['action'])){

    $action = $_POST['action'];

    /* POST REQUEST HANDLERS */

    if($action == 'login'){
      handleLogin();
    } else if($action == 'logout'){

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

    }

  } else if(!empty($_GET['action'])){

    $action = $_GET['action'];

    /* GET REQUEST HANDLERS */

    if($action == 'get-membership-ids'){

      $userId = getLoggedInUserId();
      $groupIds = getUserGroupsIds($userId);
      print json_encode($groupIds);

    } else if($action == 'get-group-card'){

      getGroupCard();

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

    }
  } else {
    print "Invalid action submitted to process.php<br>\n";
  }

  function getGroupCard(){
    if(!isset($_COOKIE['userid'])){
      header("location: ../login.php");
      exit;
    }

    require_once "../config/db.conf";

    if($mysqli->connect_error){
      print "There was an issue connecting to the database.<br>Please try again later.";
      exit;
    }

    $groupId = $_GET['groupid'];

    $query = "SELECT * FROM organizations WHERE id = $groupId";

    $result = $mysqli->query($query);

    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $groupName = $row['groupName'];
      $groupDesc = $row['groupDescription'];
      $numMembers = $row['members'];
      $numPosts = $row["numPosts"];
      $dateCreated = $row["addDate"];

      $time = strtotime($dateCreated);
      $displayDate = date("m/d/y", $time);

      print 
      "<h1>$groupName</h1>
        <h3>Description</h3>
        <div class='group-page-desc'>$groupDesc</div>
        <div>
          <ul>
            <li>Members: $numMembers</li>
            <li>Created: $displayDate</li>
            <li>Posts: $numPosts</li>
          </ul>
          <div>
            <a href='./group.php?groupid=$groupId' class='btn btn-grey'>View Feed</a>
            <button class='btn-gold btn leave-group-btn'>Leave</button>
          </div>
        </div>";
    } else {
      print "There was an issue getting group data.<br>Please contact system administrator.";
      exit;
    }
    
    $mysqli->close();
  }

  // Helper Functions

  function isGroupMember($userId, $groupId){
    require "../config/db.conf";

    if($mysqli->connect_error)
      exit("There was an error connecting to the database");

    $query = "SELECT * FROM groupMemberships WHERE userid = $userId AND groupid = $groupId";

    $result = $mysqli->query($query);

    if($result){
      $mysqli->close();
      return TRUE;
    } else {
      $mysqli->close();
      return FALSE;
    }
  }

  function getLoggedInUserId(){
    require "../config/db.conf";

    if($mysqli->connect_error)
      exit("There was an issue connecting to the database.<br>");

    $username = $_COOKIE['userid'];

    $result = $mysqli->query("SELECT * FROM users WHERE username = '$username'");

    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];

    $mysqli->close();

    return $userId;
  }

  function connectDB(){
    require "../config/db.conf";

    // If database connection fails
    if($mysqli->connect_error){
      return FALSE;
    } else {
      return $mysqli;
    }
  }

  function redirectIfNotLoggedIn(){
    // Make sure user is logged in
    if(!isset($_COOKIE['userid'])){
      header("location: ../login.php");
      exit;
    }
  }

  function handleLogin(){

    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];

    $mysqli = connectDB();

    // Stop SQL injections
    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);
    
    // Get all users from the users table in database
    $query = "SELECT id FROM users WHERE username = '$username' AND userPassword = sha1('$password')";
    
    $result = $mysqli->query($query);

    if($result){
      if($result->num_rows == 1){
        setcookie('userid', $username, time() + 1800, "/");
        print("Logged in successfully");
      } else {
        print("Incorrect username or password.<br>Please double-check your username and password.");
      }
    } else {
      print("Error. Please contact the system administrator");
    }

    $result->close();
    $mysqli->close();
  }

  /*******************/
  /* MANIPULATING DB */
  /*******************/

  function deleteGroupById($groupId){
    $mysqli = connectDB();

    $query = "DELETE FROM organizations WHERE id = $groupId";

    if($mysqli->query($query) && deletePostsByGroupId($groupId)){
      $mysqli->close();
      return TRUE;
    } else {
      $mysqli->close();
      return FALSE;
    }
  }

  function deletePostsByGroupId($groupId){
    $mysqli = connectDB();

    $query = "DELETE FROM userPosts WHERE groupid = $groupId";

    if($mysqli->query($query)){
      $mysqli->close();
      return TRUE;
    } else {
      $mysqli->close();
      return FALSE;
    }
  }
  
  function addUserToGroup($userId, $groupId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    // Add a new membership to the database
    $query = "INSERT INTO groupMemberships (userid, groupid) VALUES ($userId, $groupId)";
    $mysqli->query($query);

    // Update the member count in the joined group
    $query = "UPDATE organizations SET members = members + 1 WHERE id = $groupId";
    $mysqli->query($query);

    $mysqli->close();
  }

  function removeUserFromGroup($userId, $groupId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    // Decrement the number of members of the group
    $query = "UPDATE organizations SET members = members - 1 WHERE id = $groupId";
    $mysqli->query($query);

    // Remove the user's membership from memberships table
    $query = "DELETE FROM groupMemberships WHERE userid = $userId AND groupid = $groupId";
    $result = $mysqli->query($query);

    // Check the no. of members in group, and delete it if none are left
    $query = "SELECT * FROM organizations WHERE id = $groupId";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_assoc($result);
    $numMembers = $row['members'];

    if($numMembers == 0){
      if(deleteGroupById($groupId)){
        $result->close();
        $mysqli->close();
        return TRUE;
      } else {
        $result->close();
        $mysqli->close();
        return FALSE;
      }
    }

    $result->close();
    $mysqli->close();
    return TRUE;
  }

  /***************************/
  /* RETRIEVING DATA FROM DB */
  /***************************/

  // Returns an array of associative arrays that each represent a post from the group with the passed id
  function getPostsByGroupId($groupId){
    $userId = getLoggedInUserId();

    if(isGroupMember($userId, $groupId)){
      $mysqli = connectDB();

      $query = "SELECT * FROM userPosts WHERE groupid = $groupId ORDER BY addDate DESC";

      $result = $mysqli->query($query);

      $posts = array();

      while($row = mysqli_fetch_assoc($result)) {
        $post = array(
          'userId' => $row['userid'],
          'text' => $row['postText'],
          'link' => $row['youtubeLink'],
          'fileName' => $row['fileName'],
          'dateCreated' => $row['addDate']
        );
        array_push($posts, $post);
      }

      $mysqli->close();
      $result->close();

      return $posts;
    } else {
      print "Only logged in members of this group are allowed access to its posts<br>";
    }
  }

  function getGroupDataById($groupId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    $query = "SELECT * FROM organizations WHERE id = $groupId";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_assoc($result);

    $dateCreated = $row['addDate'];
    $time = strtotime($dateCreated);

    $groupInfo = array(
      'name' => $row['groupName'],
      'description' => $row['groupDescription'],
      'numMembers' => $row['members'],
      'numPosts' => $row['numPosts'],
      'dateCreated' => date("m/d/y", $time)
    );
    
    $mysqli->close();
    return $groupInfo;
  }

  function getUserNameById($userId){
    $mysqli = connectDB();
    
    $result = $mysqli->query("SELECT * FROM users WHERE id = $userId");

    $row = mysqli_fetch_assoc($result);

    return $row['username'];
  }

  function getUserGroupsIds($userId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    $query = "SELECT groupid FROM groupMemberships WHERE userid = $userId ORDER BY groupid ASC";

    $result = $mysqli->query($query);

    $groupIds = array();

    while($row = mysqli_fetch_assoc($result)) {
      $groupIds[] = $row['groupid'];
    }

    return $groupIds;
    
    $mysqli->close();
  }

  function getFeedPostsByUserId($userId){
    redirectIfNotLoggedIn();
    $groupIds = getUserGroupsIds($userId);
    
    $firstGroupId = array_pop($groupIds);

    $query = "SELECT * FROM userPosts WHERE groupid = ".$firstGroupId;

    foreach($groupIds as $groupId){
      $query .= " OR groupid = ".$groupId;
    }
    unset($groupId);

    $query .= " ORDER BY addDate DESC";

    $mysqli = connectDB();

    $result = $mysqli->query($query);

    $posts = array();

    while($row = mysqli_fetch_assoc($result)) {
      $post = array(
        'userId' => $row['userid'],
        'groupId' => $row['groupid'],
        'text' => $row['postText'],
        'link' => $row['youtubeLink'],
        'fileName' => $row['fileName'],
        'dateCreated' => $row['addDate']
      );
      array_push($posts, $post);
    }

    return $posts;

    $mysqli->close();
  }
  // Returns an array of of ids of all the groups the user with the passed in id is not currently a member of
  function getNoMembershipIds($userId){
    redirectIfNotLoggedIn();

    $userGroupIds = getUserGroupsIds($userId);

    $mysqli = connectDB();

    $nonMemberIds = array();

    // If the user is in at least one group...
    if(count($userGroupIds)){

      // Build a query string that selects all groups the user is not currently a member of
      $mustNotEqual = "";
      foreach($userGroupIds as &$id)
        $mustNotEqual .= " AND id != ".$id;

      $query = "SELECT id FROM organizations WHERE id != -1".$mustNotEqual;
      $result = $mysqli->query($query);

      while($row = mysqli_fetch_assoc($result)) 
        $nonMemberIds[] = $row['id'];

    } else {
      $query = "SELECT id FROM organizations";
      $result = $mysqli->query($query);

      while($row = mysqli_fetch_assoc($result))
        $nonMemberIds[] = $row['id'];
    }

    $mysqli->close();
    return $nonMemberIds;
  }

  /**************************/
  /* READING VALUES FROM DB */
  /**************************/

  // Returns true if the user with the passed id has uploaded a profile image and false if not
  function hasProfileImg($userId){
    $mysqli = connectDB();
    
    $result = $mysqli->query("SELECT * FROM profileimg WHERE userid = $userId");

    $row = mysqli_fetch_assoc($result);

    if($row['status'] == 0){
      $mysqli->close();
      return TRUE; 
    } else {
      $mysqli->close();
      return FALSE;
    }
  }
  
  function isEmailValid($email){
    $mysqli = connectDB();

    $query = "SELECT id FROM users WHERE email = '$email'";

    if($mysqli->query($query)->num_rows == 1){
      $mysqli->close();
      return FALSE;
    } else {
      $mysqli->close();
      return TRUE;
    }
  }

  function isUsernameValid(){
    $mysqli = connectDB();

    $username = empty($_POST['username']) ? '' : $_POST['username'];

    $query = "SELECT id FROM users WHERE username = '$username'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }

  function isGroupNameValid(){
    $mysqli = connectDB();

    $groupName = empty($_POST['name']) ? '' : $_POST['name'];

    $query = "SELECT * FROM organizations WHERE groupName = '$groupName'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }

?>