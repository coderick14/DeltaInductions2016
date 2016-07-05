<?php
  session_start();
  session_destroy();
  header("Location: login.php");
  //echo "You have been logged out successfully.<br>";
  //echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
?>
