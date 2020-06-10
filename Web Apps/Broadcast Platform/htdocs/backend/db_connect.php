<?php

   define('dbHostName','localhost');
   define('dbUsername','bubblebeetv');
   define('dbPassword','bubblebeetv');
   define('dbServerName','bubblebeetv');

   $db_connect = @mysqli_connect( dbHostName, dbUsername, dbPassword, dbServerName )
   or die( 'Could not connect to BubbleBeeTV Database. Error: ' .
   mysqli_connect_error() );

?>
