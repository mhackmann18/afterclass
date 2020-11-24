<!-- Got some help here from https://www.youtube.com/watch?v=y4GxrIa7MiE -->

<?php
  // Make sure the user is logged in, redirect if not redirect to login
  if(!isset($_COOKIE['userid']))
    header("location: ../login.php");

  // Makes sure submit button was actually clicked
  if(isset($_POST['submit'])){

    // Connect to db
    require_once $_SERVER["DOCUMENT_ROOT"]."/afterclass/config/db.conf";
    $main_dir = $_SERVER["DOCUMENT_ROOT"]."/afterclass";

    // Get current user's id
    $username = $_COOKIE["userid"];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if(!$result){
      print "Error. Please contact the system administrator.";
      $mysqli->close();
      exit; 
    }
    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      $id = $row['id'];
    }

    // File data
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

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
          $fileDestination = $main_dir."/"."uploads/".$fileNameNew;
          if(copy($fileTmpName, $fileDestination)){
            $query = "UPDATE profileimg SET status=0 WHERE userid='$id'";
            $mysqli->query($query);
            header("location: /afterclass/profile.php?uploadsuccess");
            // Hard refresh profile page
            header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
            header("Pragma: no-cache"); // HTTP 1.0.
            header("Expires: 0");
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
  }
  require $main_dir."/profile.php";
  $mysqli->close();
?>