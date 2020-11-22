<?php
  $main_dir = $_SERVER["DOCUMENT_ROOT"] . "/afterclass";

  if(isset($_POST['submit'])){
    $file = $_FILES['file'];

    // file data
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    // Gets the file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // All of the allowed file extensions
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if(in_array($fileActualExt, $allowed)){
      // If there was no error uploading file
      if($fileError === 0){
        if($fileSize < 1000000) {
          $fileNameNew = uniqid('', true) . "." . $fileActualExt;
          $fileDestination = $main_dir . "/" . "uploads/" . $fileNameNew;
          if(copy($fileTmpName, $fileDestination)){
            header("location: ../index.php?uploadsuccess");
          } else {
            print "error";
          }
        } else {
          print "Your file is too big!";
        }
      } else {
        print "There was an issue uploading your file.";
      }
    } else {
      print "You cannot upload files of this type!";
    }
  }
?>