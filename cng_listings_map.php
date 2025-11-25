<?php
session_start();

// Error display disabled for production (re-enable if debugging needed)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$show_listings = 1;
$build_search = 1;
$no_results = 0;
$get = 'listings';
$ppage = 2;
$show_dest = 0;
$dest_class = 'bg-listings';
$curr = 1;
$curr_desc = 'USD';

require('base.php');

// Currency handling
if((isset($_GET['currency']) && $_GET['currency'] != 'usd') || (isset($_SESSION['currency']) && $_SESSION['currency'] != 'usd')){
  if(isset($_GET['currency'])){
    $curr_desc = strtoupper($_GET['currency']);
    $_SESSION['currency'] = $_GET['currency'];
  }else{
    $curr_desc = strtoupper($_SESSION['currency']);
  }
  require_once('dist/inc/process/get_currency.php');
}elseif(isset($_GET['currency']) && $_GET['currency'] == 'usd'){
  $_SESSION['currency'] = 'usd';
}

// Initialize search and listings
$site = new Site();
$adv_search = new AdvSearch();
$adv_search->getLocations();
$adv_search->getMinMax();

// Load property types from database
$listings_helper = new Listings();
$listings_helper->getPropertyTypes();
$property_types = $listings_helper->PropertyTypes;

// Debug output (temporarily enabled to check property types)
// echo "<!-- Debug: Property types loaded: " . print_r($property_types, true) . " -->\n";

// Define property type icons mapping
$property_type_icons = array(
    'Residential' => '🏠',
    'House' => '🏠',
    'Apartment' => '🏢',
    'Condo' => '🏢', 
    'Condominium' => '🏢',
    'Townhouse' => '🏘️',
    'Townhome' => '🏘️',
    'Villa' => '🏛️',
    'Commercial' => '🏢',
    'Office' => '🏢',
    'Retail' => '🏪',
    'Industrial' => '🏭',
    'Warehouse' => '🏭',
    'Land' => '🌾',
    'Lot' => '🌾',
    'Lots & Land' => '🌾',
    'Farm' => '🚜',
    'Ranch' => '🚜',
    'Multi-family' => '🏘️',
    'Duplex' => '🏘️',
    'Triplex' => '🏘️',
    'Fourplex' => '🏘️',
    'Mobile Home' => '🚐',
    'Manufactured' => '🚐',
    'Co-op' => '🏗️',
    'Other' => '📍'
);

// Function to get icon for property type
function getPropertyTypeIcon($type_desc, $icons_map) {
    // Try exact match first
    if (isset($icons_map[$type_desc])) {
        return $icons_map[$type_desc];
    }
    
    // Try partial matches
    $type_lower = strtolower($type_desc);
    foreach ($icons_map as $key => $icon) {
        if (strpos($type_lower, strtolower($key)) !== false) {
            return $icon;
        }
    }
    
    // Default icon
    return '📍';
}

// Set default parameters to show all listings when no filters are applied
$default_params = array(
    'page' => 1,
    'search_type' => 'basic',
    'location' => 0,
    'dsearch' => 'all',
    'property_type' => 0,
    'beds' => 0,
    'baths' => 0,
    'search_type' => 'basic',
    'list_type' => 0
);

// Merge any existing GET parameters with defaults
$search_params = array_merge($default_params, $_GET);

// Get real listings data using the same logic as listings.php
if(!empty($_GET)){
    $i = 0;
    foreach($search_params as $kk=>$vv){
        if(is_array($vv)){
            foreach($vv as $ll=>$nn){
                $get .= '&'.$kk.urlencode('['.$ll.']').'='.urlencode($nn);
            }
        }else{
            if($i == 0){
                $get .= '?'.$kk.'='.urlencode($vv);
            }else{
                if($kk != 'page'){
                    $get .= '&'.$kk.'='.urlencode($vv);
                }
            }
        }
        $i++;
    }
    $listings = new SiteListings($search_params);
}else{
    // Show all listings by default when no parameters are provided
    $listings = new SiteListings($default_params);
}

