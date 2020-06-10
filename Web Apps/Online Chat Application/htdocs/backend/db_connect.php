<?php

   define('dbHostName','localhost');
   define('dbUsername','bubblechat');
   define('dbPassword','bubblechat');
   define('dbServerName','bubblechat');

   $db_connect = @mysqli_connect( dbHostName, dbUsername, dbPassword, dbServerName )
   or die( 'Could not connect to BubbleChat Database. Error: ' .
   mysqli_connect_error() );

?>
