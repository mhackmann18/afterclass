<?php
  if(!isset($_COOKIE['userid'])){
    header("location: login.html");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="css/mainStyles.css">
  <title>AfterClass | MU</title>
</head>
<body>
  <!-- Main Navbar -->
  <nav id="main-navbar">
    <div class="left-nav">
      <a href="./index.php">AfterClass</a> <!-- Change to php -->
      <img class="img-small" src="img/mu-letters.png" alt="mu-logo">
    </div>
    <div class="right-nav">
      <ul>
        <!-- Navbar Links -->
        <li><a href="#">Discover</a></li>
        <li><a href="#">Groups</a></li>
        <li><a href="#">Messages</a></li>
      </ul>
      <!-- Navbar User Icon -->
      <i id="user-btn" class="fas fa-2x fa-user-circle"></i>
      <div id="user-menu">
        <ul>
          <li>Sign Out</li>
        </ul>
      </div>
    </div>
  </nav>

  <script src="javascript/navbar.js"></script>
</body>
</html>