// Handle destinations/locations like in listings.php
if(isset($_GET['location']) && $_GET['location'] > 0){
  require_once('dist/inc/locations.php');
}

// Handle search parameters
if(isset($search_params['dsearch'])){
  if($search_params['dsearch'] == 'for-sale'){
    $search_params['list_type'] = 3;
  }elseif($search_params['dsearch'] == 'rentals'){
    $search_params['list_type'] = 2;
  }elseif($search_params['dsearch'] == 'for-lease'){
    $search_params['list_type'] = 1;
  }else{
    $search_params['list_type'] = 0; // Show all types
  }
  
  // Re-initialize listings with updated parameters if dsearch was processed
  if(!empty($_GET)){
    $listings = new SiteListings($search_params);
  }
}

// Debug information (temporarily disabled to prevent 500 errors)
// echo "<!-- Debug: Search params: " . print_r($search_params, true) . " -->\n";
// echo "<!-- Debug: Listings object exists: " . (isset($listings) ? 'yes' : 'no') . " -->\n";
// echo "<!-- Debug: List[0] exists: " . (isset($listings->List[0]) ? 'yes' : 'no') . " -->\n";
// echo "<!-- Debug: Listings count: " . (isset($listings->List[0]) ? count($listings->List[0]) : 0) . " -->\n";
// echo "<!-- Debug: property_types exists: " . (isset($property_types) ? 'yes' : 'no') . " -->\n";
// echo "<!-- Debug: listing_types exists: " . (isset($listing_types) ? 'yes' : 'no') . " -->\n";
// echo "<!-- Debug: prop_img_url: " . (isset($prop_img_url) ? $prop_img_url : 'not set') . " -->\n";

// Handle county/city relationships like in listings.php
if(isset($listings->SearchParams) && 
   count($listings->SearchParams->Counties) > 0 && 
   (count($listings->SearchParams->Cities) == 0 || $listings->SearchParams->Cities[0] == 0)){
  $cities = array();
  // get the cities from towns parents and assign
  foreach($listings->SearchParams->Counties as $kk=>$cc){
    if(isset($adv_search->SCounties[$cc])){
      if(!in_array($adv_search->SCounties[$cc]['parent_id'],$cities)){
        $cities[] = $adv_search->SCounties[$cc]['parent_id'];
      }
    }
  }
  $listings->SearchParams->Cities = $cities;
  $listings->getSideBar($listings->SearchParams->ReturnBuddQuery['query'],$listings->SearchParams->ReturnBuddQuery['vars']);
}

