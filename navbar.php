<nav id="main-navbar">
  <div class="left-nav">
    <a href="/projects/afterclass/index.php">AfterClass</a> 
    <img class="img-small" src="/projects/afterclass/img/mu-letters.png" alt="mu-logo">
  </div>
  <div class="right-nav">
    <ul id="navbar-links">
      <!-- Navbar Links -->
      <li><a href="/projects/afterclass/index.php">Feed</a></li>
      <li><a href="/projects/afterclass/groups.php">Groups</a>
      <div class="navbar-dropdown-menu">
        <ul>
          <li><a href="/projects/afterclass/groups.php">Your Groups</a></li>
          <li><a href="/projects/afterclass/findGroups.php">Join Groups</a></li>
        </ul>
      </div>
    </li>
      <li><a href="/projects/afterclass/newPost.php">Post</a></li>
    </ul>
    <!-- Navbar User Icon -->
    <i id="user-btn" class="fas fa-2x fa-user-circle"></i>
    <div id="user-menu">
      <p>Logged in as:<br>
        <?php
          print "<span class='txt-dark txt-bold'>" . $_SESSION['username'] . "</span>";
        ?>
      </p>
      <hr>
      <ul>
        <li><a class="txt-darkgray txt-thin" href="/projects/afterclass/yourProfile.php">Profile</a></li>
        <li><a href="/projects/afterclass/login.php" class="txt-darkgray txt-thin" id="log-out">Log Out</a></li>
      </ul>
    </div>
  </div>
</nav>

<script src="/projects/afterclass/javascript/navbar.js"></script>
