<?php

   require 'db_connect.php';
   session_start();

   $new_pw = password_hash( $_POST['new_password'], PASSWORD_BCRYPT );

   $email = $_POST['email'];
   $token = $_POST['token'];

   $update_pw = $db_connect->prepare( "UPDATE User SET Password = ?, Token = ? WHERE Email = ?" );
   $update_pw->bind_param( 'sss', $new_pw, $token, $email );
   $result = $update_pw->execute();
   $update_pw->close();

   $sql = "";

   if ($result) {
      $_SESSION['message'] = "Your password has been reset successfully!";
      header("location: ../success.php");
   } else {
      $_SESSION['message'] = "Failed to reset password ...";
      header("location: ../error.php");
   }

?>
