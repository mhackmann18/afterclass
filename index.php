<?php
  // Redirect to login page if no login cookie exists
  if(!isset($_COOKIE['userid']))
    header("location: login.php");
  
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
</body>
</html>