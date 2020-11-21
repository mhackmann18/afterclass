<?php
  if($_SERVER['HTTPS'] !== 'on') {
    $redirect= "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("location: $redirect");
  }

  header("Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0, s-maxage=0");
  header("Pragma:no-cache");
  header("Expires: 0");
?>