// Set city information
$city_id = 0;
$city_url = '';
if(isset($listings->SearchParams->Cities[0])){
  $city_id = $listings->SearchParams->Cities[0];
  if(count($listings->SearchParams->Cities) > 1){
    $adv_search->getTowns($listings->SearchParams->Cities);
  }else{
    $adv_search->getTowns($city_id);
  }
  $city_url = $listings->SearchParams->CityName ?? '';
}
?>
<!doctype html>
<html lang="en">
    <head>
        <base href="<?= $base_href ?>">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Listings — Map</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link href="<?= $assets_prefix ?>/dist/css/cng_base.css" rel="stylesheet">
        <link href="<?= $assets_prefix ?>/dist/css/cng.css" rel="stylesheet">

        <!-- Leaflet CSS (free, easy map) -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

        <!-- Additional styles for property features -->
        <style>
            .prop-badge {
                position: absolute;
                top: 8px;
                left: 8px;
                background: rgba(0,0,0,0.8);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 500;
            }
            
            .prop-action {
                position: absolute;
                top: 8px;
                right: 8px;
                background: rgba(255,255,255,0.9);
                color: #333;
                padding: 6px;
                border-radius: 50%;
                font-size: 14px;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .prop-featured {
                position: absolute;
                top: 8px;
                right: 8px;
                background: rgba(255, 193, 7, 0.9);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: bold;
                z-index: 2;
            }
            .prop-mls {
                font-size: 12px;
                color: #666;
                margin-top: 4px;
            }
            .no-results-message {
                color: #666;
                margin: 40px 0;
            }
            .property-card {
                display: flex;
                flex-direction: column;
                height: 100%;
                background: white;
                border-radius: 8px;
                overflow: hidden;
                text-decoration: none;
                color: inherit;
            }
            
            .property-card:hover {
                text-decoration: none;
                color: inherit;
            }
            
            .prop-image {
                height: 200px;
                background-size: cover;
                background-position: center;
                position: relative;
                background-color: #f5f5f5;
            }
            .prop-body {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                padding: 16px;
            }
            
            .prop-price {
                font-size: 18px;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
            }
            
            .prop-meta {
                font-size: 14px;
                color: #666;
                margin-bottom: 8px;
            }
            
            .prop-location {
                font-size: 14px;
                color: #555;
                margin-bottom: 0px;
            }
            
            .prop-location i {
                color: #dc3545;
                margin-right: 4px;
            }
            
            .filter-toggle .btn-filter i {
                margin-right: 6px;
            }
            
            .prop-type {
                font-size: 13px;
                color: #777;
                margin-bottom: 8px;
            }
            .prop-action-footer {
                margin-top: auto;
                padding-top: 12px;
                border-top: 1px solid #f0f0f0;
            }
            
            .btn-see-property {
                background: transparent;
                border: 2px solid #000;
                color: #000;
                padding: 12px 20px;
                border-radius: 0;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                width: 100%;
                text-align: center;
                position: relative;
                overflow: hidden;
                text-decoration: none;
                display: inline-block;
                z-index: 10;
                transition: color 0.3s ease;
            }
            
            .btn-see-property::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: #000;
                transition: left 0.3s ease;
                z-index: -1;
            }
            
            .btn-see-property:hover {
                color: #fff;
                text-decoration: none;
            }
            
            .btn-see-property:hover::before {
                left: 0;
            }
            
            /* Properties grid layout */
            .properties-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
                padding: 0;
            }
            
            .property-col {
                display: flex;
                flex-direction: column;
            }
            
            /* Mobile layout: map first, horizontal property slider */
            @media (max-width: 767px) {
                .split-wrap .row {
                    flex-direction: column-reverse;
                }
                
                .map-column {
                    height: 50vh;
                    min-height: 300px;
                    order: 1;
                }
                
                .list-column {
                    order: 2;
                    height: auto;
                    overflow: visible;
                    position: relative;
                }
                
                .properties-list {
                    position: relative;
                }
                
                .properties-grid {
                    display: flex;
                    overflow-x: auto;
                    overflow-y: hidden;
                    gap: 16px;
                    padding: 16px;
                    scroll-snap-type: x mandatory;
                    -webkit-overflow-scrolling: touch;
                }
                
                .property-col {
                    flex: 0 0 280px;
                    min-width: 280px;
                    scroll-snap-align: start;
                }
                
                .property-card {
                    border: 2px solid transparent;
                    transition: border-color 0.3s ease, box-shadow 0.3s ease;
                }
                
                .property-card.highlighted {
                    border-color: #007bff;
                    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
                }
                
                /* Hide scrollbar but keep functionality */
                .properties-grid::-webkit-scrollbar {
                    display: none;
                }
                
                .properties-grid {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
                
                /* Add scroll indicator */
                .properties-list::after {
                    content: '← Swipe to see more properties →';
                    position: absolute;
                    bottom: 8px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: rgba(0, 0, 0, 0.7);
                    color: white;
                    padding: 4px 12px;
                    border-radius: 12px;
                    font-size: 12px;
                    pointer-events: none;
                    opacity: 0.8;
                    z-index: 10;
                }
            }
            
            /* Current filters section styles */
            .current-filters {
                transition: all 0.3s ease;
            }
            
            .filter-tag {
                background: #e46248;
                color: white;
                padding: 2px 8px 2px 12px;
                border-radius: 3px;
                font-size: 12px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                border: none;
                cursor: pointer;
                transition: background-color 0.2s ease;
            }
            
            .filter-tag:hover {
                background: #1565c0;
            }
            
            .filter-tag .remove-filter {
                background: rgba(255, 255, 255, 0.3);
                border: none;
                color: white;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                line-height: 1;
                cursor: pointer;
                transition: background-color 0.2s ease;
            }
            
            .filter-tag .remove-filter:hover {
                background: rgba(255, 255, 255, 0.5);
            }
            
            /* Property Types Dropdown List Styles */
            .types-dropdown-panel {
                min-width: 280px;
                max-width: 350px;
            }
            
            .types-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 16px;
                border-bottom: 1px solid #eee;
                background-color: #f8f9fa;
            }
            
            .types-title {
                font-weight: 600;
                color: #333;
                font-size: 14px;
            }
            
            .btn-clear-types {
                background: none;
                border: none;
                color: #1976d2;
                font-size: 12px;
                cursor: pointer;
                padding: 2px 6px;
                border-radius: 3px;
                transition: background-color 0.2s ease;
            }
            
            .btn-clear-types:hover {
                background-color: #e3f2fd;
            }
            
            .types-list {
                max-height: 300px;
                overflow-y: auto;
                padding: 8px 0;
            }
            
            .type-group {
                margin-bottom: 4px;
            }
            
            .type-item, .subtype-item {
                display: flex;
                align-items: center;
                padding: 8px 16px;
                cursor: pointer;
                transition: background-color 0.2s ease;
                border: none;
                background: none;
                width: 100%;
                text-align: left;
                position: relative;
            }
            
            .type-item:hover, .subtype-item:hover {
                background-color: #f8f9fa;
            }
            
            .subtype-item {
                padding-left: 32px;
                font-size: 14px;
                color: #666;
            }
            
            .type-checkbox, .subtype-checkbox {
                margin: 0;
                margin-right: 12px;
                width: 16px;
                height: 16px;
                cursor: pointer;
            }
            
            .checkmark {
                position: relative;
                width: 16px;
                height: 16px;
                border: 2px solid #ddd;
                border-radius: 3px;
                margin-right: 12px;
                transition: all 0.2s ease;
            }
            
            .type-checkbox:checked + .checkmark,
            .subtype-checkbox:checked + .checkmark {
                background-color: #1976d2;
                border-color: #1976d2;
            }
            
            .type-checkbox:checked + .checkmark::after,
            .subtype-checkbox:checked + .checkmark::after {
                content: '✓';
                position: absolute;
                top: -2px;
                left: 2px;
                color: white;
                font-size: 12px;
                font-weight: bold;
            }
            
            .type-label, .subtype-label {
                font-size: 14px;
                color: #333;
                flex: 1;
            }
            
            .subtype-label {
                font-size: 13px;
                color: #666;
            }
            
            .type-checkbox, .subtype-checkbox {
                position: absolute;
                opacity: 0;
                cursor: pointer;
            }
        </style>

        
    </head>
    <body class="listings-map-page">

    <?php require 'dist/inc/nav-inner.php';?>

    <!-- Listings filter ribbon (copied and extended) -->
    <section class="listings-filter" aria-label="Search and filters">
        <div class="filter-ribbon">
            <div class="container-fluid">
                <div class="filter-row">
                    <!-- Search box -->
                    <div class="filter-item filter-search">
                        <div class="search-input-wrap">
                            <svg class="search-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM10 15a5 5 0 110-10 5 5 0 010 10z"></path></svg>
                            <input id="ls-search" class="form-control" type="text" placeholder="Search locations, neighborhoods, or keywords" autocomplete="off">
                        </div>

                        <div class="search-suggestions" aria-hidden="true">
                            <ul></ul>
                        </div>
                    </div>

                    <!-- For Sale / Rent -->
                    <div class="filter-item filter-dropdown" data-filter="sale">
                        <button class="btn btn-outline-secondary btn-filter" type="button" data-value="any">Search Type</button>
                        <div class="dropdown-panel">
                            <ul>
                                <li data-value="any">All Types</li>
                                <li data-value="sale">For Sale</li>
                                <li data-value="rent">For Rent</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Price range -->
                    <div class="filter-item filter-dropdown" data-filter="price">
                        <button class="btn btn-outline-secondary btn-filter" type="button">Any Price</button>
                        <div class="dropdown-panel">
                            <ul>
                                <li data-value="any">Any Price</li>
                                <li data-value="0-100000">Under $100k</li>
                                <li data-value="100000-300000">$100k–$300k</li>
                                <li data-value="300000-600000">$300k–$600k</li>
                                <li data-value="600000-999999999">$600k+</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Property types dropdown -->
                    <div class="filter-item filter-dropdown filter-types" data-filter="types">
                        <button class="btn btn-outline-secondary btn-filter" type="button">Property Types</button>
                        <div class="dropdown-panel types-dropdown-panel">
                            <div class="types-header">
                                <button class="btn-clear-types" type="button">Clear All</button>
                            </div>
                            <div class="types-list">
                                <?php 
                                if (isset($property_types) && is_array($property_types)) {
                                    foreach ($property_types as $type_id => $type_data) {
                                        $type_desc = $type_data['desc'] ?? 'Unknown';
                                        ?>
                                        <div class="type-group">
                                            <label class="type-item" data-value="<?= htmlspecialchars($type_desc) ?>">
                                                <input type="checkbox" name="property_type[]" value="<?= $type_id ?>" class="type-checkbox">
                                                <span class="checkmark"></span>
                                                <span class="type-label"><?= htmlspecialchars($type_desc) ?></span>
                                            </label>
                                            <?php 
                                            // Display sub-types if they exist
                                            if (isset($type_data['subs']) && !empty($type_data['subs'])) {
                                                foreach ($type_data['subs'] as $sub_id => $sub_desc) {
                                                    if (!empty($sub_desc)) {
                                                        ?>
                                                        <label class="subtype-item" data-value="<?= htmlspecialchars($sub_desc) ?>" data-parent="<?= $type_id ?>">
                                                            <input type="checkbox" name="property_subtype[]" value="<?= $sub_id ?>" class="subtype-checkbox">
                                                            <span class="checkmark"></span>
                                                            <span class="subtype-label"><?= htmlspecialchars($sub_desc) ?></span>
                                                        </label>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    // Fallback if property types not loaded
                                    ?>
                                    <div class="type-group">
                                        <label class="type-item" data-value="Single Home">
                                            <input type="checkbox" name="property_type[]" value="1" class="type-checkbox">
                                            <span class="checkmark"></span>
                                            <span class="type-label">Single Home</span>
                                        </label>
                                    </div>
                                    <div class="type-group">
                                        <label class="type-item" data-value="Apartment">
                                            <input type="checkbox" name="property_type[]" value="2" class="type-checkbox">
                                            <span class="checkmark"></span>
                                            <span class="type-label">Apartment</span>
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Beds -->
                    <div class="filter-item filter-dropdown" data-filter="beds">
                        <button class="btn btn-outline-secondary btn-filter" type="button">All Beds</button>
                        <div class="dropdown-panel pills-panel">
                            <ul>
                                <li><label class="pill"><input type="radio" name="beds" value="any" checked>All beds</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="1">1</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="2">2</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="3">3</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="4">4</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="5+">5+</label></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Baths -->
                    <div class="filter-item filter-dropdown" data-filter="baths">
                        <button class="btn btn-outline-secondary btn-filter" type="button">All Baths</button>
                        <div class="dropdown-panel pills-panel">
                            <ul>
                                <li><label class="pill"><input type="radio" name="baths" value="any" checked>All baths</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="1">1</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="2">2</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="3">3</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="4">4</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="5+">5+</label></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Mobile toggle -->
                    <div class="filter-item filter-toggle d-md-none">
                        <button class="btn btn-outline-secondary btn-filter" type="button" data-action="toggle-filters"><i class="bi bi-funnel"></i> Filters</button>
                    </div>

                    
                </div>
            </div>
        </div>
    </section>

   

    <div class="container-fluid split-wrap">
        <div class="row gx-3">
            <div class="col-md-6 list-column" id="list-column">
                <div class="search-results mb-2">
                    <!-- Replace the previous results-count + buttons block with this toolbar -->
                    <div class="properties-controls d-flex align-items-center justify-content-end">
                      <div class="results-left">
                        <div class="results-count" style="margin-right: 20px;">
                          Showing <span class="results-number text-bold"><?= isset($listings->Pagination->TotalResults) ? $listings->Pagination->TotalResults : count($properties_for_map) ?></span> results
                        </div>
                      </div>

                      <div class="results-actions d-flex align-items-center">
                        <!-- keep visual order: result count on left, controls on right -->
                        <div class="view-toggle btn-group" role="group" aria-label="View toggle">
                          <button type="button" class="btn btn-outline-secondary btn-sm view-list" data-view="list">List</button>
                          <button type="button" class="btn btn-outline-secondary btn-sm view-map active" data-view="map">Map</button>
                        </div>
                      </div>
                    </div>
                    <div class="no-results" style="display:none; padding:24px; text-align:center; color:#666">
                        No properties match your filters. Try adjusting your search.
                        <br><br>
                        <a href="<?= $base_href ?>listings" type="button" class="btn btn-primary" onclick="showAllProperties()">Show All Properties</a>
                    </div>

                    <!-- Current Filters Section -->
                        <div class="current-filters" style="background-color: rgb(239 245 250); padding: 12px 0; display: none;">
                            <div class="container-fluid">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="me-2 text-muted fw-medium" style="font-size: 14px;">Active Filters:</span>
                                    <div class="filter-tags d-flex flex-wrap gap-2" id="current-filter-tags">
                                        <!-- Filter tags will be dynamically inserted here -->
                                    </div>
                                    <button class="btn btn-link btn-sm p-0 ms-auto text-decoration-none" id="clear-all-filters" style="font-size: 14px; color: #1976d2;">
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        </div>
                </div>

                <!-- Properties list -->
                <section class="properties-list" aria-label="Property results">
                    <div class="properties-wrap" data-min-width="1400">
                        <div class="properties-grid">
                            <?php
                            // Get real properties from database instead of test data
                            $properties_for_map = array();
                            
                            // Ensure required variables exist
                            if(!isset($property_types)) $property_types = array();
                            if(!isset($listing_types)) $listing_types = array();
                            if(!isset($prop_img_url)) $prop_img_url = 'dist/img/';
                            if(!isset($link_property)) $link_property = array('en' => 'listing/', 'es' => 'propiedad/');
                            if(!isset($lang)) $lang = 'en';
                            
                            if(!empty($listings->List[0])){
                                foreach($listings->List[0] as $ll=>$ff){
                                    // Handle currency conversion like in listings.php
                                    if($curr_desc == 'MXN' && isset($ff->PropCostsMXN) && ($ff->PropCostsMXN + 0) > 0){
                                        $ff->PropCosts = $ff->PropCostsMXN;
                                    }elseif($curr_desc == 'MXN' && isset($curr)){
                                        $ff->PropCosts = $ff->PropCosts * $curr;
                                    }
                                    
                                    // Determine property status
                                    $prop_status = 'Active';
                                    if(isset($ff->DisplayStatus) && $ff->DisplayStatus != ''){
                                        $prop_status = $ff->DisplayStatus;
                                    }
                                    
                                    // Determine listing type (sale/rent)
                                    $listing_type = 'sale'; // Default
                                    if(isset($ff->SaleType) && isset($listing_types[$ff->SaleType])){
                                        $listing_type = strtolower($listing_types[$ff->SaleType]) == 'rental' ? 'rent' : 'sale';
                                    }
                                    
                                    // Map property types to the filter format
                                    $prop_type_name = 'Other';
                                    $prop_subtype_name = '';
                                    $all_type_names = array();
                                    $all_type_ids = array();
                                    
                                    if(isset($ff->PropType) && isset($property_types[$ff->PropType])){
                                        $prop_type_name = $property_types[$ff->PropType]['desc'];
                                        $all_type_names[] = $prop_type_name;
                                        $all_type_ids[] = $ff->PropType; // Add the main type ID
                                        
                                        // Check for subtype
                                        if(isset($ff->PropertySubTypeId) && $ff->PropertySubTypeId && 
                                           isset($property_types[$ff->PropType]['subs'][$ff->PropertySubTypeId])){
                                            $prop_subtype_name = $property_types[$ff->PropType]['subs'][$ff->PropertySubTypeId];
                                            $all_type_names[] = $prop_subtype_name;
                                            $all_type_ids[] = $ff->PropertySubTypeId; // Add the subtype ID
                                        }
                                    }
                                    
                                    // Determine price range
                                    $price_range = 'any';
                                    $price = $ff->PropCosts;
                                    if($price < 100000) $price_range = '0-100000';
                                    elseif($price < 300000) $price_range = '100000-300000';
                                    elseif($price < 600000) $price_range = '300000-600000';
                                    else $price_range = '600000-999999999';
                                    
                                    // Build property array for map with safety checks
                                    $map_property = array(
                                        'id' => isset($ff->PropId) ? $ff->PropId : 0,
                                        'title' => isset($ff->PropTitle) ? $ff->PropTitle : 'Property',
                                        'image' => isset($ff->PropThumb) ? $prop_img_url.$ff->PropThumb : $assets_prefix.'/dist/img/default-property.svg',
                                        'status' => $prop_status,
                                        'listing' => $listing_type,
                                        'price' => isset($ff->PropCosts) ? $ff->PropCosts : 0,
                                        'price_range' => $price_range,
                                        'types' => $all_type_names,
                                        'type_ids' => $all_type_ids,
                                        'beds' => isset($ff->PropSize->Bedrooms) ? $ff->PropSize->Bedrooms : 0,
                                        'baths' => isset($ff->PropSize->TotalBaths) ? $ff->PropSize->TotalBaths : 0,
                                        'sqft' => isset($ff->PropSize->SqFt) ? $ff->PropSize->SqFt : 0,
                                        'location' => isset($ff->PropLocation->City) ? $ff->PropLocation->City.', '.$ff->PropLocation->State : 'Unknown',
                                        'postal' => isset($ff->PropLocation->Zip) ? $ff->PropLocation->Zip : '',
                                        'area' => isset($ff->PropLocation->Area) && $ff->PropLocation->Area ? $ff->PropLocation->Area : (isset($ff->PropLocation->County) ? $ff->PropLocation->County : 'Unknown'),
                                        'lat' => isset($ff->PropLocation->Latitude) ? $ff->PropLocation->Latitude : 0,
                                        'lng' => isset($ff->PropLocation->Longitude) ? $ff->PropLocation->Longitude : 0,
                                        'url' => $link_property[$lang].(isset($ff->PropId) ? $ff->PropId : 0),
                                        'mls' => isset($ff->MLS) ? $ff->MLS : '',
                                        'is_featured' => isset($ff->IsFeatured) ? $ff->IsFeatured : 0
                                    );
                                    
                                    // Only add properties with valid coordinates
                                    if($map_property['lat'] != 0 && $map_property['lng'] != 0) {
                                        $properties_for_map[] = $map_property;
                                    }
                                }
                            }
                            
                            // Handle no results case
                            if(empty($properties_for_map)){ ?>
                            <?php } else {
                            
                            foreach($properties_for_map as $p):
                                $types_attr = implode(',', $p['types']);
                                $type_ids_attr = implode(',', $p['type_ids'] ?? array());
                            ?>
                            <div class="property-col">
                                <div class="property-card" href="<?= $p['url'] ?>" data-id="<?= $p['id'] ?>" data-lat="<?= $p['lat'] ?>" data-lng="<?= $p['lng'] ?>" data-listing="<?= $p['listing'] ?>" data-price="<?= $p['price'] ?>" data-price-range="<?= $p['price_range'] ?>" data-types="<?= htmlspecialchars($types_attr) ?>" data-type-ids="<?= htmlspecialchars($type_ids_attr) ?>" data-beds="<?= $p['beds'] ?>" data-baths="<?= $p['baths'] ?>" data-sqft="<?= $p['sqft'] ?>" data-location="<?= htmlspecialchars($p['location']) ?>" data-area="<?= $p['area'] ?>" data-status="<?= $p['status'] ?>">
                                    <div class="prop-image" style="background-image:url('<?= $p['image'] ?>');">
                                        <div class="prop-badge"><?= htmlspecialchars($p['status']) ?></div>
                                        <?php if($p['is_featured'] == 1){ ?>
                                        <div class="prop-featured" title="Featured Property">⭐</div>
                                        <?php }else{ ?>
                                            <div class="prop-action" aria-hidden="true">✉</div>
                                        <?php } ?>
                                    </div>
                                    <div class="prop-body">
                                        <div class="prop-price"><?= $p['listing'] === 'rent' ? '$'.number_format($p['price']).' /mo' : '$'.number_format($p['price']) ?> <small><?= $curr_desc ?></small></div>
                                        <div class="prop-meta"><?= $p['beds'] ?> bd <span class="sep">&middot;</span> <?= $p['baths'] ?> ba <span class="sep">&middot;</span> <?= number_format($p['sqft']) ?> sqft</div>
                                        <div class="prop-location"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($p['location']) ?></div>
                                        <div class="prop-type"><?= htmlspecialchars(implode(', ', $p['types'])) ?> — <?= htmlspecialchars($p['area']) ?></div>
                                        <?php if(!empty($p['mls'])){ ?>
                                        <div class="prop-mls">MLS #: <?= htmlspecialchars($p['mls']) ?></div>
                                        <?php } ?>
                                        <div class="prop-action-footer">
                                            <a href="<?= $p['url'] ?>" class="btn-see-property" onclick="event.stopPropagation();">See Property</a>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <?php endforeach; 
                            } // End of else statement for properties display ?>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-6 map-column" id="map-column">
                <div id="mapid"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="<?= $assets_prefix ?>/dist/js/cng_listings_filters.js"></script>
    <script src="<?= $assets_prefix ?>/dist/js/cng_listings_map.js"></script>

    <script>
        function showAllProperties() {
            // Clear all filters and reload page to show all properties
            window.location.href = window.location.pathname;
        }
        
        // Mobile map marker to property card highlighting
        function highlightPropertyCard(propertyId) {
            if (window.innerWidth <= 767) {
                // Remove previous highlights
                document.querySelectorAll('.property-card.highlighted').forEach(card => {
                    card.classList.remove('highlighted');
                });
                
                // Find and highlight the corresponding property card
                const targetCard = document.querySelector(`[data-id="${propertyId}"]`);
                if (targetCard) {
                    targetCard.classList.add('highlighted');
                    
                    // Scroll the property into view in the horizontal slider
                    const propertiesGrid = document.querySelector('.properties-grid');
                    const cardRect = targetCard.getBoundingClientRect();
                    const gridRect = propertiesGrid.getBoundingClientRect();
                    
                    if (cardRect.left < gridRect.left || cardRect.right > gridRect.right) {
                        targetCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }
                }
            }
        }
        
        // Listen for map marker clicks (this will be called from the map JavaScript)
        window.onMarkerClick = highlightPropertyCard;
    </script>

    </body>
</html>
