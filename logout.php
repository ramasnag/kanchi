<?php
   session_start();
   unset($_SESSION["uname"]);
   unset($_SESSION["pwd"]);
   unset($_SESSION["valid"]);
   unset($_SESSION["role"]);
   
   echo 'You have logged out';
   header('Refresh: 2; URL = login.php');
?>
