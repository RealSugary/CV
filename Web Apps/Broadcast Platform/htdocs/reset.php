<?php
   session_start();
   require('backend/db_connect.php');

   if ( isset($_GET['email'] ) && isset( $_GET['token']) ) {

      $email = $_GET['email'];
      $token = $_GET['token'];

      /* Prepare Statement of provide user form's data */
      $user_check = $db_connect->prepare( "SELECT Username FROM User WHERE Email = ? AND Token = ?" );
      $user_check->bind_param( 'ss', $email, $token );
      $user_check->execute();
      $user_check->store_result();
      $sameUserRow = $user_check->num_rows;
      $user_check->close();

      if ( $sameUserRow <= 0 ){
         $_SESSION['message'] = 'Your have entered invaild URL for password reset!';
         header('location: error.php');
      }

   } else {
      $_SESSION['message'] = 'Sorry, verification failed. Please try again!';
      header('location: error.php');
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

   <title>BumbleBeeTV - Reset Password</title>
</head>

<body class="bluetheme">
   <div class="modal fade in" id="resetModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="resetModal" aria-hidden="true">
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
                           Reset Password
                        </h5>
                     </div>
                     <div class="col-md-3">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <form id="resetform" action="backend/reset_password.php" method="POST" autocomplete="off">
                  <div class="form-group">
                     <label for="new_password" class="col-form-label">New Password</label>
                     <input type="password" class="form-control" id="new_password" name="new_password">
                  </div>
                  <div class="form-group">
                     <label for="confirm_new_pw" class="col-form-label">Confrim New Password</label>
                     <input type="password" class="form-control" id="confirm_new_pw" name="confirm_new_pw">
                  </div>
                  <input type="hidden" name="email" value="<?php echo $email;?>">
                  <input type="hidden" name="token" value="<?php echo $token;?>">
               </form>
            </div>
            <div class="modal-footer">
               <button type="submit" id="btn_submit_reset" class="btn btn-submitform" name="reset" form="resetform">Reset</button>
            </div>
         </div>
     </div>
   </div>

   <!-- Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <script type="text/javascript">
      $(window).on('load',function(){
         $('#resetModal').modal('show');
      });
   </script>
</body>
</html>
