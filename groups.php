<?php
  // Redirect to login page if no login cookie exists
  if(!isset($_COOKIE['userid']))
    header("location: login.php");

  // If user is logged in, refresh the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");
  
  // HTTPS Redirect
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/REDIRECT.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>Groups | AfterClass MU</title>
</head>
<body>
  <?php require 'navbar.php'; ?>

  <form action="./php/UPLOAD.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit" name="submit">Upload Image</button>
  </form>
</body>
</html>