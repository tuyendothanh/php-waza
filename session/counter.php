<?php
  session_start();
  if (!isset($_SESSION['counter'])) {
    $counter = 0;
  }
  else {
    $counter = $_SESSION['counter'];
  }
  $_SESSION['counter'] = ++$counter;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h3><?= $counter?> 回数</h3>
  </body>
</html>
