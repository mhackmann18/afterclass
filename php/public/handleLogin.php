<?php
  require "../../config/db.conf";

  // If database connection fails
  if($mysqli->connect_error){
    exit("Failed to connect to database.");
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
      setcookie('userid', $username, time() + 1800, "/");
      $_COOKIE['userid'] = $username;
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