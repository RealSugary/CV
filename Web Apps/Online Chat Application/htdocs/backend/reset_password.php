<?php

   require 'db_connect.php';
   session_start();

   $salt = saltGenerator();
   $saltedpassword = $_POST['password'].$salt;
   $new_pw = password_hash( $saltedpassword, PASSWORD_BCRYPT );

   $email = $_POST['email'];
   $token = $_POST['token'];

   $update_pw = $db_connect->prepare( "UPDATE User SET Password = ?, Token = ? WHERE Email = ?" );
   $update_pw->bind_param( 'sss', $new_pw, $token, $email );
   $updatePW_Success = $update_pw->execute();
   $update_pw->close();

   if ( $updatePW_Success ) {
      $_SESSION['message'] = "Your password has been reset successfully!";
      header("location: ../success.php");
   } else {
      $_SESSION['message'] = "Failed to reset password ...";
      header("location: ../error.php");
   }

?>

<?php
   function saltGenerator() {
      $salt_storage = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $salt = '';
      for ($i = 0; $i < 8; $i++) {
         $salt .= $salt_storage[rand(0, strlen($salt_storage))];
      }
      return $salt;
   }
?>
