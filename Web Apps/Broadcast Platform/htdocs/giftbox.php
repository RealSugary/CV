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

      $admin = 'Admin';
      $receiver = $_SESSION['user'];
      $sql = "SELECT Sender, Gift_Name, Time, Quantity FROM GiftTransaction, Gift WHERE Receiver = '$receiver' and Gift.Gift_ID = GiftTransaction.Gift_ID";
      $gift_result = $db_connect->query($sql);

      $sql_qty = "SELECT SUM(Quantity) FROM GiftTransaction WhERE Receiver = '$receiver' GROUP BY Gift_ID";
      $qty_result = $db_connect->query($sql_qty);

      while($row = mysqli_fetch_assoc($qty_result)) {
        $qty[] = $row['SUM(Quantity)'];
      }
      $sql_qty2 = "SELECT SUM(Quantity) FROM GiftTransaction WhERE Sender = '$receiver' GROUP BY Gift_ID";
      $qty_result2 = $db_connect->query($sql_qty2);

      while($row2 = mysqli_fetch_assoc($qty_result2)) {
        $qty2[] = $row2['SUM(Quantity)'];
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
   <title>BumbleBeeTV - Gifts Box</title>
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
                     <a class="dropdown-item my-3 active" href="#"><i class="fas fa-boxes"></i> Gifts Box</a>
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

         <div class="giftsbox-header row">
            <div class="col-md-12 text-center">
               <h2><i class="fas fa-gifts"></i> Gifts Box</h2>
               <p> Show your own gifts.</p>
            </div>
         </div>

         <div class="giftsbox-list row">
            <div class="d-flex flex-wrap bd-highlight text-center justify-content-center">
               <div class="order-1 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-gem align-middle"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_1">Diamond</h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[0])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[0])) {
                                       echo $qty[0];
                                    } else {
                                       echo $qty[0]-$qty2[0];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund1" onclick="refund1()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-2 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-birthday-cake"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_2">Happy Birthday</h5>
                           <p class="card-text">Quantity:
                              <?php
                              if(!isset($qty[1])) {
                                 echo '0';
                              } else {
                                 if(!isset($qty2[1])) {
                                    echo $qty[1];
                                 } else {
                                    echo $qty[1]-$qty2[1];
                                 }
                              }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund2" onclick="refund2()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-3 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-motorcycle"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_3">Drive You Around</h5>
                           <p class="card-text">Quantity:
                              <?php
                              if(!isset($qty[2])) {
                                 echo '0';
                              } else {
                                 if(!isset($qty2[2])) {
                                    echo $qty[2];
                                 } else {
                                    echo $qty[2]-$qty2[2];
                                 }
                              }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund3" onclick="refund3()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-4 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-guitar"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_4">Play You Some Music</h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[3])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[3])) {
                                       echo $qty[3];
                                    } else {
                                       echo $qty[3]-$qty2[3];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund4" onclick="refund4()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-5 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-glass-cheers"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_5">Cheers</h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[4])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[4])) {
                                       echo $qty[4];
                                    } else {
                                       echo $qty[4]-$qty2[4];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund5" onclick="refund5()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-6 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-mug-hot"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_6">Warm Your Body</h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[5])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[5])) {
                                       echo $qty[5];
                                    } else {
                                       echo $qty[5]-$qty2[5];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund6" onclick="refund6()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-7 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-pizza-slice"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_7">PIZZA TIME</h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[6])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[6])) {
                                       echo $qty[6];
                                    } else {
                                       echo $qty[6]-$qty2[6];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund7" onclick="refund7()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-8 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-ice-cream"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_8"><i class="fas fa-icicles"></i> FREEZE <i class="fas fa-icicles"></i></h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[7])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[7])) {
                                       echo $qty[7];
                                    } else {
                                       echo $qty[7]-$qty2[7];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund8" onclick="refund8()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="order-9 p-2 bd-highlight">
                  <div class="card">
                     <div class="card-img-top d-flex align-items-center  justify-content-center">
                        <h1><i class="fas fa-toilet-paper"></i></h1>
                     </div>
                     <form action="giftbox.php" method="post">
                        <div class="card-body">
                           <h5 class="card-title" id="gift_9">Don't Panic</h5>
                           <p class="card-text">Quantity:
                              <?php
                                 if(!isset($qty[8])) {
                                    echo '0';
                                 } else {
                                    if(!isset($qty2[8])) {
                                       echo $qty[8];
                                    } else {
                                       echo $qty[8]-$qty2[8];
                                    }
                                 }
                              ?>
                           </p>
                        </div>
                        <div class="card-body">
                           <button type="submit" class="btn btn-refund" name="refund9" onclick="refund9()">Refund</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>

         <div class="giftsbox-record row py-5">
            <div class="col-md-12 text-center">
               <table class="table table-hover table-dark text-center">
                  <thead>
                     <tr>
                        <th scope="col">Sender</th>
                        <th scope="col">Gift</th>
                        <th scope="col">Time</th>
                        <th scope="col">Quantity</th>
                     </tr>
                  </thead>
                  <tbody>
                  <?php
                     while ( $gift_info = mysqli_fetch_assoc($gift_result) ) {
                           echo "<tr>";
                              echo "<td scope='row'>".$gift_info['Sender']."</td>";
                              echo "<td scope='row'>".$gift_info['Gift_Name']."</td>";
                              echo "<td scope='row'>".$gift_info['Time']."</td>";
                              echo "<td scope='row'>".$gift_info['Quantity']."</td>";
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
   <script type="text/javascript">
   function refund1() {
      <?php
         if (isset($_POST['refund1'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '1', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund2() {
      <?php
         if (isset($_POST['refund2'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '2', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund3() {
      <?php
         if (isset($_POST['refund3'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '3', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund4() {
      <?php
         if (isset($_POST['refund4'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '4', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund5() {
      <?php
         if (isset($_POST['refund5'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '5', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund6() {
      <?php
         if (isset($_POST['refund6'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '6', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund7() {
      <?php
         if (isset($_POST['refund7'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '7', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund8() {
      <?php
         if (isset($_POST['refund8'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '8', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   function refund9() {
      <?php
         if (isset($_POST['refund9'])) {
            $stmt = $db_connect->prepare("INSERT INTO GiftTransaction (Sender, Receiver, Gift_ID, Quantity, Cashed) VALUES (?, ?, '9', '1', '0')");
            $stmt->bind_param("ss", $_SESSION['user'], $admin);
            $stmt->execute();
            $stmt->close();
         }
      ?>
   }
   </script>

</body>
