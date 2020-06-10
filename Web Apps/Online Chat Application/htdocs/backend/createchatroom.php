<?php
   require 'db_connect.php';

   $owner = $_POST['owner'];
   $chatroom_name = $_POST['chatroom_name'];
   $createChatroom = $db_connect->prepare( "INSERT INTO `Chatroom`( `RoomName`, `Owner` ) VALUES ( ?, ? )" );
   $createChatroom->bind_param( 'ss', $chatroom_name, $owner );
   $createChatroom_Success = $createChatroom->execute();
   $createChatroom->close();

   if ( $createChatroom_Success ) {
      echo "Chatroom Created";
   } else {
      echo "Error: unable to create the chatroom";
   }

?>
