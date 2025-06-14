<?php if (!isset($_SESSION)) session_start(); ?>
<nav class="main-nav">
  <!-- ***** Logo Start ***** -->
  <a href="index.php" class="logo">
    <img src="assets/images/logo.png" alt="">
  </a>
  <!-- ***** Logo End ***** -->

  <!-- ***** Search Start ***** -->
  <div class="search-input">
    <form id="search" action="#">
      <input type="text" placeholder="Type Something" id='searchText' name="searchKeyword" />
      <i class="fa fa-search"></i>
    </form>
  </div>
  <!-- ***** Search End ***** -->

  <!-- ***** Menu Start ***** -->
  <ul class="nav">
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="browse.php">Browse</a></li>
    <li><a href="qna.php">QnA</a></li>
    <li><a href="streams.html">Streams</a></li>

    <?php if (!empty($_SESSION['user'])): ?>
      <!-- Logged in -->
      <li><a href="#"><i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['user']['username']); ?></a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
    <?php else: ?>
      <!-- Not logged in -->
      <li><a href="login.php"><i class="fa fa-sign-in-alt"></i> Login</a></li>
      <li><a href="register.php"><i class="fa fa-user-plus"></i> Register</a></li>
    <?php endif; ?>
  </ul>

  <a class="menu-trigger">
    <span>Menu</span>
  </a>
  <!-- ***** Menu End ***** -->
</nav>
