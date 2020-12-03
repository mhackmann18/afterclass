<?php 
  $main_dir = $_SERVER["DOCUMENT_ROOT"] . "/afterclass";
  
  $action = empty($_POST['action']) ? false : $_POST['action'];
  //exit;
  if($action == 'login'){
    handle_login();
    // If logging out, delete user cookie
  } else if($action == 'logout'){
    setcookie('userid', '', 1, "/");
    print "Logged out";

  } else if($action == 'get') {
    // If a valid login cookie exists, then retrieve the user's main account page
    $userid = empty($_COOKIE['userid']) ? '' : $_COOKIE['userid'];
    if($userid == 'test'){
      print "dfdsffdsd";
    } else {
      print "User is not logged in";
    }
    // Check if email address exist in database yet.
  } else if($action == 'check-email'){
    check_email();

  } else if($action == 'check-username'){
    check_username();

  } else if($action == 'check-group-name'){
    checkGroupName();
  } 
  // Method must be GET
  else {
    $action = empty($_GET['action']) ? false : $_GET['action'];

    if($action == 'get-membership-ids'){
      get_membership_ids();
    } else if($action == 'get-group-card'){
      get_group_card();
    } else if($action == 'leave-group'){
      leave_group();
    } else if($action == 'get-no-membership-ids'){
      get_no_membership_ids();
    } else if($action == 'get-group-info'){
      get_group_info();
    } else if($action == 'add-new-membership'){
      add_new_membership();
    } else if($action == 'get-posts-by-group-id'){
      get_posts_by_group_id();
    } else if($action == 'check-profile-img'){
      print hasProfileImg($_GET['userid']);
    } else if($action == 'get-username-by-id'){
      print getUsernameById($_GET['userid']);
    } else {
      print "Invalid action submitted to process.php.";
    }
  }

  function handle_login(){
    // Pull username and password strings out of post body
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    
    // Connect to database
    require_once($GLOBALS["main_dir"] . "/config/db.conf");

    // If database connection fails
    if ($mysqli->connect_error){
      print "There was a problem establishing a database connection.";
      exit;
    }

    // Stop SQL injections
    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);

    // Get all users from the users table in database
    $query = "SELECT id FROM users WHERE username = '$username' AND userPassword = sha1('$password')";

    $result = $mysqli->query($query);

    if($result){
      $match = $result->num_rows;

      if($match == 1){
        setcookie('userid', $username, time() + 1800, "/");
        print("Logged in successfully");
      } else {
        print("Incorrect username or password.<br>Please double-check your username and password.");
      }
    } else {
      print('Error. Please contact the system administrator.');
    }

    // while($row = $result->fetch_assoc()){
    //   print "Row 1";
    // }

    $result->close();
    $mysqli->close();
  }

  function check_email(){
    require_once($GLOBALS["main_dir"] . "/config/db.conf");

    if($mysqli->connect_error){ exit; }

    $email = empty($_POST['email']) ? '' : $_POST['email'];

    $query = "SELECT id FROM users WHERE email = '$email'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }

  function check_username(){
    require_once($GLOBALS["main_dir"] . "/config/db.conf");

    if($mysqli->connect_error){ exit; }

    $username = empty($_POST['username']) ? '' : $_POST['username'];

    $query = "SELECT id FROM users WHERE username = '$username'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }

  function checkGroupName(){
    require_once($GLOBALS["main_dir"] . "/config/db.conf");

    if($mysqli->connect_error){ exit; }

    $groupName = empty($_POST['name']) ? '' : $_POST['name'];

    $query = "SELECT * FROM organizations WHERE groupName = '$groupName'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }

  function get_membership_ids(){
    // Make sure the user is logged in, redirect if not redirect to login
    if(!isset($_COOKIE['userid'])){
      header("location: ../login.php");
      exit;
    }

    $username = $_COOKIE['userid'];

    require_once "../config/db.conf";

    if($mysqli->connect_error){
      print "There was an issue connecting to the database.<br>Please try again later.";
      exit;
    }

    // Get current user's id
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userId = $row['id'];
    }

    $query = "SELECT groupid FROM groupMemberships WHERE userid = $userId";

    $result = $mysqli->query($query);

    $groupIds = array();

    while($row = mysqli_fetch_assoc($result)) {
      $groupIds[] = $row['groupid'];
    }

    print json_encode($groupIds);
    
    $mysqli->close();
    exit;
  }

  function get_group_card(){
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
          <a href='./group.php?groupid=$groupId' class='view-group-feed'>View Feed</a>
          <button class='btn-gold leave-group-btn'>Leave</button>
        </div>";
    } else {
      print "There was an issue getting group data.<br>Please contact system administrator.";
      exit;
    }
    
    $mysqli->close();
  }

  function get_no_membership_ids(){
     // Make sure the user is logged in, redirect if not redirect to login
     if(!isset($_COOKIE['userid'])){
      header("location: ../login.php");
      exit;
    }

    $username = $_COOKIE['userid'];

    require_once "../config/db.conf";

    if($mysqli->connect_error){
      print "There was an issue connecting to the database.<br>Please try again later.";
      exit;
    }

    // Get current user's id
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userId = $row['id'];
    }

    // Get group memberships
    $query = "SELECT groupid FROM groupMemberships WHERE userid = $userId";

    $result = $mysqli->query($query);

    $nonMemberIds = array();

    if($result->num_rows > 0){
      // Build a query string that selects all groups the user is not currently a member of
      $mustNotEqual = "";
      while($row = mysqli_fetch_assoc($result)) {
        $mustNotEqual .= " AND id != ".$row['groupid'];
      }

      $query = "SELECT id FROM organizations WHERE id != -1".$mustNotEqual;
      // Get group ids where the user isn't a member
      $result = $mysqli->query($query);

      while($row = mysqli_fetch_assoc($result)) {
        $nonMemberIds[] = $row['id'];
      }
    }

    print json_encode($nonMemberIds);

    $mysqli->close();
    exit;
  }

  function leave_group(){
    if(!isset($_COOKIE['userid'])){
      header("location: ../login.php");
      exit;
    }

    require_once "../config/db.conf";
    if($mysqli->connect_error){
      print "There was an issue connecting to the database.<br>Please try again later.";
      exit;
    }
    $username = $_COOKIE['userid'];

    // Get current user's id
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userId = $row['id'];
    }
    

    $groupId = $_GET['groupid'];

    $query = "DELETE FROM groupMemberships WHERE userid = $userId AND groupid = $groupId";

    $result = $mysqli->query($query);

    if($result === TRUE){
      // If user membership was cancelled, subtract a member from group and check to see if group has members left. If not, delete it.
      $query = "SELECT * FROM organizations WHERE id = $groupId";
      $result = $mysqli->query($query);
      if($result->num_rows == 1){
        $row = mysqli_fetch_assoc($result);
        $numMembers = $row['members'];
      } 

      if($numMembers - 1 == 0){
        $query = "DELETE FROM organizations WHERE id = $groupId";
        if($mysqli->query($query) === TRUE){
          print "Successfully left and deleted group.";
        } else {
          print "There was a problem deleting the group.";
        }
      } else {
        $query = "UPDATE organizations SET members = members - 1 WHERE id = $groupId";

        if($mysqli->query($query) === TRUE){
          print "Successfully left group.";
        } else {
          print "There was a problem leaving the group.";
        }
      }
    } else {
      print $mysqli->error;
    }
    
    $mysqli->close();
  }

  function get_group_info(){
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
      $groupInfo = array();

      $row = mysqli_fetch_assoc($result);
      $groupInfo[] = $row['groupName'];
      $groupInfo[] = $row['groupDescription'];
      $groupInfo[] = $row['members'];
      $groupInfo[] = $row["numPosts"];

      $dateCreated = $row['addDate'];
      $time = strtotime($dateCreated);
      $groupInfo[] = date("m/d/y", $time);

      print json_encode($groupInfo);
    } else {
      print "There was an issue getting group data.<br>Please contact system administrator.";
      exit;
    }
    
    $mysqli->close();
  }

  function add_new_membership(){
    // Make sure user is logged in
    if(!isset($_COOKIE['userid'])){
      header("location: ../login.php");
      exit;
    }

    // Connect to database
    require_once "../config/db.conf";
    if($mysqli->connect_error){
      print "There was an issue connecting to the database.<br>Please try again later.";
      exit;
    }

    // Get the id of the current user
    $username = $_COOKIE['userid'];
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userId = $row['id'];

      // Get the id of the group to be joined
      $groupId = $_GET['groupid'];

      // Add a new membership to the database
      $query = "INSERT INTO groupMemberships (userid, groupid) VALUES ($userId, $groupId)";

      if($mysqli->query($query) == TRUE){
        // Update the member count in the joined group
        $query = "UPDATE organizations SET members = members + 1 WHERE id = $groupId";
        if($mysqli->query($query) == TRUE){
          print TRUE;
        } else {
          print FALSE;
        }
      } else {
        print "There was a problem adding the new membership.";
      }
    } else {
      print "Could not get user id.";
    }

    $mysqli->close();
  }

  function get_posts_by_group_id(){
    $groupId = $_GET['groupid'];
    $userId = getLoggedInUserId();

    if(isGroupMember($userId, $groupId)){
      require "../config/db.conf";
      if($mysqli->connect_error)
        exit("There was an issue connecting to the database.<br>");

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

      print json_encode($posts);
      $mysqli->close();
    } else {
      print "Silly user. You can't see this group's posts unless you're a member.<br>";
    }
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

  // Returns true if the user with the passed id has uploaded a profile image and false if not
  function hasProfileImg($userId){
    require "../config/db.conf";

    if($mysqli->connect_error)
      exit("There was an issue connecting to the database.<br>");
    
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

  function getUserNameById($userId){
    require "../config/db.conf";

    if($mysqli->connect_error)
      exit("There was an issue connecting to the database.<br>");
    
    $result = $mysqli->query("SELECT * FROM users WHERE id = $userId");

    $row = mysqli_fetch_assoc($result);

    return $row['username'];
  }
?>