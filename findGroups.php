<?php 
  if(session_id() == "")
    session_start();
    
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
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>AfterClass | Join Groups</title>
</head>
<body>
  <?php require 'navbar.php'; ?>
  <div class="container" id="find-groups-page">
    <div class="space-between">
      <h1 id="find-groups-header" class="large-header">Join Groups</h1>
    </div>

    <section id="groups">
    <!-- Will be populated with group cards -->
    </section>

    <div class="flex-center"><img src="./img/spinner.gif" alt="loading..." class="margin-top-lrg" id="spinner"></div>

    <div class="center-on-page">
      <div class="middle">
        <div id="find-groups-no-groups" class="txt-lightgrey">
          <h2>No groups to show</h2>
        </div>
      </div>
    </div>
  </div>

  <script src="./javascript/findGroups.js"></script>
</body>
</html>