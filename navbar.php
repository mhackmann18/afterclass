<nav id="main-navbar">
  <div class="left-nav">
    <a href="./index.php">AfterClass</a> 
    <img class="img-small" src="./img/mu-letters.png" alt="mu-logo">
  </div>
  <div class="right-nav">
    <ul>
      <!-- Navbar Links -->
      <li><a href="#">Discover</a></li>
      <li><a href="groups.php">Groups</a></li>
      <li><a href="#">Messages</a></li>
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
        <li><a class="txt-darkgray txt-thin" href="#">Profile</a></li>
        <li><a class="txt-darkgray txt-thin" href="#">Settings</a></li>
        <li class="txt-darkgray txt-thin" id="log-out">Log Out</li>
      </ul>
    </div>
  </div>
</nav>