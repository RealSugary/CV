<?php
   session_start();

   if ( !isset( $_SESSION['logged_in'] ) ) {
      header ( 'location: index.php' );
   } else {
      require 'backend/db_connect.php';
      $stmt = $db_connect->prepare( "SELECT * FROM User WHERE Username = ?" );
      $stmt->bind_param( 's', $_SESSION['user'] );
      $stmt->execute();
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();
      $stmt->close();
   }

   if ( isset( $_POST['upload'] ) ) {
      $image = $_FILES['image']['name'];

      // Set image file directory
      $target = "images/".basename($image);

      $upload = $db_connect->query("UPDATE User SET Profile_image = '$image' WHERE Username = '".$_SESSION['user']."'");

      if ( move_uploaded_file( $_FILES['image']['tmp_name'], $target ) ) {
         header('location: profile.php');
         $msg = "Image uploaded successfully";
      } else {
         $msg = "Failed to upload image";
      }
   }

   if ( isset( $_POST['changePw'] ) ) {

      $old_pw = $_POST['old_pw'];
      $salt = saltGenerator();
      $saltedpassword = $_POST['new_pw'].$salt;
      $new_pw = password_hash( $saltedpassword, PASSWORD_BCRYPT );

      if ( password_verify( $old_pw.$data['Salt'], $data['Password'] ) ) {
         $update_pw = $db_connect->prepare( "UPDATE User SET Password = ? WHERE Username = ?" );
         $update_pw->bind_param( 'ss', $new_pw, $_SESSION['user'] );
         $updatePW_Success = $update_pw->execute();
         $update_pw->close();

         if ( $updatePW_Success ) {
            $msg = "Change password successfully!";
         } else {
            $msg = "Failed to change password.";
         }

      } else {
         $msg = "Failed to change password.";
      }

   }

 ?>

 <html lang="en">
 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   <!-- Ours CSS -->
   <link rel="stylesheet" href="css/style.css">

    <title>BubbleChat - Profile</title>
 </head>

 <body class="profile lighttheme container-fiuld">

    <!-- TOP - Navigation Bar -->
    <header class="top-navbar">
       <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
          <div class="container">
             <a class="navbar-brand" href="#">
                <img src="images/bubblechat.png" width="45.5" height="40" alt="BubbleChat's icon">
             </a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
             </button>

             <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="navbar-nav">
                   <a class="nav-item nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                   <a class="nav-item nav-link" href="lobby.php"><i class="fas fa-comments"></i> Lobby</a>

                </div>
                <div class="ml-md-auto">
                   <span id="username"><?php echo $data['Nickname']; ?></span>
                   <a class="user-icon" href="profile.php">
                      <?php
                         if ( isset( $data['Profile_image'] ) ) {
                            echo "<img src='images/".$data['Profile_image']."' >";
                         } else {
                            echo "<img src='images/default.png'>";
                         }
                      ?>
                   </a>
                   <a class="btn btn-logout my-2" id="navbar_btn_logout" href="backend/logout.php">Logout</a>
                </div>
             </div>
          </div>

      </nav>
    </header>

   <div class="main row">
      <nav class="col-md-1 d-none d-md-block bg-light sidebar">
         <div class="sidebar-sticky">
            <ul class="nav flex-column">

               <li class="nav-item">
                  <button type="button" id="navbar_btn_createchatroom" class="btn btn_createchatroom" data-toggle="modal" data-target="#chatroomModal">
                     Create Chatroom
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                  </button>
               </li>

            </ul>

         </div>
      </nav>
      <div class="col-md-11">
         <div class="container">
            <div class="profile-header row">
               <div class="col-md-12 text-center">
                  <h2><i class="fas fa-user-edit"></i> Profile</h2>
                  <p>Feel free to change anything !</p>
               </div>
            </div>
            <?php if ( isset( $msg ) ) {  ?>
               <div class="profile-msg row my-5">
                  <div class="col-md-12 text-center">
                     <p><?php echo $msg; ?></p>
                  </div>
               </div>
            <?php
            }
            ?>

            <div class="profile-body row align-items-center">
               <div class="col-md-5">
                  <?php
                     if ( isset( $data['Profile_image'] ) ) {
                        $profile_image = "images/".$data['Profile_image'];
                     } else {
                        $profile_image = "images/default.png";
                     }
                  ?>
                  <div class="text-center">
                     <figure class="figure">
                        <figcaption class="figure-caption"><?php ?></figcaption>
                        <img src="<?php echo $profile_image; ?>" class="figure-img img-thumbnail rounded-circle" width="300px" height="300px" alt="user's image">
                     </figure>
                  </div>
                  <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

                     <div class="input-group mb-3">
                        <div class="custom-file">
                           <input type="file" class="custom-file-input" name="image">
                           <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Change Profile Image</label>
                        </div>
                        <div class="input-group-append">
                           <input class="input-group-text" type="submit" value="Upload" name="upload"></input>
                        </div>
                     </div>
                  </form>
               </div>

               <div class="col-md-7">

                  <div class="card text-center">
                     <div class="card-header">
                        <h4>BubbleChat Member Card</h4>
                     </div>
                     <div class="card-body container">
                        <div class="row my-2 align-items-center">
                           <div class="col-md-6 text-left">
                              <h5>Username</h5>
                           </div>
                           <div class="col-md-6 text-right">
                              <h5><?php echo $data['Username'] ?></h5>
                           </div>

                        </div>
                        <div class="row my-2 align-items-center">
                           <div class="col-md-6 text-left">
                              <h5>Nickname</h5>
                           </div>
                           <div class="col-md-6 text-right">
                              <h5><?php echo $data['Nickname'] ?></h5>
                           </div>
                        </div>
                        <div class="row my-2 align-items-center">
                           <div class="col-md-4 text-left">
                              <h5>Age</h5>
                           </div>
                           <div class="col-md-8 text-right">
                              <h5><?php echo $data['Age'] ?></h5>
                           </div>
                        </div>
                        <div class="row my-2 align-items-center">
                           <div class="col-md-4 text-left">
                              <h5>Gender</h5>
                           </div>
                           <div class="col-md-8 text-right">
                              <h5><?php echo $data['Gender'] ?></h5>
                           </div>
                        </div>
                        <div class="row my-2 align-items-center">
                           <div class="col-md-4 text-left">
                              <h5>Location</h5>
                           </div>
                           <div class="col-md-8 text-right">
                              <h5><?php echo $data['Location'] ?></h5>
                           </div>
                        </div>
                        <div class="row my-2 align-items-center">
                           <div class="col-md-4 text-left">
                              <h5>Email</h5>
                           </div>
                           <div class="col-md-8 text-right">
                              <h5><?php echo $data['Email'] ?></h5>
                           </div>
                        </div>

                     </div>
                  </div>
               </div>
            </div>

            <div id="privacySetting" class="profile-privacy row align-items-center my-5">
               <div class="col-md-12 text-center">
                  <h3><i class="fas fa-lock"></i> Privacy Setting</h3>
                  <form class="form-inline my-3" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                     <div class="form-group mx-auto">
                        <input type="password" class="form-control" name="old_pw" placeholder="Old Password">
                        <div class="input-group mx-sm-3">
                           <input type="password" class="form-control" name="new_pw" placeholder="New Password">
                           <div class="input-group-append">
                              <input class="input-group-text" type="submit" value="Change Password" name="changePw"></input>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>

         </div>
      </div>
   </div>

   <!-- Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <!-- Font Awesome JS -->
   <script src="https://kit.fontawesome.com/66eccce44d.js" crossorigin="anonymous"></script>
   <!-- Our JS -->
   <script src="javascripts/chatroom.js"></script>

</body>
</html>

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
