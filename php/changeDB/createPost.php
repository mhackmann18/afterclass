<?php
  function createPost($userId, $postGroupId, $postText, $postLink, $file){
    $mysqli = connectDB();
    
    $postText = $mysqli->real_escape_string($postText);

    // If a youtube video is being uploaded
    if($postLink){
      // Modify the passed in youtube link to be an embed link
      $embedLink = explode('v=', $postLink);
      $embedLink = "https://www.youtube.com/embed/".end($embedLink);

      $query = "INSERT INTO userPosts (userid, groupid, postText, youtubeLink, addDate) VALUES ($userId, $postGroupId, '$postText', '$embedLink', now())";

      if(!$mysqli->query($query)){
        exit("There was an error uploading the youtube post.");
      }
    } 
    // If a file is being uploaded
    else if($file['name']){
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileError = $file['error'];
      $fileType = $file['type'];

      // Extracts the file extension
      $fileExt = explode('.', $fileName);
      $fileActualExt = strtolower(end($fileExt));

      // All of the allowed file extensions
      $allowed = array('jpg', 'jpeg', 'png', 'pdf');

      if(in_array($fileActualExt, $allowed)){
        if($fileError === 0){
          if($fileSize < 1000000) {
            $fileNameNew = uniqid().".".$fileActualExt;
            $fileDestination = "/var/www/html/afterclass/uploads/".$fileNameNew;
            if(copy($fileTmpName, $fileDestination)){
              $query = "INSERT INTO userPosts (userid, groupid, postText, fileName, addDate) VALUES ($userId, $postGroupId, '$postText', '$fileNameNew', now())";
              if(!$mysqli->query($query)){
                exit("There was an error uploading the image post.");
              }
            } else {
              exit("There was an error saving your image to the server.<br>Please contact the system administrator.");
            }
          } else {
            exit("File size must be less than 1 MB.");
          }
        } else {
          exit("There was an issue uploading your file.<br>Error: $fileError");
        }
      } else {
        exit("File extension is invalid");
      }
    } 
    // If no media is being uploaded
    else {
      $query = "INSERT INTO userPosts (userid, groupid, postText, addDate) VALUES ($userId, $postGroupId, '$postText', now())";

      if(!$mysqli->query($query)){
        exit($mysqli->error);
      }
    }

    // After post is uploaded, update number of posts for the corresponding group
    $mysqli->query("UPDATE organizations SET numPosts = numPosts + 1 WHERE id = $postGroupId");

    header("location: ../group.php?groupid=$postGroupId");

    $mysqli->close();
  }
?>