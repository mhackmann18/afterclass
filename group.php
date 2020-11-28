<?php 
  // HTTPS Redirect
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/REDIRECT.php";
  // Make sure the user is logged in, redirect if not
  if(!isset($_COOKIE['userid']))
    header("location: login.php");
  // Refresh the time on the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");

  $groupId = $_GET["groupid"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>AfterClass | Join Groups</title>
</head>
<body>
  <?php require 'navbar.php'; ?>
  <div class="container">
    <?php
      print $groupId;
    ?>
  </div>

  <!-- <script src="/afterclass/javascript/group.php"></script> -->
</body>
</html>