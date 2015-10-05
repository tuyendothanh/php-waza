<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
      echo $_COOKIE['name'] . "</br>";
      if (!isSet($_COOKIE['count'])) {
        $count = 1;
      }
      else {
        $count = $_COOKIE['count'];
        ++$count;
      }
      setCookie('count', $count, time()+60);
    ?>
    <p>
      <?= $count?>回数訪問
    </p>
  </body>
</html>
