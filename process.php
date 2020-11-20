<?php 
  $action = empty($_POST['action']) ? false : $_POST['action'];

  if($action == 'login'){
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    // Only set the cookie if username and password are correct
    if($username == 'test' && $password = 'pass'){
      setcookie('userid', $username, time() + 1800, "/");
      $response = "Logged in successfully";
    } else {
      $response = "Invalid credentials";
    }
    print $response;
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
?>