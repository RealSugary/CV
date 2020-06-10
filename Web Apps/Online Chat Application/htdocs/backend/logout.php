<?php
   session_start();
   setcookie('user', $data['Username'], time() - 600, '/', null, 1, 1, ['samesite'=>'Strict'] );
   session_destroy();

   header("location: ../index.php");
?>
