<?php
// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Check if includes exist
$sql_path = '../../../classes/sql.class.php';
$listings_path = '../../../classes/listings.class.php';

if (!file_exists($sql_path)) {
    error_log("get_areas.php - ERROR: sql.class.php not found at: " . realpath($sql_path));
    echo '<option value="0">SQL class error</option>';
    exit;
}

try {
    include $sql_path;
    //echo "get_areas.php - sql.class.php included successfully";
} catch (Exception $e) {
    error_log("get_areas.php - ERROR including sql.class.php: " . $e->getMessage());
    $return = '<option value="0">SQL include error</option>';
}

try {
    include $listings_path;
    //echo "get_areas.php - listings.class.php included successfully";
} catch (Exception $e) {
    error_log("get_areas.php - ERROR including listings.class.php: " . $e->getMessage());
    $return = '<option value="0">Listings include error</option>';
}

$success = 0;
$return = '<option value="Undefined">No areas found</option>';

// Log the incoming request
// echo "get_areas.php - POST data received: " . print_r($_POST, true);
// echo "get_areas.php - REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'];
// echo "get_areas.php - Current working directory: " . getcwd();

// Simple output test
if (empty($_POST)) {
    error_log("get_areas.php - No POST data received");
    echo '<option value="0">No data received</option>';
    exit;
}

if(isset($_POST['county']) && $_POST['county'] != ''){
    $return = '<option value="Undefined">- Select Area -</option>';
    $list = new Listings();
    
    try {
        $list->getAreas($_POST['county']);
        error_log("get_areas.php - getAreas called for county: " . $_POST['county']);
        
        if(is_array($list->Areas) && !empty($list->Areas)){
            $success = 1;
            foreach($list->Areas as $kk=>$ss){
                $return .= '<option value="'.$kk.'">'.utf8_encode($ss).'</option>';
            }
            error_log("get_areas.php - Found " . count($list->Areas) . " areas");
        }else{
            $return = '<option value="0">No Results</option>';
            error_log("get_areas.php - No areas found for county: " . $_POST['county']);
        }
    } catch (Exception $e) {
        error_log("get_areas.php - Error getting areas: " . $e->getMessage());
        $return = '<option value="0">Error loading areas</option>';
    }
}


if(isset($_POST['city']) && $_POST['city'] != ''){
    $return = '<option value="Undefined">- Select City -</option>';
    $list = new Listings();
    
    try {
        $list->getTowns($_POST['city']);
        error_log("get_areas.php - getTowns called for city: " . $_POST['city']);
        
        if(is_array($list->Towns) && !empty($list->Towns)){
            $success = 1;
            foreach($list->Towns as $kk=>$ss){
                $return .= '<option value="'.$kk.'">'.utf8_encode($ss).'</option>';
            }
            error_log("get_areas.php - Found " . count($list->Towns) . " towns");
        }else{
            $return = '<option value="0">No Results</option>';
            error_log("get_areas.php - No cities found in : " . utf8_encode($_POST['city']));
        }
    } catch (Exception $e) {
        error_log("get_areas.php - Error getting towns: " . $e->getMessage());
        $return = '<option value="0">Error loading towns</option>';
    }
}


if(isset($_POST['states']) && $_POST['states'] != ''){
    $return = '<option value="Undefined">- Select Municipality -</option>';
    $list = new Listings();
    
    try {
        $list->getCities($_POST['states']);
        error_log("get_areas.php - getCities called for state: " . $_POST['states']);
        
        if(is_array($list->Cities) && !empty($list->Cities)){
            $success = 1;
            foreach($list->Cities as $kk=>$ss){
                $return .= '<option value="'.$kk.'">'.utf8_encode($ss).'</option>';
            }
            error_log("get_areas.php - Found " . count($list->Cities) . " cities");
        }else{
            $return = '<option value="0">No Results</option>';
            error_log("get_areas.php - No cities found for state: " . $_POST['states']);
        }
    } catch (Exception $e) {
        error_log("get_areas.php - Error getting cities: " . $e->getMessage());
        $return = '<option value="0">Error loading cities</option>';
    }
}

error_log("get_areas.php - Final output: " . $return);
error_log("get_areas.php - SCRIPT ENDING - " . date('Y-m-d H:i:s'));
echo $return;
?>
