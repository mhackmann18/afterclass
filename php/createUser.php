<?php
  if(isset($_COOKIE['userid'])){
    header("location: index.php");
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

    echo $email;
    echo $name;
    echo $major;
    echo $username;
    echo $password;
  }
?>