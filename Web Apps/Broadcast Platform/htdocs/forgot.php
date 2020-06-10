<?php

   require 'backend/db_connect.php';
   require_once "backend/Mail.php";

   $email = $_POST['email'];

   /* Prepare Statement of User check */
   $user_check = $db_connect->prepare( "SELECT * FROM User WHERE Email = ?" );
   $user_check->bind_param( 's', $email );
   $user_check->execute();
   $result = $user_check->get_result();
   $sameUserRow = $result->num_rows;
   $user_check->close();

   if ( $sameUserRow > 0 ) {
      $data = $result->fetch_assoc();
      $email = $data['Email'];
      $token = $data['Token'];
      $user = $data['Username'];

      $host = "ssl://smtp.gmail.com";
      $username = "bubblebeetv02@gmail.com";
      $password = "Bubblebeetv02@";
      $port = "465";
      $to = $email;
      $email_from = "bubblebeetv02@gmail.com";
      $email_subject = "Password Reset Link (BubbleBee TV)" ;
      $email_body =
      "Hello, " . $user . ",

      You have requested password reset!

      Please click this link to reset your password:

      https://bubblebeetv.sytes.net/reset.php?email=".$email."&token=".$token;

      $headers = array ('From' => $email_from, 'To' => $to, 'Subject' => $email_subject);
      $smtp = Mail::factory('smtp', array ('host' => $host, 'port' => $port, 'auth' => true, 'username' => $username, 'password' => $password));
      $mail = $smtp->send($to, $headers, $email_body);

      if ( PEAR::isError($mail) ) {
         $forgot_message = $mail->getMessage();
      } else {
         $forgot_message = "Please check your email<br>".$email."<br>for verify your identity, we will link you to complete your password reset ...";
      }

   } else {
      $forgot_message = "ERROR";
   }

?>

<html>

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   <!-- Ours CSS -->
   <link rel="stylesheet" href="css/style.css">

   <title>BubbleBeeTV - Forgot Password</title>
</head>

<body class="bluetheme">
   <div class="modal fade in" id="forgotModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="resetModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <div class="container-fiuld">
                  <div class="row">
                     <div class="col-md-3 align-self-center">
                        <img src="images/bubblebeetv.png" width="50" height="50" alt="BumbleBeeTV's icon">
                     </div>
                     <div class="col-md-6 align-self-center">
                        <h5 class="modal-title text-center" id="modaltitle">
                           Forgot Password
                        </h5>
                     </div>
                     <div class="col-md-3">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <p>
                  <?php
                     echo $forgot_message;
                  ?>
               </p>
            </div>
            <div class="modal-footer">
               <a href="index.php" class="btn btn-submitform">
                  Confirm
               </a>
            </div>
         </div>
     </div>
   </div>

   <!-- Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <!-- Our JS -->
   <script type="text/javascript">
      $(window).on('load',function(){
         $('#forgotModal').modal('show');
      });

   </script>

</body>
</html>
