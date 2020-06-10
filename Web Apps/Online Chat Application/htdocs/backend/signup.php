<?php
   require 'db_connect.php';
   require_once "Mail.php";

   $user = $_POST['username'];
   $email = $_POST['email'];

   /* Prepare Statement of User check */
   $user_check = $db_connect->prepare( "SELECT Username FROM User WHERE Username = ?" );
   $user_check->bind_param( 's', $user );
   $user_check->execute();
   $user_check->store_result();
   $sameUserRow = $user_check->num_rows;
   $user_check->close();

   /* Prepare Statement of Email check */
   $email_check = $db_connect->prepare( "SELECT Email FROM User WHERE Email = ?" );
   $email_check->bind_param( 's', $email );
   $email_check->execute();
   $email_check->store_result();
   $sameEmailRow = $email_check->num_rows;
   $email_check->close();

   if ( $sameUserRow > 0 ) {
      echo "Username already exists";
   } else {
      if ( $sameEmailRow > 0 ) {
         echo "Email already exists";
      } else {
         $age = $_POST['age'];
         $gender = $_POST['gender'];
         $location = $_POST['location'];
         $nickname = $_POST['nickname'];
         $salt = saltGenerator();
         $saltedpassword = $_POST['password'].$salt;
         $password = password_hash( $saltedpassword, PASSWORD_BCRYPT );
         $token = md5 ( rand(0, 1000) );
         $userSignUp = $db_connect->prepare( "  INSERT INTO `User` (`Username`, `Nickname`, `Password`, `Salt`, `Age`, `Email`, `Gender`, `Location`, `Profile_image`, `Token`, `Activated`)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, '0')" );
         $userSignUp->bind_param( 'ssssissss', $user, $nickname, $password, $salt, $age, $email, $gender, $location, $token );
         $userSignUp_Success = $userSignUp->execute();
         $userSignUp->close();

         /*     Activate Email       */
         if ( $userSignUp_Success ) {
            $host = "ssl://smtp.gmail.com";
            $email_user = "bubblechatcorp@gmail.com";
            $email_password = "@bubblechat";
            $port = "465";
            $to = $email;
            $email_from = "bubblechatcorp@gmail.com";
            $email_subject = "Account Verification ( BubbleChat )" ;
            $email_body =
            "Hello, " .$nickname. " !

            Thank you for signing up!

            Please click this link to activate your account:

            https://bubblechat.hopto.org/backend/verify.php?email=".$email."&token=".$token."";

            $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject);
            $smtp = Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $email_user, 'password' => $email_password));
            $mail = $smtp->send($to, $headers, $email_body);

            if (PEAR::isError($mail)) {
               echo("Error: Unable to send activated mail");
            } else {
               echo("Activated mail had sent. Please activate your account via the activated link.");
            }

         } else {
            echo "Error: unable to sign up your User account";
         }

      }

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
