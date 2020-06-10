var navbar_btn_createchatroom = document.getElementById("navbar_btn_createchatroom");

var modaltitle = document.getElementById("modaltitle");

var createchatroomform = document.getElementById("createchatroomform");

var feedback = document.getElementById("feedback");

// Invalid feedback in create chatroom form
var chatroom_name = document.getElementById("chatroom_name");
var chatroom_name_feedback = document.getElementById("chatroom_name_feedback");

var btn_submit_create = document.getElementById("btn_submit_create");

var btn_submit_confirm = document.getElementById("btn_submit_confirm");

function showcreatechatroomform() {
   modaltitle.innerHTML = "Create a Chatroom";
   feedback.style.display = "none";
   btn_submit_confirm.style.display = "none";

   createchatroomform.style.display = "block";
   btn_submit_create.style.display = "block";
}

function showconfirmform( title, feedbackMessage ) {
   $("#btn_submit_create").prop('disabled', false);
   modaltitle.innerHTML = title;
   createchatroomform.style.display = "none";
   btn_submit_create.style.display = "none";

   feedback.style.display = "block";
   feedback.innerHTML = feedbackMessage;
   btn_submit_confirm.style.display = "block";
}

navbar_btn_createchatroom.onclick = function() {
   showcreatechatroomform()
};

// Handle Validation

// Create form validation
btn_submit_create.onclick = function() {
   if (createchatroomform.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
      createchatroomform.classList.add('was-validated');
   } else {
      $.ajax( {
         url:  $('#createchatroomform').attr("action"),
         type: $('#createchatroomform').attr("method"),
         data: $('#createchatroomform').serialize(),
         success: function(data) {
            if ( data == "Error: unable to create the chatroom" ) {
               chatroom_name.value = "";
               chatroom_name.classList.add('is-invalid');
               chatroom_name_feedback.innerHTML = "Error: unable to create the chatroom";

               createchatroomform.classList.add('was-validated');
            } else if ( data == "Chatroom Created") {
               showconfirmform( 'Success', data );
            }

         }
      } );
   }
}

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
