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

    // add new contact - updated for new structure
    $('.add-contact').on('click',function(e){
      e.preventDefault();
      $(".errors").html('');
      var dtype = $(this).data('type');
      var contactType = $(this).data('contact-type');
      var did = $(this).data('id');
      
      var dval = $("#cval_" + did).val();
      var dtitle = $("#ctitle_" + did).val() || '';
      var ddesc = $("#cdesc_" + did).val() || '';
      var disWhatsapp = 0;
      var dlat = '';
      var dlong = '';
      
      // Get WhatsApp status for phone numbers
      if(contactType === 'phone') {
        disWhatsapp = $("#is_whatsapp_" + did).is(':checked') ? 1 : 0;
      }
      
      // Get coordinates for location
      if(contactType === 'location') {
        dlat = $("#clat_" + did).val() || '';
        dlong = $("#clong_" + did).val() || '';
      }
      
      console.log('Adding contact:', {type: contactType, value: dval, title: dtitle});

      $.post('assets/inc/process/contact_info.php',{
        ctype:'add',
        csec:dtype,
        cval:dval,
        cinput:contactType,
        cdisplay:'',
        ctitle:dtitle,
        cdesc:ddesc,
        cwhatsapp:disWhatsapp,
        clat:dlat,
        clong:dlong
      },function(data){
        if(data.success === 1){
          if(data.return !== ''){
            $("#" + contactType + "_" + dtype).prepend(data.return);
            $(".contact-wrap").delay('2000').removeClass('added');
            // Clear form fields
            $("#cval_" + did).val('');
            $("#ctitle_" + did).val('');
            $("#cdesc_" + did).val('');
            if(contactType === 'phone') {
              $("#is_whatsapp_" + did).prop('checked', false);
            }
            if(contactType === 'location') {
              $("#clat_" + did).val('');
              $("#clong_" + did).val('');
            }
          }
        }else{
          var errs = data.errors;
          if(errs.length > 0){
            $("#err_" + contactType + "_" + dtype).html('');
            $.each(data.errors, function(index, value) {
              $("#err_" + contactType + "_" + dtype).append('<div class="err alert alert-danger">'+value+'</div>');
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