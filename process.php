<?php 
  $action = empty($_POST['action']) ? false : $_POST['action'];

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
  } else {
    print "You submitted an invalid action";
  }

  function handle_login(){
    // Pull username and password strings out of post body
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    
    // Connect to database
    require_once("../config/db.conf");

    // If database connection fails
    if ($mysqli->connect_error){
      print "There was a problem establishing a database connection.";
      exit;
    }

    print "Connected Successfully.";
    exit;

    // Get all users from the users table in database
    $query = "SELECT * FROM users";
    $result = $mysqli->query($query);

    // See if the users' credentials match any registered users
    while($row = $result->fetch_assoc()){
      print "Row 1";
    }

    // Only set the cookie if username and password are correct
    if($username == 'test' && $password = 'pass'){
      setcookie('userid', $username, time() + 1800, "/");
      $response = "Logged in successfully";
    } else {
      $response = "Invalid credentials";
    }
    print $response;

    $result->close();
    $mysqli->close();
  }
?>