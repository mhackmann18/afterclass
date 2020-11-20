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
  <div id="log-in-box">
    <!-- Container for header and MU logo -->
    <div>
      <h1>AfterClass</h1>
      <img class="img-small" src="./img/mu-letters.png" alt="mu-logo">
    </div>
    <!-- Login form -->
    <form autocomplete="off">
      <input type="text" name="user-name" placeholder="username or email">
      <input type="password" name="password" placeholder="password">
      <button>Log In</button>
    </form>
    <!-- Error message -->
    <p class="error-msg"></p>
  </div>

  <script src="./javascript/loginPage.js"></script>
</body>
</html>