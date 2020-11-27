<?php
  $main_dir = $_SERVER["DOCUMENT_ROOT"] . "/afterclass";

  // Makes sure a user is logged in
  if(!isset($_COOKIE['userid'])){
    header("location: ../index.php");
    exit;
  }

  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");

  // Connect to SQL database
  require_once($main_dir . "/config/db.conf");

  // Display error and exit if connection faied
  if($mysqli->connect_error){
    $error = "There was an issue connecting to the SQL database.<br>Please try again later."; // Fix this
    exit;
  }
  
  $groupName = $_POST['group-name'];
  $bio = $_POST['description'];

  // Stop SQL Injections
  $groupName = $mysqli->real_escape_string($groupName);
  $bio = $mysqli->real_escape_string($bio);

  // Inserts new group into organizations table
  $query = "INSERT INTO organizations (groupName, groupDescription, addDate) VALUES ('$groupName', '$bio', now())";

  // If group was successfully added to database
  if($mysqli->query($query) === TRUE){
    
    // Get the id of the current user
    $query = "SELECT id FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userId = $row['id'];

      // Get the id of the newly created group
      $query = "SELECT id FROM organizations WHERE groupName = '$groupName'";
      $result = $mysqli->query($query);
      if($result->num_rows == 1){
        $row = mysqli_fetch_assoc($result);
        $groupId = $row['id'];

        // Add a new membership to the database
        $query = "INSERT INTO groupMemberships (userid, groupid) VALUES ('$userId', '$groupId')";

        if($mysqli->query($query) == TRUE){
          header("location: ../groups.php");
        } else {
          print "Failed to create new group membership.<br>Please contact the system administrator.";
        }
      } else {
        print "Failed to fetch group's id from the database.<br>Please contact the system administrator.";
      }
    } else {
      print "Failed to fetch user's id from the database.<br>Please contact the system administrator.";
    }
  } else {
    print "Failed to add the new group to the database.<br>Please contact the system administrator.";
  }
?>