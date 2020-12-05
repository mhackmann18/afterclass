<?php
  // Returns an array of associative arrays that each represent a post from the group with the passed id
  function getPostsByGroupId($groupId){
    $userId = getLoggedInUserId();

    if(isGroupMember($userId, $groupId)){
      $mysqli = connectDB();

      $query = "SELECT * FROM userPosts WHERE groupid = $groupId ORDER BY addDate DESC";

      $result = $mysqli->query($query);

      $posts = array();

      while($row = mysqli_fetch_assoc($result)) {
        $post = array(
          'userId' => $row['userid'],
          'text' => $row['postText'],
          'link' => $row['youtubeLink'],
          'fileName' => $row['fileName'],
          'dateCreated' => $row['addDate']
        );
        array_push($posts, $post);
      }

      $mysqli->close();
      $result->close();

      return $posts;
    } else {
      print "Only logged in members of this group are allowed access to its posts<br>";
    }
  }

  function getGroupDataById($groupId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    $query = "SELECT * FROM organizations WHERE id = $groupId";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_assoc($result);

    $dateCreated = $row['addDate'];
    $time = strtotime($dateCreated);

    $groupInfo = array(
      'name' => $row['groupName'],
      'description' => $row['groupDescription'],
      'numMembers' => $row['members'],
      'numPosts' => $row['numPosts'],
      'dateCreated' => date("m/d/y", $time)
    );
    
    $mysqli->close();
    return $groupInfo;
  }

  function getUserNameById($userId){
    $mysqli = connectDB();
    
    $result = $mysqli->query("SELECT * FROM users WHERE id = $userId");

    $row = mysqli_fetch_assoc($result);

    return $row['username'];
  }

  function getUserGroupsIds($userId){
    redirectIfNotLoggedIn();

    $mysqli = connectDB();

    $query = "SELECT groupid FROM groupMemberships WHERE userid = $userId ORDER BY groupid ASC";

    $result = $mysqli->query($query);

    $groupIds = array();

    while($row = mysqli_fetch_assoc($result)) {
      $groupIds[] = $row['groupid'];
    }

    return $groupIds;
    
    $mysqli->close();
  }

  function getFeedPostsByUserId($userId){
    redirectIfNotLoggedIn();
    $groupIds = getUserGroupsIds($userId);
    
    $firstGroupId = array_pop($groupIds);

    $query = "SELECT * FROM userPosts WHERE groupid = ".$firstGroupId;

    foreach($groupIds as $groupId){
      $query .= " OR groupid = ".$groupId;
    }
    unset($groupId);

    $query .= " ORDER BY addDate DESC";

    $mysqli = connectDB();

    $result = $mysqli->query($query);

    $posts = array();

    while($row = mysqli_fetch_assoc($result)) {
      $post = array(
        'userId' => $row['userid'],
        'groupId' => $row['groupid'],
        'text' => $row['postText'],
        'link' => $row['youtubeLink'],
        'fileName' => $row['fileName'],
        'dateCreated' => $row['addDate']
      );
      array_push($posts, $post);
    }

    return $posts;

    $mysqli->close();
  }
  // Returns an array of of ids of all the groups the user with the passed in id is not currently a member of
  function getNoMembershipIds($userId){
    redirectIfNotLoggedIn();

    $userGroupIds = getUserGroupsIds($userId);

    $mysqli = connectDB();

    $nonMemberIds = array();

    // If the user is in at least one group...
    if(count($userGroupIds)){

      // Build a query string that selects all groups the user is not currently a member of
      $mustNotEqual = "";
      foreach($userGroupIds as &$id)
        $mustNotEqual .= " AND id != ".$id;

      $query = "SELECT id FROM organizations WHERE id != -1".$mustNotEqual;
      $result = $mysqli->query($query);

      while($row = mysqli_fetch_assoc($result)) 
        $nonMemberIds[] = $row['id'];

    } else {
      $query = "SELECT id FROM organizations";
      $result = $mysqli->query($query);

      while($row = mysqli_fetch_assoc($result))
        $nonMemberIds[] = $row['id'];
    }

    $mysqli->close();
    return $nonMemberIds;
  }

  function getUserDataById($userId){
    redirectIfNotLoggedIn();
    $mysqli = connectDB();

    $query = "SELECT * FROM users WHERE id = $userId";
    $result = $mysqli->query($query);

    $row = mysqli_fetch_assoc($result);

    $userData = array(
      'name' => $row['fullName'],
      'email' => $row['email'],
      'username' => $row['username'],
      'major' => $row['major'],
      'bio' => $row['bio'],
      'dateJoined' => $row['addDate']
    );

    $mysqli->close();
    $result->close();
    return $userData;
  }
?>