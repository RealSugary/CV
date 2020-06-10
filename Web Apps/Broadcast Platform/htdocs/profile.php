<?php
   session_start();
   require __DIR__  . '/php-client/autoload.php';

   use BlockCypher\Auth\SimpleTokenCredential;
   use BlockCypher\Rest\ApiContext;
   use BlockCypher\Client\AddressClient;
   use BlockCypher\Client\TXClient;
   use BlockCypher\Api\TX;

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
      if (isset($data['Address'])) {

         $apiContext = ApiContext::create(
                                 'test3', 'btc', 'v1',
                                 new SimpleTokenCredential($data['Btc_token']),
                                 array('log.LogEnabled' => true, 'log.FileName' =>
         'BlockCypher.log', 'log.LogLevel' => 'DEBUG'));

         $apiContextNoToken = ApiContext::create( 'test3', 'btc', 'v1',
         new SimpleTokenCredential(""),
         array('log.LogEnabled' => true, 'log.FileName' => 'BlockCypher.log', 'log.LogLevel' => 'DEBUG'));

         $addressClient = new AddressClient($apiContextNoToken);
         $addressBalance = $addressClient->getBalance($data['Address']);
      }

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

      $old_pw = $db_connect->real_escape_string($_POST['old_pw']);
      $new_pw = $db_connect->real_escape_string(password_hash($_POST['new_pw'], PASSWORD_BCRYPT));

      if ( password_verify( $old_pw, $data['Password'] ) ) {
         $changePw = $db_connect->prepare("UPDATE User SET Password = ? WHERE Username = ?");
         $changePw->bind_param("ss", $new_pw, $_SESSION['user']);
         if ($changePw->execute()) {
            $msg = "Change password successfully!";
         } else {
            $msg = "Failed to change password.";
         }
         $changePw->close();
      } else {
         $msg = "Failed to change password.";
      }

   }

   if ( isset( $_POST['changeWallet'] ) ) {

      $addr = $db_connect->real_escape_string($_POST['address']);
      $token = $db_connect->real_escape_string($_POST['token']);
      $privkey = $db_connect->real_escape_string($_POST['private_key']);

      $changeWallet = $db_connect->prepare("UPDATE User SET Btc_token = ?, Address = ?, Private_key = ? WHERE Username = ?");
      $changeWallet->bind_param("ssss", $token, $addr, $privkey, $_SESSION['user']);
      if ($changeWallet->execute()) {
         $msg = "Linked successfully!";
      } else {
         $msg = "Failed to link Bitcoin Wallet";
      }
      $changeWallet->close();

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
   <?php
      if ( isset( $_SESSION['logged_in'] ) ) {
         echo "<style>.user-prop { display: block; }</style>";
         echo "<style>.guest-prop { display: none; }</style>";
         $stmt_broadcaster = $db_connect->prepare( "SELECT * FROM Broadcaster WHERE Username = ?" );
         $stmt_broadcaster->bind_param( 's', $_SESSION['user'] );
         $stmt_broadcaster->execute();
         $stmt_broadcaster->store_result();
         $isbroadcaster = $stmt_broadcaster->num_rows;
         $stmt_broadcaster->close();

         if ( $isbroadcaster > 0 ) {
            echo "<style>.broadcaster-prop { display: block; }</style>";
         } else {
            echo "<style>.broadcaster-prop { display: none; }</style>";
         }
      }
   ?>
    <title>BumbleBeeTV - Profile</title>
 </head>

 <body class="profile bluetheme container-fiuld">

   <!-- TOP - Navigation Bar -->
   <header class="top-navbar">
     <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
         <a class="navbar-brand" href="#">
            <img src="images/bubblebeetv.png" width="40" height="40" alt="BumbleBeeTV's icon">
         </a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>

         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="navbar-nav">
               <a class="nav-item nav-link" href="index.php"><i class="fas fa-home"></i> Lobby</a>
               <a class="nav-item nav-link link-following" href="#"><i class="fas fa-heart"></i> Following</a>
               <a class="nav-item nav-link link-giftshop" href="gift.php"><i class="fas fa-gifts"></i> Gifts Shop</a>
               <a class="nav-item nav-link link-cashout broadcaster-prop" href="cashout.php"><i class="fas fa-coins"></i> Cash Out</a>

            </div>
            <div class="ml-md-auto">
               <div class="dropdown">
                  <a class="user-icon" data-toggle="dropdown" href="#">
                     <?php
                        if ( isset( $data['Profile_image'] ) ) {
                           echo "<img src='images/".$data['Profile_image']."' >";
                        } else {
                           echo "<img src='images/default.png'>";
                        }
                     ?>
                  </a>
                  <div class="dropdown-menu text-center">
                     <a id="username" class="dropdown-item text-center active" href="profile.php"><?php echo $data['Nickname']; ?></a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item my-3" href="#"><i class="fab fa-bitcoin"></i> Bitcoin</a>
                     <a class="dropdown-item my-3" href="giftbox.php"><i class="fas fa-boxes"></i> Gifts Box</a>
                     <a class="dropdown-item my-3 broadcaster-prop" href="broadcastroom.php?user=<?php echo $_SESSION['user'] ?>"><i class="fas fa-power-off"></i> GO LIVE</a>
                     <div class="dropdown-divider"></div>
                     <a class="btn btn-logout my-2" id="navbar_btn_logout" href="backend/logout.php">Logout</a>
                  </div>
               </div>
            </div>
         </div>
     </nav>
   </header>

   <div class="main row">
      <div class="forpadding container">
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
                     <h4>BubbleBeeTV Member Card</h4>
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
                           <h5>Email</h5>
                        </div>
                        <div class="col-md-8 text-right">
                           <h5><?php echo $data['Email'] ?></h5>
                        </div>
                     </div>
                     <div class="row my-2 align-items-center">
                        <div class="col-md-4 text-left">
                           <h5>BTC Token</h5>
                        </div>
                        <div class="col-md-8 text-right">
                           <h5><?php
                                 if(isset($data['Btc_token'])) {
                                    echo "Enable";
                                 } else {
                                    echo "Disable";
                                 }
                                ?>
                         </h5>
                        </div>
                     </div>
                     <div class="row my-2 align-items-center">
                        <div class="col-md-4 text-left">
                           <h5>BTC Address</h5>
                        </div>
                        <div class="col-md-8 text-right">
                           <h5><?php
                                 if(isset($data['Address'])) {
                                    echo "Enable";
                                 } else {
                                    echo "Disable";
                                 }
                                ?>
                         </h5>
                        </div>
                     </div>
                     <div class="row my-2 align-items-center">
                        <div class="col-md-4 text-left">
                           <h5>BTC Private Key</h5>
                        </div>
                        <div class="col-md-8 text-right">
                           <h5>
                              <?php
                                    if( isset($data['Private_key'])) {
                                       echo "Enable";
                                    } else {
                                       echo "Disable";
                                    }
                              ?>
                           </h5>
                        </div>
                     </div>
                     <div class="row my-2 align-items-center">
                        <div class="col-md-4 text-left">
                           <h5>BTC Balance</h5>
                        </div>
                        <div class="col-md-8 text-right">
                           <h5>
                              <?php
                                 if(isset($data['Address'])) {
                                    echo $addressBalance->final_balance." (Satoshis)";
                                 } else {
                                    echo "0 (Satoshis)";
                                 }
                              ?>
                           </h5>
                        </div>
                     </div>
                     <div class="row my-4 align-items-center">
                        <div class="col-md-3 text-left">
                           <h5>Hashtags</h5>
                        </div>
                        <div class="col-md-9 text-right">
                           <?php
                              $badges = explode( ",", $data['Hashtags'] );
                              foreach( $badges as $badge ) {
                                echo "<h5 class='btn badge'>".$badge."</h5>";
                              }
                           ?>
                        </div>
                     </div>
                     <div class="row my-4 align-items-center">
                        <div class="col-md-12 text-center">
                           <a class="btn" href="#privacySetting"><h5>Privacy Setting</h5></a>
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
         <div class="profile-wallet row align-items-center">
            <div class="col-md-12 text-center">
               <h3><i class="fab fa-bitcoin"></i> Link To Wallet</h3>
               <form class="form-inline my-3" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="form-group mx-auto">
                     <input type="text" class="form-control" name="address" placeholder="Bitcoin Address">
                     <div class="input-group mx-sm-3">
                        <input type="text" class="form-control" name="token" placeholder="Bitcoin Token">
                        <input type="text" class="form-control" name="private_key" placeholder="Private Key">
                        <div class="input-group-append">
                           <input class="input-group-text" type="submit" value="Change Wallet Info" name="changeWallet"></input>
                        </div>
                     </div>
                  </div>
               </form>
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

</body>
</html>
