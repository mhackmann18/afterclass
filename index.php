<?php
  if(!isset($_COOKIE['userid'])){
    header("location: login.php");
  }

  // if($_SERVER['HTTPS'] !== 'on') {
  //   $redirect= "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  //   header("location: $redirect");
  // }

  header("Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0, s-maxage=0");
  header("Pragma:no-cache");
  header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>AfterClass | MU</title>
</head>
<body>
  <!-- Main Navbar -->
  <?php
    require 'navbar.php';
  ?>
  <script src="./javascript/navbar.js"></script>
</body>
</html>