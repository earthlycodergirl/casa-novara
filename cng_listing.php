<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug: Output the received parameters (remove this after debugging)
// echo "<!-- DEBUG: prop_id = " . (isset($_GET['prop_id']) ? $_GET['prop_id'] : 'NOT SET') . " -->\n";
// echo "<!-- DEBUG: type = " . (isset($_GET['type']) ? $_GET['type'] : 'NOT SET') . " -->\n";
// echo "<!-- DEBUG: All GET params: " . print_r($_GET, true) . " -->\n";

session_start();
$listing = 1;
$ppage = 2;
$curr = 1;
$curr_desc = 'USD';

require('base.php');

$current_link['en'] = isset($_GET['prop_id']) ? $link_property['en'].$_GET['prop_id'] : $link_property['en'];
$current_link['es'] = isset($_GET['prop_id']) ? $link_property['es'].$_GET['prop_id'] : $link_property['es'];

$site = new Site();
$list = new Listings();

// Initialize default values to prevent undefined variable errors
$prop = new stdClass();
$price = 0;
$pname = '';
$show_price = 'Not Available';
$fees = array();

if(isset($_GET['prop_id']) && $_GET['prop_id'] > 0){
    if(isset($_GET['type'])){
        if($_GET['type'] == 'budd'){
            $prop = new Listing($_GET['prop_id']);

            if(isset($prop->PropertyId) && $prop->PropertyId > 0){
                // Initialize property type display with safe defaults
                if(isset($listings->PropertyTypes[$prop->PropertyTypeId]['desc'])) {
                    $prop->PropTypeDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['desc'];
                }
                if(isset($listings->ZoningTypes[$prop->ZoningId][$lang])) {
                    $prop->ZoneDisplay = $listings->ZoningTypes[$prop->ZoningId][$lang];
                }
                if(!empty($listings->PropertyTypes[$prop->PropertyTypeId]['subs'])) {
                    $prop->PropTypeSubDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['subs'][$prop->PropertySubTypeId];
                } else {
                    $prop->PropTypeSubDisplay = '';
                }

                if($lang == 'es'){
                    if(isset($listings->PropertyTypes[$prop->PropertyTypeId]['desc_es'])) {
                        $prop->PropTypeDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['desc_es'];
                    }
                    if(!empty($listings->PropertyTypes[$prop->PropertyTypeId]['subs_es'])) {
                        $prop->PropTypeSubDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['subs_es'][$prop->PropertySubTypeId];
                    }
                }

                if($prop->IsVisible != 1){
                    header('location: /'.$base_href);
                    exit();
                }
            }else{
                // Property doesn't exist, use defaults
                $prop = new stdClass();
                $prop->PropertyId = 0;
            }
        }
        if($_GET['type'] == 'up'){
            $prop = new Listing($_GET['prop_id'],'up');
            if(isset($prop->PropThumb)) {
                $thumb = $prop->PropThumb;
            }
        }
    }
    $list->getPriceTypes();
    if(isset($list->PriceTypes)) {
        $price_types = $list->PriceTypes;
    }
}

// Set default property values if not loaded from database
if(!isset($prop->PropertyTitle)) {
    $prop->PropertyTitle = 'Property Not Found';
    $prop->PropertyTitleEs = 'Propiedad No Encontrada';
    $prop->PropertyDesc = 'We apologize, but the requested property could not be found in our database. The property may have been sold, removed from our listings, or the URL may be incorrect. Please contact us for assistance or browse our available properties.';
    $prop->PropertyDescEs = 'Disculpamos, pero la propiedad solicitada no se pudo encontrar en nuestra base de datos. La propiedad puede haber sido vendida, eliminada de nuestros listados, o la URL puede ser incorrecta. Por favor contáctenos para asistencia o navegue nuestras propiedades disponibles.';
    $prop->PropertyId = $prop->PropertyId ?? 0;
}

