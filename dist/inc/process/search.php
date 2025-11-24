<?php 
$show_listings = 1;

include '../../base.php';

$listings = new SiteListings($_POST);
$search = $listings->SearchParams;

// Test variables
printme($listings);
printme($_POST);
?>