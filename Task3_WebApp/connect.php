<?php
DEFINE ('DB_USER','MyUsername');
DEFINE ('DB_HOST','localhost');
DEFINE ('DB_PSWD','MyPassword');
DEFINE ('DB_NAME','MyDatabase');

$dbcon = mysqli_connect(DB_HOST,DB_USER,DB_PSWD,DB_NAME);
if(!$dbcon)
{
  die('Error connecting to database');
}
?>
