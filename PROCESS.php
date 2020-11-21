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
    // Check if email address exist in database yet.
  } else if($action == 'check-email'){
    check_email();

  } else if($action == 'check-username'){
    check_username();

  } else {
    print "You submitted an invalid action";
  }

  function handle_login(){
    // Pull username and password strings out of post body
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    
    // Connect to database
    require_once("./config/db.conf");

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
    require_once('config/db.conf');

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
    require_once('config/db.conf');

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
?>