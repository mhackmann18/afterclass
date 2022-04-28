<?php 
  if(session_id() == "")
    session_start();
    
  // HTTPS Redirect
  // require "./php/redirect.php";

  // Connect to db, check for error
  require_once "./config/db.conf";
  if($mysqli->connect_error)
    exit("There was an issue connecting to the database.<br>Please try again later.");

  // Make sure the user is logged in, redirect if not
  if(!isset($_SESSION['username']))
    header("location: login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="/afterclass/css/mainStyles.css">
  <title>AfterClass | Profile</title>
</head>
<body>
  <?php require '/var/www/html/afterclass/navbar.php'; ?>

  <div class="container">
    <div id="profile-left">
      <div id="profile-page-image-container" class="fill">
        <img id="profile-page-img" alt='blank-profile-image'>
      </div>
      <h1 id="profile-header" class="txt-bold"></h1>
      <ul id="profile-page-info">
        <li></li>
        <li>Username: <span id='username'></span></li>
        <li>Major: <span id='major'></span></li>
      </ul>
    </div>
    <div id="profile-right">
      <h1 id="profile-page-bio-header">Bio</h1>
      <p id="profile-bio"></p>
      <h1 id="profile-page-groups-header">Groups</h1>
      <ul id="profile-page-groups"></ul>
    </div>
  </div>

  <script type="text/javascript">
    const userId = <?=$_GET['userid']?>;
  </script>
  <script src="/afterclass/javascript/profile.js"></script>
</body>
</html>