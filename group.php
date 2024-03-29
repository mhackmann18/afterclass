<?php 
  if(session_id() == "")
    session_start();

  // Make sure the user is logged in, redirect if not
  if(!isset($_SESSION['username']))
    header("location: login.php");

  $groupId = $_GET["groupid"];
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
  <title>AfterClass | Group Page</title>
</head>
<body>
  <?php require 'navbar.php'; ?>
  <div class="container">
    <div id="feed-page-header" class="group-page-info">
      <h1></h1>
      <h3>Description</h3>
      <div class="group-page-desc"></div>
      <div>
        <ul>
          <li>Members: <span id="feed-page-members"></span></li>
          <li>Created: <span id="feed-page-date"></span></li>
          <li>Posts: <span id="feed-page-posts"></span></li>
        </ul>
        <div>
          <a href="./newPost.php" class="btn btn-grey">Post</a>
          <button id="feed-page-leave-btn" class="btn-gold leave-group-btn">Leave</button>
        </div>
      </div>
    </div>
    <h1 id="feed-page-feed-header">Activity in <span id="feed-page-group-name"></span></h1>
    <div id="feed">
    </div>
    <div class="flex-center"><img id="spinner" class="margin-top-lrg" src="./img/spinner.gif" alt="loading..."></div>
  </div>
  
  <script type="text/javascript">
    const groupId = <?php print $groupId ?>;
  </script>
  <script type="module" src="/projects/afterclass/javascript/group.js"></script>
</body>
</html>