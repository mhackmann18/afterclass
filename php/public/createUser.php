<?php
  if(session_id() == "")
    session_start();
  require "../../config/db.conf";

  // If database connection fails
  if($mysqli->connect_error){
    exit("Failed to connect to database.");
  } 

  // Makes sure there is no user logged in
  if(isset($_SESSION['username'])){
    header("location: ../../index.php");
    exit;
  }

  // Form values
  $email = empty($_POST['email']) ? '' : $_POST['email'];
  $name = empty($_POST['name']) ? '' : $_POST['name'];
  $major = empty($_POST['major']) ? '' : $_POST['major'];
  $username = empty($_POST['username']) ? '' : $_POST['username'];
  $password = empty($_POST['password']) ? '' : $_POST['password'];

  // Stop SQL Injections
  $username = $mysqli->real_escape_string($username);
  $password = $mysqli->real_escape_string($password);
  $email = $mysqli->real_escape_string($email);
  $name = $mysqli->real_escape_string($name);
  $major = $mysqli->real_escape_string($major);

  $query = "INSERT INTO users (fullName, email, username, userPassword, major, addDate) VALUES ('$name', '$email', '$username', sha1('$password'), '$major', now())";

  // If user was successfully added to database, set profile image to default, create login cookie and redirect to index.php
  if($mysqli->query($query)){

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userid = $row['id'];
      $query = "INSERT INTO profileimg (userid, status) VALUES ('$userid', 1)";
      $mysqli->query($query);
    }

    $_SESSION['username'] = $username;
    header("location: ../../index.php");
  } 
  // If there was an issue adding the user to database, display error and redirect to createAccount.php
  else {
    $query = "SELECT id FROM users WHERE username = '$username'";
    // If username was already taken
    if($mysqli->query($query)->num_rows == 1){
      $error = "Oops! That username is already taken.<br>Please enter a new username.";
    // If email was already taken
    } else {
      $error = "An account already exists using that email.<br>Please use another email.";
    }
    require "/var/www/html/afterclass/createAccount.php";
  }

  $mysqli->close();
?>