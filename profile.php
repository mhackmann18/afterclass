<?php 
  // HTTPS Redirect
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/REDIRECT.php";
  // Connect to MYSQL db, check for error
  require_once $_SERVER["DOCUMENT_ROOT"] . "/afterclass/config/db.conf";
  if($mysqli->connect_error){
    print "There was an issue connecting to the database.<br>Please try again later.";
    exit;
  }
  // Make sure the user is logged in, redirect if not
  if(!isset($_COOKIE['userid']))
    header("location: login.php");
  // Refresh the time on the login cookie
  $username = $_COOKIE['userid'];
  setcookie('userid', $username, time() + 1800, "/");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/1b8d9746c3.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="/afterclass/css/mainStyles.css">
  <title>Profile | AfterClass MU</title>
</head>
<body>
  <?php
    // First query gets the info of the logged in user, second checks if they uploaded an image or not.
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysqli->query($query);
    if(!$result){
      print "Error. Please contact the system administrator.";
      exit; 
    }
    if($result->num_rows == 1){
      $row = mysqli_fetch_assoc($result);
      // User Info
      $id = $row['id'];
      $userEmail = $row['email'];
      $userMajor = $row['major'];
      $userFullName = $row['fullName'];
      $userBio = $row['bio'];
      $query = "SELECT * FROM profileimg WHERE userid = '$id'";
      $result = $mysqli->query($query);
      if(!$result){
        print "Error. Please contact the system administrator.";
        exit; 
      }
      $row = mysqli_fetch_assoc($result);
      // If user uploaded an image display it, otherwise display default
      if($row['status'] == 0){
        $userImage = "<img src='/afterclass/uploads/profile".$id.".jpg?'".mt_rand()." alt='profile-image'>";
      } else {
        $userImage = "<img src='/afterclass/img/blank-profile.jpg' alt='blank-profile-image'>";
      }
    }
    $mysqli->close();
  ?>
  <?php require $_SERVER["DOCUMENT_ROOT"] . '/afterclass/navbar.php'; ?>

  <div class="container">
    <!-- Left column -->
    <div id="profile-left">
      <!-- Will either display the default user or a custom image. -->
      <div id="profile-page-image-container">
        <?php print $userImage; ?>
      </div>
      <?php if($error){ print "<p id='profile-image-upload-error'>$error</p>"; }?>
      <!-- Button to change profile pic -->
      <button id="change-profile-image" title="Change profile picture"><i class="fas fa-camera fa-2x"></i></button>
      <!-- Hidden form with file input is submitted by the change-profile-image button via javascript -->
      <form id="upload-image-form" action="/afterclass/php/UPLOAD.php" method="POST" enctype="multipart/form-data" style="display:none;">
        <input type="file" name="file">
        <button type="submit" name="submit">Upload Image</button>
      </form>
      <?php print '<h1 class="txt-bold">'.$userFullName.'</h1>'; ?>
      <ul id="profile-page-info">
        <?php print "<li>Email: $userEmail</li>"; ?>
        <?php print "<li>Username: <span id='username'>$username</span></li>"; ?>
        <?php print "<li>Major: <span id='major'>$userMajor</span></li>"; ?>
      </ul>
      <!-- Hidden input fields -->
      <ul id="profile-page-inputs">
        <?php print "<li>Email: $userEmail</li>"; ?>
        <?php print "<li>Username:<input id='username-input' type='text' value='$username'></li>"; ?>
        <?php print "<li>Major:<input id='major-input' type='text' value='$userMajor'></li>"; ?>
      </ul>
      <p id="input-err-msg" class="err-msg"></p>
      <button id="edit-profile-btn" class="btn-gold">Edit Profile</button>
      <!-- Save and cancel buttons only show after edit button is clicked -->
      <button id="save-edit-profile-btn" class="btn-gold">Save Changes</button>
      <button id="cancel-edit-profile-btn">Cancel</button>
    </div>
    <!-- Right Column -->
    <div id="profile-right">
      <!-- Bio -->
      <h1 id="profile-page-bio-header">Bio</h1>
      <?php
        if(!$userBio){
          print "<p id='empty-bio'>$userFullName hasn't added a bio.</p>";
        } else {
          print "<p>$userBio</p>";
        }
      ?>
      <!-- Textarea is hidden until edit button is clicked -->
      <textarea id="edit-bio" cols="30" rows="6"></textarea>
      <p id="bio-err-msg" class="err-msg"></p>
      <!-- Groups -->
      <h1 id="profile-page-groups-header">Groups</h1>
      <ul id="profile-page-groups">
        <li><a href="#">CS 2830</a></li>
        <li><a href="#">CS 3050</a></li>
        <li><a href="#">Mizzou Athletics</a></li>
        <li><a href="#">Kappa Sigma</a></li>
      </ul>
    </div>
  </div>

  <script src="/afterclass/javascript/profilePage.js"></script>
</body>
</html>