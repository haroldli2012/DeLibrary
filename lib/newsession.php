<?php
  session_start();
  $name=$_REQUEST["name"];
  $value = $_REQUEST["value"];
  $_SESSION[$name] = $value;
?>