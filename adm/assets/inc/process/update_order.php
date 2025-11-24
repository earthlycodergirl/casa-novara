<?php 
include '../../classes/sql.class.php';

if(isset($_POST['type'])){
    if($_POST['type'] == 'update_gallery_order'){
        if(!empty($_POST['ord'])){
            foreach($_POST['ord'] as $key=>$val){
                new SqlIt("UPDATE sys_gallery SET gallery_order = ? WHERE gallery_id = ?","update",array($val,$key));
            }
        }
    }
}

?>