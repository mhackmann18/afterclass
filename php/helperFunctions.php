<?php
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
?>