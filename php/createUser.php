<?php
  $main_dir = $_SERVER["DOCUMENT_ROOT"] . "/afterclass";

  // Makes sure there is no user logged in
  if(isset($_COOKIE['userid'])){
    header("location: ../index.php");
    exit;
  }

  // Form values
  $email = empty($_POST['email']) ? '' : $_POST['email'];
  $name = empty($_POST['name']) ? '' : $_POST['name'];
  $major = empty($_POST['major']) ? '' : $_POST['major'];
  $username = empty($_POST['username']) ? '' : $_POST['username'];
  $password = empty($_POST['password']) ? '' : $_POST['password'];

  // Connect to SQL database
  require_once($main_dir . "/config/db.conf");

  // Display error and exit if connection faied
  if($mysqli->connect_error){
    $error = "There was an issue connecting to the SQL database.<br>Please try again later.";
    require $main_dir . "/createAccount.php";
    exit;
  }

  // Stop SQL Injections
  $username = $mysqli->real_escape_string($username);
  $password = $mysqli->real_escape_string($password);
  $email = $mysqli->real_escape_string($email);
  $name = $mysqli->real_escape_string($name);
  $major = $mysqli->real_escape_string($major);

  $query = "INSERT INTO users (fullName, email, username, userPassword, major, addDate) VALUES ('$name', '$email', '$username', sha1('$password'), '$major', now())";

  // If user was successfully added to database, set profile image to default, create login cookie and redirect to index.php
  if($mysqli->query($query) === TRUE){

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);

    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $userid = $row['id'];
      $query = "INSERT INTO profileimg (userid, status) VALUES ('$userid', 1)";
      $mysqli->query($query);
    }

    setcookie('userid', $username, time() + 1800, "/");
    header("location: ../index.php");
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
    require $main_dir . '/createAccount.php';
  }

  $mysqli->close();
?>