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
  <title>AfterClass</title>
</head>
<body>
  <!-- Main Navbar -->
  <?php require 'navbar.php'; ?>
  <div class="container">
    <div id="group-feed">

      <div class="post margin-top-mid">
        <div class="post-head">
          <div class="profile-img-container">
            <img class="profile-img" src="/afterclass/img/blank-profile.jpg" alt="profile image">
          </div>
          <p class="who-when">test posted on 12/03/20</p>
        </div>
        <p class="post-text">Testing the update posts query</p>
        <div class="post-media"></div>
      </div>

    </div>
  </div>

  <script src="./javascript/index.js"></script>
</body>
</html>