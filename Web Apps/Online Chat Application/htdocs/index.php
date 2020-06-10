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
   $siteKey = '6LfwEf4UAAAAAJNP1evrlAUKsOAVtKcPJbpRbuvP';
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
   <!-- Show the dynamic content when user login -->
   <?php
      if ( isset( $_SESSION['logged_in'] ) ) {
         echo "<style>.user-prop { display: block; }</style>";
         echo "<style>.guest-prop { display: none; }</style>";
      }
   ?>

   <title>BubbleChat - First Online Chatting Platform in Hong Kong</title>
</head>

<body class="home lighttheme container-fiuld">

   <!-- Modal -->
   <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
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
                        <option selected>Central & Western</option>
                        <option>Eastern</option>
                        <option>Southern</option>
                        <option>Wan Chai</option>
                        <option>Kowloon City</option>
                        <option>Kwun Tong</option>
                        <option>Sham Shui Po</option>
                        <option>Wong Tai Sin</option>
                        <option>Yau Tsim Mong</option>
                        <option>Islands</option>
                        <option>Kwai Tsing</option>
                        <option>North</option>
                        <option>Sai Kung</option>
                        <option>Sha Tin</option>
                        <option>Tai Po</option>
                        <option>Tsuen Wan</option>
                        <option>Tuen Mun</option>
                        <option>Yuen Long</option>
                     </select>
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
         <div class="container">
            <a class="navbar-brand" href="#">
               <img src="images/bubblechat.png" width="45.5" height="40" alt="BubbleChat's icon">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <div class="navbar-nav">
                  <a class="nav-item nav-link active" href="index.php"><i class="fas fa-home"></i> Home</a>
                  <a class="nav-item nav-link user-prop" href="lobby.php"><i class="fas fa-comments"></i> Lobby</a>

               </div>
               <div class="ml-md-auto">
                  <div class="user-prop">
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
                  <!-- Button trigger modal -->
                  <button type="button" id="navbar_btn_login" class="btn btn-login guest-prop" data-toggle="modal" data-target="#memberModal">Login</button>
                  <button type="button" id="navbar_btn_signup" class="btn btn-signup guest-prop" data-toggle="modal" data-target="#memberModal">Sign Up</button>
               </div>
            </div>
         </div>

     </nav>
   </header>

   <!-- Main Content -->
   <div class="main row">
      <div class="forpadding container">
         <div class="row banner align-items-center">
            <div class="col-md-12">
               <svg class="logo" width="848" height="119" viewBox="0 0 848 119" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <mask id="path-1-outside-1" maskUnits="userSpaceOnUse" x="0.1427" y="0.440002" width="848" height="118" fill="black">
                     <rect fill="white" x="0.1427" y="0.440002" width="848" height="118"/>
                     <path d="M55.3987 60.304C59.0467 60.88 62.3587 62.368 65.3347 64.768C68.4067 67.168 70.8067 70.144 72.5347 73.696C74.3587 77.248 75.2707 81.04 75.2707 85.072C75.2707 90.16 73.9747 94.768 71.3827 98.896C68.7907 102.928 64.9987 106.144 60.0067 108.544C55.1107 110.848 49.3027 112 42.5827 112H5.1427V11.632H41.1427C47.9587 11.632 53.7667 12.784 58.5667 15.088C63.3667 17.296 66.9667 20.32 69.3667 24.16C71.7667 28 72.9667 32.32 72.9667 37.12C72.9667 43.072 71.3347 48.016 68.0707 51.952C64.9027 55.792 60.6787 58.576 55.3987 60.304ZM18.2467 54.976H40.2787C46.4227 54.976 51.1747 53.536 54.5347 50.656C57.8947 47.776 59.5747 43.792 59.5747 38.704C59.5747 33.616 57.8947 29.632 54.5347 26.752C51.1747 23.872 46.3267 22.432 39.9907 22.432H18.2467V54.976ZM41.4307 101.2C47.9587 101.2 53.0467 99.664 56.6947 96.592C60.3427 93.52 62.1667 89.248 62.1667 83.776C62.1667 78.208 60.2467 73.84 56.4067 70.672C52.5667 67.408 47.4307 65.776 40.9987 65.776H18.2467V101.2H41.4307Z"/>
                     <path d="M163.439 33.088V112H150.335V100.336C147.839 104.368 144.335 107.536 139.823 109.84C135.407 112.048 130.511 113.152 125.135 113.152C118.991 113.152 113.471 111.904 108.575 109.408C103.679 106.816 99.7912 102.976 96.9112 97.888C94.1272 92.8 92.7352 86.608 92.7352 79.312V33.088H105.695V77.584C105.695 85.36 107.663 91.36 111.599 95.584C115.535 99.712 120.911 101.776 127.727 101.776C134.735 101.776 140.255 99.616 144.287 95.296C148.319 90.976 150.335 84.688 150.335 76.432V33.088H163.439Z"/>
                     <path d="M198.669 47.776C201.357 43.072 205.293 39.232 210.477 36.256C215.661 33.28 221.565 31.792 228.189 31.792C235.293 31.792 241.677 33.472 247.341 36.832C253.005 40.192 257.469 44.944 260.733 51.088C263.997 57.136 265.629 64.192 265.629 72.256C265.629 80.224 263.997 87.328 260.733 93.568C257.469 99.808 252.957 104.656 247.197 108.112C241.533 111.568 235.197 113.296 228.189 113.296C221.373 113.296 215.373 111.808 210.189 108.832C205.101 105.856 201.261 102.064 198.669 97.456V112H185.565V5.44H198.669V47.776ZM252.237 72.256C252.237 66.304 251.037 61.12 248.637 56.704C246.237 52.288 242.973 48.928 238.845 46.624C234.813 44.32 230.349 43.168 225.453 43.168C220.653 43.168 216.189 44.368 212.061 46.768C208.029 49.072 204.765 52.48 202.269 56.992C199.869 61.408 198.669 66.544 198.669 72.4C198.669 78.352 199.869 83.584 202.269 88.096C204.765 92.512 208.029 95.92 212.061 98.32C216.189 100.624 220.653 101.776 225.453 101.776C230.349 101.776 234.813 100.624 238.845 98.32C242.973 95.92 246.237 92.512 248.637 88.096C251.037 83.584 252.237 78.304 252.237 72.256Z"/>
                     <path d="M295.981 47.776C298.669 43.072 302.605 39.232 307.789 36.256C312.973 33.28 318.877 31.792 325.501 31.792C332.605 31.792 338.989 33.472 344.653 36.832C350.317 40.192 354.781 44.944 358.045 51.088C361.309 57.136 362.941 64.192 362.941 72.256C362.941 80.224 361.309 87.328 358.045 93.568C354.781 99.808 350.269 104.656 344.509 108.112C338.845 111.568 332.509 113.296 325.501 113.296C318.685 113.296 312.685 111.808 307.501 108.832C302.413 105.856 298.573 102.064 295.981 97.456V112H282.877V5.44H295.981V47.776ZM349.549 72.256C349.549 66.304 348.349 61.12 345.949 56.704C343.549 52.288 340.285 48.928 336.157 46.624C332.125 44.32 327.661 43.168 322.765 43.168C317.965 43.168 313.501 44.368 309.373 46.768C305.341 49.072 302.077 52.48 299.581 56.992C297.181 61.408 295.981 66.544 295.981 72.4C295.981 78.352 297.181 83.584 299.581 88.096C302.077 92.512 305.341 95.92 309.373 98.32C313.501 100.624 317.965 101.776 322.765 101.776C327.661 101.776 332.125 100.624 336.157 98.32C340.285 95.92 343.549 92.512 345.949 88.096C348.349 83.584 349.549 78.304 349.549 72.256Z"/>
                     <path d="M393.294 5.44V112H380.19V5.44H393.294Z"/>
                     <path d="M487.627 69.52C487.627 72.016 487.483 74.656 487.195 77.44H424.123C424.603 85.216 427.243 91.312 432.043 95.728C436.939 100.048 442.843 102.208 449.755 102.208C455.419 102.208 460.123 100.912 463.867 98.32C467.707 95.632 470.395 92.08 471.931 87.664H486.043C483.931 95.248 479.707 101.44 473.371 106.24C467.035 110.944 459.163 113.296 449.755 113.296C442.267 113.296 435.547 111.616 429.595 108.256C423.739 104.896 419.131 100.144 415.771 94C412.411 87.76 410.731 80.56 410.731 72.4C410.731 64.24 412.363 57.088 415.627 50.944C418.891 44.8 423.451 40.096 429.307 36.832C435.259 33.472 442.075 31.792 449.755 31.792C457.243 31.792 463.867 33.424 469.627 36.688C475.387 39.952 479.803 44.464 482.875 50.224C486.043 55.888 487.627 62.32 487.627 69.52ZM474.091 66.784C474.091 61.792 472.987 57.52 470.779 53.968C468.571 50.32 465.547 47.584 461.707 45.76C457.963 43.84 453.787 42.88 449.179 42.88C442.555 42.88 436.891 44.992 432.187 49.216C427.579 53.44 424.939 59.296 424.267 66.784H474.091Z"/>
                     <path d="M500.028 61.744C500.028 51.952 502.236 43.168 506.652 35.392C511.068 27.52 517.068 21.376 524.652 16.96C532.332 12.544 540.828 10.336 550.14 10.336C561.084 10.336 570.636 12.976 578.796 18.256C586.956 23.536 592.908 31.024 596.652 40.72H580.956C578.172 34.672 574.14 30.016 568.86 26.752C563.676 23.488 557.436 21.856 550.14 21.856C543.132 21.856 536.844 23.488 531.276 26.752C525.708 30.016 521.34 34.672 518.172 40.72C515.004 46.672 513.42 53.68 513.42 61.744C513.42 69.712 515.004 76.72 518.172 82.768C521.34 88.72 525.708 93.328 531.276 96.592C536.844 99.856 543.132 101.488 550.14 101.488C557.436 101.488 563.676 99.904 568.86 96.736C574.14 93.472 578.172 88.816 580.956 82.768H596.652C592.908 92.368 586.956 99.808 578.796 105.088C570.636 110.272 561.084 112.864 550.14 112.864C540.828 112.864 532.332 110.704 524.652 106.384C517.068 101.968 511.068 95.872 506.652 88.096C502.236 80.32 500.028 71.536 500.028 61.744Z"/>
                     <path d="M655.326 31.648C661.278 31.648 666.654 32.944 671.454 35.536C676.254 38.032 679.998 41.824 682.686 46.912C685.47 52 686.862 58.192 686.862 65.488V112H673.902V67.36C673.902 59.488 671.934 53.488 667.998 49.36C664.062 45.136 658.686 43.024 651.87 43.024C644.958 43.024 639.438 45.184 635.31 49.504C631.278 53.824 629.262 60.112 629.262 68.368V112H616.158V5.44H629.262V44.32C631.854 40.288 635.406 37.168 639.918 34.96C644.526 32.752 649.662 31.648 655.326 31.648Z"/>
                     <path d="M703.372 72.256C703.372 64.192 705.004 57.136 708.268 51.088C711.532 44.944 715.996 40.192 721.66 36.832C727.42 33.472 733.804 31.792 740.812 31.792C747.724 31.792 753.724 33.28 758.812 36.256C763.9 39.232 767.692 42.976 770.188 47.488V33.088H783.436V112H770.188V97.312C767.596 101.92 763.708 105.76 758.524 108.832C753.436 111.808 747.484 113.296 740.668 113.296C733.66 113.296 727.324 111.568 721.66 108.112C715.996 104.656 711.532 99.808 708.268 93.568C705.004 87.328 703.372 80.224 703.372 72.256ZM770.188 72.4C770.188 66.448 768.988 61.264 766.588 56.848C764.188 52.432 760.924 49.072 756.796 46.768C752.764 44.368 748.3 43.168 743.404 43.168C738.508 43.168 734.044 44.32 730.012 46.624C725.98 48.928 722.764 52.288 720.364 56.704C717.964 61.12 716.764 66.304 716.764 72.256C716.764 78.304 717.964 83.584 720.364 88.096C722.764 92.512 725.98 95.92 730.012 98.32C734.044 100.624 738.508 101.776 743.404 101.776C748.3 101.776 752.764 100.624 756.796 98.32C760.924 95.92 764.188 92.512 766.588 88.096C768.988 83.584 770.188 78.352 770.188 72.4Z"/>
                     <path d="M821.564 43.888V90.4C821.564 94.24 822.38 96.976 824.012 98.608C825.644 100.144 828.476 100.912 832.508 100.912H842.156V112H830.348C823.052 112 817.58 110.32 813.932 106.96C810.284 103.6 808.46 98.08 808.46 90.4V43.888H798.236V33.088H808.46V13.216H821.564V33.088H842.156V43.888H821.564Z"/>
                  </mask>
                  <path d="M55.3987 60.304C59.0467 60.88 62.3587 62.368 65.3347 64.768C68.4067 67.168 70.8067 70.144 72.5347 73.696C74.3587 77.248 75.2707 81.04 75.2707 85.072C75.2707 90.16 73.9747 94.768 71.3827 98.896C68.7907 102.928 64.9987 106.144 60.0067 108.544C55.1107 110.848 49.3027 112 42.5827 112H5.1427V11.632H41.1427C47.9587 11.632 53.7667 12.784 58.5667 15.088C63.3667 17.296 66.9667 20.32 69.3667 24.16C71.7667 28 72.9667 32.32 72.9667 37.12C72.9667 43.072 71.3347 48.016 68.0707 51.952C64.9027 55.792 60.6787 58.576 55.3987 60.304ZM18.2467 54.976H40.2787C46.4227 54.976 51.1747 53.536 54.5347 50.656C57.8947 47.776 59.5747 43.792 59.5747 38.704C59.5747 33.616 57.8947 29.632 54.5347 26.752C51.1747 23.872 46.3267 22.432 39.9907 22.432H18.2467V54.976ZM41.4307 101.2C47.9587 101.2 53.0467 99.664 56.6947 96.592C60.3427 93.52 62.1667 89.248 62.1667 83.776C62.1667 78.208 60.2467 73.84 56.4067 70.672C52.5667 67.408 47.4307 65.776 40.9987 65.776H18.2467V101.2H41.4307Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M163.439 33.088V112H150.335V100.336C147.839 104.368 144.335 107.536 139.823 109.84C135.407 112.048 130.511 113.152 125.135 113.152C118.991 113.152 113.471 111.904 108.575 109.408C103.679 106.816 99.7912 102.976 96.9112 97.888C94.1272 92.8 92.7352 86.608 92.7352 79.312V33.088H105.695V77.584C105.695 85.36 107.663 91.36 111.599 95.584C115.535 99.712 120.911 101.776 127.727 101.776C134.735 101.776 140.255 99.616 144.287 95.296C148.319 90.976 150.335 84.688 150.335 76.432V33.088H163.439Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M198.669 47.776C201.357 43.072 205.293 39.232 210.477 36.256C215.661 33.28 221.565 31.792 228.189 31.792C235.293 31.792 241.677 33.472 247.341 36.832C253.005 40.192 257.469 44.944 260.733 51.088C263.997 57.136 265.629 64.192 265.629 72.256C265.629 80.224 263.997 87.328 260.733 93.568C257.469 99.808 252.957 104.656 247.197 108.112C241.533 111.568 235.197 113.296 228.189 113.296C221.373 113.296 215.373 111.808 210.189 108.832C205.101 105.856 201.261 102.064 198.669 97.456V112H185.565V5.44H198.669V47.776ZM252.237 72.256C252.237 66.304 251.037 61.12 248.637 56.704C246.237 52.288 242.973 48.928 238.845 46.624C234.813 44.32 230.349 43.168 225.453 43.168C220.653 43.168 216.189 44.368 212.061 46.768C208.029 49.072 204.765 52.48 202.269 56.992C199.869 61.408 198.669 66.544 198.669 72.4C198.669 78.352 199.869 83.584 202.269 88.096C204.765 92.512 208.029 95.92 212.061 98.32C216.189 100.624 220.653 101.776 225.453 101.776C230.349 101.776 234.813 100.624 238.845 98.32C242.973 95.92 246.237 92.512 248.637 88.096C251.037 83.584 252.237 78.304 252.237 72.256Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M295.981 47.776C298.669 43.072 302.605 39.232 307.789 36.256C312.973 33.28 318.877 31.792 325.501 31.792C332.605 31.792 338.989 33.472 344.653 36.832C350.317 40.192 354.781 44.944 358.045 51.088C361.309 57.136 362.941 64.192 362.941 72.256C362.941 80.224 361.309 87.328 358.045 93.568C354.781 99.808 350.269 104.656 344.509 108.112C338.845 111.568 332.509 113.296 325.501 113.296C318.685 113.296 312.685 111.808 307.501 108.832C302.413 105.856 298.573 102.064 295.981 97.456V112H282.877V5.44H295.981V47.776ZM349.549 72.256C349.549 66.304 348.349 61.12 345.949 56.704C343.549 52.288 340.285 48.928 336.157 46.624C332.125 44.32 327.661 43.168 322.765 43.168C317.965 43.168 313.501 44.368 309.373 46.768C305.341 49.072 302.077 52.48 299.581 56.992C297.181 61.408 295.981 66.544 295.981 72.4C295.981 78.352 297.181 83.584 299.581 88.096C302.077 92.512 305.341 95.92 309.373 98.32C313.501 100.624 317.965 101.776 322.765 101.776C327.661 101.776 332.125 100.624 336.157 98.32C340.285 95.92 343.549 92.512 345.949 88.096C348.349 83.584 349.549 78.304 349.549 72.256Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M393.294 5.44V112H380.19V5.44H393.294Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M487.627 69.52C487.627 72.016 487.483 74.656 487.195 77.44H424.123C424.603 85.216 427.243 91.312 432.043 95.728C436.939 100.048 442.843 102.208 449.755 102.208C455.419 102.208 460.123 100.912 463.867 98.32C467.707 95.632 470.395 92.08 471.931 87.664H486.043C483.931 95.248 479.707 101.44 473.371 106.24C467.035 110.944 459.163 113.296 449.755 113.296C442.267 113.296 435.547 111.616 429.595 108.256C423.739 104.896 419.131 100.144 415.771 94C412.411 87.76 410.731 80.56 410.731 72.4C410.731 64.24 412.363 57.088 415.627 50.944C418.891 44.8 423.451 40.096 429.307 36.832C435.259 33.472 442.075 31.792 449.755 31.792C457.243 31.792 463.867 33.424 469.627 36.688C475.387 39.952 479.803 44.464 482.875 50.224C486.043 55.888 487.627 62.32 487.627 69.52ZM474.091 66.784C474.091 61.792 472.987 57.52 470.779 53.968C468.571 50.32 465.547 47.584 461.707 45.76C457.963 43.84 453.787 42.88 449.179 42.88C442.555 42.88 436.891 44.992 432.187 49.216C427.579 53.44 424.939 59.296 424.267 66.784H474.091Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M500.028 61.744C500.028 51.952 502.236 43.168 506.652 35.392C511.068 27.52 517.068 21.376 524.652 16.96C532.332 12.544 540.828 10.336 550.14 10.336C561.084 10.336 570.636 12.976 578.796 18.256C586.956 23.536 592.908 31.024 596.652 40.72H580.956C578.172 34.672 574.14 30.016 568.86 26.752C563.676 23.488 557.436 21.856 550.14 21.856C543.132 21.856 536.844 23.488 531.276 26.752C525.708 30.016 521.34 34.672 518.172 40.72C515.004 46.672 513.42 53.68 513.42 61.744C513.42 69.712 515.004 76.72 518.172 82.768C521.34 88.72 525.708 93.328 531.276 96.592C536.844 99.856 543.132 101.488 550.14 101.488C557.436 101.488 563.676 99.904 568.86 96.736C574.14 93.472 578.172 88.816 580.956 82.768H596.652C592.908 92.368 586.956 99.808 578.796 105.088C570.636 110.272 561.084 112.864 550.14 112.864C540.828 112.864 532.332 110.704 524.652 106.384C517.068 101.968 511.068 95.872 506.652 88.096C502.236 80.32 500.028 71.536 500.028 61.744Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M655.326 31.648C661.278 31.648 666.654 32.944 671.454 35.536C676.254 38.032 679.998 41.824 682.686 46.912C685.47 52 686.862 58.192 686.862 65.488V112H673.902V67.36C673.902 59.488 671.934 53.488 667.998 49.36C664.062 45.136 658.686 43.024 651.87 43.024C644.958 43.024 639.438 45.184 635.31 49.504C631.278 53.824 629.262 60.112 629.262 68.368V112H616.158V5.44H629.262V44.32C631.854 40.288 635.406 37.168 639.918 34.96C644.526 32.752 649.662 31.648 655.326 31.648Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M703.372 72.256C703.372 64.192 705.004 57.136 708.268 51.088C711.532 44.944 715.996 40.192 721.66 36.832C727.42 33.472 733.804 31.792 740.812 31.792C747.724 31.792 753.724 33.28 758.812 36.256C763.9 39.232 767.692 42.976 770.188 47.488V33.088H783.436V112H770.188V97.312C767.596 101.92 763.708 105.76 758.524 108.832C753.436 111.808 747.484 113.296 740.668 113.296C733.66 113.296 727.324 111.568 721.66 108.112C715.996 104.656 711.532 99.808 708.268 93.568C705.004 87.328 703.372 80.224 703.372 72.256ZM770.188 72.4C770.188 66.448 768.988 61.264 766.588 56.848C764.188 52.432 760.924 49.072 756.796 46.768C752.764 44.368 748.3 43.168 743.404 43.168C738.508 43.168 734.044 44.32 730.012 46.624C725.98 48.928 722.764 52.288 720.364 56.704C717.964 61.12 716.764 66.304 716.764 72.256C716.764 78.304 717.964 83.584 720.364 88.096C722.764 92.512 725.98 95.92 730.012 98.32C734.044 100.624 738.508 101.776 743.404 101.776C748.3 101.776 752.764 100.624 756.796 98.32C760.924 95.92 764.188 92.512 766.588 88.096C768.988 83.584 770.188 78.352 770.188 72.4Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
                  <path d="M821.564 43.888V90.4C821.564 94.24 822.38 96.976 824.012 98.608C825.644 100.144 828.476 100.912 832.508 100.912H842.156V112H830.348C823.052 112 817.58 110.32 813.932 106.96C810.284 103.6 808.46 98.08 808.46 90.4V43.888H798.236V33.088H808.46V13.216H821.564V33.088H842.156V43.888H821.564Z" stroke="#292F36" stroke-width="10" mask="url(#path-1-outside-1)"/>
               </svg>

            </div>
         </div>
         <div class="second row justify-content-center">
            <div class="col-md-6">
               <img src="images/chat_1.gif" class="img-fluid" alt="Responsive image">
            </div>
            <div class="col-md-6">
               <p>
                  BubbleChat offers a real-time transmission of text messages from sender to receiver. Chat messages are generally short in order to enable other participants to respond quickly. Thereby, a feeling similar to a spoken conversation is created, which distinguishes chatting from other text-based online communication forms such as Internet forums and email. Online chat may address point-to-point communications as well as multicast communications from one sender to many receivers and voice and video chat, or may be a feature of a web conferencing service.
               </p>
            </div>
         </div>
         <div class="third row justify-content-center">
            <div class="col-md-12">
               <img src="images/chat_2.jpg" class="img-fluid" alt="Responsive image">
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
