<?php 
include '../../../classes/sql.class.php';

$select = '';

if(isset($_POST['pid']) && $_POST['pid'] > 0){
    $getSubs = new SqlIt("SELECT * FROM property_types_sub WHERE pr_type_id = ?","select",array($_POST['pid']));
    
    if($getSubs->NumResults > 0){
        $select = '<div class="form-group"><label>Select proprty sub type</label><select name="sub_id" class="form-control"><option value="0" selected>-- Select --</option>';
        foreach($getSubs->Response as $sub){
            $select .= '<option value="'.$sub->sub_id.'">'.$sub->sub_desc.'</option>';
        }
        $select .= '</select></div>';
    }
    
    echo $select;
}
?>