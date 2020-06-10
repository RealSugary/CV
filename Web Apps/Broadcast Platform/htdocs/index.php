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
   $siteKey = '6LdEhvoUAAAAAH50cn0hxLWHYgl0-Mkpcc1oFo1T';
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
   <!-- Show the dynamic content when user login -->
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

   <title>BumbleBeeTV - First Broadcast Platform in Hong Kong</title>
</head>

<body class="lobby bluetheme container-fiuld">

   <!-- Modal -->
   <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
         <div class="modal-content">

            <div class="modal-header">
               <div class="container-fiuld">
                  <div class="row">
                     <div class="col-md-3 align-self-center">
                        <img src="images/bubblebeetv.png" width="50" height="50" alt="BumbleBeeTV's icon">
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
                  <div class="row align-items-center">
                     <div class="col-md-6">
                        <button type="button" id="form_selector_login" class="btn-formselector">Login</button>
                     </div>
                     <div class="col-md-6">
                        <button type="button" id="form_selector_signup" class="btn-formselector">Sign Up</button>
                     </div>
                  </div>
               </div>
            </div>

            <div class="modal-body">
               <div id="feedback">

               </div>
               <form id="loginform" class="needs-validation" action="backend/login.php" method="POST" autocomplete="off" novalidate>
                  <div class="form-group">
                     <label for="username" class="col-form-label">Username</label>
                     <input type="text" class="form-control enter" id="login_username" name="username" required>
                     <div id="login_username_feedback" class="invalid-feedback">
                        Please enter a username
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="password" class="col-form-label">Password</label>
                     <input type="password" class="form-control enter" id="login_pw" name="password" required>
                     <div id="login_pw_feedback" class="invalid-feedback">
                        Please enter a password
                     </div>
                  </div>
                     <div class="form-group g-recaptcha" data-sitekey="<?php echo $siteKey;?>">

                     </div>

                     <div class="recovery">
                        <small id="link_forgotpw">Forget Password ?</small>
                     </div>

               </form>

               <form id="forgotpwform" class="needs-validation" action="forgot.php" method="POST" autocomplete="off" novalidate>
                  <div class="form-group">
                     <label for="email" class="col-form-label">Enter your account's email below:</label>
                     <input type="email" class="form-control" id="email" name="email" required>
                     <div class="invalid-feedback">
                        Please enter an email in correct format
                     </div>
                  </div>
               </form>

               <form id="signupform" class="needs-validation" action="backend/signup.php" method="POST" autocomplete="off" novalidate>
                  <div class="form-group">
                     <label for="username" class="col-form-label">Username</label>
                     <input type="text" class="form-control" id="signup_username" name="username" required>
                     <div id="signup_username_feedback" class="invalid-feedback">
                        Please enter a username
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="nickname" class="col-form-label">Nickname</label>
                     <input type="text" class="form-control" id="nickname" name="nickname" required>
                     <div class="invalid-feedback">
                        Please enter a nickname
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="password" class="col-form-label">Password</label>
                     <input type="password" class="form-control" id="signup_pw" name="password" required>
                     <div id="signup_pw_feedback" class="invalid-feedback">
                        Please enter a password
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="confirm_password" class="col-form-label">Confirm Password</label>
                     <input type="password" class="form-control" id="signup_confirm_pw" name="confirm_password" required>
                     <div id="signup_confirm_pw_feedback" class="invalid-feedback">
                        Please enter a password
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="email" class="col-form-label">Email</label>
                     <input type="email" class="form-control" id="signup_email" name="email" required>
                     <div id="signup_email_feedback" class="invalid-feedback">
                        Please enter an email in correct format
                     </div>
                  </div>

                  <div class="hashtags form-group">
                     <label for="hashtags"># Hashtags</label>
                     <div class="btn-group-toggle hashtags-row text-center" data-toggle="buttons">
                        <label class="btn btn-radio">
                           <input type="checkbox" name="hashtags[]" value="Artistic">
                           Artistic
                        </label>

                        <label class="btn btn-radio">
                           <input type="checkbox" name="hashtags[]" value="Educational">
                           Educational
                        </label>

                        <label class="btn btn-radio">
                           <input type="checkbox" name="hashtags[]" value="Mobile">
                           Mobile
                        </label>
                     </div>

                     <div class="btn-group-toggle hashtags-row text-center" data-toggle="buttons">
                        <label class="btn btn-radio">
                           <input type="checkbox" name="hashtags[]" value="Sport">
                           Sport
                        </label>

                        <label class="btn btn-radio">
                           <input type="checkbox" name="hashtags[]" value="FPS">
                           FPS
                        </label>

                        <label class="btn btn-radio">
                           <input type="checkbox" name="hashtags[]" value="RPG">
                           RPG
                        </label>
                     </div>

                     <div id="signup_hashtags_feedback" class="invalid-feedback">
                        Please check at least one # Hashtag
                     </div>
                  </div>

                  <div id="regbroadcasterform" class="needs-validation">
                     <div class="form-group">
                        <label for="age">Age</label>
                        <select id="age" class="form-control" name="age">
                           <option selected>1</option>
                           <option>2</option>
                           <option>3</option>
                           <option>4</option>
                           <option>5</option>
                           <option>6</option>
                           <option>7</option>
                           <option>8</option>
                           <option>9</option>
                           <option>10</option>
                           <option>11</option>
                           <option>12</option>
                           <option>13</option>
                           <option>14</option>
                           <option>15</option>
                           <option>16</option>
                           <option>17</option>
                           <option>18</option>
                           <option>19</option>
                           <option>20</option>
                           <option>21</option>
                           <option>22</option>
                           <option>23</option>
                           <option>24</option>
                           <option>25</option>
                           <option>26</option>
                           <option>27</option>
                           <option>28</option>
                           <option>29</option>
                           <option>30</option>
                           <option>32</option>
                           <option>33</option>
                           <option>34</option>
                           <option>35</option>
                           <option>36</option>
                           <option>37</option>
                           <option>38</option>
                           <option>39</option>
                           <option>40</option>
                           <option>41</option>
                           <option>42</option>
                           <option>43</option>
                           <option>44</option>
                           <option>45</option>
                           <option>46</option>
                           <option>47</option>
                           <option>48</option>
                           <option>49</option>
                           <option>50</option>
                           <option>51</option>
                           <option>52</option>
                           <option>53</option>
                           <option>54</option>
                           <option>55</option>
                           <option>56</option>
                           <option>50</option>
                           <option>51</option>
                           <option>52</option>
                           <option>53</option>
                           <option>54</option>
                           <option>55</option>
                           <option>56</option>
                           <option>57</option>
                           <option>58</option>
                           <option>59</option>
                           <option>60</option>
                           <option>61</option>
                           <option>62</option>
                           <option>63</option>
                           <option>64</option>
                           <option>65</option>
                           <option>66</option>
                           <option>67</option>
                           <option>68</option>
                           <option>69</option>
                           <option>70</option>
                           <option>71</option>
                           <option>72</option>
                           <option>73</option>
                           <option>74</option>
                           <option>75</option>
                           <option>76</option>
                           <option>77</option>
                           <option>78</option>
                           <option>79</option>
                           <option>80</option>
                           <option>81</option>
                           <option>82</option>
                           <option>83</option>
                           <option>84</option>
                           <option>85</option>
                           <option>86</option>
                           <option>87</option>
                           <option>88</option>
                           <option>89</option>
                           <option>90</option>
                           <option>91</option>
                           <option>92</option>
                           <option>93</option>
                           <option>94</option>
                           <option>95</option>
                           <option>96</option>
                           <option>97</option>
                           <option>98</option>
                           <option>99</option>
                           <option>100</option>
                        </select>
                     </div>
                     <div class="form-group form-inline">
                        <label for="gender" class="col-form-label mr-3">Gender</label>
                        <div class="form-check">
                           <input class="form-check-input" type="radio" name="gender" value="male" checked>
                           <label class="form-check-label mr-3" for="male">
                              Male
                           </label>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="radio" name="gender" value="female">
                           <label class="form-check-label" for="female">
                              Female
                           </label>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="inputLocation">Location</label>
                        <select id="inputLocation" class="form-control" name="location">
                           <option selected>New Territories</option>
                           <option>Kowloon</option>
                           <option>Hong Kong Island</option>
                        </select>
                     </div>
                  </div>

                  <div class="signup-selector form-group text-center">
                     <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-radio">
                           <input type="radio" name="role" id="signup_selector_viewer" value="Viewer" checked>
                           Viewer
                        </label>
                        <label class="btn btn-radio">
                           <input type="radio" name="role" id="signup_selector_broadcaster" value="Broadcaster">
                           Broadcaster
                        </label>
                     </div>
                  </div>

               </form>

            </div>

            <div class="modal-footer">
               <button type="button" id="btn_submit_login" class="btn btn-submitform" name="login">Login</button>
               <button type="button" id="btn_submit_signup" class="btn btn-submitform" name="signup">Sign Up</button>
               <button type="button" id="btn_submit_confirm" class="btn btn-submitform" name="confirm" data-dismiss="modal">Confirm</button>
               <button type="submit" id="btn_submit_forgotpw" class="btn btn-submitform" name="send_forgotpw" form="forgotpwform">Send</button>
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
               <a class="nav-item nav-link active" href="index.php"><i class="fas fa-home"></i> Lobby</a>
               <a class="nav-item nav-link user-prop" href="#"><i class="fas fa-heart"></i> Following</a>
               <a class="nav-item nav-link user-prop" href="gift.php"><i class="fas fa-gifts"></i> Gifts Shop</a>
               <a class="nav-item nav-link user-prop broadcaster-prop" href="cashout.php"><i class="fas fa-coins"></i> Cash Out</a>

            </div>
            <div class="ml-md-auto">
               <div class="dropdown user-prop">
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
               <!-- Button trigger modal -->
               <button type="button" id="navbar_btn_login" class="btn btn-login guest-prop" data-toggle="modal" data-target="#memberModal">Login</button>
               <button type="button" id="navbar_btn_signup" class="btn btn-signup guest-prop" data-toggle="modal" data-target="#memberModal">Sign Up</button>
            </div>
         </div>
     </nav>
   </header>


   <!-- Main Content -->
   <div class="main row">
      <div class="forpadding container">
         <div class="row">
            <div class="col-md-12">
               <h2>Discover</h2>
               <p>the broadcast that you'll like !</p>
               <form class="form-inline">
                  <label for="filter">Filter: &nbsp;</label>
                  <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
               </form>
            </div>
         </div>

         <div class="room-list row">
            <div class="d-flex flex-wrap bd-highlight col-md-12 justify-content-center">
               <div class="order-1 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/wow-188x250.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 1</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-2 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/re3-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 2</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-3 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/mw-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 3</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-4 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/af-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 4</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-5 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/tw3-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 5</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-6 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/2k-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 6</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-7 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/fifa-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 7</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-8 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/uno-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 8</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-9 p-2 bd-highlight">
                  <div class="card">
                     <a href="#">
                        <div class="card-img-top">
                           <img src="images/minecraft-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 9</h5>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="order-10 p-2 bd-highlight">
                  <div class="card">
                     <a href="broadcastroom.php?user=carlos">
                        <div class="card-img-top">
                           <img src="images/lol-285x380.jpg" alt="" class="img-fluid">
                        </div>
                        <div class="card-body">
                           <h5 class="card-title text-center">Room 10</h5>
                        </div>
                     </a>
                  </div>
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
   <script src="javascripts/membermodal.js"></script>

</body>

</html>
