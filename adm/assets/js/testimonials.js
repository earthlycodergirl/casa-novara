var showAlert = function () {
    'use strict';
    var atype = $('body').attr('data-alert'),
        ahea = $('body').attr('data-head'),
        atxt = $('body').attr('data-txt');
    console.log()
    if (atype !== 0) {
        if(atype === 'success'){
            swal({
                title: ahea,
                text: atxt,
                type: atype
            });
        }
        if(atype === 'danger'){
            swal({
                title: ahea,
                text: atxt,
                type: atype
            });
        }
    }
};


app.ready(function(){
    $('.delete-entry').on('click',function(e){
        e.preventDefault();
        var bid = $(this).attr('data-id');
        $("#dd").val(bid);
        $('#delete_entry').modal('show');
    });
    
    // Check if a notification dialog needs to be shown
    if ($('body').attr('data-alert') !== 0 && $('body').attr('data-alert') !== '0') {
        console.log($('body').attr('data-alert'));
        showAlert();
    }
});