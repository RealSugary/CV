<?php

  require 'db_connect.php';
  session_start();

  if (!isset($_GET['email']) || !isset($_GET['token'])) {
    $_SESSION['message'] = 'Invaild parameters provided for account verification!';
    header('Location: ../error.php');
	} else {
		$email = $_GET['email'];
		$token = $_GET['token'];

      /* Prepare Statement of Activate User check */
      $activate_user = $db_connect->prepare( "SELECT Username FROM User WHERE Email= ? AND Token= ? AND Activated = 0" );
      $activate_user->bind_param( 'ss', $email, $token );
      $activate_user->execute();
      $activate_user->store_result();
      $activateUserRow = $activate_user->num_rows;
      $activate_user->close();

		if ( $activateUserRow > 0 ) {
         $activated_token = '';
         $activate = $db_connect->prepare( "UPDATE User SET Activated = 1, Token = ? WHERE Email = ?" );
         $activate->bind_param( 'ss', $activated_token, $email );
         $activated = $activate->execute();
         $activate->close();

         if ( $activated ) {
            $_SESSION['message'] = 'Your email has been verified! You can log in now!';
            header('Location: ../success.php');
         } else {
            $_SESSION['message'] = 'Account has already been activated or the URL is invaild!';
            header('Location: ../error.php');
         }

		} else {
      $_SESSION['message'] = 'Account has already been activated or the URL is invaild!';
      header('Location: ../error.php');
    }

	}

 ?>
