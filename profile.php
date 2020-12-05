<?php 
  // HTTPS Redirect
  require "./php/redirect.php";

  // Connect to db, check for error
  require_once "./config/db.conf";
  if($mysqli->connect_error)
    exit("There was an issue connecting to the database.<br>Please try again later.");

  // Make sure the user is logged in, redirect if not
  if(!isset($_COOKIE['userid']))
    header("location: login.php");

  // Refresh the time on the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");
?>
<!-- HANDLE WHEN THE USERID DOESNT EXIST -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="/afterclass/css/mainStyles.css">
  <title><?=$username?> | AfterClass MU</title>
</head>
<body>
  <?php require '/var/www/html/afterclass/navbar.php'; ?>

  <div class="container">
    <div id="profile-left">
      <div id="profile-page-image-container">
        <img id="profile-page-img" alt='blank-profile-image'>
      </div>
      <h1 id="profile-header" class="txt-bold">Joh DOe</h1>
      <ul id="profile-page-info">
        <li>Email: msnsh@sgsyg</li>
        <li>Username: <span id='username'>test</span></li>
        <li>Major: <span id='major'>CS</span></li>
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