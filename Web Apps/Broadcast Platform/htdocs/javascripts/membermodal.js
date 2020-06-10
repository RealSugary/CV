// Open member modal
var navbar_btn_login = document.getElementById("navbar_btn_login");
var navbar_btn_signup = document.getElementById("navbar_btn_signup");

var modaltitle = document.getElementById("modaltitle");

// Form selectors
var link_forgotpw = document.getElementById("link_forgotpw");
var form_selector_login = document.getElementById("form_selector_login");
var form_selector_signup = document.getElementById("form_selector_signup");
var signup_selector_viewer = document.getElementById("signup_selector_viewer");
var signup_selector_broadcaster = document.getElementById("signup_selector_broadcaster");

// Fetch all the forms
var loginform = document.getElementById("loginform");
var forgotpwform = document.getElementById("forgotpwform");
var signupform = document.getElementById("signupform");
var regbroadcasterform = document.getElementById("regbroadcasterform");
var feedback = document.getElementById("feedback");

// Invalid feedback in login form
var login_username = document.getElementById("login_username");
var login_username_feedback = document.getElementById("login_username_feedback");
var login_pw = document.getElementById("login_pw");
var login_pw_feedback = document.getElementById("login_pw_feedback");

// Invalid feedback in signup form
var signup_username = document.getElementById("signup_username");
var signup_username_feedback = document.getElementById("signup_username_feedback");
var signup_pw = document.getElementById("signup_pw");
var signup_pw_feedback = document.getElementById("signup_pw_feedback");
var signup_confirm_pw = document.getElementById("signup_confirm_pw");
var signup_confirm_pw_feedback = document.getElementById("signup_confirm_pw_feedback");
var signup_email = document.getElementById("signup_email");
var signup_email_feedback = document.getElementById("signup_email_feedback");
var signup_hashtags_feedback = document.getElementById("signup_hashtags_feedback");

// Form buttons
var btn_submit_login = document.getElementById("btn_submit_login");
var btn_submit_signup = document.getElementById("btn_submit_signup");
var btn_submit_confirm = document.getElementById("btn_submit_confirm");
var btn_submit_forgotpw = document.getElementById("btn_submit_forgotpw");

function showloginform() {
   if ( form_selector_signup.classList.contains("active") ) {
      form_selector_signup.classList.remove("active");
   }
   modaltitle.innerHTML = "Login to BubbleBeeTV";
   feedback.style.display = "none";
   signupform.style.display = "none";
   forgotpwform.style.display = "none";
   regbroadcasterform.style.display = "none";
   btn_submit_signup.style.display = "none";
   btn_submit_confirm.style.display = "none";
   btn_submit_forgotpw.style.display = "none";

   form_selector_login.classList.add("active");
   loginform.style.display = "block";
   btn_submit_login.style.display = "block";
}

function showforgotpwform() {
   modaltitle.innerHTML = "Forgot Password";
   feedback.style.display = "none";
   loginform.style.display = "none";
   signupform.style.display = "none";
   regbroadcasterform.style.display = "none";
   btn_submit_login.style.display = "none";
   btn_submit_confirm.style.display = "none";
   btn_submit_signup.style.display = "none";
   form_selector_login.style.display = "none";
   form_selector_signup.style.display = "none"


   forgotpwform.style.display = "block";
   btn_submit_forgotpw.style.display = "block";
}

function showsignupform() {
   if ( form_selector_login.classList.contains("active") ) {
      form_selector_login.classList.remove("active");
   }
   modaltitle.innerHTML = "Join BubbleBeeTV";
   feedback.style.display = "none";
   loginform.style.display = "none";
   forgotpwform.style.display = "none";
   btn_submit_login.style.display = "none";
   btn_submit_confirm.style.display = "none";
   btn_submit_forgotpw.style.display = "none";
   form_selector_signup.classList.add("active");

   signupform.style.display = "block";
   btn_submit_signup.style.display = "block";

   if ( signup_selector_viewer.checked == true ) {
      regbroadcasterform.style.display = "none";
   } else {
      regbroadcasterform.style.display = "block";
   }
}

function showconfirmform( title, feedbackMessage ) {
   $("#btn_submit_signup").prop('disabled', false);
   modaltitle.innerHTML = title;
   loginform.style.display = "none";
   signupform.style.display = "none";
   forgotpwform.style.display = "none";
   regbroadcasterform.style.display = "none";
   btn_submit_login.style.display = "none";
   btn_submit_signup.style.display = "none";
   btn_submit_forgotpw.style.display = "none";
   form_selector_login.style.display = "none";
   form_selector_signup.style.display = "none";

   feedback.style.display = "block";
   feedback.innerHTML = feedbackMessage;
   btn_submit_confirm.style.display = "block";
}

link_forgotpw.onclick = function() {
   showforgotpwform()
};

navbar_btn_login.onclick = function() {
   showloginform()
};

navbar_btn_signup.onclick = function() {
   showsignupform()
};

form_selector_login.onclick = function() {
   showloginform()
};

form_selector_signup.onclick = function() {
   showsignupform()
};

signup_selector_viewer.onclick = function() {
   regbroadcasterform.style.display = "none"
}

