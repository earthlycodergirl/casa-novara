<?php
// Enable error reporting for production debugging (commented out for live site)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Start output buffering to capture any errors
// ob_start();

// Debug: Log to error log and output (commented out for live site)
// error_log("=== CONTACT INFO DEBUG START ===");
// error_log("POST data received: " . print_r($_POST, true));

// echo "<pre>";
// echo "DEBUG: Contact Info Processing Started\n";
// echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
// echo "CONTENT_TYPE: " . (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'Not set') . "\n";
// echo "POST data received:\n";
// print_r($_POST);
// echo "</pre>";

try {
    require_once('../../../base.php');
    require_once('../../../classes/sql.class.php');
    // echo "<pre>DEBUG: SQL class loaded successfully</pre>";
} catch (Exception $e) {
    // echo "<pre>ERROR: Failed to load SQL class: " . $e->getMessage() . "</pre>";
    error_log("ERROR: Failed to load SQL class: " . $e->getMessage());
    exit;
}

$errors = array();
$success = 0;
$return = '';

if(isset($_POST['ctype'])){
    // echo "<pre>DEBUG: Processing ctype: " . $_POST['ctype'] . "</pre>";
    // error_log("Processing ctype: " . $_POST['ctype']);

  if($_POST['ctype'] == 'add'){
    // echo "<pre>DEBUG: Processing ADD request</pre>";
    
    $cinput = $_POST['cinput'];
    $cval = $_POST['cval'];
    $ctype = $_POST['csec'];
    $cdisplay = '';
    $icon = '';
    
    // echo "<pre>DEBUG: Initial values - cinput: $cinput, cval: $cval, ctype: $ctype</pre>";
    
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

    // echo "<pre>DEBUG: Enhanced fields - ctitle: '$ctitle', cdesc: '$cdesc', cwhatsapp: $cwhatsapp, clat: '$clat', clong: '$clong'</pre>";
    // echo "<pre>DEBUG: Meta data: $cmeta</pre>";

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $phone_expr = "/^[0-9.+-]/";
    
    // echo "<pre>DEBUG: Starting validation for type: $cinput</pre>";

    // Clean out data type
    if($cinput == 'email'){
      // echo "<pre>DEBUG: Validating email: $cval</pre>";
      if (filter_var($cval, FILTER_VALIDATE_EMAIL)) {
        $cval = test_input($cval);
        $icon = 'email';
        // echo "<pre>DEBUG: Email validation passed</pre>";
      }else{
        $errors[] = 'Invalid email';
        // echo "<pre>DEBUG: Email validation failed</pre>";
      }
    }

    if($cinput == 'link'){
      // echo "<pre>DEBUG: Validating link: $cval</pre>";
      $url = filter_var($cval, FILTER_SANITIZE_URL);
      if (filter_var($cval, FILTER_VALIDATE_URL)) {
          $cval = $url;
          $icon = 'link';
          // echo "<pre>DEBUG: Link validation passed</pre>";
      } else {
          $errors[] = "$url is not a valid URL";
          // echo "<pre>DEBUG: Link validation failed</pre>";
      }
    }

    if($cinput == 'phone'){
      // echo "<pre>DEBUG: Validating phone: $cval</pre>";
      if (preg_match($phone_expr, $cval) == 1){
        $icon = 'mobile';
        // echo "<pre>DEBUG: Phone validation passed</pre>";
       }else {
         $errors[] = "This phone is not valid.";
         // echo "<pre>DEBUG: Phone validation failed</pre>";
       }
    }
    
    if($cinput == 'availability'){
      // echo "<pre>DEBUG: Processing availability: $cval</pre>";
      $icon = 'time';
      $cval = test_input($cval);
    }
    
    if($cinput == 'location'){
      // echo "<pre>DEBUG: Processing location: $cval</pre>";
      $icon = 'location-pin';
      $cval = test_input($cval);
    }

    if(isset($_POST['cdisplay']) && strlen($_POST['cdisplay']) > 2){
      $cdisplay = $_POST['cdisplay'];
      $icon = $_POST['cdisplay'];
      // echo "<pre>DEBUG: Display value set: $cdisplay, icon: $icon</pre>";
    }

    // echo "<pre>DEBUG: Validation complete. Errors: " . count($errors) . "</pre>";
    if(!empty($errors)){
        // echo "<pre>DEBUG: Validation errors: " . implode(', ', $errors) . "</pre>";
    }

    if(empty($errors)){
      // echo "<pre>DEBUG: No validation errors, proceeding with database insert</pre>";
      if($_POST['ctype'] == 'add'){
        // echo "<pre>DEBUG: Preparing SQL insert with values: cinput=$cinput, cval=$cval, cdisplay=$cdisplay, ctype=$ctype, ctitle=$ctitle, cdesc=$cdesc, cwhatsapp=$cwhatsapp, cmeta=$cmeta</pre>";
        
        try {
            $addIt = new SqlIt("INSERT INTO site_contact (contact_type,contact_value,contact_display,contact_section,contact_title,contact_description,is_whatsapp,contact_meta) VALUES (?,?,?,?,?,?,?,?)","insert",array($cinput,$cval,$cdisplay,$ctype,$ctitle,$cdesc,$cwhatsapp,$cmeta));
           // echo "<pre>DEBUG: SQL insert executed</pre>";
        } catch (Exception $e) {
           // echo "<pre>ERROR: SQL insert failed: " . $e->getMessage() . "</pre>";
            error_log("ERROR: SQL insert failed: " . $e->getMessage());
            $errors[] = "Database error: " . $e->getMessage();
        }

        if($addIt){
         // echo "<pre>DEBUG: Insert successful, last ID: " . $addIt->LastID . "</pre>";
          $lastid = $addIt->LastID;
          $success = 1;
          
          // Generate proper return HTML based on section type
          if($ctype == 'office_info') {
              $titleDisplay = !empty($ctitle) ? htmlspecialchars($ctitle) : ucfirst($cinput);
              $descDisplay = !empty($cdesc) ? '<small class="text-muted d-block mt-1">'.htmlspecialchars($cdesc).'</small>' : '';
              $whatsappBadge = ($cwhatsapp == 1 && $cinput == 'phone') ? '<span class="badge badge-success">WhatsApp</span>' : '';
              
              $locationFields = '';
              if($cinput == 'location' && (!empty($clat) || !empty($clong))) {
                  $locationFields = '<div class="row mt-2">
                      <div class="col-6">
                          <small class="text-muted">Lat: '.htmlspecialchars($clat).'</small>
                      </div>
                      <div class="col-6">
                          <small class="text-muted">Lng: '.htmlspecialchars($clong).'</small>
                      </div>
                  </div>';
              }
              
              $return = '<div class="contact-wrap added contact-display-item" id="wrap_'.$lastid.'">
                  <div class="row align-items-center">
                      <div class="col-9">
                          <h6 class="mb-1">
                              <i class="ti-'.$icon.'"></i> '.$titleDisplay.' '.$whatsappBadge.'
                          </h6>
                          <div class="mb-1">
                              <strong>'.htmlspecialchars($cval).'</strong>
                          </div>
                          '.$descDisplay.'
                          '.$locationFields.'
                      </div>
                      <div class="col-3 text-right">
                          <button type="button" class="btn btn-outline-primary btn-sm update-contact" data-id="'.$lastid.'" title="Edit">
                              <i class="ti-pencil"></i>
                          </button>
                          <button type="button" class="btn btn-outline-danger btn-sm del-contact" data-id="'.$lastid.'" title="Delete">
                              <i class="ti-trash"></i>
                          </button>
                      </div>
                  </div>
              </div>';
          } else {
              $return = '<div class="contact-wrap added" id="wrap_'.$lastid.'">
                  <div class="input-group">
                      <span class="input-group-addon"><i class="ti-'.$icon.'"></i></span>
                      <input type="text" class="form-control" name="c_value" id="cval_'.$lastid.'" value="'.htmlspecialchars($cval).'">
                      <button type="button" class="btn btn-info-outline update-contact" data-id="'.$lastid.'"><i class="ti-save"></i></button>
                      <button type="button" class="btn btn-danger-outline del-contact" data-id="'.$lastid.'"><i class="ti-trash"></i></button>
                  </div>
              </div>';
          }
        }
      }
    }
  }elseif($_POST['ctype'] == 'update'){
    //echo "<pre>DEBUG: Processing UPDATE request</pre>";
    if(strlen($_POST['cval']) > 3 && $_POST['cdid'] > 0){
      $addIt = new SqlIt("UPDATE site_contact SET contact_value = ? WHERE contact_id = ?","insert",array($_POST['cval'],$_POST['cdid']));
      $success = 1;
      $return = $_POST['cdid'];
      //echo "<pre>DEBUG: Update successful for ID: " . $_POST['cdid'] . "</pre>";
    }
  }elseif($_POST['ctype'] == 'del'){
    //echo "<pre>DEBUG: Processing DELETE request</pre>";
    // delete the contact info
    if(isset($_POST['cdid']) && $_POST['cdid'] > 0){
      new SqlIt("DELETE FROM site_contact WHERE contact_id = ?","delete",array($_POST['cdid']));
      $success = 1;
      //echo "<pre>DEBUG: Delete successful for ID: " . $_POST['cdid'] . "</pre>";
    }
  }

 // echo "<pre>DEBUG: Final output - Success: $success, Errors: " . count($errors) . "</pre>";
  echo json_encode(array('errors'=>$errors,'success'=>$success,'return'=>$return));

} else {
  //echo "<pre>ERROR: No ctype parameter received in POST data</pre>";
  echo json_encode(array('errors'=>array('No ctype parameter received'),'success'=>0,'return'=>''));
}
?>