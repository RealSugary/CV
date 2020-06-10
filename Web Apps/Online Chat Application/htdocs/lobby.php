<?php
   session_start();
   require 'backend/db_connect.php';

   if ( !isset( $_SESSION['logged_in'] ) ) {
      header ( 'location: index.php' );
   } else {
      $stmt = $db_connect->prepare( "SELECT * FROM User WHERE Username = ?" );
      $stmt->bind_param( 's', $_SESSION['user'] );
      $stmt->execute();
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();
      $stmt->close();
   }

   if ( isset( $_POST['search'] ) ) {
      $criteria = "%".$_POST['criteria']."%";

      $sql_chatroom = $db_connect->prepare( "SELECT * FROM Chatroom WHERE RoomName LIKE ? ORDER BY RoomID" );
      // Bind the form data to the corresonding data in database
      $sql_chatroom->bind_param( 's', $criteria );
      $sql_chatroom->execute();
      $chatroomList = $sql_chatroom->get_result();
      $sql_chatroom->close();
   } else {
      $sql_chatroom = $db_connect->prepare( "SELECT * FROM Chatroom ORDER BY RoomID" );
      // Bind the form data to the corresonding data in database
      $sql_chatroom->execute();
      $chatroomList = $sql_chatroom->get_result();
      $sql_chatroom->close();
   }
?>

<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Bootstrap CS6S -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   <!-- Ours CSS -->
   <link rel="stylesheet" href="css/style.css">

   <title>BubbleChat - Chatroom Lobby</title>
</head>

<body class="lobby lighttheme container-fiuld">

   <!-- Create Chatroom Modal -->
   <div class="modal fade" id="chatroomModal" tabindex="-1" role="dialog" aria-labelledby="chatroomModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
         <div class="modal-content">

            <div class="modal-header">
               <div class="container-fiuld">
                  <div class="row">
                     <div class="col-md-3 align-self-center">
                        <img src="images/bubblechat.png" width="45.5" height="40" alt="BubbleChat's icon">
                     </div>
                     <div class="col-md-6 align-self-center text-center">
                        <h5 class="modal-title form-inline" id="modaltitle">

                        </h5>
                     </div>
                     <div class="col-md-3">
                        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                  </div>
               </div>
            </div>

            <div class="modal-body">

               <div id="feedback">

               </div>

               <form id="createchatroomform" class="needs-validation" action="backend/createchatroom.php" method="POST" autocomplete="off" novalidate>
                  <div class="form-group">
                     <label for="chatroom_name" class="col-form-label">Chatroom Name</label>
                     <input type="text" class="form-control enter" id="chatroom_name" name="chatroom_name" required>
                     <div id="chatroom_name_feedback" class="invalid-feedback">
                        Please enter a chatroom name
                     </div>
                  </div>
                  <input type="hidden" id="owner" name="owner" value="<?php echo $_SESSION['user']; ?>">
               </form>

            </div>

            <div class="modal-footer">
               <button type="button" id="btn_submit_create" class="btn btn-submitform" name="create">Create</button>
               <button type="button" id="btn_submit_confirm" class="btn btn-submitform" name="confirm" data-dismiss="modal">Confirm</button>
            </div>
         </div>
     </div>
   </div>
   <!-- End of Modal -->

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
                  <a class="nav-item nav-link active" href="#"><i class="fas fa-comments"></i> Lobby</a>

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

   <!-- Main Content -->
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
            <div class="row">
               <div class="col-md-12">
                  <h2>Discover</h2>
                  <p>the chatroom that you'll like !</p>
                  <form class="needs-validation form-inline" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off" novalidate>
                     <label for="filter">Filter: &nbsp;</label>
                     <input class="form-control mr-sm-2" name="criteria" type="search" placeholder="Search" aria-label="Search" required>
                     <div class="invalid-feedback">
                        Please enter a keyword
                     </div>
                     <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="search">Search</button>
                  </form>
               </div>
            </div>

            <div class="room-list row my-3">
               <div class="d-flex flex-wrap bd-highlight col-md-12 justify-content-center">

                  <?php
                     if ( !( $chatroomList->num_rows > 0 ) ) {
                        echo "<h2>No Chatroom available ...</h2>";
                     } else {

                        while ( $chatroom = mysqli_fetch_assoc( $chatroomList ) ) {
                           echo '<div class="order-1 p-2 bd-highlight">
                                    <div class="card">
                                       <a href="chatroom.php?chatroom='.$chatroom[RoomID].'">
                                          <div class="card-img-top">
                                             <h5 class="card-title text-center">Room '.$chatroom[RoomID].'</h5>
                                          </div>
                                          <div class="card-body">
                                             <h4 class="card-title text-center">'.$chatroom[RoomName].'</h4>
                                          </div>
                                       </a>
                                    </div>
                                 </div>';
                        }

                     }

                  ?>

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
   <!-- Ajax -->
   <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   <!-- Google reCAPTCHA-->
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   <!-- Ours JS -->
   <script src="javascripts/chatroom.js"></script>

</body>

</html>
