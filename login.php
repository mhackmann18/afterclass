<?php
  // **************** //
  // This php code gets the test user's password so it can prefill the username input
  // **************** //

  // Connect to DB
  require "./config/db.conf";

  if($mysqli->connect_error){
    exit($mysqli->connect_error);
  } 
  
  // Get test user's username
  $query = "SELECT * FROM users WHERE id=1";
  $result = $mysqli->query($query);
  
  if($result){
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
  } else {
    print("Error. Please contact the system administrator");
  }

  $result->close();
  $mysqli->close();
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
  <a href="/" id="back-to-site">Back to portfolio</a>
  <div class="log-in-box">
  
    <!-- Container for header and MU logo -->
    <div>
      <h1>AfterClass</h1>
      <img class="img-small" src="./img/mu-letters.png" alt="mu-logo">
    </div>

    <!-- Login form -->
    <form autocomplete="off">
      <input id="username" type="text" name="user-name" placeholder="username" value=<?php print $username?>>
      <input id="password" type="password" name="password" placeholder="password" value="1234">
      <button>Log In</button>
    </form>

    <p id="create-account">Don't have an account? <a href="./createAccount.php">Create an account</a></p>

    <!-- Error message -->
    <p class="error-msg"></p>
  </div>

  <script src="./javascript/loginPage.js?v=2"></script>
</body>
</html>