signup_selector_broadcaster.onclick = function() {
   regbroadcasterform.style.display = "block"
}

btn_submit_confirm.onclick = function() {
   signupform.reset();
   if ( signup_selector_viewer.checked == true ) {
      regbroadcasterform.style.display = "none";
   } else {
      regbroadcasterform.style.display = "block";
   }
}

// Handle Validation

// Login form validation
btn_submit_login.onclick = function() {
   if (loginform.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
      loginform.classList.add('was-validated');
   } else {
      $.ajax( {
         url:  $('#loginform').attr("action"),
         type: $('#loginform').attr("method"),
         data: $('#loginform').serialize(),
         success: function(data) {
            if ( data == "Captcha is required" ) {
               login_username.value = "";
               login_username.classList.add('is-invalid');
               login_username_feedback.innerHTML = "";

               login_pw.value = "";
               login_pw.classList.add('is-invalid');
               login_pw_feedback.innerHTML = data;

               loginform.classList.add('was-validated');
            } else {
               if ( data == "Captcha verification failed" ) {
                  login_username.value = "";
                  login_username.classList.add('is-invalid');
                  login_username_feedback.innerHTML = "";

                  login_pw.value = "";
                  login_pw.classList.add('is-invalid');
                  login_pw_feedback.innerHTML = data;

                  loginform.classList.add('was-validated');
               } else {
                  if ( data == "Invalid username or password" ) {
                     login_username.value = "";
                     login_username_feedback.innerHTML = "";
                     login_username.classList.add('is-invalid');

                     login_pw.value = "";
                     login_pw.classList.add('is-invalid');
                     login_pw_feedback.innerHTML = data;

                     loginform.classList.add('was-validated');
                  } else {
                     if ( data == "Account have not been activated" ) {
                        login_username_feedback.innerHTML = "";
                        login_username.classList.add('is-invalid');

                        login_pw.value = "";
                        login_pw.classList.add('is-invalid');
                        login_pw_feedback.innerHTML = data;

                        loginform.classList.add('was-validated');

                     } else if ( data == "Login successful") {
                        location.reload();
                     }
                  }
               }
            }

         }
      } );
   }
}

btn_submit_signup.onclick = function() {
   if (signupform.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
      signupform.classList.add('was-validated');
   } else {
      if ( signup_pw.value != signup_confirm_pw.value ) {
         signup_pw.value = "";
         signup_confirm_pw.value = "";
         signup_pw_feedback.innerHTML = "Please enter same password for confirm";
         signup_confirm_pw_feedback.innerHTML = "Please enter same password for confirm";
         signup_pw.classList.add('is-invalid');
         signup_confirm_pw.classList.add('is-invalid');
         signupform.classList.add('was-validated');
      } else {
         $.ajax( {
            url:  $('#signupform').attr("action"),
            type: $('#signupform').attr("method"),
            data: $('#signupform').serialize(),
            beforeSend: function() {
               $("#btn_submit_signup").prop('disabled', true);},
            success: function(data) {
               if ( data == "Please check at least one # Hashtag" ) {
                  signup_hashtags_feedback.style.display = "block";
                  signupform.classList.add('was-validated');
                  $("#btn_submit_signup").prop('disabled', false);
               } else {
                  if ( data == "Username already exists" ) {
                     signup_username.value = "";
                     signup_pw.value = "";
                     signup_confirm_pw.value = "";
                     signup_username_feedback.innerHTML = data;
                     signup_username.classList.add('is-invalid');
                     signupform.classList.add('was-validated');
                     $("#btn_submit_signup").prop('disabled', false);
                  } else {
                     if ( data == "Email already exists" ) {
                        signup_pw.value = "";
                        signup_confirm_pw.value = "";
                        signup_email.value = "";
                        signup_email_feedback.innerHTML = data;
                        signup_email.classList.add('is-invalid');
                        signupform.classList.add('was-validated');
                        $("#btn_submit_signup").prop('disabled', false);
                     } else {
                        if ( data == "Activated mail had sent. Please activate your account via the activated link." ) {
                           showconfirmform( 'Congratulations', data );
                        } else {
                           if ( data == "Error: Unable to send activated mail" ) {
                              showconfirmform( 'System', data );
                           } else {
                              if ( data == "Error: unable to sign up your Broadcaster account" ) {
                                 showconfirmform( 'System', data );
                              } else {
                                 if ( data == "Error: unable to sign up your User account" ) {
                                    showconfirmform( 'System', data );
                                 } else {
                                    showconfirmform( 'System', data );
                                 }
                              }
                           }
                        }




                     }
                  }
               }
            }
         });
      }
   }
}
// Handle Login by Enter
$(".enter").on('keyup',function(e){
   if(e.keyCode==13 && !e.shiftKey)
   {
      btn_submit_login.click();
   }
});
window.addEventListener('load', function() {
   // Fetch all the forms we want to apply custom Bootstrap validation styles to
   var forms = document.getElementsByClassName('needs-validation');
   // Loop over them and prevent submission
   var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
         if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
         }
        form.classList.add('was-validated');
      }, false);
   });


}, false);
