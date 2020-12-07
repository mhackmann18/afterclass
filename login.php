<?php 
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/redirect.php"; 
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