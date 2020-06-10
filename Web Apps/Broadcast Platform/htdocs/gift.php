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
   }

   if ( isset ( $_POST['pay'] ) ){

      $id = $_POST['gift_id'];
      $qty = $_POST['gift_qty'];
      $amount = $_POST['gift_amount'];
      $admin = 'Admin';
      foreach( $id as $key => $value ) {
         $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, ?, ?, '0')");
         $stmt->bind_param("ssii", $admin, $_SESSION['user'], $value, $qty[$key]);
         if( $stmt->execute() ) {
            $_SESSION['success'] = "The gifts sent to your gift box.";
         } else {
            $_SESSION['failure'] = "Sorry, you don't have enough Bitcoin to buy it.";
         }
         $stmt->close();
      }

      $config = array();
      $config["btc.privkey"] = $data['Private_key']; // Master BTC Private Key
      $config["btc.addr"] = $data['Address']; // Master BTC Address
      $config["btc.blockcypher.apitoken"] = $data['Btc_token']; // BlockCypher API Token

      $apiContext = ApiContext::create(
         'test3', 'btc', 'v1',
         new SimpleTokenCredential($config["btc.blockcypher.apitoken"]), array('log.LogEnabled' => true, 'log.FileName' =>
         'BlockCypher.log', 'log.LogLevel' => 'DEBUG'));

      $tx = new TX();

		// Tx inputs
		$input = new \BlockCypher\Api\TXInput();
		$input->addAddress($config['btc.addr']);
		$tx->addInput($input);
		// Tx outputs
		$output = new \BlockCypher\Api\TXOutput();
		$output->addAddress("n2Jb3UgEC5Doe5QQ79FD2xfRtUBuVVBu5v");
		$tx->addOutput($output);
		// Tx amount
		$output->setValue((int)$amount); // Satoshis

		$txClient = new TXClient($apiContext);
		$txSkeleton = $txClient->create($tx);
		$privateKeys = array($config['btc.privkey']);
		$txSkeleton = $txClient->sign($txSkeleton, $privateKeys);

		$txSkeleton = $txClient->send($txSkeleton);
      echo "Transaction sent with tx id: " . $txSkeleton->tx->hash;
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
   ?>
   <title>BumbleBeeTV - Gifts Shop</title>
</head>

