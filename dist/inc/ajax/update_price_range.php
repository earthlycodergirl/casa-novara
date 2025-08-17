<?php 
include '../../adm/classes/sql.class.php';
include '../../classes/publist.class.php';

if(isset($_POST['ltype']) && $_POST['ltype'] > 0){
    $listings = new SiteListings();
    $price_range = $listings->getPriceRange($_POST['ltype']);
    echo json_encode($price_range);
}
?>