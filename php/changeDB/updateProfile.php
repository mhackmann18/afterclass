<?php
  function updateUserProfile($userId, $newUsername, $newMajor, $newBio){
    $mysqli = connectDB();

    $newUsername = $mysqli->real_escape_string($newUsername);
    $newMajor = $mysqli->real_escape_string($newMajor);
    $newBio = $mysqli->real_escape_string($newBio);

    $query = "UPDATE users SET username = '$newUsername', major = '$newMajor', bio = '$newBio' WHERE id = '$userId'";

    if(!$mysqli->query($query)){
      $mysqli->close();
      exit("Error. Please contact the system administrator.");
    }

    setcookie('userid', $newUsername, time() + 1800, "/");

    $mysqli->close();
  }
?>