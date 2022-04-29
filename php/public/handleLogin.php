<?php
  // Connect to DB
  require "../../config/db.conf";

  if($mysqli->connect_error){
    exit($mysqli->connect_error);
  } 

  $username = empty($_POST['username']) ? '' : $_POST['username'];
  $password = empty($_POST['password']) ? '' : $_POST['password'];

  // Stop SQL injections
  $username = $mysqli->real_escape_string($username);
  $password = $mysqli->real_escape_string($password);
  
  // Get all users from the users table in database
  $query = "SELECT id FROM users WHERE username = '$username' AND userPassword = sha1('$password')";
  
  $result = $mysqli->query($query);

  if($result){
    if($result->num_rows == 1){
      session_start();
      $_SESSION['username'] = $username;
      print "Logged in successfully";
    } else {
      print("Incorrect username or password.<br>Please double-check your username and password.");
    }
  } else {
    print("Error. Please contact the system administrator");
  }

  $result->close();
  $mysqli->close();
?>