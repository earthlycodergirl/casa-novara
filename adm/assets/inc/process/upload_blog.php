<?php
require_once('../../plugin/php-image-resize/lib/ImageResize.php');
require_once('../../plugin/php-image-resize/lib/ImageResizeException.php');
use \Gumlet\ImageResize;
//use \Gumlet\ImageResize;


$upload_dir = '../../../uploads/blog/';
$return = uploadImg($upload_dir,'file');

/* IMAGE TYPE CAN BE: gallery, main, page, listing_gallery */

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

    $return_array = array('errors'=>'','success'=>0,'filename'=>$files_name,'filedir'=>$directory);

    if(!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    if(!file_exists($directory.'/thumbs/')) {
        mkdir($directory.'/thumbs/', 0777, true);
    }

    $target_dir = $directory;
    $file_name = $_FILES[$files_name]["name"];
    $target_file = $target_dir . basename($_FILES[$files_name]["name"]);

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
        $return_array['errors'] = "Sorry, your file was not uploaded. Here are the errors returned:<br>".implode('<br>',$err);
    }else{
        if(move_uploaded_file($_FILES[$files_name]["tmp_name"], $target_file)){
            $return_array['filename'] = $file_name;
            $return_array['success'] = 1;
        }else{
            $return_array['errors'] = 'The file could not be uploaded';
        }
    }


    // add a reduced size image if too big
    if($width > 900 || $height > 900){
      $image = new ImageResize($directory.$file_name);
      $image->resizeToLongSide(900);
      $image->save($directory.$file_name);
    }

    // add a resized thumbnail image
    $image = new ImageResize($directory.$file_name);
    $image->resizeToBestFit(250,150);
    $image->save($directory.'thumbs/'.$file_name);




    //echo "<pre>"; print_r($err); echo "</pre>";
    return $return_array;
}

echo json_encode($return);
?>