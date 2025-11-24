$(document).ready(function(){
  $('.next-btn').on('click',function(e){
    e.preventDefault();
    var dtype = $(this).data('type');

    if(dtype === 2){
      // check address
      if($('input[name="address"]').val().length > 8){
        $(".step-1").fadeOut('slow',function(){
          $(".step-2").fadeIn();
          $('.error').remove();
        });
      }else{
        $(".step-1").append('<div class="error">Please enter your full address to continue</div>');
      }
    }else if(dtype === 3){
      if($('input[name="full_name"]').val().length > 5){
        $(".step-2").fadeOut('slow',function(){
          $(".step-3").fadeIn();
          $('.error').remove();
        });
      }else{
        $(".step-2").append('<div class="error">Please enter your name continue</div>');
      }
    }else{
      if($('input[name="email"]').val().length > 5){
        $(".step-3").fadeOut('slow',function(){
          $(".success-message").fadeIn().delay(2000).fadeOut();
          $(".step-1").fadeIn();
          $('.error').remove();
        });
      }else{
        $(".step-1").append('<div class="error">Please enter your full address to continue</div>');
      }
    }
  })
})