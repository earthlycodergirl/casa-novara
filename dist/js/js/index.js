$(document).ready(function(){
  // input masks
  $('.money').mask('000,000,000,000,000', {reverse: true,selectOnFocus: true});

  $(".min-drop li a").on('click',function(e){
    e.preventDefault();
    var mpr = $(this).html();
    console.log(mpr);
    if($(this).hasClass('other')){
      $("#minpr").focus();
    }else{
      $("#minpr").val(mpr);
    }
  });

  $(".max-drop li a").on('click',function(e){
    e.preventDefault();
    var mpr = $(this).html();
    if($(this).hasClass('other')){
      $("#maxpr").focus();
    }else{
      $("#maxpr").val(mpr);
    }
  });

  $(".show-advanced").on('click',function(){

    $('.pricer').slideToggle();
    var htxt = $(this).data('hide');
    var stxt = $(this).data('show');
    if($(this).hasClass('on')){

      $(this).html('<i class="bi bi-gear"></i> '+stxt);
      $(this).removeClass('on');
    }else{
      $(this).html('<i class="bi bi-x-circle"></i> '+htxt);
      $(this).addClass('on');
    }
  });


});

var changeIt = function(ptype){
    var pricer1 = $('.price-1').clone();
    var pricer2 = $('.price-2').clone();
    var show1 = $('.show-1').clone();
    var show2 = $('.show-2').clone();

    

}