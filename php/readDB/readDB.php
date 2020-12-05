<?php
  // Returns true if the user with the passed id has uploaded a profile image and false if not
  function hasProfileImg($userId){
    $mysqli = connectDB();
    
    $result = $mysqli->query("SELECT * FROM profileimg WHERE userid = $userId");

    $row = mysqli_fetch_assoc($result);

    if($row['status'] == 0){
      $mysqli->close();
      return TRUE; 
    } else {
      $mysqli->close();
      return FALSE;
    }
  }
  
  function isEmailValid($email){
    $mysqli = connectDB();

    $query = "SELECT id FROM users WHERE email = '$email'";

    if($mysqli->query($query)->num_rows == 1){
      $mysqli->close();
      return FALSE;
    } else {
      $mysqli->close();
      return TRUE;
    }
  }

  function isUsernameValid(){
    $mysqli = connectDB();

    $username = empty($_POST['username']) ? '' : $_POST['username'];

    $query = "SELECT id FROM users WHERE username = '$username'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }

  function isGroupNameValid(){
    $mysqli = connectDB();

    $groupName = empty($_POST['name']) ? '' : $_POST['name'];

    $query = "SELECT * FROM organizations WHERE groupName = '$groupName'";

    if($mysqli->query($query)->num_rows == 1){
      print FALSE;
    } else {
      print TRUE;
    }

    $mysqli->close();
  }
?>