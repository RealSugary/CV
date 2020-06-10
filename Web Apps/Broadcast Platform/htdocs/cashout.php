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

      $receiver = $_SESSION['user'];
      $sql = "SELECT GiftTransaction.Gift_ID, Gift_Name, SUM(Quantity), Price FROM GiftTransaction, Gift WHERE Receiver = '$receiver' and Gift.Gift_ID = GiftTransaction.Gift_ID and Cashed = '0' GROUP BY Gift_ID";
      $gift_result = $db_connect->query($sql);

      $trans_sql = "SELECT * FROM Cashout WHERE Username = '$receiver'";
      $trans_result = $db_connect->query($trans_sql);

      $update_sql = "SELECT * FROM GiftTransaction WHERE Receiver = '$receiver' and Cashed = '0'";
      $update_result = $db_connect->query($update_sql);

      if(isset($_POST['cashout'])) {
         if ($_POST['total'] != 0) {
            while ( $update_info = mysqli_fetch_assoc($update_result) ) {
               $stmt = $db_connect->prepare("UPDATE GiftTransaction SET Cashed = '1' WHERE Receiver = ? and Gift_ID = ?");
               $stmt->bind_param("ss",$_SESSION['user'], $update_info['Gift_ID']);
               $stmt->execute();

            }
            $stmt = $db_connect->prepare("INSERT INTO Cashout (Username, Amount) VALUES (?, ?)");
            $stmt->bind_param("si",$_SESSION['user'], $_POST['total']);
            $stmt->execute();
            $stmt->close();

            $admin = 'Admin';
            $stmt1 = $db_connect->prepare( "SELECT * FROM User WHERE Username = ?" );
            $stmt1->bind_param( 's', $admin );
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $data1 = $result1->fetch_assoc();
            $stmt1->close();

            $config = array();
            $config["btc.pubkey"] = $data1['Public_key']; // Master BTC Public Key
            $config["btc.privkey"] = $data1['Private_key']; // Master BTC Private Key
            $config["btc.addr"] = $data1['Address']; // Master BTC Address
            $config["btc.blockcypher.apitoken"] = $data1['Btc_token']; // BlockCypher API Token

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
      		$output->addAddress($data['Address']);
      		$tx->addOutput($output);
      		// Tx amount
      		$output->setValue((int)$_POST['total']); // Satoshis

      		$txClient = new TXClient($apiContext);
      		$txSkeleton = $txClient->create($tx);
      		$privateKeys = array($config['btc.privkey']);
      		$txSkeleton = $txClient->sign($txSkeleton, $privateKeys);

      		$txSkeleton = $txClient->send($txSkeleton);
            echo "Transaction sent with tx id: " . $txSkeleton->tx->hash;

         }
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

   <title>BumbleBeeTV - Cash Out</title>
</head>

<body class="bluetheme container-fiuld">

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
               <a class="nav-item nav-link link-cashout broadcaster-prop active" href="#"><i class="fas fa-coins"></i> Cash Out</a>

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

   <div class="main row">
      <div class="forpadding container">

         <div class="cashout-header row">
            <div class="col-md-12 text-center">
               <h2><i class="fas fa-coins"></i> Cash Out</h2>
            </div>
         </div>

         <div class="cashout-list row my-5">
            <div class="d-flex flex-wrap bd-highlight col-md-12 justify-content-center text-center">
               <form action="cashout.php" method="post">
                  <table class="table table-hover table-dark text-center">
                     <thead>
                        <tr>
                           <th scope="col">Gift</th>
                           <th scope="col">Quantity</th>
                           <th scope="col"><i class="fab fa-bitcoin"></i> Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php
                        $total = 0;
                        if ($gift_result->num_rows > 0) {
                           while ( $gift_info = mysqli_fetch_assoc($gift_result) ) {
                              echo "<tr>";
                                 echo "<td scope='row'>".$gift_info['Gift_Name']."</td>";
                                 echo "<td scope='row'>".$gift_info['SUM(Quantity)']."</td>";
                                 echo "<td scope='row'>".$gift_info['SUM(Quantity)'] * $gift_info['Price']."</td>";
                              echo "</tr>";
                              $total += $gift_info['SUM(Quantity)'] * $gift_info['Price'];
                           }
                        }
                        echo "<tr>";
                           echo "<td scope='row'>Total Amount:</td>";
                           echo "<td scope='row'></td>";
                           echo "<td scope='row'>".$total."</td>";
                        echo "</tr>";
                     ?>
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                     </tbody>
                  </table>
                  <div class="text-center">
                     <button type="submit" class="btn" name="cashout">Cash It Out!</button>
                  </div>
               </form>
            </div>
         </div>

         <div class="cashout-record row my-5">
            <div class="col-md-12 text-center">
               <h3><i class="fas fa-scroll"></i> History </h3>
               <table class="table table-hover table-dark text-center">
                  <thead>
                     <tr>
                        <th scope="col">Time</th>
                        <th scope="col"><i class="fab fa-bitcoin"></i> Amount</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php
                     while ( $trans_info = mysqli_fetch_assoc($trans_result) ) {
                           echo "<tr>";
                              echo "<td scope='row'>".$trans_info['Time']."</td>";
                              echo "<td scope='row'>".$trans_info['Amount']."</td>";
                           echo "</tr>";
                     }
                  ?>
                  </tbody>
               </table>
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

</body>
