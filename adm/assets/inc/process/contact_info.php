<?php
require_once('../../../classes/sql.class.php');

$errors = array();
$success = 0;
$return = '';

if(isset($_POST['ctype'])){


  if($_POST['ctype'] == 'add'){
    $cinput = $_POST['cinput'];
    $cval = $_POST['cval'];
    $ctype = $_POST['csec'];
    $cdisplay = '';
    $icon = '';

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    //$phone_expr = '^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$';
    $phone_expr = "/^[0-9.+-]/";



    // Clean out data type
    if($cinput == 'email'){
      if (filter_var($cval, FILTER_VALIDATE_EMAIL)) {
        $cval = test_input($cval);
        $icon = 'email';
      }else{
        $errors[] = 'Invalid email';
      }
    }

    if($cinput == 'link'){
      $url = filter_var($cval, FILTER_SANITIZE_URL);
      if (filter_var($cval, FILTER_VALIDATE_URL)) {
          $cval = $url;
          $icon = 'link';
      } else {
          $errors[] = "$url is not a valid URL";
      }
    }

    if($cinput == 'phone'){
      if (preg_match($phone_expr, $cval) == 1){
        $icon = 'mobile';
       }else {
         $errors[] = "This phone is not valid.";
       }
    }

    if(isset($_POST['cdisplay']) && strlen($_POST['cdisplay']) > 2){
      $cdisplay = $_POST['cdisplay'];
      $icon = $_POST['cdisplay'];
    }


    if(empty($errors)){
      if($_POST['ctype'] == 'add'){
        $addIt = new SqlIt("INSERT INTO site_contact (contact_type,contact_value,contact_display,contact_section) VALUES (?,?,?,?)","insert",array($cinput,$cval,$cdisplay,$ctype));

        if($addIt){
          $lastid = $addIt->LastID;
          $success = 1;
          $return = '<div class="contact-wrap added" id="wrap_'.$lastid.'">
            <div class="input-group">
              <span class="input-group-addon"><i class="ti-'.$icon.'"></i></span>
              <input type="text" class="form-control" name="c_value" id="cval_'.$lastid.'" value="'.$cval.'">
              <button type="button" class="btn btn-info-outline update-contact" data-id="'.$lastid.'"><i class="ti-save"></i></button>
              <button type="button" class="btn btn-danger-outline del-contact" data-id="'.$lastid.'"><i class="ti-trash"></i></button>
            </div>
          </div>';
        }
      }
    }
  }elseif($_POST['ctype'] == 'update'){
    if(strlen($_POST['cval']) > 3 && $_POST['cdid'] > 0){
      $addIt = new SqlIt("UPDATE site_contact SET contact_value = ? WHERE contact_id = ?","insert",array($_POST['cval'],$_POST['cdid']));
      $success = 1;
      $return = $_POST['cdid'];
    }
  }elseif($_POST['ctype'] == 'del'){
    // delete the contact info
    if(isset($_POST['cdid']) && $_POST['cdid'] > 0){
      new SqlIt("DELETE FROM site_contact WHERE contact_id = ?","delete",array($_POST['cdid']));
      $success = 1;
    }
  }

  echo json_encode(array('errors'=>$errors,'success'=>$success,'return'=>$return));

}
?>