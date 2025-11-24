<?php
// Enable error logging but disable display to prevent JSON contamination
error_reporting(E_ALL);
ini_set('display_errors', 0); // Changed to 0 to prevent output contamination
ini_set('log_errors', 1);

// Start output buffering to catch any unwanted output
ob_start();

// Add debug logging
function debug_log($message) {
    error_log(date('Y-m-d H:i:s') . " - IMAGE_UPLOAD: " . $message . "\n", 3, "../../logs/upload_debug.log");
}

debug_log("Upload script started - LID: " . (isset($_GET['lid']) ? $_GET['lid'] : 'not set'));

require_once('../../plugin/php-image-resize/lib/ImageResize.php');
require_once('../../plugin/php-image-resize/lib/ImageResizeException.php');
use \Gumlet\ImageResize;
//use \Gumlet\ImageResize;

try {
    require('../../../classes/sql.class.php');
    debug_log("SQL class loaded successfully");
} catch (Exception $e) {
    debug_log("Failed to load SQL class: " . $e->getMessage());
}

function rename_file($filename,$target){
    // Get the extension first
     $filename = strtolower($filename) ;
     $exts = preg_split("[/\\.]", $filename) ;
     $n = count($exts)-1;
     $exts = $exts[$n];

    // new name to be associated with file
     $ran = rand();

    // Return the new filename
    return $ran.'.'.$exts;
 }



