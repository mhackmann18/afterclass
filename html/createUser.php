<?php
  if(isset($_COOKIE['userid'])){
    header("location: ../index.php");
    exit;
  }

  $action = empty($_POST['action']) ? '' : $_POST['action'];

  if($action == 'create_user'){
    create_user();
  } 

  function create_user(){
    $email = empty($_POST['email']) ? '' : $_POST['email'];
    $name = empty($_POST['name']) ? '' : $_POST['name'];
    $major = empty($_POST['major']) ? '' : $_POST['major'];
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];

    require_once('../config/db.conf');

    if($mysqli->connect_error){
      require "createAccount.html";
      echo "There was an issue connecting to the database";
      exit;
    }

    // Stop SQL Injections
    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);
    $email = $mysqli->real_escape_string($email);
    $name = $mysqli->real_escape_string($name);
    $major = $mysqli->real_escape_string($major);

    $query = "INSERT INTO users (fullName, email, username, userPassword, major, addDate) VALUES ('$name', '$email', '$username', sha1('$password'), '$major', now())";

    // If user insertion was successful
    if($mysqli->query($query) === TRUE){
      setcookie('userid', $username, time() + 1800, "/");
      header('location: ../index.php');
    } else {
      header('location: createAccount.html');
      echo 'Insert Error: ' . $query . '<br>' . $mysqli->error;
    }

    $mysqli->close();
    exit;
  }
?>