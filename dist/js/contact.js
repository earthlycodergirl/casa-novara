var code;
function createCaptcha() {
  //clear the contents of captcha div first
  document.getElementById('captcha').innerHTML = "";
  var charsArray =
  "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
  var lengthOtp = 6;
  var captcha = [];
  for (var i = 0; i < lengthOtp; i++) {
    //below code will not allow Repetition of Characters
    var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
    if (captcha.indexOf(charsArray[index]) == -1)
      captcha.push(charsArray[index]);
    else i--;
  }
  var canv = document.createElement("canvas");
  canv.id = "captcha1";
  canv.width = 100;
  canv.height = 38;
  var ctx = canv.getContext("2d");
  ctx.font = "20px Georgia";
  ctx.strokeText(captcha.join(""), 0, 30);
  //storing captcha so that can validate you can save it somewhere else according to your specific requirements
  code = captcha.join("");
  document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
}

function validateCaptcha() {
  event.preventDefault();
  //debugger
  if (document.getElementById("cpatchaTextBox").value == code) {
    return 1;
  }else{
    alert("Invalid Captcha. Please try again.");
    createCaptcha();
    return 0;
  }
}




// validate form
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})();



$(document).ready(function(){
  $("#con_form").on('submit',function(e){
    e.preventDefault();
    if(validateCaptcha()){
      var str = $("#con_form").serializeArray();
      $.ajax({
          type: "POST",
          url: "ss/bb/ei/send",
          data: str,
          dataType: 'json',
          success: function(value1) {

            console.log(value1);
            if(value1.success === 1){
              $("#con_form").fadeOut('slow',function(){
                $(".success-message").fadeIn('slow');
              });
            }else{
              var errs = value1.errors;
              console.log(Object.keys(errs).length);
              if(Object.keys(errs).length > 0){
                $.each(value1.errors, function(index, value) {
                  console.log(index+' - '+value);

                  if(index !== 'general'){
                    $("#con_form").removeClass('was-validated');
                    $("#"+index+" .form-control").addClass('is-invalid');
                  }
                });
              }
            }
          }
      });

    }
  });
  createCaptcha();
});