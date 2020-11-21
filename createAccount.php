<!-- HTTPS Redirect -->
<?php require 'REDIRECT.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>Log In | AfterClass MU</title>
</head>
<body class="flex-center">
  <div id="create-user-box" class="log-in-box">

    <h1>Create New Account</h1>
    <!-- Sign up form -->
    <form id="create-user-form" action="CREATEUSER.php" method="POST" autocomplete="off"> <!--Checkup on that action-->
      <!-- <input type="hidden" name="action" value="create_user"> -->
      <input id="email" type="email" name="email" placeholder="um email" required>
      <input id="name" type="text" name="name" placeholder="full name" required>
      <input id="major" type="text" name="major" placeholder="major" required>
      <input id="username" type="text" name="username" placeholder="username" required>
      <input id="password" type="password" name="password" placeholder="password" required>
      <button>Create Account</button>
    </form>

    <p id="create-account">Already have an account? <a href="./login.php">Log In</a></p>

    <!-- Will display error message from CREATEUSER.php if the operation failed. -->
    <?php
      if($error){
        print "<p class='create-account-error'>$error</p>";
      } else {
        print "<p class='create-account-error'></p>";
      }
    ?>

  </div>

  <script src="./javascript/createAccount.js?v=2"></script>
</body>
</html>