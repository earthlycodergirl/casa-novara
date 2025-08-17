var showAlert = function () {
    'use strict';
    var atype = $('body').attr('data-alert'),
        ahea = $('body').attr('data-head'),
        atxt = $('body').attr('data-txt');
    if (atype !== 0) {
        swal({
            title: ahea,
            text: atxt,
            type: atype
        });
    }
};

app.ready(function(){

    // Check if a notification dialog needs to be shown
    if ($('body').attr('data-alert') !== 0 && $('body').attr('data-alert') !== '0') {
        console.log($('body').attr('data-alert'));
        showAlert();
    }

    // add new contact
    $('.add-contact').on('click',function(e){
      e.preventDefault();
      $(".errors").html('');
      var did = $(this).data('id');
      var dtype = $(this).data('type');
      var dval = $("#cval_"+did).val();
      var dinput = $("#ctype_"+did).val();
      var ddisplay = $("#cdisplay_"+did).val();
      var dcnt = $('.contact-wrap').length;
      console.log(dcnt);

      $.post('assets/inc/process/contact_info.php',{ctype:'add',csec:dtype,cval:dval,cinput:dinput,cdisplay:ddisplay},function(data){
        if(data.success === 1){
          if(data.return !== ''){
            $("#"+dtype).prepend(data.return);
            $(".contact-wrap").delay('2000').removeClass('added');
            $("#cval_"+did).val('');
          }
        }else{
          var errs = data.errors;
          if(errs.length > 0){
            $("#err_"+dtype).html('');
            $.each(data.errors, function(index, value) {
              $("#err_"+dtype).append('<div class="err">'+value+'</div>');
            });
          }
        }
      },'json');

    });


    // update contact
    $('.contact-content').on('click','.update-contact',function(e){
      e.preventDefault();
      var did = $(this).data('id');
      var dval = $("#cval_"+did).val();
      $(this).html('<i class="ti-check"></i>');
      $(this).parents('.input-group').addClass('editing');

      $.post('assets/inc/process/contact_info.php',{ctype:'update',cval:dval,cdid:did},function(data){
        if(data.success === 1){
          //console.log('success');
          if(data.return !== ''){
            $('.update-contact[data-id="'+data.return+'"]').delay(2000).html('<i class="ti-save"></i>');
            $('.input-group').delay(2000).removeClass('editing');
          }
        }
      },'json');

    });


    $('.contact-content').on('click','.del-contact',function(e){
      e.preventDefault();
      var did = $(this).data('id');
      $("#wrap_"+did).addClass('deleted');
      $.post('assets/inc/process/contact_info.php',{ctype:'del',cdid:did},function(data){
        if(data.success === 1){
          $("#wrap_"+did).delay('800').fadeOut();
        }
      },'json');
    })

});