function uploadImg($directory,$files_name){

    debug_log("Starting uploadImg function - Directory: $directory, Files name: $files_name");

    $return_array = array('errors'=>'','success'=>0,'filename'=>$files_name,'filedir'=>$directory);

    if(!file_exists($directory)) {
        mkdir($directory, 0777, true);
        debug_log("Created directory: $directory");
    }
    if(!file_exists($directory.'/thumbs/')) {
        mkdir($directory.'/thumbs/', 0777, true);
        debug_log("Created thumbs directory: " . $directory . '/thumbs/');
    }

    if (!isset($_FILES[$files_name])) {
        debug_log("ERROR: No file found in \$_FILES['$files_name']");
        $return_array['errors'] = "No file uploaded";
        return $return_array;
    }

    $target_dir = $directory;
    $file_name = $_FILES[$files_name]["name"];
    $target_file = $target_dir . basename($_FILES[$files_name]["name"]);

    debug_log("File info - Name: $file_name, Target: $target_file, Size: " . $_FILES[$files_name]["size"]);

    $uploadOk = 1;
    $err = array();

    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES[$files_name]["tmp_name"]);
    $width = $check[0];
    $height = $check[1];
    if($check === false) {
        $err[] = "File is not an image.";
        $uploadOk = 0;
    }

    if(file_exists($target_file)) {
        $err[] = "File already existed, renamed file automatically.";
        $file_name = rename_file($_FILES[$files_name]["name"],$target_dir);
        $target_file = $target_dir.$file_name;
    }

    if($_FILES[$files_name]["size"] > 5000000) {
        $err[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
        $err[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if($uploadOk == 0) {
        $return_array['errors'] = "Sorry, your file was not uploaded. Here are the errors returned: ".implode(' | ',$err);
    }else{
        if(move_uploaded_file($_FILES[$files_name]["tmp_name"], $target_file)){
            $return_array['filename'] = $file_name;
            $return_array['success'] = 1;
        }else{
            $return_array['errors'] = 'The file could not be uploaded';
        }
    }


    // add a reduced size image if too big
    try {
        if($width > 900 || $height > 900){
          $image = new ImageResize($directory.$file_name);
          $image->resizeToLongSide(900);
          $image->save($directory.$file_name);
        }

        // add a resized thumbnail image
        $image = new ImageResize($directory.$file_name);
        $image->resize(150,150);
        $image->save($directory.'thumbs/'.$file_name);
    } catch (Exception $e) {
        // Silently handle image processing errors
        // Could log to file if needed
    }

    //echo "<pre>"; print_r($err); echo "</pre>";
    return $return_array;
}




// Delete image function
if(isset($_POST['del_img']) && $_POST['del_img'] > 0){
    debug_log("Delete request for image ID: " . $_POST['del_img']);
    
    $getImg = new SqlIt("SELECT img_name, img_folder FROM property_photos WHERE photo_id = ?","select",array($_POST['del_img']));
    if($getImg && isset($getImg->Response[0])){
        $img_path = '../../../uploads/'.$getImg->Response[0]->img_folder.$getImg->Response[0]->img_name;
        $thumb_path = '../../../uploads/'.$getImg->Response[0]->img_folder.'thumbs/'.$getImg->Response[0]->img_name;
        
        debug_log("Attempting to delete files: " . $img_path . " and " . $thumb_path);
        
        // Delete main image
        if(file_exists($img_path)) {
            unlink($img_path);
            debug_log("Main image deleted: " . $img_path);
        } else {
            debug_log("Main image not found: " . $img_path);
        }
        
        // Delete thumbnail
        if(file_exists($thumb_path)) {
            unlink($thumb_path);
            debug_log("Thumbnail deleted: " . $thumb_path);
        } else {
            debug_log("Thumbnail not found: " . $thumb_path);
        }
    } else {
        debug_log("No image record found for ID: " . $_POST['del_img']);
    }
    
    $del_img = new SqlIt("DELETE FROM property_photos WHERE photo_id = ?","delete",array($_POST['del_img']));

    if($del_img){
        $return['success'] = 1;
        $return['id'] = $_POST['del_img'];
        debug_log("Database record deleted successfully for ID: " . $_POST['del_img']);
    }else{
        $return['success'] = 0;
        $return['id'] = 0;
        debug_log("Failed to delete database record for ID: " . $_POST['del_img']);
    }
    
    debug_log("Delete response: " . json_encode($return));
    
    // Clean any unwanted output before sending JSON
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($return);
    exit;
}



// For listings
if(isset($_GET['lid']) && $_GET['lid'] > 0){
    debug_log("Processing upload for existing listing ID: " . $_GET['lid']);
    $upload_dir = '../../../uploads/listings/'.$_GET['lid'].'/';
    $gallery_dir = 'listings/'.$_GET['lid'].'/';
    $return = uploadImg($upload_dir,'file');

    debug_log("Upload result - Success: " . $return['success'] . ", Errors: " . $return['errors'] . ", Filename: " . $return['filename']);

    if($return['success'] == 1) {
        /* IMAGE TYPE CAN BE: gallery, main, page, listing_gallery */
        try {
            $addIt = new SqlIt("INSERT INTO property_photos (img_name,img_folder,property_id,img_type) VALUES (?,?,?,?)","insert",array($return['filename'],$gallery_dir,$_GET['lid'],'listing_gallery'));

            if($addIt && $addIt->LastID > 0){
                $return['imgid'] = $addIt->LastID;
                debug_log("Database insert successful - Image ID: " . $addIt->LastID);
            } else {
                debug_log("Database insert failed - No LastID returned");
                $return['errors'] = "Database insert failed";
                $return['success'] = 0;
            }
        } catch (Exception $e) {
            debug_log("Database insert exception: " . $e->getMessage());
            $return['errors'] = "Database error: " . $e->getMessage();
            $return['success'] = 0;
        }
    }

    debug_log("Final response: " . json_encode($return));
    
    // Clean any unwanted output before sending JSON
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($return);
}elseif(isset($_GET['lid']) && $_GET['lid'] == 0){
    debug_log("Processing upload for new property (temp storage)");
    $upload_dir = '../../../uploads/listings/temp/';
    $gallery_dir = 'listings/temp/';
    $return = uploadImg($upload_dir,'file');

    debug_log("Temp upload result - Success: " . $return['success'] . ", Errors: " . $return['errors'] . ", Filename: " . $return['filename']);

    /* For new properties, we don't insert into database yet - images will be moved when property is saved */
    if($return['success'] == 1) {
        // Store the temp filename for later use
        $return['temp_file'] = 1;
        debug_log("Temp file stored successfully");
    }

    debug_log("Final temp response: " . json_encode($return));

    // Clean any unwanted output before sending JSON
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($return);
    exit;
} else {
    // No valid lid parameter
    debug_log("ERROR: No valid lid parameter provided");
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(array('errors' => 'Invalid request', 'success' => 0));
    exit;
}
?>