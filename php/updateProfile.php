<?php
  // Make sure the user is logged in, redirect if not
  if(!isset($_COOKIE['userid']))
    header("location: login.php");
  // Refresh the time on the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");
  // Connect to MYSQL db, check for error
  require_once $_SERVER["DOCUMENT_ROOT"] . "/afterclass/config/db.conf";
  if($mysqli->connect_error){
    print "There was an issue connecting to the database.<br>Please try again later.";
    exit;
  }

  $newUsername = $_POST['username'];
  $newMajor = $_POST['major'];
  $newBio = $_POST['bio'];

  $query = "UPDATE users SET username = '$newUsername', major = '$newMajor', bio = '$newBio' WHERE username = '$username'";
  $result = $mysqli->query($query);
  if(!$result){
    print "Error. Please contact the system administrator.";
    $mysqli->close();
    exit; 
  }

  setcookie('userid', $newUsername, time() + 1800, "/");

  $mysqli->close();
?>