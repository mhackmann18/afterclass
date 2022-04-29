<!-- Got some help here from https://www.youtube.com/watch?v=y4GxrIa7MiE -->

<?php
  function uploadProfileImg($id, $file){
    $mysqli = connectDB();

    // File data
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Extracts the file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // All of the allowed file extensions
    $allowed = array('jpg');

    if(in_array($fileActualExt, $allowed)){
      // If there was no error uploading file
      if($fileError === 0){
        if($fileSize < 1000000) {
          $fileNameNew = "profile".$id.".".$fileActualExt;
          $fileDestination = "/var/www/html/projects/afterclass/uploads/".$fileNameNew;
          if(copy($fileTmpName, $fileDestination)){
            $query = "UPDATE users SET profile_image=0 WHERE id='$id'";
            $mysqli->query($query);
            header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header("location: /projects/afterclass/yourProfile.php?uploadsuccess");
          } else {
            $error = "There was an error saving your image to the server.<br>Please contact the system administrator.";
          }
        } else {
          $error = "File size must be less than 1 MB.";
        }
      } else {
        $error = "There was an issue uploading your file.<br>Error: $fileError";
      }
    } else {
      $error = "File extension must be .jpg.<br>Check that file extension is .jpg and not .jpeg.";
    }
    require "/var/www/html/projects/afterclass/yourProfile.php";
    $mysqli->close();
  }
?>