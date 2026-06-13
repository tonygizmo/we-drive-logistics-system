<?php
  $db_host = 'localhost';
  $db_user = 'u452006220_WeDriveApp';
  $db_password = 'Coast49tower!';
  $db_db = 'u452006220_WeDriveApp';

  $mysqli = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );

  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }
  
?>
