<?php
session_start();
?>
<body>
  <?php include 'menu.html';?>
  <h1>Sign up</h1>
  <div>
    <?php 
    if (isset($_SESSION['username'])) {
      print('You need to log out first to signup, '.$_SESSION['username']);
    }
    else{
      echo'
      <form method="post" action="do_signup.php">
        Enter username <input type="text" name="username" /><br />
        Create password <input type="password" name="password" /> <br />
        <input type="submit" />
      </form>';}
    ?>
  </div>
</body>