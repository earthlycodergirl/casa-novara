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

    // Edit property types
    $('.edit-kk').on('click',function(e){
        e.preventDefault();
        var kk = $(this).attr('data-id');
        $(".kk-edit").hide();
        $("#kk_"+kk).slideDown();
    });

    // Delete property type
    $('.delete-kk').on('click',function(e){
        e.preventDefault();
        var kk = $(this).attr('data-id');
        $("#dd").val(kk);
        $("#delete_prop").modal('show');
    });

});