<body class="bluetheme container-fiuld">

   <!-- Modal -->
   <!-- Buy Gifts Modal -->
   <div class="modal fade" id="buygiftModal" tabindex="-1" role="dialog" aria-labelledby="buygiftModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">

            <div class="modal-header container-fiuld">
               <div class="row w-100 justify-content-between">
                  <div class="col-md-3">
                     <i class="fas fa-gifts"></i>
                  </div>
                  <div class="col-md-6 text-center">
                     <h5 class="modal-title">Gifts Cart</h5>
                  </div>
                  <div class="col-md-3 text-right">
                     <button type="button" class="close btn-close px-0" data-dismiss="modal" onclick="delrow()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
               </div>
            </div>

            <div class="modal-body">
               <!-- Checklist -->
               <form id="cart" action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                  <table id="checklist" class="table table-hover table-dark text-center">
                     <thead>
                        <tr>
                           <th scope="col">Title</th>
                           <th scope="col">Quantity</th>
                           <th scope="col">Totle Amount ( <i class="fab fa-btc"></i> )</th>
                        </tr>
                     </thead>
                     <tbody>

                     </tbody>
                  </table>

                  <p>Total: <i class="fab fa-btc" id="total"></i></p>

               </form>
            </div>

            <div class="modal-footer">
               <button class="btn" data-dismiss="modal" onclick="delrow()">Back</button>
               <div class="ml-md-auto">
                  <button type="submit" id="btn_pay" class="btn" name="pay" form="cart">Confirm</button>
               </div>
            </div>

         </div>
      </div>
   </div>
   <!-- Response Modal -->
   <div class="modal fade" id="responseModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="responseModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content text-center">
            <div class="modal-header container-fiuld">
               <div class="row w-100 justify-content-between">
                  <div class="col-md-3">
                     <i class="fas fa-gifts"></i>
                  </div>
                  <div class="col-md-6 text-center">
                     <h5 class="modal-title">Gifts Cart</h5>
                  </div>
                  <div class="col-md-3">
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <?php
                  if ( isset( $_SESSION['success'] ) ) {
                     echo "<p>".$_SESSION['success']."</p>";
                  } else if ( isset ( $_SESSION['failure'] ) ) {
                     echo "<p>".$_SESSION['failure']."</p>";
                  }
               ?>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn" data-dismiss="modal" onclick="unsetResponse()">Confirm</button>
            </div>
         </div>
      </div>
   </div>
   <!-- End of Modal -->

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
               <a class="nav-item nav-link link-giftshop active" href="gift.php"><i class="fas fa-gifts"></i> Gifts Shop</a>
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
                     <a id="username" class="dropdown-item text-center" href="profile.php"><?php echo $data['Nickname']; ?></a>
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

   <!-- Main Content -->
   <div class="main row">
      <div class="forpadding container">
         <div class="giftshop-header row">
            <div class="col-md-12 text-center">
               <h2><i class="fas fa-gifts"></i> Gift Something</h2>
               <p> Show favor toward broadcaster, honor an occasion, or make a gesture of assistance</p>
            </div>
         </div>

         <div class="giftshop-list row">
            <div class="d-flex flex-wrap bd-highlight text-center justify-content-center">
               <div class="order-1 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-gem align-middle"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_1">Diamond</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_1_price"> 100</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_diamond_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_1" class="gift-qty" min="0" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>

                     </div>
                  </div>
               </div>
               <div class="order-2 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-birthday-cake"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_2">Happy Birthday</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_2_price"> 200</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_birthdaycake_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_2" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-3 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-motorcycle"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_3">Drive You Around</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_3_price"> 500</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_motor_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_3" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-4 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-guitar"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_4">Play You Some Music</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_4_price"> 1000</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_guitar_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_4" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-5 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-glass-cheers"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_5">Cheers</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_5_price"> 2000</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_wine_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_5" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-6 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-mug-hot"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_6">Warm Your Body</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_6_price"> 3000</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_coffe_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_6" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-7 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-pizza-slice"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_7">PIZZA TIME</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_7_price"> 5000</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_pizza_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_7" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-8 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-ice-cream"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_8"><i class="fas fa-icicles"></i> FREEZE <i class="fas fa-icicles"></i></h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_8_price"> 10000</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_icecream_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_8" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="order-9 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-toilet-paper"></i></h1>
                     </div>
                     <div class="card-body">
                        <h5 class="card-title" id="gift_9">Don't Panic</h5>
                        <p class="card-text">

                        </p>
                        <span><i class="fab fa-btc" id="gift_9_price"> 50000</i></span>
                        <div class="form-inline justify-content-center mt-3">
                           <button class="btn btn-minus" type="button">
                           -
                           </button>
                           <label for="gift_tp_qty" class="gift-qty-label mx-2">0</label>
                           <input type="hidden" id="id_9" class="gift-qty" value="0">
                           <button class="btn btn-plus" type="button">
                           +
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="row py-5">
            <div class="col-md-12 text-center">
               <button type="button" class="btn btn-checkout" data-toggle="modal" data-target="#buygiftModal" onclick="checkout()">Checkout</button>
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
   <!-- Ours JS -->
   <script src="javascripts/giftshop.js"></script>
   <script type="text/javascript">
      <?php
         if ( isset( $_SESSION['success'] ) || isset( $_SESSION['failure'] ) ) {
      ?>
            $(window).on('load',function(){
               $('#responseModal').modal('show');
            });

            function unsetResponse() {
               <?php
                  if ( isset( $_SESSION['success'] ) ) {
                     unset( $_SESSION['success'] );
                  } else if ( isset ( $_SESSION['failure'] ) ) {
                     unset( $_SESSION['failure'] );
                  }

               ?>
            }
      <?php
         }
      ?>
   </script>

</body>

</html>
