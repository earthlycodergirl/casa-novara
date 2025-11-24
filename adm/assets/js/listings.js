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

app.ready(function () {
    
    // Check if a notification dialog needs to be shown
    if ($('body').attr('data-alert') !== 0 && $('body').attr('data-alert') !== '0') {
        console.log($('body').attr('data-alert'));
        showAlert();
    }

    // Update listing availability
    $('.update-visibility').on('click',function(){
        var vis_id = $(this).attr('data-rel');
        var pr_id = $(this).attr('data-id');
        
        $.post('assets/inc/process/update_listing.php',{vis:vis_id,lid:pr_id},function(data){
            $('#tr_vis_'+data.id).html(data.icon);
            $('#btn_vis_'+data.id).html(data.btn);
            $('#btn_vis_'+data.id).attr('data-rel',data.new);
            $('#tr_vis_'+data.id).attr('data-rel',data.new);
        },'json');
    });
    
    // Update listing status
    $('.update-status').on('click',function(e){
        e.preventDefault();
        var new_stat = $(this).attr('data-rel');
        var pr_id = $(this).attr('data-id');
        
        $.post('assets/inc/process/update_listing.php',{stat:new_stat,lid:pr_id},function(data){
            $("#stat_"+data.id).html(data.new);
            $('.update-status').addClass('show-me');
            $('.update-status[data-rel="'+data.new+'"]').removeClass('show-me');
        },'json');
    });
    
    // Update listing as featured
    $('.update-featured').on('click',function(e){
        e.preventDefault();
        console.log('clicked!');
        var new_stat = $(this).attr('data-rel');
        var pr_id = $(this).attr('data-id');
        
        $.post('assets/inc/process/update_listing.php',{featured:new_stat,lid:pr_id},function(data){
            $('#tr_feat_'+data.id).html(data.icon);
            $('#btn_feat_'+data.id).html(data.btn);
            $('#btn_feat_'+data.id).attr('data-rel',data.new);
            $('#tr_feat_'+data.id).attr('data-rel',data.new);
        },'json');
    });
    
    // Delete listing
    $('body').on('click','.delete-listing',function(e){
        e.preventDefault();
        $("#dd").val($(this).attr('data-id'));
        $("#delete_prop").modal('show');
    });
    
    $('[data-toggle="tooltip"]').tooltip();

    var multisel = '<div class="multi_sel"><div class="dropdown">'+
        '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
            'Select Action'+
        '</button>'+
        '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'+
        '<a class="dropdown-item delete-all" href="javascript:void(0)">Delete all</a>'+
        '<a class="dropdown-item" href="javascript:void(0)">Mark Featured</a>'+
        '<a class="dropdown-item" href="javascript:void(0)">Mark Unfeatured</a>'+
        '<a class="dropdown-item" href="javascript:void(0)">Set Active</a>'+
        '<a class="dropdown-item" href="javascript:void(0)">Set Inactive</a>'+
        '</div> <span class="info">will apply to all selected</span>'+
    '</div>';

    $("#select_all").on('click',function(){
        if($(this).is(":checked")){
            $('input[name="select_id"]').prop( "checked", true );
            $(".dataTables_length").append(multisel);
        }else{
            $('input[name="select_id"]').prop( "checked", false );
            $('#multi_sel').remove();
        }
    });

    $('body').on('click','.delete-all',function(e){
        e.preventDefault();
        $("#delete_ids").empty();
        $("delete_all_inp").val(1);
        $('input[name="select_id"]:checked').each(function(){
            $("#delete_ids").append("<input type='hidden' name='delete_ids[]' value='" + $(this).val() + "'>");
        });
        $("#delete_all").modal('show');
    });
});