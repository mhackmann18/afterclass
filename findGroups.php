<?php 
  // HTTPS Redirect
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/redirect.php";
  // Make sure the user is logged in, redirect if not
  if(!isset($_COOKIE['userid']))
    header("location: login.php");
  // Refresh the time on the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");
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
  <div class="container" id="find-groups-page">
    <div class="space-between">
      <h1 id="find-groups-header" class="large-header">Join Groups</h1>
      <div id="find-groups-btns">
        <button id="clear-search-btn">Clear Search</button>
        <div class="searchbar">
          <input placeholder="Search Groups" type="text">
          <button><i class="fas fa-search"></i></button>
        </div>
      </div>
    </div>

    <section id="groups">
    <!-- Will be populated with group cards -->
    </section>
  </div>

  <script src="./javascript/findGroups.js"></script>
</body>
</html>