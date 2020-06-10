<?php
   session_start();
   require 'backend/db_connect.php';
   if ( isset( $_SESSION['logged_in'] ) ) {
      $stmt = $db_connect->prepare( "SELECT * FROM User WHERE Username = ?" );
      $stmt->bind_param( 's', $_SESSION['user'] );
      $stmt->execute();
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();
      $stmt->close();
   }

   if ( isset( $_GET['chatroom'] ) ) {
      $sql_chatroom = $db_connect->prepare( "SELECT * FROM Chatroom WHERE RoomID = ?" );
      $sql_chatroom->bind_param( 's', $_GET['chatroom'] );
      $sql_chatroom->execute();
      $chatroomList = $sql_chatroom->get_result();
      $currentChatroom = $chatroomList->fetch_assoc();
      $sql_chatroom->close();
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

   <title>BubbleChat - ChatRoom</title>
</head>

<body class="chatroom lighttheme container-fiuld">

   <!-- Modal -->
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


   <!-- Main Content -->
   <div class="main row">

      <nav class="col-md-1 d-none d-md-block bg-light sidebar">
         <div class="sidebar-sticky">
            <ul class="nav flex-column">
               <!-- <li class="nav-item">
                  <a class="nav-link active" href="#">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                     Dashboard <span class="sr-only">(current)</span>
                  </a>
               </li> -->

               <li class="nav-item">
                  <button type="button" id="navbar_btn_createchatroom" class="btn btn_createchatroom" data-toggle="modal" data-target="#chatroomModal">
                     Create Chatroom
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                  </button>
               </li>

            </ul>

         </div>
      </nav>

      <div class="chatroom-sidebar col-md-11">
         <div class="container">

            <div class="chatroom-header row justify-content-between">
               <div class="col-md-3">
                  <h6>Chatroom ID: <?php echo $currentChatroom['RoomID']; ?> </h6>
               </div>
               <div class="col-md-6 text-center">
                  <h6><?php echo $currentChatroom['RoomID']; ?></h6>
               </div>
               <div class="col-md-3 text-right">
                  <h6>Owner: <?php echo $currentChatroom['Owner']; ?> </h6>
               </div>
            </div>

            <div class="chatroom row">
               <div class="col-md-12">
                  <div class="container-md">

                     <div class="chatroom-chatboard row">
                        <div id="chatroom-message" class="col-md-12">

                        </div>
                     </div>

                     <div class="chatroom-input row align-items-center">
                        <div class="col-md-12">

                           <div class="row form-inline">
                              <textarea class="form-control chatroom-input-textarea" id="chatroom-input-textarea" placeholder="Send a message"></textarea>
                              <div class="ml-md-auto">
                                 <button type="button" id="send" class="btn mx-5"><h6>Send</h6></button>
                                 <i id="btn-emoji" class="fas fa-laugh-squint mr-4"></i>
                              </div>
                           </div>

                        </div>
                     </div>

                  </div>
               </div>
            </div>

         </div>
      </div>

   </div>

   <!-- Emoji Picker -->
   <script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.1/dist/index.min.js"></script>
   <!-- Font Awesome JS -->
   <script src="https://kit.fontawesome.com/66eccce44d.js" crossorigin="anonymous"></script>
   <!-- Chatroom JS -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <!-- Ajax -->
   <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <!-- Ours JS -->
   <script src="javascripts/chatroom.js"></script>

   <script type="text/javascript">
      var username = document.getElementById("username").innerHTML;


      // WebSocket
      jQuery(function($) {

         var websocket_server = new WebSocket("wss://bubblechat.hopto.org/wss2/");

         // Receive message from websocket server
         // And show it on chatroom
         websocket_server.onmessage = function(e)
         {
            var json = JSON.parse(e.data);
            switch(json.type) {
               case 'message':
                  $("#chatroom-message").append(json.msg);
                  break;
            }
         }

         // Sending Message
         // By send button
         $("#send").on("click", function(e){
            var msg = $("#chatroom-input-textarea").val();
            websocket_server.send(
               JSON.stringify({
                  'type':'message',
                  'user_name':username,
                  'msg':msg
               })
            );
            $("#chatroom-input-textarea").val('');
         });

         // By Enter
         $("#chatroom-input-textarea").on('keyup',function(e){
            if(e.keyCode==13 && !e.shiftKey)
            {
               var msg = $(this).val();
               websocket_server.send(
                  JSON.stringify({
                     'type':'message',
                     'user_name':username,
                     'msg':msg
                  })
               );
               $(this).val('');
            }
         });

      });

      window.addEventListener('DOMContentLoaded', () => {
         // Emoji Picker
         const button = document.querySelector('#btn-emoji');
         const picker = new EmojiButton();

         picker.on('emoji', emoji => {
            document.getElementById("chatroom-input-textarea").value += emoji;
         });

         button.addEventListener('click', () => {
            picker.togglePicker(button);
         });

      });



   </script>
</body>
</html>
