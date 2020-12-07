<?php
  // Redirect to login page if no login cookie exists
  if(!isset($_COOKIE['userid']))
    header("location: login.php");

  // If user is logged in, refresh the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");

  // HTTPS Redirect
  require "/var/www/html/afterclass/php/redirect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://formden.com/static/assets/demos/bootstrap-iso/bootstrap-iso/bootstrap-iso.css">
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>AfterClass</title>
</head>
<body>
  <!-- Main Navbar -->
  <?php require 'navbar.php'; ?>
  <div class="container">
    <div id="feed"></div>

    <div class="flex-center"><img id="spinner" class="margin-top-lrg" src="./img/spinner.gif" alt="loading..."></div>
    
    <div class="center-on-page">
      <div class="middle">
        <div id="home-no-posts-msg" class="txt-lightgrey">
          <h2>No posts to show</h2>
          <a href="./findGroups.php" class="txt-lightgrey underline">Join a group</a> 
          to get in on the action
        </div>
      </div>
    </div>
    
  </div>

  <script type="module" src="./javascript/index.js"></script>
</body>
</html>