// Set default location if not set
if(!isset($prop->Location)) {
    $prop->Location = new stdClass();
    $prop->Location->Latitude = '20.6534';
    $prop->Location->Longitude = '-105.2253';
    $prop->Location->CityName = 'Puerto Vallarta';
    $prop->Location->StateName = 'Jalisco';
    $prop->Location->CountyName = 'Marina Vallarta';
    $prop->Location->AreaName = 'Marina District';
    $prop->Location->Address = 'Marina Vallarta';
    $prop->Location->Zip = '48354';
    $prop->Location->City = 'puerto-vallarta';
}

// Set default property features if not set
if(!isset($prop->Bedrooms)) $prop->Bedrooms = 'N/A';
if(!isset($prop->TotalBaths)) $prop->TotalBaths = 'N/A';
// Handle DisplayStatus with fallback to Status field
if(!isset($prop->DisplayStatus) || trim($prop->DisplayStatus) == '') {
    $prop->DisplayStatus = ($prop->Status == 'sold' ? 'Sold' : ($prop->Status == 'pending' ? 'Pending' : 'Not Available'));
}
if(!isset($prop->PropTypeDisplay)) $prop->PropTypeDisplay = 'Property Type Unknown';
if(!isset($prop->YearBuilt)) $prop->YearBuilt = 0;
if(!isset($prop->PhotosDisplay)) $prop->PhotosDisplay = array();

$month_arr = array(
  1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun',
  7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec',
);

if(isset($_SESSION['currency']) && $_SESSION['currency'] != 'usd'){
  if(file_exists('dist/inc/process/get_currency.php')) {
      require_once('dist/inc/process/get_currency.php');
  }
  $curr_desc = strtoupper($_SESSION['currency']);
  $_SESSION['currency'] = $_SESSION['currency'];
}

$fees = array();
// Calculate pricing to be displayed
if(isset($prop->Prices) && !empty($prop->Prices)){
  foreach($prop->Prices as $pp){
    if($curr_desc == 'MXN' && $pp['curr'] == 'usd'){
      $pp['amt'] = $pp['amt'] * $curr;
    }
    if(!array_key_exists($pp['type'],$fees)){
      $fees[$pp['type']] = $pp;
    }else{
      if(strtoupper($pp['curr']) == $curr_desc){
        $fees[$pp['type']] = $pp;
      }
    }
  }
  if(isset($fees[1]['amt'])) {
      $price = $fees[1]['amt'];
      $pname = $fees[1]['name'];
      $show_price = number_format($price);
  }
}

