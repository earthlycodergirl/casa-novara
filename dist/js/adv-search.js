$(document).ready(function(){

    // search types drop down function
    $("#search_type").on('change',function(){
        var stype = $(this).val();

        // hide all previously opened search types
        $('.adv-search-drop').slideUp('fast');
        if(stype !== 'all'){
            $('#'+stype).slideDown('slow');
        }

        if(stype === 'mls'){
            $("#non_mls").hide();
            $("#stype").val('mls_lookup');
        }else{
            $("#non_mls").show();
            $("#stype").val('advanced');
        }
    });

    // property types to show subtypes
    $(".ptypes").on('change',function(){
        if($(this).attr('id') == 'all_ptypes'){
            $('.ptypes').prop('checked',false);
            $("#all_ptypes").prop('checked',true);
            $("#sub_message").show();
        }
        // hide sub message

        if ($(".ptypes:checked").length >= 1){
            $("#sub_message").hide();
            $("#all_ptypes").prop('checked', false);
        }else{
            $("#sub_message").show();
            if($(".ptypes:checked").length == 0){
                $("#all_ptypes").prop('checked', true);
            }
        }

        // loop through to check checked
        $(".ptypes").each(function(){
            var ptype = $(this).attr('data-id');
            if($(this).is(":checked") && $(this).val() !== 0){
                $("#sub"+ptype).show();
            }else{
                $("#sub"+ptype).hide();
            }
        });
    });

    // input masks
    $('.money').mask('000,000,000,000,000', {reverse: true,selectOnFocus: true});

    // uncheck all on search type checkboxes
    $(".adv-search-drop .form-check input").on("change",function(){
        var chbox = $(this).attr('name');
        var chcheck = $('input[name="'+chbox+'"]:checked').length;
        var chall = chbox.slice(0,-2);
        var chval = $(this).val();
        console.log($(this).attr('id'));
        if($(this).attr('id') == 'all_'+chall){
            $('input[name="'+chbox+'"]').prop('checked',false);
            $("#all_"+chall).prop('checked',true);
        }else{
            if(chcheck < 1){
                $("#all_"+chall).prop('checked', true);
            }else{
                $("#all_"+chall).prop('checked', false);
            }
        }

    });


    // check the parent when child destination is selected
    $('.county-check').on('change',function(){
      var pid = $(this).data('parent');
      $("#ci"+pid).attr('checked','checked');

    });

});