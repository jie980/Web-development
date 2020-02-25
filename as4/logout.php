<?php
// https://www.w3schools.com/php/php_sessions.asp
// session_start must be run before any other output
session_start();
unset($_SESSION['username']);
?>
<body>
  <?php include 'menu.html';?>
  <p>
    You are now logged out.
  </p>
</body>