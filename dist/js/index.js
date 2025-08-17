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

    $('.pricer-1 .formBlock').slideToggle();
    $('.pricer-2 .formBlock').slideToggle();
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

  $("#propertyType").on('change',function(){
    var ptyp = $("option:selected", this).val();
    changeIt(ptyp);
  })

});


var changeIt = function(ptype){
    var isRes = $('.shower-1').data('rel');
    var pricer1 = $('.price-1').clone();
    var pricer2 = $('.price-2').clone();
    var show1 = $('.show-1').clone();
    var show2 = $('.show-2').clone();

    console.log(ptype);
    console.log(isRes);

    if(ptype == '20-'){
        console.log('its residential');
        if($('.shower-1 .price-1')){
            $('.shower-1').html(show1);
            $('.shower-2').html(show2);
            $('.pricer-1').html(pricer1);
            $('.pricer-2').html(pricer2);
            $('.show-advanced').show();
        }
    }else{
        console.log('its not residential');
        if($('.shower-1 .show-1')){
            $('.shower-1').html(pricer1);
            $('.shower-2').html(pricer2);
            $('.pricer-1').html(show1);
            $('.pricer-2').html(show2);
            $('.show-advanced').hide();
        }
    }

    if($('.shower-1').hasClass('pri')){
        console.log('prices are shown');
    }else if($(".shower-1").hasClass('res')){
        console.log('rooms are showing');
    }else{
        console.log('dont know why I am here');
    }
    

}