<nav id="main-navbar">
  <div class="left-nav">
    <a href="/afterclass/index.php">AfterClass</a> 
    <img class="img-small" src="/afterclass/img/mu-letters.png" alt="mu-logo">
  </div>
  <div class="right-nav">
    <ul id="navbar-links">
      <!-- Navbar Links -->
      <li><a href="/afterclass/index.php">Home</a></li>
      <li><a href="/afterclass/groups.php">Groups</a>
      <div class="navbar-dropdown-menu">
        <ul>
          <li><a href="/afterclass/groups.php">Your Groups</a></li>
          <li><a href="/afterclass/findGroups.php">Join Groups</a></li>
        </ul>
      </div>
    </li>
      <li><a href="/afterclass/newPost.php">Post</a></li>
    </ul>
    <!-- Navbar User Icon -->
    <i id="user-btn" class="fas fa-2x fa-user-circle"></i>
    <div id="user-menu">
      <p>Logged in as:<br>
        <?php
          print "<span class='txt-dark txt-bold'>" . $_COOKIE['userid'] . "</span>";
        ?>
      </p>
      <hr>
      <ul>
        <li><a class="txt-darkgray txt-thin" href="/afterclass/profile.php">Profile</a></li>
        <li><a class="txt-darkgray txt-thin" href="#">Settings</a></li>
        <li><a href="./login.php" class="txt-darkgray txt-thin" id="log-out">Log Out</a></li>
      </ul>
    </div>
  </div>
</nav>

<script src="/afterclass/javascript/navbar.js"></script>