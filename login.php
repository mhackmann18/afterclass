<?php
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
  <link rel="stylesheet" href="./css/mainStyles.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Log In | AfterClass MU</title>
</head>
<body class="flex-center">
  <div class="log-in-box">
    <!-- Container for header and MU logo -->
    <div>
      <h1>AfterClass</h1>
      <img class="img-small" src="./img/mu-letters.png" alt="mu-logo">
    </div>
    <!-- Login form -->
    <form autocomplete="off">
      <input id="username" type="text" name="user-name" placeholder="username">
      <input id="password" type="password" name="password" placeholder="password">
      <button>Log In</button>
    </form>
    <p id="create-account">Don't have an account? <a href="./createAccount.php">Create an account</a></p>
    <!-- Error message -->
    <p class="error-msg"></p>
  </div>

  <script src="./javascript/loginPage.js?v=2"></script>
</body>
</html>