?>
<!doctype html>
<html lang="en">
<head>
    <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= ($lang == 'es') ? ($prop->PropertyTitleEs ?? 'Villa de Lujo en Puerto Vallarta') : ($prop->PropertyTitle ?? 'Luxury Villa in Puerto Vallarta') ?> - Casa Novara</title>
    <meta name="robots" content="index" />
    <link rel="canonical" href="<?= $link_property[$lang].$prop->PropertyId ?>">
    <meta name="description" content="<?= ($lang == 'es') ? substr($prop->PropertyDescEs ?? 'Hermosa villa de lujo con vista al mar en Puerto Vallarta.',0,155).'...' : substr($prop->PropertyDesc ?? 'Beautiful luxury villa with ocean views in Puerto Vallarta.',0,155).'...' ?>">

    <meta property="og:title" content="<?= ($lang == 'es') ? ($prop->PropertyTitleEs ?? 'Villa de Lujo en Puerto Vallarta') : ($prop->PropertyTitle ?? 'Luxury Villa in Puerto Vallarta') ?> - Casa Novara">
    <meta property="og:description" content="<?= ($lang == 'es') ? substr($prop->PropertyDescEs ?? 'Hermosa villa de lujo con vista al mar en Puerto Vallarta.',0,155).'...' : substr($prop->PropertyDesc ?? 'Beautiful luxury villa with ocean views in Puerto Vallarta.',0,155).'...' ?>">
    <meta property="og:image" content="<?= !empty($prop->PhotosDisplay) ? $prop_img_url.reset($prop->PhotosDisplay) : 'https://casanovaragroup.com/images/properties/villa-1.jpg' ?>">
    <meta property="og:url" content="https://casanovaragroup.com/<?= $link_property[$lang].$prop->PropertyId ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:site_name" content="Casa Novara">
    <meta name="twitter:image:alt" content="<?= ($lang == 'es') ? ($prop->PropertyTitleEs ?? 'Villa de Lujo en Puerto Vallarta') : ($prop->PropertyTitle ?? 'Luxury Villa in Puerto Vallarta') ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="dist/plugins/owl/owl.carousel.css" rel="stylesheet" type="text/css"/>
    <link href="dist/plugins/owl/owl.transitions.css" rel="stylesheet" type="text/css"/>
    <?php if(isset($prop->Location->Latitude) && $prop->Location->Latitude != ''){ ?>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <?php } ?>

    <!-- Custom CSS -->
    <link href="dist/css/cng_base.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dist/css/cng.css" />
    <link rel="stylesheet" type="text/css" href="dist/css/cng_listing.css" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.3/dist/less.min.js"></script>
    
    <!-- Alignment Fix for Container Consistency -->
    <style>
        /* Ensure all container-fluid elements have consistent padding to align with breadcrumb section */
        .breadcrumb-section .container-fluid,
        .properties .container-fluid,
        .content-sections .container-fluid,
        .bottom-sidebar-section .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Ensure consistent margins for mobile and desktop */
        @media (min-width: 576px) {
            .breadcrumb-section .container-fluid,
            .properties .container-fluid,
            .content-sections .container-fluid,
            .bottom-sidebar-section .container-fluid {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
        
        @media (min-width: 768px) {
            .breadcrumb-section .container-fluid,
            .properties .container-fluid,
            .content-sections .container-fluid,
            .bottom-sidebar-section .container-fluid {
                padding-left: 24px;
                padding-right: 24px;
            }
        }
    </style>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body class="cng-listing-page" data-lat="<?= $prop->Location->Latitude ?? '20.6534' ?>" data-lon="<?= $prop->Location->Longitude ?? '-105.2253' ?>">
   <?php require 'dist/inc/nav-inner.php';?>

    <!-- Breadcrumbs Section -->
    <div class="breadcrumb-section">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" onclick="goBack()"><?= $prop->Location->CityName ?? 'Puerto Vallarta' ?> Properties</li>
                    <li class="breadcrumb-item"><a href="<?= $link_properties[$lang] ?? '/properties' ?>?location=<?= $prop->Location->City ?? 'puerto-vallarta' ?>"><?= $lan['prop']['sres'] ?? 'Search Results' ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Property Details</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content Section -->
    <section class="properties cng-listing-content" id="property_page">
        <div class="container-fluid">
            <div class="row mobile-price">
                <div class="col-6">
                    <a class="search-res" href="<?= $link_properties[$lang] ?? '/properties' ?>?location=<?= $prop->Location->City ?? 'puerto-vallarta' ?>">
                        <i class="bi bi-arrow-left"></i> <?= $lan['prop']['sres'] ?? 'Search Results' ?>
                    </a>
                </div>
                <div class="col-6">
                    <div class="mobile-price-txt">
                        <?php
                        if($pname != ''){
                           echo '<span class="pname">'.$pname.'</span>';
                        }
                        echo '<small>$</small>'.$show_price.'  <small>'.$curr_desc.'</small>';
                        ?>
                    </div>
                </div>
            </div>

            <!-- Main Content - Full Width -->
            <div class="main-content-wrapper">
                
                <!-- Property Header with side-by-side layout -->
                <div class="property-header-section">
                    <div class="container-fluid">
                        <div class="row g-0">
                            <!-- Property Info - Left Side (40%) -->
                            <div class="col-lg-4 col-md-5">
                                <div class="property-info-left">
                                <!-- <div class="location-badge"><?= $prop->Location->AreaName ?? 'Marina Vallarta' ?></div> -->
                                <h1><?= ($lang == 'es') ? ($prop->PropertyTitleEs ?? 'Villa de Lujo con Vista al Mar') : ($prop->PropertyTitle ?? 'Luxury Villa with Ocean Views') ?></h1>
                                <div class="location-detail">
                                    <i class="bi bi-geo-alt"></i>
                                    <?= ($prop->Location->CityName ?? 'Puerto Vallarta').', '.($prop->Location->StateName ?? 'Jalisco').' '.($prop->Location->Zip ?? '48354') ?>
                                </div>
                                
                                <!-- Property Features Grid -->
                                <div class="property-features-grid">
                                    <div class="features-row-1">
                                        <div class="feature-item">
                                            <i class="bi bi-house-door"></i>
                                            <span><?= $prop->Bedrooms ?? '4' ?> Beds</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="bi bi-droplet"></i>
                                            <span><?= $prop->TotalBaths ?? '3' ?> Baths</span>
                                        </div>
                                    </div>
                                    <?php if(isset($prop->Size->Ft) && $prop->Size->Ft > 0) { ?>
                                    <div class="feature-item">
                                        <i class="bi bi-rulers"></i>
                                        <span><?= number_format($prop->Size->Ft) ?> ft²</span>
                                    </div>
                                    <?php } else { ?>
                                    <div class="feature-item">
                                        <i class="bi bi-rulers"></i>
                                        <span>Size Unknown</span>
                                    </div>
                                    <?php } ?>
                                    <div class="feature-item">
                                        <i class="bi bi-car-front"></i>
                                        <span><?= $prop->Garage ?? 'Unknown' ?> <?= isset($prop->Garage) && $prop->Garage != 'Unknown' ? 'Car Garage' : 'Parking' ?></span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="bi bi-door-open"></i>
                                        <span><?= $prop->PropTypeDisplay ?? 'Villa' ?></span>
                                    </div>
                                    <div class="feature-item status-item">
                                        <i class="bi bi-tag"></i>
                                        <span><?= 
                                            $prop->DisplayStatus && trim($prop->DisplayStatus) != '' 
                                                ? $prop->DisplayStatus 
                                                : ($prop->Status == 'sold' ? 'Sold' : ($prop->Status == 'pending' ? 'Pending' : 'For Sale'))
                                        ?></span>
                                    </div>
                                    <?php if(isset($prop->YearBuilt) && $prop->YearBuilt > 0) { ?>
                                    <div class="feature-item">
                                        <i class="bi bi-calendar"></i>
                                        <span>Built <?= $prop->YearBuilt ?></span>
                                    </div>
                                    <?php } else { ?>
                                    <div class="feature-item">
                                        <i class="bi bi-calendar"></i>
                                        <span>Year Built Unknown</span>
                                    </div>
                                    <?php } ?>
                                </div>

                                <!-- Price Display -->
                                <div class="price-display">
                                    <div class="price-amount">
                                        <span class="currency">$</span><?= $show_price ?> 
                                        <span class="currency-type"><?= $curr_desc ?></span>
                                    </div>
                                    <div class="price-label"><?= $pname ?: 'Sale Price' ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Gallery - Right Side (60%) -->
                        <div class="col-lg-8 col-md-7">
                            <div class="image-gallery-section">
                                <div id="image_holder">
                                    <div id="property-d-1" class="owl-carousel">
                                        <?php if(!empty($prop->PhotosDisplay)){ 
                                            foreach($prop->PhotosDisplay as $pp){  ?>
                                                <div class="item"><img src="<?= $prop_img_url.$pp ?>" alt="Property Image"/></div>
                                        <?php } 
                                        } else { ?>
                                            <div class="item"><img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Property Image 1"/></div>
                                            <div class="item"><img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2075&q=80" alt="Property Image 2"/></div>
                                            <div class="item"><img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2053&q=80" alt="Property Image 3"/></div>
                                        <?php } ?>
                                    </div>
                                    <div id="property-d-1-2" class="owl-carousel">
                                        <?php if(!empty($prop->Photos)){ 
                                            foreach($prop->Photos as $pp){  ?>
                                                <div class="item"><img src="<?= $prop_img_url.$pp[0].'/thumbs/'.$pp[1] ?>" alt="Thumbnail"/></div>
                                        <?php } 
                                        } else { ?>
                                            <div class="item"><img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Thumbnail 1"/></div>
                                            <div class="item"><img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Thumbnail 2"/></div>
                                            <div class="item"><img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Thumbnail 3"/></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                                <!-- Virtual Tour Link -->
                                <div class="vtour-section-gallery">
                                    <a href="#" target="_blank" rel="nofollow" class="vtour">
                                        <i class="bi bi-camera-video"></i>
                                        <span>Take a Virtual Tour</span>
                                    </a>
                                    <!-- <div class="vtour-description">
                                        Experience this property from the comfort of your home
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="content-sections">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <!-- Property Description -->
                                <div class="prop-description">
                                    <h3>Description</h3>
                                    <div class="desc">
                                        <?php if(!empty($prop->description)) { ?>
                                            <?= nl2br(htmlspecialchars($prop->description)) ?>
                                        <?php } elseif(!empty($prop->descr_es) && $lang == 'es') { ?>
                                            <?= nl2br(htmlspecialchars($prop->descr_es)) ?>
                                        <?php } else { ?>
                                            <p><strong>Property Not Available</strong></p>
                                            <p>We're sorry, but the property you're looking for is not currently available in our system. This could be due to several reasons:</p>
                                            <ul>
                                                <li>The property may have been recently sold</li>
                                                <li>The listing may have been temporarily removed for updates</li>
                                                <li>There may be an error in the property URL or ID</li>
                                            </ul>
                                            <p>Please contact our team at <strong>+52 322 123-4567</strong> or <strong>info@casanovaragroup.com</strong> for assistance in finding this property or exploring similar available options.</p>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Property Features -->
                                <div class="features-section">
                                    <h3>Property Features</h3>
                                    <div class="features-grid">
                                        <?php if(isset($prop->pool) && $prop->pool == 1) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-water"></i>
                                            <span>Swimming Pool</span>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if(isset($prop->garden) && $prop->garden == 1) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-tree"></i>
                                            <span>Garden</span>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if(isset($prop->ocean_view) && $prop->ocean_view == 1) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-eye"></i>
                                            <span>Ocean View</span>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if(isset($prop->security_system) && $prop->security_system == 1) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-shield-check"></i>
                                            <span>Security System</span>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if(isset($prop->ac) && $prop->ac == 1) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-wind"></i>
                                            <span>Air Conditioning</span>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if(isset($prop->internet) && $prop->internet == 1) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-wifi"></i>
                                            <span>Internet Ready</span>
                                        </div>
                                        <?php } ?>
                                        
                                        <!-- Default features if no dynamic data -->
                                        <?php if(empty($prop->pool) && empty($prop->garden) && empty($prop->ocean_view) && empty($prop->security_system) && empty($prop->ac) && empty($prop->internet)) { ?>
                                        <div class="feature-card">
                                            <i class="bi bi-water"></i>
                                            <span>Swimming Pool</span>
                                        </div>
                                        <div class="feature-card">
                                            <i class="bi bi-tree"></i>
                                            <span>Garden</span>
                                        </div>
                                        <div class="feature-card">
                                            <i class="bi bi-eye"></i>
                                            <span>Ocean View</span>
                                        </div>
                                        <div class="feature-card">
                                            <i class="bi bi-shield-check"></i>
                                            <span>Security System</span>
                                        </div>
                                        <div class="feature-card">
                                            <i class="bi bi-wind"></i>
                                            <span>Air Conditioning</span>
                                        </div>
                                        <div class="feature-card">
                                            <i class="bi bi-wifi"></i>
                                            <span>Internet Ready</span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Property Details and Location Side by Side -->
                                <div class="details-location-section">
                                    <div class="details-section">
                                        <h3>Property Details</h3>
                                        <div class="details-grid">
                                            <div class="detail-row">
                                                <span class="label">Property ID:</span>
                                                <span class="value"><?= htmlspecialchars($prop->prop_id ?? $prop->PropertyId ?? 'Contact for Details') ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Interior Size:</span>
                                                <span class="value">
                                                    <?php if(isset($prop->construction_area_sqmts) && $prop->construction_area_sqmts > 0) {
                                                        echo number_format($prop->construction_area_sqmts * 10.764, 0) . ' ft² / ' . number_format($prop->construction_area_sqmts) . ' mt²';
                                                    } else {
                                                        echo 'Not Available';
                                                    } ?>
                                                </span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Lot Size:</span>
                                                <span class="value">
                                                    <?php if(isset($prop->lot_size_sqmts) && $prop->lot_size_sqmts > 0) {
                                                        echo number_format($prop->lot_size_sqmts * 10.764, 0) . ' ft² / ' . number_format($prop->lot_size_sqmts) . ' mt²';
                                                    } else {
                                                        echo 'Not Available';
                                                    } ?>
                                                </span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Development:</span>
                                                <span class="value"><?= htmlspecialchars($prop->development ?? 'Not Available') ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Furnished:</span>
                                                <span class="value"><?= htmlspecialchars($prop->furnished ?? 'Not Available') ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Pets Allowed:</span>
                                                <span class="value"><?= isset($prop->pets_allowed) && $prop->pets_allowed == 1 ? 'Yes' : 'No' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="location-section">
                                        <h3>Location & Map</h3>
                                        <div class="location-card">
                                            <div class="address">
                                                <i class="bi bi-geo-alt"></i>
                                                <div>
                                                    <strong><?= htmlspecialchars($prop->zone ?? 'Marina Vallarta') ?></strong><br>
                                                    <?= htmlspecialchars($prop->city ?? 'Puerto Vallarta') ?>, <?= htmlspecialchars($prop->state ?? 'Jalisco') ?><br>
                                                    Mexico <?= htmlspecialchars($prop->zip_code ?? '48354') ?>
                                                </div>
                                            </div>
                                            <div id="map" class="map-container" 
                                                data-lat="<?= $prop->latitude ?? '20.6598' ?>" 
                                                data-lng="<?= $prop->longitude ?? '-105.2257' ?>"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Section -->
            <div class="bottom-sidebar-section">
                <div class="container-fluid">
                    <!-- CTA Header -->
                    <div class="cta-header">
                        <h2>Ready to Make This Your Dream Home?</h2>
                        <p class="cta-subtitle">Don't let this exceptional property slip away. Contact our experienced agents today for an exclusive viewing and personalized consultation.</p>
                    </div>
                    
                    <div class="row">
                        <!-- Contact Form -->
                        <div class="col-lg-6 col-md-12">
                            <div class="contact-card">
                                <h4>Schedule Your Private Tour</h4>
                                <p class="contact-subtitle">Our luxury property specialists are standing by to provide you with detailed information and arrange a personalized viewing at your convenience.</p>
                                
                                <div class="urgency-text">
                                    <i class="bi bi-clock"></i>
                                    <strong>Limited Time:</strong> This exclusive property is generating high interest. Secure your viewing today!
                                </div>
                                
                                <form method="post" action="#" id="info_form" class="contact-form">
                                    <input type="hidden" name="prop_id" value="<?= htmlspecialchars($prop->prop_id ?? $prop->PropertyId ?? 'general-inquiry') ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="full_name" class="form-control" placeholder="Your Full Name *" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control" placeholder="Email Address *" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
                                    </div>
                                    
                                    <div class="form-group">
                                        <textarea name="message" class="form-control" rows="4" placeholder="Tell us about your preferred viewing time or any specific questions..."></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-contact">
                                        <i class="bi bi-calendar-check"></i>
                                        Schedule My Exclusive Tour
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Enhanced Pricing -->
                        <div class="col-lg-6 col-md-6">
                            <div class="pricing-card">
                                <h4>Investment Summary</h4>
                                <div class="pricing-item">
                                    <span class="label">Sale Price</span>
                                    <span class="value">$<?= $show_price ?> <?= $curr_desc ?></span>
                                </div>
                                
                                <?php if(isset($fees[2]['amt']) && $fees[2]['amt'] > 0) { ?>
                                <div class="pricing-item">
                                    <span class="label"><?= $fees[2]['name'] ?? 'HOA Fees (Monthly)' ?></span>
                                    <span class="value">$<?= number_format($fees[2]['amt']) ?> <?= strtoupper($fees[2]['curr'] ?? $curr_desc) ?></span>
                                </div>
                                <?php } elseif(isset($prop->hoa_fees) && $prop->hoa_fees > 0) { ?>
                                <div class="pricing-item">
                                    <span class="label">HOA Fees (Monthly)</span>
                                    <span class="value">$<?= number_format($prop->hoa_fees) ?> <?= $curr_desc ?></span>
                                </div>
                                <?php } ?>
                                
                                <?php if(isset($fees[3]['amt']) && $fees[3]['amt'] > 0) { ?>
                                <div class="pricing-item">
                                    <span class="label"><?= $fees[3]['name'] ?? 'Property Tax (Annual)' ?></span>
                                    <span class="value">$<?= number_format($fees[3]['amt']) ?> <?= strtoupper($fees[3]['curr'] ?? $curr_desc) ?></span>
                                </div>
                                <?php } elseif(isset($prop->property_tax) && $prop->property_tax > 0) { ?>
                                <div class="pricing-item">
                                    <span class="label">Property Tax (Annual)</span>
                                    <span class="value">$<?= number_format($prop->property_tax) ?> <?= $curr_desc ?></span>
                                </div>
                                <?php } ?>
                                
                                <?php if(isset($fees[4]['amt']) && $fees[4]['amt'] > 0) { ?>
                                <div class="pricing-item">
                                    <span class="label"><?= $fees[4]['name'] ?? 'Maintenance Fee' ?></span>
                                    <span class="value">$<?= number_format($fees[4]['amt']) ?> <?= strtoupper($fees[4]['curr'] ?? $curr_desc) ?></span>
                                </div>
                                <?php } ?>
                                
                                <!-- Show additional fees if available -->
                                <?php 
                                if(!empty($fees)) {
                                    foreach($fees as $fee_type => $fee) {
                                        // Skip already displayed fees (sale price=1, hoa=2, tax=3, maintenance=4)
                                        if(!in_array($fee_type, [1, 2, 3, 4]) && isset($fee['amt']) && $fee['amt'] > 0) {
                                ?>
                                <div class="pricing-item">
                                    <span class="label"><?= htmlspecialchars($fee['name']) ?></span>
                                    <span class="value">$<?= number_format($fee['amt']) ?> <?= strtoupper($fee['curr'] ?? $curr_desc) ?></span>
                                </div>
                                <?php 
                                        }
                                    }
                                } 
                                ?>
                                
                                <!-- Contact Information -->
                                <div class="agent-contact">
                                    <h5>Your Agent</h5>
                                    <div class="agent-info">
                                        <?php if(isset($prop->agent_name) && !empty($prop->agent_name)) { ?>
                                            <strong><?= htmlspecialchars($prop->agent_name) ?></strong><br>
                                            <?php if(isset($prop->agent_title)) { ?>
                                                <span><?= htmlspecialchars($prop->agent_title) ?></span><br>
                                            <?php } ?>
                                            <?php if(isset($prop->agent_phone)) { ?>
                                                <a href="tel:<?= htmlspecialchars($prop->agent_phone) ?>"><?= htmlspecialchars($prop->agent_phone) ?></a><br>
                                            <?php } ?>
                                            <?php if(isset($prop->agent_email)) { ?>
                                                <a href="mailto:<?= htmlspecialchars($prop->agent_email) ?>"><?= htmlspecialchars($prop->agent_email) ?></a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <strong>Casa Novara Sales Team</strong><br>
                                            <span>Luxury Property Specialist</span><br>
                                            <a href="tel:+523221234567">+52 322 123-4567</a><br>
                                            <a href="mailto:info@casanovaragroup.com">info@casanovaragroup.com</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require 'dist/inc/foot.php'; ?>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
    <script src="dist/plugins/owl/owl.carousel.min.js"></script>
    <script src="dist/js/listing.js"></script>
    
    <script>
        // Initialize Mapbox Map with dynamic coordinates
        mapboxgl.accessToken = 'pk.eyJ1IjoiZG5hdmFycm8iLCJhIjoiY2p6bjQyZGZjMGFhZDNpcnl5OWt3cmczZyJ9.Gx3BfLBFnfLfNLGDnxHFGQ';
        
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            const lat = parseFloat(mapContainer.dataset.lat) || 20.6598;
            const lng = parseFloat(mapContainer.dataset.lng) || -105.2257;
            
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [lng, lat],
                zoom: 15
            });
            
            // Add marker
            new mapboxgl.Marker({
                color: '#dc3545'
            })
            .setLngLat([lng, lat])
            .addTo(map);
        }
    </script>

</body>
</html>