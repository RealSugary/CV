<?php
   session_start();
?>

<html>

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
   <!-- Ours CSS -->
   <link rel="stylesheet" href="css/style.css">

   <title>BumbleBeeTV - SUCCESSFUL</title>
</head>

<body class="bluetheme">
   <div class="modal fade in" id="messageModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="resetModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <div class="container-fiuld">
                  <div class="row">
                     <div class="col-md-3 align-self-center">
                        <img src="images/bubblebeetv.png" width="50" height="50" alt="BumbleBeeTV's icon">
                     </div>
                     <div class="col-md-6 align-self-center">
                        <h5 class="modal-title text-center" id="modaltitle">
                           SUCCESSFUL
                        </h5>
                     </div>
                     <div class="col-md-3">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-body">
               <p>
                  <?php
                     if(isset($_SESSION['message'])){
                        echo $_SESSION['message'];
                     } else {
                        header('location: index.php');
                     }
                  ?>
               </p>
            </div>
            <div class="modal-footer">
               <a href="index.php" class="btn btn-submitform">
                  Confirm
               </a>
            </div>
         </div>
     </div>
   </div>

   <!-- Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   <script type="text/javascript">
      $(window).on('load',function(){
         $('#messageModal').modal('show');
      });
   </script>

</body>

</html>
