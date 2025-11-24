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

function show_form(){
  console.log('show form');
}

app.ready(function(){

    $(".is-featured").on('click',function(){
      var runf = 'nope';
      if($(this).hasClass('no')){
        $(this).removeClass('no');
        $(this).addClass('yes');
        runf = 'yes';
      }else if($(this).hasClass('yes')){
        $(this).removeClass('yes');
        $(this).addClass('no');
        runf = 'no';
      }

      var dtype = $(this).data('type');
      var did = $(this).data('id');
      console.log(dtype);
      console.log(did);
      $.post('assets/inc/process/update_locations.php',{subtype:354,dt:dtype,di:did,dv:runf},function(data){
        if(data.success){
          console.log('success');
        }else{
          console.log('no success');
        }
      },'json');

    })

    // Check if a notification dialog needs to be shown
    if ($('body').attr('data-alert') !== 0 && $('body').attr('data-alert') !== '0') {
        console.log($('body').attr('data-alert'));
        showAlert();
    }

    $('[data-toggle="popover"]').popover({
      content: function(){
        var did = $(this).data('id');
        var dname = $(this).data('name');
        var dtype = $(this).data('type');
        console.log(did);
        var con = '<div class="input-group edit-loc"><input type="text" class="form-control" id="input_'+did+'" placeholder="enter name" aria-label="enter name" value="'+dname+'" aria-describedby="button-addon2"><div class="input-group-append"><button class="btn btn-info loc-save" type="button" data-id="'+did+'" data-type="'+dtype+'"><i class="ti-check"></i></button><button class="btn btn-outline-secondary popover-close" type="button"><i class="ti-close"></i></button></div></div>';
        return con;
      },
      html: true
    });

    $("body").on('click',".popover-close", function(e){
      e.preventDefault();
      //console.log('close it');
      $("[data-toggle='popover']").popover('hide');
    });

    $("body").on('click',".loc-save", function(e){
      e.preventDefault();
      $('.loc-error').remove();
      $(this).html('<span class="popover-loader" role="status" aria-hidden="true"></span>');
      //console.log('save it');
      var ddid = $(this).data('id');
      var ddtype = $(this).data('type');
      var ddname = $("#input_"+ddid).val();
      $.post('assets/inc/process/update_locations.php',{act:'update',did:ddid,dname:ddname,dtype:ddtype},function(data){
          $(this).html('<i class="ti-check"></i>');
          if(data.success){
            if(data.success === 1){
              var blink = $("#"+data.return.id).find('a').attr('href');
              if(ddtype !== 'area'){
                $("#"+data.return.id).html('<a href="'+blink+'">'+data.return.name+'</a>');
              }else{
                $("#"+data.return.id).html(data.return.name);
              }
              $('.reas-select option[value="'+data.return.id+'"]').text(data.return.name);
              $("#"+data.return.id).addClass('success');
              $("[data-toggle='popover']").popover('hide');
            }
            if(data.success === 1 && data.errors.length > 0){
              $(this).insertAfter('<div class="loc-error error">'+data.errors[0]+'</div>');
            }
          }
      },'json');
    });


    $(".add-loc").on('click',function(){
      $("#dform").submit();
    });

    $(".update-loctype").on('change',function(){
      var dtype = $(this).data('type');
      if(dtype === 'state'){
        $('.load-states').show();
        window. location. replace("property_locations.php?sid="+$(this).val());
      }
      if(dtype === 'city'){
        window. location. replace("property_locations.php?sid="+$("#dstate option:selected").val()+'&cid='+$("#dcity option:selected").val());
        $('.load-cities').show();
      }
      if(dtype == 'town'){
        $('.load-towns').show();
        $("#show_locs").submit();

      }
    });

    // Edit property types
    // $('.edit-kk').on('click',function(e){
    //     e.preventDefault();
    //     var kk = $(this).attr('data-id');
    //     $(".kk-edit").hide();
    //     $("#kk_"+kk).slideDown();
    // });

    // Delete property type
    $('.delete-kk').on('click',function(e){
        e.preventDefault();
        var kk = $(this).data('id');
        var dcnt = $(this).data('cnt');
        $("#dd").val(kk);
        if(dcnt === 0){
          $('.reassign-info').hide();
          $('.yes-cnt').hide();
        }else{
          $('.reassign-info').show();
          $('.yes-cnt').show();
        }
        $('.delete-cnt').html(dcnt);
        $("#delete_prop").modal('show');
    });

});