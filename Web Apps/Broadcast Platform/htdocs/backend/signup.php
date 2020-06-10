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

   /* Check Hashtags is empty or not */
   if ( !isset( $_POST['hashtags'] ) ) {
      echo "Please check at least one # Hashtag";
   } else {
      if ( $sameUserRow > 0 ) {
            echo "Username already exists";
      } else {
         if ( $sameEmailRow > 0 ) {
               echo "Email already exists";
         } else {
            $role = $_POST['role'];
            $nickname = $_POST['nickname'];
            $salt = saltGenerator();
            $saltedpassword = $_POST['password'].$salt;
            $password = password_hash( $saltedpassword, PASSWORD_BCRYPT );
            $token = md5 ( rand(0, 1000) );
            $hashtags = implode( ",", $_POST['hashtags'] );
            $viewer_sign_up = $db_connect->prepare( "INSERT INTO `User` (`Username`, `Nickname`, `Password`, `Salt`,`Email`, `Profile_image`, `Hashtags`, `Wallet`, `Activated`, `Token`) VALUES ( ?, ?, ?, ?, ?, NULL, ?, '0', '0', ? )" );
            $viewer_sign_up->bind_param( 'sssssss', $user, $nickname, $password, $salt, $email, $hashtags, $token );
            $viewer_success = $viewer_sign_up->execute();
            $viewer_sign_up->close();

            // Defind User Role
            if ( $role == "Viewer" ) {

               /*     Activate Email       */
               if ( $viewer_success ) {
                  $host = "ssl://smtp.gmail.com";
                  $email_user = "bubblebeetv02@gmail.com";
                  $email_password = "Bubblebeetv02@";
                  $port = "465";
                  $to = $email;
                  $email_from = "bubblebeetv02@gmail.com";
                  $email_subject = "Account Verification (BubbleBee TV)" ;
                  $email_body =
                  "Hello, " .$nickname. " !

                  Thank you for signing up!

                  Please click this link to activate your account:

                  https://bubblebeetv.sytes.net/backend/verify.php?email=".$email."&token=".$token."";

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

            if ( $role == "Broadcaster" ) {
               $age = $_POST['age'];
               $gender = $_POST['gender'];
               $location = $_POST['location'];

               // Insert broadcaster record
               $broadcaster_sign_up = $db_connect->prepare("INSERT INTO Broadcaster(Username, Age, Gender, Location) VALUES (?,?,?,?)");
               $broadcaster_sign_up->bind_param( 'siss', $user, $age, $gender, $location );
               $broadcaster_success = $broadcaster_sign_up->execute();
               $broadcaster_sign_up->close();

               /*     Activate Email       */
               if ( $viewer_success && $broadcaster_success ) {
                  $host = "ssl://smtp.gmail.com";
                  $email_user = "bubblebeetv02@gmail.com";
                  $email_password = "Bubblebeetv02@";
                  $port = "465";
                  $to = $email;
                  $email_from = "bubblebeetv02@gmail.com";
                  $email_subject = "Account Verification (BubbleBee TV)" ;
                  $email_body =
                  "Hello, " .$nickname. " !

                  Thank you for signing up!

                  Please click this link to activate your account:

                  https://bubblebeetv.sytes.net/backend/verify.php?email=".$email."&token=".$token."";

                  $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject);
                  $smtp = Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $email_user, 'password' => $email_password));
                  $mail = $smtp->send($to, $headers, $email_body);

                  if (PEAR::isError($mail)) {
                     echo("Error: Unable to send activated mail");
                  } else {
                     echo("Activated mail had sent. Please activate your account via the activated link.");
                  }

               } else {
                  echo "Error: unable to sign up your Broadcaster account";
               }

            }

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
