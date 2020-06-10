<?php

   session_start();
   require 'db_connect.php';

   $user = $_POST['username'];
   $password = $_POST['password'];

   if( empty( $_POST['g-recaptcha-response'] ) ) {

      echo "Captcha is required";

   } else {
      $secret_key = '6LdEhvoUAAAAAIANzAB99jwDoRxf0p38QmN5YEOv';
      $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
      $response_data = json_decode($response);

      if(!($response_data->success)) {
         echo "Captcha verification failed";
      }

      /* Prepare Statement of User check */
      $user_check = $db_connect->prepare( "SELECT * FROM User WHERE Username = ?" );
      $user_check->bind_param( 's', $user );
      $user_check->execute();
      $result = $user_check->get_result();
      $sameUserRow = $result->num_rows;
      $user_check->close();

      if ( $sameUserRow > 0 ) {
         $data = $result->fetch_array();
         if (password_verify($password.$data['Salt'], $data['Password'])) {
            if ($data['Activated'] == 0) {
              $_SESSION['login_error'] = "Please verify your email!";
              echo "Account have not been activated";
            } else {
              $_SESSION['user'] = $data['Username'];
              $_SESSION['active'] = $data['Activated'];
              $_SESSION['logged_in'] = TRUE;
              setcookie('user', $data['Username'], time() + 600, '/', null, 1, 1, ['samesite'=>'Strict'] );
              echo "Login successful";
            }
         } else {
           echo "Invalid username or password";
         }
      } else {
        echo "Invalid username or password";
      }
   }

?>
