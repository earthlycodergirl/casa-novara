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

jQuery(document).ready(function(){

    // Load existing office info items from JSON data
    var officeInfoData = $('#office-info-data');
    if(officeInfoData.length > 0) {
        try {
            var contactData = JSON.parse(officeInfoData.text());
            $.each(contactData, function(index, item) {
                var sectionId = item.type + '_office_info';
                var whatsappBadge = (item.is_whatsapp == 1 && item.type == 'phone') ? '<span class="badge badge-success">WhatsApp</span>' : '';
                var metaDisplay = '';
                
                if(item.type == 'location' && item.meta) {
                    if(item.meta.latitude || item.meta.longitude) {
                        metaDisplay = '<div class="row mt-1">';
                        if(item.meta.latitude) {
                            metaDisplay += '<div class="col-6"><small class="text-muted">Lat: ' + item.meta.latitude + '</small></div>';
                        }
                        if(item.meta.longitude) {
                            metaDisplay += '<div class="col-6"><small class="text-muted">Lng: ' + item.meta.longitude + '</small></div>';
                        }
                        metaDisplay += '</div>';
                    }
                }
                
                var iconMap = {
                    'phone': 'mobile',
                    'availability': 'time',
                    'location': 'location-pin'
                };
                
                var existingItem = '<div class="contact-display-item" id="wrap_' + item.id + '">' +
                    '<div class="row align-items-center">' +
                    '<div class="col-9">' +
                    '<h6 class="mb-1">' +
                    '<i class="ti-' + iconMap[item.type] + '"></i> ' +
                    (item.title || item.type.charAt(0).toUpperCase() + item.type.slice(1)) +
                    whatsappBadge +
                    '</h6>' +
                    '<div class="mb-1"><strong>' + item.val + '</strong></div>' +
                    (item.description ? '<small class="text-muted d-block">' + item.description + '</small>' : '') +
                    metaDisplay +
                    '</div>' +
                    '<div class="col-3 text-right">' +
                    '<button type="button" class="btn btn-outline-primary btn-sm update-contact" data-id="' + item.id + '" title="Edit">' +
                    '<i class="ti-pencil"></i>' +
                    '</button>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm del-contact" data-id="' + item.id + '" title="Delete">' +
                    '<i class="ti-trash"></i>' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                
                $('#' + sectionId).append(existingItem);
            });
        } catch(e) {
            console.log('Error parsing office info data:', e);
        }
    }

    // Check if a notification dialog needs to be shown
    if ($('body').attr('data-alert') !== 0 && $('body').attr('data-alert') !== '0') {
        console.log($('body').attr('data-alert'));
        showAlert();
    }

    // add new contact - updated for new structure
    $('.add-contact').on('click',function(e){
      e.preventDefault();
      $(".errors").html('');
      
      // Store button reference before AJAX call
      var $button = $(this);
      var dtype = $button.data('type');
      var contactType = $button.data('contact-type');
      var did = $button.data('id');
      
      var dval = $("#cval_" + did).val();
      var dtitle = $("#ctitle_" + did).val() || '';
      var ddesc = $("#cdesc_" + did).val() || '';
      var disWhatsapp = 0;
      var dlat = '';
      var dlong = '';
      
      // For non-office forms, get the contact type from the form
      if(!contactType) {
        if($("#ctype_" + did).length) {
          contactType = $("#ctype_" + did).val();
        } else {
          contactType = 'email'; // Default fallback
        }
      }
      
      // Get WhatsApp status for phone numbers (from hidden input or checkbox)
      if(contactType === 'phone') {
        if($("#is_whatsapp_" + did + ":checkbox").length) {
          disWhatsapp = $("#is_whatsapp_" + did).is(':checked') ? 1 : 0;
        } else {
          disWhatsapp = parseInt($("#is_whatsapp_" + did).val()) || 0;
        }
      }
      
      // Get coordinates for location (from visible inputs or hidden inputs)
      if(contactType === 'location') {
        dlat = $("#clat_" + did).val() || '';
        dlong = $("#clong_" + did).val() || '';
      }
      
      console.log('Adding contact:', {type: contactType, value: dval, title: dtitle, section: dtype});

      $.post('assets/inc/process/contact_info.php', {
        ctype:'add',
        csec:dtype,
        cval:dval,
        cinput:contactType,
        cdisplay: $("#cdisplay_" + did).val() || '',
        ctitle:dtitle,
        cdesc:ddesc,
        cwhatsapp:disWhatsapp,
        clat:dlat,
        clong:dlong
      }, function(data){
        if(data.success === 1){
          if(data.return !== ''){
            // Determine the correct target based on whether this is an office form or regular form
            var targetId = $button.data('contact-type') ? contactType + "_" + dtype : dtype;
            console.log('Appending to target:', targetId, 'HTML:', data.return);
            $("#" + targetId).prepend(data.return);
            $(".contact-wrap").delay('2000').removeClass('added');
            
            // Clear main value field
            $("#cval_" + did).val('');
            
            // Reset hidden inputs to default values for non-office forms
            if(!$button.data('contact-type')) {
              var sectionName = dtype.replace('_', ' ');
              $("#ctitle_" + did).val(sectionName.charAt(0).toUpperCase() + sectionName.slice(1) + " Contact");
              $("#cdesc_" + did).val("Contact information for " + sectionName);
              $("#is_whatsapp_" + did).val('0');
              $("#clat_" + did).val('');
              $("#clong_" + did).val('');
              // Reset selectors
              if($("#ctype_" + did).length) {
                // Check if this is a social media form (has both ctype and cdisplay)
                if($("#cdisplay_" + did).length && $("#ctype_" + did).val() === 'link') {
                  // For social media, keep type as 'link' and reset display to first option
                  $("#ctype_" + did).val('link');
                  $("#cdisplay_" + did).prop('selectedIndex', 0);
                } else {
                  // For contact_page, reset to default 'email'
                  $("#ctype_" + did).val('email');
                }
              }
              if($("#cdisplay_" + did).length && $("#ctype_" + did).val() !== 'link') {
                // Only reset cdisplay if it's not a social media form
                $("#cdisplay_" + did).val('');
              }
            } else {
              // Clear visible fields for office forms
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
          }
        }else{
          var errs = data.errors;
          if(errs.length > 0){
            // Determine error target based on form type
            var errorTarget = $button.data('contact-type') ? "err_" + contactType + "_" + dtype : "err_" + dtype;
            $("#" + errorTarget).html('');
            $.each(data.errors, function(index, value) {
              $("#" + errorTarget).append('<div class="err alert alert-danger">'+value+'</div>');
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