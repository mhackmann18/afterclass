<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>Log In | AfterClass MU</title>
</head>
<body class="flex-center">
  <div id="log-in-box">
    <h1>Create New Account</h1>
    <!-- Sign up form -->
    <form name="createUserForm" action="createUser.php" method="POST" autocomplete="off"> <!--Checkup on that action-->
      <input type="hidden" name="action" value="create_user">

      <input id="email" type="email" name="email" placeholder="UM System Email" required>
      <input id="name" type="text" name="name" placeholder="Full Name" required>
      <input id="major" type="text" name="major" placeholder="Major" required>
      <input id="username" type="text" name="username" placeholder="Username" required>
      <input id="password" type="password" name="password" placeholder="Password" required>
      <button>Create Account</button>
    </form>
    <p id="create-account">Already have an account? <a href="./login.html">Log In</a></p>
  </div>
</body>
</html>