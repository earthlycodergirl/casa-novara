<?php 
include '../../../classes/sql.class.php';

if(isset($_POST['vis'])){
    
    
    if($_POST['vis'] != 0){
        $return['icon'] = '<i class="ti-eye text-secondary"></i>';
        $return['btn'] = '<i class="ti-eye text-success"></i> Make visible';
        $return['id'] = $_POST['lid'];
        $return['new'] = 0;
        $visibility = 0;
    }else{
        $return['icon'] = '<i class="ti-eye text-success"></i>';
        $return['btn'] = '<i class="ti-eye text-danger"></i> Make Invisible';
        $return['id'] = $_POST['lid'];
        $return['new'] = 1;
        $visibility = 1;
    }
    
    new SqlIt("UPDATE property_list SET is_visible = ? WHERE property_id = ?","update",array($visibility,$_POST['lid']));
    
    echo json_encode($return);
}


if(isset($_POST['stat'])){
    
    $return['id'] = $_POST['lid'];
    $return['new'] = $_POST['stat'];
    
    new SqlIt("UPDATE property_list SET pr_status = ? WHERE property_id = ?","update",array($return['new'],$_POST['lid']));
    
    echo json_encode($return);
}


if(isset($_POST['featured'])){
    if($_POST['featured'] != 0){
        $return['icon'] = '<i class="ti-star text-secondary"></i>';
        $return['btn'] = '<i class="ti-star text-warning"></i> Set as featured listing';
        $return['id'] = $_POST['lid'];
        $return['new'] = 0;
    }else{
        $return['icon'] = '<i class="ti-star text-warning"></i>';
        $return['btn'] = '<i class="ti-star text-mute"></i> Remove from featured listings';
        $return['id'] = $_POST['lid'];
        $return['new'] = 1;
    }
    
    new SqlIt("UPDATE property_list SET is_featured = ? WHERE property_id = ?","update",array($return['new'],$_POST['lid']));
    
    echo json_encode($return);
}

?>