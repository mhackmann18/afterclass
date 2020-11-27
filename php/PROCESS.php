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
          <button class='view-group-feed'>View Feed</button>
          <button class='btn-gold leave-group-btn'>Leave</button>
        </div>";
    } else {
      print "There was an issue getting group data.<br>Please contact system administrator.";
      exit;
    }
    
    $mysqli->close();
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
        $numMembers -= 1;
        $query = "UPDATE organizations SET members = $numMembers WHERE id = $groupId";

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
?>