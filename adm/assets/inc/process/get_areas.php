<?php
include '../../../classes/sql.class.php';
include '../../../classes/listings.class.php';

$success = 0;
$return = '<option value="Undefined">No areas found</option>';

if(isset($_POST['county']) && $_POST['county'] != ''){

    $return = '<option value="Undefined">- Select Area -</option>';
    $list = new Listings();
    $list->getAreas($_POST['county']);


    if(is_array($list->Areas) && !empty($list->Areas)){
        $success = 1;
        foreach($list->Areas as $kk=>$ss){
            $return .= '<option value="'.$kk.'">'.$ss.'</option>';
        }
    }else{
        $return = '<option value="0">No Results</option>';
    }
}


if(isset($_POST['city']) && $_POST['city'] != ''){

    $return = '<option value="Undefined">- Select Town -</option>';
    $list = new Listings();
    $list->getTowns($_POST['city']);


    if(is_array($list->Towns) && !empty($list->Towns)){
        $success = 1;
        foreach($list->Towns as $kk=>$ss){
            $return .= '<option value="'.$kk.'">'.$ss.'</option>';
        }
    }else{
      $return = '<option value="0">No Results</option>';
    }
}


if(isset($_POST['states']) && $_POST['states'] != ''){

    $return = '<option value="Undefined">- Select Municipality -</option>';
    $list = new Listings();
    $list->getCities($_POST['states']);


    if(is_array($list->Cities) && !empty($list->Cities)){
        $success = 1;
        foreach($list->Cities as $kk=>$ss){
            $return .= '<option value="'.$kk.'">'.$ss.'</option>';
        }
    }
}

echo $return;

?>
