$(document).ready(function() {
    //Date change
    $('.applyBtn').on('click', function() {
        $("#modalSlideUpSmall").modal('show');
        setTimeout(function() {
            $("#dateSearch").submit();
        }, 1000);
    });
    $('.editc').on('shown.bs.modal', function(e) {
        $("#phone").mask("(999) 999-9999");
    });
    $('#addClient').on('shown.bs.modal', function(e) {
        $("#phone").mask("(999) 999-9999");

    });
});

<?php if(isset($_GET['open'])){ ?>
$("#addUser").modal('show');
<?php } ?>

<?php if($success == 1){ ?>
$(document).ready(function() {
    // Apply the plugin to the body
    $('body').pgNotification({
        style: 'bar',
        message: "<?php echo $message ?>",
        position: 'top',
        type: 'info',
        timeout: 8000
    }).show();
});