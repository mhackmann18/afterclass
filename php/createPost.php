<?php
  if(!isset($_COOKIE['userid']))
    header("location: ../login.php");

  require_once $_SERVER["DOCUMENT_ROOT"]."/afterclass/config/db.conf";
  $main_dir = $_SERVER["DOCUMENT_ROOT"]."/afterclass";

  // Get the current user's id

  $username = $_COOKIE['userid'];

  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = $mysqli->query($query);
  if(!$result){
    print "Error. Please contact the system administrator.";
    $mysqli->close();
    exit; 
  }
  if($result->num_rows == 1){
    $row = mysqli_fetch_assoc($result);
    $userId = $row['id'];
  }

  // Get POST body data
  $postGroupId = $_POST["group-name"];
  $postText = $_POST["post-body"];
  $postLink = empty($_POST["yt-link"]) ? "" : $_POST["yt-link"];
  $fileName = $_FILES['file']['name'];

  // If a youtube video is being uploaded
  if($postLink){
    $embedLink = explode('v=', $postLink);

    $embedLink = "https://www.youtube.com/embed/".end($embedLink);

    $query = "INSERT INTO userPosts (userid, groupid, postText, youtubeLink, addDate) VALUES ($userId, $postGroupId, '$postText', '$embedLink', now())";

    if($mysqli->query($query)){
      print "Youtube video post successfully uploaded.<br>";
    } else {
      print "There was an error uploading the youtube post.";
    }
  } 
  // If a file is being uploaded
  else if($fileName) {
    // File data
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    // Extracts the file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // All of the allowed file extensions
    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){
      // If there was no error uploading file
      if($fileError === 0){
        if($fileSize < 1000000) {
          $fileNameNew = uniqid().".".$fileActualExt;
          $fileDestination = $main_dir."/"."uploads/".$fileNameNew;
          if(copy($fileTmpName, $fileDestination)){
            $query = "INSERT INTO userPosts (userid, groupid, postText, fileName, addDate) VALUES ($userId, $postGroupId, '$postText', '$fileNameNew', now())";
            if($mysqli->query($query)){
              print "Image post was successfully uploaded.";
            } else {
              print "There was an error uploading the image post.";
            }
          } else {
            print "There was an error saving your image to the server.<br>Please contact the system administrator.";
          }
        } else {
          print "File size must be less than 1 MB.";
        }
      } else {
        print "There was an issue uploading your file.<br>Error: $fileError";
      }
    } else {
      print "File extension is invalid";
    }
  } 
  // If no media is being uploaded
  else {
    $query = "INSERT INTO userPosts (userid, groupid, postText, addDate) VALUES ($userId, $postGroupId, '$postText', now())";

    if($mysqli->query($query)){
      print "Post with no media was successfully uploaded.<br>";
    } else {
      print "There was an error uploading the no media post.";
    }
  }

  //After post is uploaded, update number of posts for the corresponding group

  $mysqli->query("UPDATE organizations SET numPosts = numPosts + 1 WHERE id = $postGroupId");

  $mysqli->close();
?>