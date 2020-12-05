<?php
  function deleteGroupById($groupId){
    $mysqli = connectDB();

    $query = "DELETE FROM organizations WHERE id = $groupId";

    if($mysqli->query($query) && deletePostsByGroupId($groupId)){
      $mysqli->close();
      return TRUE;
    } else {
      $mysqli->close();
      return FALSE;
    }
  }

  function deletePostsByGroupId($groupId){
    $mysqli = connectDB();

    $query = "DELETE FROM userPosts WHERE groupid = $groupId";

    if($mysqli->query($query)){
      $mysqli->close();
      return TRUE;
    } else {
      $mysqli->close();
      return FALSE;
    }
  }

  function addUserToGroup($userId, $groupId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    // Add a new membership to the database
    $query = "INSERT INTO groupMemberships (userid, groupid) VALUES ($userId, $groupId)";
    $mysqli->query($query);

    // Update the member count in the joined group
    $query = "UPDATE organizations SET members = members + 1 WHERE id = $groupId";
    $mysqli->query($query);

    $mysqli->close();
  }

  function removeUserFromGroup($userId, $groupId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    // Decrement the number of members of the group
    $query = "UPDATE organizations SET members = members - 1 WHERE id = $groupId";
    $mysqli->query($query);

    // Remove the user's membership from memberships table
    $query = "DELETE FROM groupMemberships WHERE userid = $userId AND groupid = $groupId";
    $result = $mysqli->query($query);

    // Check the no. of members in group, and delete it if none are left
    $query = "SELECT * FROM organizations WHERE id = $groupId";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_assoc($result);
    $numMembers = $row['members'];

    if($numMembers == 0){
      if(deleteGroupById($groupId)){
        $result->close();
        $mysqli->close();
        return TRUE;
      } else {
        $result->close();
        $mysqli->close();
        return FALSE;
      }
    }

    $result->close();
    $mysqli->close();
    return TRUE;
  }
?>