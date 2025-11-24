$(document).ready(function(){
   $("#update_se").on('submit',function(){
      $('#loading_modal').modal('show');
    });

    $("#close_adv").on('click',function(e){
      e.preventDefault();
      $(".sidebarWidget").fadeOut();
   });
   $("#show_adv").on('click',function(e){
     e.preventDefault();
     $(".sidebarWidget").fadeIn();
  });
   $("#up_adv").on('click',function(e){
     e.preventDefault();
     $(".sidebarWidget").fadeOut();
     $('#loading_modal').modal('show');
     $("#search_sidebar").submit().delay(800);
  });

    $('.remove-filter').on('click',function(){
        $('#loading_modal').modal('show');
        var did = $(this).attr('data-id');
        var dtype = $(this).attr('data-type');
        var dval = $(this).attr('data-val');
        if(dtype == 'arr'){
            $('input[value='+dval+']').remove();
        }else{
            $('input[name='+did+']').val('0');
        }
        $(this).parent().remove();
        if($('.adv-filter').length == 0){
            $("#adv_filters").remove();
        }
        $('input[name="page"]').val(1);
        $("#search_sidebar").submit().delay(800);
    });

    $('#list_filters input[type="checkbox"]').on('change',function(){
      console.log('changed');
      $('input[name="page"]').val(1);
      if($('body').hasClass('no_mobile')){
        console.log('show-it');
         $('#loading_modal').modal('show');
         $("#search_sidebar").submit().delay(800);
      }

    });

    $("#order_li").on('change',function(){
      console.log('changed');
      var vv = $(this).val();
      console.log(vv);
      $("#order_by").val(vv);
      $('input[name="page"]').val(1);
      if($('body').hasClass('no_mobile')){
        console.log('show-it');
         $('#loading_modal').modal('show');
         $("#search_sidebar").submit().delay(800);
      }
    });

    $('#list_filters select').on('change',function(){
      if($('body').hasClass('no_mobile')){
        $('#loading_modal').modal('show');
        $("#search_sidebar").submit().delay(800);
      }
    });



    $(".btn-increment").on('click',function(){
      var dtype = $(this).data('type');
      var dinput = $(this).data('input');
      var currentval = parseInt($('#'+dinput).val());
      var newval = 0;
      if(dtype == 'minus' && currentval !== 0){
        newval = currentval - 1;
      }
      if(dtype == 'plus'){
        newval = currentval + 1;
      }
      $('#'+dinput).val(newval);
      if($('body').hasClass('no_mobile')){
        $('#loading_modal').modal('show');
        $("#search_sidebar").submit().delay(800);
      }
    });


    var Link = $.noUiSlider.Link;
    var pmin = parseInt($('input[name="price_min"]').val());
    var pmax = parseInt($('input[name="price_max"]').val());
    var plimmin = parseInt($("#prange").attr('data-min'));
    var plimmax = parseInt($("#prange").attr('data-max'));
    console.log(pmin);
    console.log(pmax);
    $(".priceSlider").noUiSlider({
         range: {
          'min': plimmin,
          'max': plimmax
        },
        start: [pmin, pmax],
        step: 1000,
        margin: 100000,
        connect: true,
        direction: 'ltr',
        orientation: 'horizontal',
        behaviour: 'tap-drag',
        serialization: {
            lower: [
                new Link({
                    target: $("#price_min")
                })
            ],

            upper: [
                new Link({
                    target: $("#price_max")
                })
            ],

            format: {
            // Set formatting
                decimals: 0,
                thousand: ',',
                prefix: '$'
            }
        }
    }).on('set', function(){
      if($('body').hasClass('no-mobile')){
        $('#loading_modal').modal('show');
        $("#search_sidebar").submit().delay(800);
      }
    });


});
