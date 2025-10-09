<?php
require_once('../../../classes/sql.class.php');

$errors = array();
$success = 0;
$return = '';

if(isset($_POST['ctype'])){


    // Add new property type
    $cinput = $_POST['cinput'];
    $cval = $_POST['cval'];
    $ctype = $_POST['csec'];
    $cdisplay = '';
    $icon = '';
    
    // Get new fields
    $ctitle = isset($_POST['ctitle']) ? $_POST['ctitle'] : '';
    $cdesc = isset($_POST['cdesc']) ? $_POST['cdesc'] : '';
    $cwhatsapp = isset($_POST['cwhatsapp']) ? (int)$_POST['cwhatsapp'] : 0;
    $clat = isset($_POST['clat']) ? $_POST['clat'] : '';
    $clong = isset($_POST['clong']) ? $_POST['clong'] : '';
    
    // Build meta data for location
    $cmeta = '{}';
    if($cinput == 'location' && (!empty($clat) || !empty($clong))) {
        $cmeta = json_encode(array(
            'address' => $cval,
            'latitude' => $clat,
            'longitude' => $clong
        ));
    }
    
    // Get additional fields
    $ctitle = isset($_POST['ctitle']) ? $_POST['ctitle'] : '';
    $cdesc = isset($_POST['cdesc']) ? $_POST['cdesc'] : '';
    $cwhatsapp = isset($_POST['cwhatsapp']) ? (int)$_POST['cwhatsapp'] : 0;
    $clat = isset($_POST['clat']) ? $_POST['clat'] : '';
    $clong = isset($_POST['clong']) ? $_POST['clong'] : '';
    
    // Build meta data for location
    $meta = array();
    if($cinput == 'location' && (!empty($clat) || !empty($clong))) {
        $meta = array(
            'latitude' => $clat,
            'longitude' => $clong
        );
    }
    $meta_json = json_encode($meta);

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
    
    if($cinput == 'availability'){
        $icon = 'time';
    }
    
    if($cinput == 'location'){
        $icon = 'location-pin';
    }

    if(isset($_POST['cdisplay']) && strlen($_POST['cdisplay']) > 2){
      $cdisplay = $_POST['cdisplay'];
      $icon = $_POST['cdisplay'];
    }


    if(empty($errors)){
      if($_POST['ctype'] == 'add'){
        $addIt = new SqlIt("INSERT INTO site_contact (contact_type,contact_value,contact_display,contact_section,contact_title,contact_description,is_whatsapp,contact_meta) VALUES (?,?,?,?,?,?,?,?)","insert",array($cinput,$cval,$cdisplay,$ctype,$ctitle,$cdesc,$cwhatsapp,$meta_json));

        if($addIt){
          $lastid = $addIt->LastID;
          $success = 1;
          
          // Generate appropriate return HTML based on section type
          if($ctype == 'office_info') {
              $whatsappBadge = ($cwhatsapp == 1 && $cinput == 'phone') ? '<span class="badge badge-success">WhatsApp</span>' : '';
              $titleDisplay = !empty($ctitle) ? $ctitle : ucfirst($cinput);
              $descDisplay = !empty($cdesc) ? '<small class="text-muted">'.$cdesc.'</small>' : '';
              
              $locationFields = '';
              if($cinput == 'location' && (!empty($clat) || !empty($clong))) {
                  $locationFields = '<div class="row mt-2">
                      <div class="col-6">
                          <label class="small">Latitude:</label>
                          <input type="text" class="form-control form-control-sm" value="'.$clat.'" id="lat_'.$lastid.'">
                      </div>
                      <div class="col-6">
                          <label class="small">Longitude:</label>
                          <input type="text" class="form-control form-control-sm" value="'.$clong.'" id="lng_'.$lastid.'">
                      </div>
                  </div>';
              }
              
              $return = '<div class="contact-wrap added" id="wrap_'.$lastid.'">
                  <div class="card mb-2">
                      <div class="card-body">
                          <div class="row">
                              <div class="col-8">
                                  <h6 class="mb-1">
                                      <i class="ti-'.$icon.'"></i> '.$titleDisplay.' '.$whatsappBadge.'
                                  </h6>
                                  <div class="input-group mb-2">
                                      <input type="text" class="form-control" name="c_value" id="cval_'.$lastid.'" value="'.$cval.'">
                                  </div>
                                  '.$descDisplay.'
                                  '.$locationFields.'
                              </div>
                              <div class="col-4 text-right">
                                  <button type="button" class="btn btn-info-outline btn-sm update-contact" data-id="'.$lastid.'"><i class="ti-save"></i></button>
                                  <button type="button" class="btn btn-danger-outline btn-sm del-contact" data-id="'.$lastid.'"><i class="ti-trash"></i></button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>';
          } else {
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