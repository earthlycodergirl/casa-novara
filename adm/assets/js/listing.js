var showAlert = function () {
    'use strict';
    var atype = $('body').attr('data-alert'),
        ahea = $('body').attr('data-head'),
        dprop = $('body').attr('data-prop'),
        atxt = $('body').attr('data-txt');
    if (atype !== 0) {
        if(atype === 'success'){
            Swal.fire({
                title: ahea,
                html: '<p>The listing has been saved successfully! You can see the details saved by closing this box. <br><a href="listings.php"><u>Click here to return to all listings</u></a></p>',
                icon: atype,
                showCancelButton: true,
                showConfirmButton: true,
                showCloseButton: true,
                confirmButtonText: 'New Listing',
                cancelButtonText: 'Preview Listing',
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                  // redirect to new window
                  window.location.replace("listing.php");

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                  //window.location.replace("listings.php");
                  window.open('https://kiinrealty.com/listing/'+dprop, '_blank');
                }
              });
        }
        if(atype === 'errors'){
            Swal.fire({
                title: ahea,
                html: '<p>'+atxt+'</p>',
                type: atype
            });
        }
    }
};

app.ready(function () {

  $(".select2").select2({
    tags: true
    });

    $('input[type=text]').bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function(event) {
      event.stopImmediatePropagation();
    });


    $("#imgs_contain").sortable({
      update: function (event, ui) {
        $("#img_list").html('');
        $('#imgs_contain li.sort-me').each(function(index){
          var isrc = $(this).attr('data-name');
          $("#img_list").append('<input type="hidden" class="img-input" name="images['+index+']" value="'+isrc+'">');
        });
      },
      start: function( event, ui ) {
        ui.item.addClass('grabbing');
      },
      stop: function( event, ui ) {
        ui.item.removeClass('grabbing');
      }
    });


    $("#tb_features").sortable({
      revert: 'invalid',
      placeholder: 'feature-placeholder',
      cancel: "#add_new_feature",
      start: function(e, ui){
            ui.placeholder.height(ui.item.height());
            ui.placeholder.css('visibility', 'visible');
            ui.item.addClass('grabbing');
        },
      update: function (event, ui) {
        console.log('moved');
      },
      stop: function( event, ui ) {
        ui.item.removeClass('grabbing');
      }
    });


    $("#tb_features_es").sortable({
      revert: 'invalid',
      placeholder: 'feature-placeholder',
      cancel: "#add_new_feature_es",
      start: function(e, ui){
            ui.placeholder.height(ui.item.height());
            ui.placeholder.css('visibility', 'visible');
            ui.item.addClass('grabbing');
        },
      update: function (event, ui) {
        console.log('moved');
      },
      stop: function( event, ui ) {
        ui.item.removeClass('grabbing');
      }
    });

    $( "#tb_features_es tr, #tb_features tr, #imgs_contain li" ).disableSelection();

    // Check if a notification dialog needs to be shown
    if ($('body').attr('data-alert') !== 0 && $('body').attr('data-alert') !== '0') {
        showAlert();
    }


    // Get and display areas
    $("#nj_city").on('change',function(){
        var pp = $(this).val();
        $("#nj_county").html('<option>Loading</option>');
        $("#nj_county").attr('disabled','disabled');
        $("#nj_areas").html('<option></option>');
        $("#nj_areas").attr('disabled','disabled');
        $.post('assets/inc/process/get_areas.php',{city:pp},function(data){
            $("#nj_county").html(data);
            $("#nj_county").removeAttr('disabled');
        });
    });


    // Get and display areas
    $("#nj_county").on('change',function(){
        var pp = $(this).val();
        $("#nj_areas").html('<option>Loading...</option>');
        $("#nj_areas").attr('disabled','disabled');
        $.post('assets/inc/process/get_areas.php',{county:pp},function(data){
            $("#nj_areas").html(data);
            $("#nj_areas").removeAttr('disabled');
        });
    });

    // Get and display sub types
    $("#ptype").on('change',function(){
        var pp = $(this).val();
        if(pp === 0){
            $("#sub_types_select").html(' ');
        }else{
            $.post('assets/inc/process/get_sub_types.php',{pid:pp},function(data){
                $("#sub_types_select").html(data);
            });
        }
    });

    // Add a new feature to list
    $("#add_feature").on('click', function (e) {
        e.preventDefault();
        var fname = $('#add_new_feature input[name="feature_name[]"]');
        var fval = $('#add_new_feature input[name="feature_value[]"]');

        var newtr = $('<tr class="bg-pale-success"><td><input type="text" name="feature_name[]" class="form-control" placeholder="Feature name" value="' + fname.val() + '"></td><td><input type="text" name="feature_value[]" class="form-control" placeholder="Feature value" value="' + fval.val() + '"></td><td><button type="button" class="btn btn-pure btn-danger" data-provide="tooltip" title="Delete this feature"><i class="ti-trash"></i></button></td><td><i class="ti-move"></i></td></tr>');

        newtr.insertAfter('#add_new_feature');

        fname.val('');
        fval.val('');

        setTimeout(function () {
            $('.bg-pale-success').removeClass("bg-pale-success");
        }, 1500);
    });


    // Add a new feature to list
    $('body').on('click',"#add_feature_es", function (e) {
        e.preventDefault();
        var fname = $('#add_new_feature_es input[name="feature_name_es[]"]');
        var fval = $('#add_new_feature_es input[name="feature_value_es[]"]');

        var newtr = $('<tr class="bg-pale-success"><td><input type="text" name="feature_name_es[]" class="form-control" placeholder="Feature name" value="' + fname.val() + '"></td><td><input type="text" name="feature_value_es[]" class="form-control" placeholder="Feature value" value="' + fval.val() + '"></td><td><button type="button" class="btn btn-pure btn-danger" data-provide="tooltip" title="Delete this feature"><i class="ti-trash"></i></button></td><td><i class="ti-move"></i></td></tr>');

        newtr.insertAfter('#add_new_feature_es');

        fname.val('');
        fval.val('');

        setTimeout(function () {
            $('.bg-pale-success').removeClass("bg-pale-success");
        }, 1500);
    });

    // Remove a feature from list
    $('.delete-tr').on('click', function (e) {
        e.preventDefault();
        $(this).parents('tr').addClass('bg-pale-danger');
        $(this).parents('tr').fadeOut('slow');
        $(this).parents('tr').remove();

    });

    // Delete pricing
    $('body').on('click','.delete-price',function(e){
        e.preventDefault();
        var did = $(this).attr('data-id');
        $("#price"+did).remove();
    });

    // Delete image from gallery
    $('.del-img').on('click',function(e){
        e.preventDefault();
        var did = $(this).attr('data-id');
        $.post('assets/inc/process/upload_img.php',{del_img: did},function(data){
            $("#img"+data.id).fadeOut();
        },'json');

    });

    // Jump to section on page
    $(".nav-page").find("a").click(function (e) {
        e.preventDefault();
        var section = $(this).attr("href");
        $("html, body").animate({
            scrollTop: $(section).offset().top - 100
        });
    });

    // Add another price section
    $('.add-price').on('click',function(e){
        e.preventDefault();
        var pname = $("#pname");
        var pdesc = $("#pdesc");
        var pamt = $("#pamt");
        var ptype = $("#pricetype");
        var pcnt = $('.prices').length;
        var pcurr = $('#pcurr');
        var addcnt = pcnt + 655;
        var $options = $("#pricetype > option").clone();
        var $curr = $("#pcurr > option").clone();

        var new_price = '<input type="hidden" name="price_desc[]" class="form-control" value="'+pdesc.val()+'"><div class="row prices" id="price'+addcnt+'"><div class="col-3"><div class="form-group"><label>Price Title</label><input type="text" name="price_name[]" class="form-control" value="'+pname.val()+'"></div></div><div class="col-3"><div class="form-group"><label>Price amount</label><div class="input-group"><span class="input-group-addon"><i class="ti-money"></i></span><input type="text" name="price_amt[]" class="form-control" value="'+pamt.val()+'"></div></div></div><div class="col-2"><div class="form-group"><label>Currency</label><select name="price_curr[]" class="form-control" id="curr'+addcnt+'"></select></div></div><div class="col-3"><div class="form-group"><label>Price type</label><select name="price_type[]" class="form-control" id="select'+addcnt+'"></select></div></div><div class="col-1"><button type="button" data-id="'+addcnt+'" class="btn btn-sm btn-outline btn-danger text-center delete-price" data-provide="tooltip" title="Delete this pricing from listing"><i class="ti-trash"></i></button></div></div>';

        $("#prices_to_add").prepend(new_price);
        $('#select'+addcnt).append($options);
        $('#select'+addcnt+' option[value="'+ptype.val()+'"]').attr('selected','selected');

        $('#curr'+addcnt).append($curr);
        $('#curr'+addcnt+' option[value="'+pcurr.val()+'"]').attr('selected','selected');

        $('#pricetype option[value="'+ptype.val()+'"]').prop('selected',false);
        $('#pricetype option[value="0"]').attr('selected','selected');

        pname.val('');
        pdesc.val('');
        pamt.val('');

    });

    $('.submit-form').on('click',function(){
        $("#loading_modal").modal('show');
    });


});

$.fn.isFullyInViewport = function () {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop() + 200;
    var viewportBottom = viewportTop + $(window).height();

    return elementTop <= viewportTop && elementBottom > viewportTop;
};

$(window).on('resize scroll', function () {
    $('.card').each(function () {
        var activeSec = $(this).attr('id');
        if ($(this).isFullyInViewport()) {
            $('a[href="#' + activeSec + '"]').addClass('active');
        } else {
            $('a[href="#' + activeSec + '"]').removeClass('active');
        }
    });
});
