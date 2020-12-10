<?php 
  if(session_id() == "")
    session_start();
  // HTTPS Redirect
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/redirect.php";
  // Make sure the user is logged in, redirect if not
  if(!isset($_SESSION['username']))
    header("location: login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>Afterclass | Groups</title>
</head>
<body>
  <?php require 'navbar.php'; ?>
  <div class="container" id="group-page">
    <h1 id="your-groups-header" class="large-header">Your Groups</h1>
    <button id="create-new-group" class="btn-gold">Create New</button>
    <section id="your-groups">
    <!-- Javascript AJAX request will fill this with the user's groups -->
    </section>

    <div class="flex-center"><img src="./img/spinner.gif" alt="loading..." class="margin-top-lrg" id="spinner"></div>

    <!-- Popup group creation form -->
    <form method="POST" id="create-group-form" action="/afterclass/php/process.php">
      <button id="cancel-create-group-btn">
        <i class="fas fa-times"></i>
      </button>
      <h3>Create New Group</h3>
      <input name="group-name" type="text" placeholder="name of your group" class="input-field">
      <textarea name="description" id="" cols="50" rows="10" placeholder="group description" class="input-field"></textarea>
      <button id="create-group-btn" class="btn-gold" name="action" value="create-new-group">Create Group</button>
      <p id="create-group-err-msg" class="err-msg"></p>
    </form>

    <!-- Popup leave group confirmation -->
    <div id="leave-group-confirmation-window">
      <div class="div-content">
        <h3>Leave <span id="group-name-here"></span>?</h3>
        <div class="div-buttons">
          <button id="leave-group-confirm-btn">Leave</button>
          <button class="btn-gold" id="leave-group-cancel-btn">Stay</button>
        </div>
      </div>
    </div>
  </div>

  <div id="overlay"></div>

  <script type="module" src="./javascript/groupsPage.js"></script>
</body>
</html>