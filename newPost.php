<?php 
  // HTTPS Redirect
  require $_SERVER["DOCUMENT_ROOT"] . "/afterclass/php/REDIRECT.php";
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
  <link rel="stylesheet" href="./css/mainStyles.css">
  <title>AfterClass | New Post</title>
</head>
<body>
  <?php require 'navbar.php'; ?>
  <div class="container">

    <form id="new-post-form" action="/afterclass/php/createPost.php" method="POST" enctype="multipart/form-data">
      <div class="space-between padding-bottom-mid">
        <h1 id="new-post-header">New Post</h1>
        <div id="post-to-container">
          <label for="group-name">Post to: </label>
          <select name="group-name" id="select-group-to-post">
            <option value="invalid" selected disabled>Choose Group</option>
          </select>
        </div>
      </div>
      <textarea name="post-body" id="new-post-text" cols="30" rows="6" placeholder="Type something" required></textarea>
      <div class="space-between">
        <div>
          <label id="post-img-btn" for="file-upload" class="btn-gold btn">Add Image</label>
          <input name="file" id="file-upload" type="file"/> or 
          <button id="embed-yt-btn" class="btn btn-grey">Embed Youtube Video</button>
        </div>
        <div>
          <button id="preview-new-post-btn" class="btn btn-gold">Continue to Post</button>
        </div>
      </div>
      <input id="yt-link-input" name="yt-link" type="text" placeholder="Youtube video link">
      <p id="post-err-msg" class="err-msg"></p>
    </form>

    <div class="outer">
      <div class="middle">
        <div id="post-preview" class="post">
          <div class="post-head">
            <div class="profile-img-container">
              <img class="profile-img" src="/afterclass/img/blank-profile.jpg" alt="profile image">
            </div>
            <p class="who-when">(preview) You posted on <span class="post-date"></span></p>
          </div>
          <p class="post-text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Omnis a ipsam ratione. Laborum repellat maiores sit, repellendus voluptate sapiente, ad vel mollitia dignissimos ipsa perspiciatis tempora suscipit omnis, accusamus consequatur repudiandae enim doloribus error eveniet? Dignissimos omnis reiciendis rerum temporibus.</p>
          <div class="post-media">
          </div>
          <div id="preview-post-btns">
            <button id="confirm-post-btn" class="btn btn-gold">Post</button>
            <button id="change-post-btn" class="btn btn-grey">Edit</button>
          </div>
          <i id="cancel-preview-btn" class="fas fa-times"></i>
        </div>
      </div>
    </div>
    
    
    <div id="overlay"></div>
  </div>

  <script src="/afterclass/javascript/newPost.js"></script>
</body>
</html>