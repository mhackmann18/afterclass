<?php
  function createGroup($userId, $name, $description){
    $mysqli = connectDB();

    $name = $mysqli->real_escape_string($name);
    $description = $mysqli->real_escape_string($description);
    
    // Stop SQL Injections
    $groupName = $mysqli->real_escape_string($name);
    $description = $mysqli->real_escape_string($description);

    // Inserts new group into organizations table
    $query = "INSERT INTO organizations (groupName, groupDescription, addDate) VALUES ('$groupName', '$description', now())";

    // If group was successfully added to database...
    if($mysqli->query($query)){

      // Get the id of the newly created group
      $query = "SELECT id FROM organizations WHERE groupName = '$groupName'";
      $result = $mysqli->query($query);

      if($result){
        $row = mysqli_fetch_assoc($result);
        $groupId = $row['id'];
        $result->close();

        // Add a new membership to the database
        $query = "INSERT INTO groupMemberships (userid, groupid) VALUES ('$userId', '$groupId')";

        if($mysqli->query($query)){
          $mysqli->close();
          $result->close();
          return TRUE;
        }
      }
    } 

    $mysqli->close();
    return FALSE;
  }
?>