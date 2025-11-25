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
$list->getPropertyTypes();
$list->getListTypes();
$list->getZones();
$list->getPriceTypes();

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
                if(isset($list->PropertyTypes[$prop->PropertyTypeId]['desc'])) {
                    $prop->PropTypeDisplay = $list->PropertyTypes[$prop->PropertyTypeId]['desc'];
                } else {
                    $prop->PropTypeDisplay = 'Property Type Unknown';
                }
                
                if(isset($list->ZoningTypes[$prop->ZoningId][$lang])) {
                    $prop->ZoneDisplay = $list->ZoningTypes[$prop->ZoningId][$lang];
                } else {
                    $prop->ZoneDisplay = 'Zone Unknown';
                }
                
                if(!empty($list->PropertyTypes[$prop->PropertyTypeId]['subs']) && isset($list->PropertyTypes[$prop->PropertyTypeId]['subs'][$prop->PropertySubTypeId])) {
                    $prop->PropTypeSubDisplay = $list->PropertyTypes[$prop->PropertyTypeId]['subs'][$prop->PropertySubTypeId];
                } else {
                    $prop->PropTypeSubDisplay = '';
                }

                if($lang == 'es'){
                    if(isset($list->PropertyTypes[$prop->PropertyTypeId]['desc_es'])) {
                        $prop->PropTypeDisplay = $list->PropertyTypes[$prop->PropertyTypeId]['desc_es'];
                    }
                    if(!empty($list->PropertyTypes[$prop->PropertyTypeId]['subs_es']) && isset($list->PropertyTypes[$prop->PropertyTypeId]['subs_es'][$prop->PropertySubTypeId])) {
                        $prop->PropTypeSubDisplay = $list->PropertyTypes[$prop->PropertyTypeId]['subs_es'][$prop->PropertySubTypeId];
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
    // Ensure price types are loaded
    if(empty($list->PriceTypes)){
        $list->getPriceTypes();
    }
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

// Set default description and details if not set
if(!isset($prop->description) || trim($prop->description) == '') {
    $prop->description = $prop->PropertyDesc ?? 'No description available for this property.';
}
if(!isset($prop->descr_es) || trim($prop->descr_es) == '') {
    $prop->descr_es = $prop->PropertyDescEs ?? 'No hay descripción disponible para esta propiedad.';
}
if(!isset($prop->construction_area_sqmts)) {
    $prop->construction_area_sqmts = 0;
}
if(!isset($prop->lot_size_sqmts)) {
    $prop->lot_size_sqmts = 0;
}
if(!isset($prop->development)) {
    $prop->development = 'Not Available';
}
if(!isset($prop->furnished)) {
    $prop->furnished = 'Not Available';
}
if(!isset($prop->pets_allowed)) {
    $prop->pets_allowed = 0;
}
if(!isset($prop->zone)) {
    $prop->zone = $prop->Location->AreaName ?? 'Quinta Avenida';
}
if(!isset($prop->city)) {
    $prop->city = $prop->Location->CityName ?? 'Playa del Carmen';
}
if(!isset($prop->state)) {
    $prop->state = $prop->Location->StateName ?? 'Quintana Roo';
}
if(!isset($prop->zip_code)) {
    $prop->zip_code = $prop->Location->Zip ?? '09870';
}
if(!isset($prop->latitude)) {
    $prop->latitude = $prop->Location->Latitude ?? '20.373864';
}
if(!isset($prop->longitude)) {
    $prop->longitude = $prop->Location->Longitude ?? '-87.044753';
}

// Set default location if not set
if(!isset($prop->Location)) {
    $prop->Location = new stdClass();
    $prop->Location->Latitude = '20.373864';
    $prop->Location->Longitude = '-87.044753';
    $prop->Location->CityName = 'Solidaridad';
    $prop->Location->StateName = 'Quintana Roo';
    $prop->Location->CountyName = 'Playa del Carmen';
    $prop->Location->AreaName = '5th Avenue';
    $prop->Location->Address = 'Quinta Avenida';
    $prop->Location->Zip = '09870';
    $prop->Location->City = 'playa-del-carmen';
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
  // Try multiple price type IDs for sale price, then use first available
  if(isset($fees[1]['amt'])) {
      $price = $fees[1]['amt'];
      $pname = $fees[1]['name'];
      $show_price = number_format($price);
  } elseif(isset($fees[3]['amt'])) {
      $price = $fees[3]['amt'];
      $pname = $fees[3]['name'];
      $show_price = number_format($price);
  } elseif(!empty($fees)) {
      // Get the first available price
      $first_price = reset($fees);
      $price = $first_price['amt'];
      $pname = $first_price['name'];
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
    <link href="<?= $assets_prefix ?>/dist/plugins/owl/owl.carousel.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $assets_prefix ?>/dist/plugins/owl/owl.transitions.css" rel="stylesheet" type="text/css"/>
    <?php if(isset($prop->Location->Latitude) && $prop->Location->Latitude != ''){ ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <?php } ?>

    <!-- Custom CSS -->
    <link href="<?= $assets_prefix ?>/dist/css/cng_base.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $assets_prefix ?>/dist/css/cng.css" />
    <link rel="stylesheet" type="text/css" href="<?= $assets_prefix ?>/dist/css/cng_listing.css" />
    <link href="<?= $assets_prefix ?>/adm/assets/css/cng-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.3/dist/less.min.js"></script>
    
    <!-- Alignment Fix for Container Consistency -->
    <style>
        /* Feature card styling with icons */
        .feature-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .prop-sub {
            margin-bottom: 10px;
            margin-top: -10px;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 12px;
        }
        .feature-card .icon-sprite {
            width: 50px;
            height: 50px;
        }
        
        .feature-card i {
            font-size: 50px;
            color: #007bff;
        }
        
        .feature-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
        }
        
        .feature-content strong {
            font-size: 15px;
            font-weight: 600;
            color: #000;
        }
        
        .feature-content span {
            font-size: 13px;
            color: #888;
        }
        
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

        /* Set max height for owl carousel wrapper */
        .owl-carousel .owl-wrapper-outer {
            max-height: 70vh;
        }

        /* Center main slider image vertically and horizontally */
        #property-d-1 .item {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 70vh;
            min-height: 300px;
            background: #f8f8f8;
        }
        #property-d-1 .item img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* Thumbnails: inactive = grayscale, active = color */
        #property-d-1-2 .item {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        #property-d-1-2 .item img {
            width: auto;
            height: 100%;
            max-width: 100%;
            object-fit: cover;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: filter 0.2s;
        }
        #property-d-1-2 .item img.active,
        #property-d-1-2 .owl-item.center .item img {
            filter: none;
            opacity: 1;
        }
    </style>

    <script>
        function goBack() {
            window.history.back();
        }
        
        function scrollToContactForm() {
            const contactForm = document.getElementById('info_form');
            if (contactForm) {
                contactForm.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                // Add a subtle animation to highlight the form
                contactForm.style.transition = 'all 0.5s ease';
                contactForm.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    contactForm.style.transform = 'scale(1)';
                }, 500);
            }
        }
        
        // Add hover effects to CTA button
        document.addEventListener('DOMContentLoaded', function() {
            const ctaBtn = document.querySelector('.cta-scroll-btn');
            if (ctaBtn) {
                ctaBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 8px 25px rgba(0, 123, 255, 0.4)';
                });
                ctaBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 15px rgba(0, 123, 255, 0.3)';
                });
            }
            
            // Handle contact form submission
            const contactForm = document.getElementById('info_form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = contactForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
                    submitBtn.disabled = true;
                    
                    // Get form data
                    const formData = new FormData(contactForm);
                    
                    // Submit via AJAX
                    fetch(contactForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showMessage('Success! ' + data.message, 'success');
                            contactForm.reset();
                        } else {
                            // Show error message
                            showMessage('Error: ' + data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
            
            // Check for URL parameters for form status
            const urlParams = new URLSearchParams(window.location.search);
            const contactStatus = urlParams.get('contact_status');
            const contactMessage = urlParams.get('contact_message');
            
            if (contactStatus && contactMessage) {
                showMessage(decodeURIComponent(contactMessage), contactStatus);
            }
        });
        
        function showMessage(message, type) {
            // Remove any existing messages
            const existingMessages = document.querySelectorAll('.alert-message');
            existingMessages.forEach(msg => msg.remove());
            
            // Create message element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-message`;
            alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" aria-label="Close"></button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(alertDiv);
            
            // Add close functionality
            const closeBtn = alertDiv.querySelector('.btn-close');
            closeBtn.addEventListener('click', () => alertDiv.remove());
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
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
            

            <!-- Main Content - Full Width -->
            <div class="main-content-wrapper">
                
                <!-- Property Header with side-by-side layout -->
                <div class="property-header-section">
                    <div class="container-fluid">
                        <div class="row g-0">
                            <!-- Property Info - Left Side (40%) -->
                            <div class="col-lg-4 col-md-5">
                                <div class="property-info-left">
                                <!-- <div class="location-badge"><?= $prop->Location->AreaName ?></div> -->
                                <h1><?= ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?></h1>
                                <div class="prop-sub">
                                        <span><?= $prop->DisplayStatus ?></span>
                                    </div>
                                <div class="location-detail">
                                    <i class="bi bi-geo-alt"></i>
                                    <?= $prop->Location->CityName .', '. $prop->Location->StateName .' '. $prop->Location->Zip ?>
                                    <?php if(isset($prop->DisplayStatus) && $prop->DisplayStatus != ''){ ?>
                                    
                                    <?php } ?>
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
                                        <?php if(isset($prop->Size->Mt) && $prop->Size->Mt > 0) { ?>
                                            /<span><?= number_format($prop->Size->Mt) ?> mt²</span>
                                        <?php } ?>
                                    </div>
                                    <?php } elseif(isset($prop->Size->Mt) && $prop->Size->Mt > 0) { ?>
                                        <div class="feature-item">
                                        <i class="bi bi-rulers"></i>
                                        <span><?= number_format($prop->Size->Mt) ?> mt²</span>
                                    </div>
                                    <?php } else { ?>
                                    <div class="feature-item">
                                        <i class="bi bi-rulers"></i>
                                        <span>Size Unknown</span>
                                    </div>
                                    <?php } ?>
                                    <?php if(isset($prop->Location->AreaName)){ ?>
                                    <div class="feature-item">
                                        <i class="bi bi-geo-alt"></i>
                                        <span><?= $prop->Location->AreaName ?></span>
                                    </div>
                                    <?php } ?>
                                    <div class="feature-item">
                                        <i class="bi bi-door-open"></i>
                                        <span><?= $prop->PropTypeDisplay ?? 'Villa' ?></span>
                                    </div>
                                    
                                    <?php if(isset($prop->YearBuilt) && $prop->YearBuilt > 0) { ?>
                                    <div class="feature-item">
                                        <i class="bi bi-calendar"></i>
                                        <span>Built <?= $prop->YearBuilt ?></span>
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
                                     <!-- Call to Action Button -->
                                    <div class="cta-button-section" style="margin-top: 2rem; text-align: center;">
                                        <button type="button" class="btn btn-primary btn-lg cta-scroll-btn" onclick="scrollToContactForm()">
                                            <i class="bi bi-calendar-check me-2"></i>
                                            Schedule Your Tour
                                        </button>
                                        <p style="margin-top: 1rem; color: #6c757d; font-size: 0.9rem;"><i class="bi bi-check-lg"></i> Quick response guaranteed <i class="bi bi-check-lg"></i> Personalized viewing</p>
                                    </div>
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
                                <?php if(isset($prop->VirtualTour) && !empty($prop->VirtualTour)) { ?>
                                <div class="vtour-section-gallery">
                                    <a href="<?= htmlspecialchars($prop->VirtualTour) ?>" target="_blank" rel="nofollow" class="vtour">
                                        <i class="bi bi-camera-video"></i>
                                        <span>Take a Virtual Tour</span>
                                    </a>
                                    <!-- <div class="vtour-description">
                                        Experience this property from the comfort of your home
                                    </div> -->
                                </div>
                                <?php } ?>
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
                                        <?php 
                                        // Display features from database
                                        $features_displayed = false;
                                        if(isset($prop->Features) && !empty($prop->Features)) { 
                                            $features_displayed = true;
                                            foreach($prop->Features as $feature_name => $feature_data) { 
                                                $feature_value = is_array($feature_data) ? ($feature_data['value'] ?? $feature_data) : $feature_data;
                                                $feature_icon = is_array($feature_data) && isset($feature_data['icon']) && $feature_data['icon'] > 0 ? $feature_data['icon'] : 0;
                                        ?>
                                        <div class="feature-card">
                                            <?php if($feature_icon > 0) { ?>
                                                <span class="icon-sprite icon-<?= $feature_icon ?>" title="<?= htmlspecialchars($feature_name) ?>"></span>
                                            <?php } else { ?>
                                                <i class="bi bi-check-circle"></i>
                                            <?php } ?>
                                            <div class="feature-content">
                                                <strong><?= htmlspecialchars($feature_name) ?></strong>
                                                <span><?= htmlspecialchars($feature_value) ?></span>
                                            </div>
                                        </div>
                                        <?php 
                                            }
                                        }
                                        
                                        // Fallback features if no features in database
                                        if(!$features_displayed) { 
                                        ?>
                                        <div class="feature-card">
                                            <i class="bi bi-info-circle"></i>
                                            <span>No specific features listed for this property</span>
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
                                           
                                            <?php if(isset($prop->YearBuilt) && $prop->YearBuilt > 0){ ?>
                                                <div class="detail-row">
                                                <span class="label">Year Built:</span>
                                                <span class="value"><?= $prop->YearBuilt ?></span>
                                                </div>
                                            <?php } ?>
                                             <?php if(isset($prop->ZoneDisplay) && $prop->ZoneDisplay != ''){ ?>
                                                <div class="detail-row">
                                                <span class="label">Zoning Type:</span>
                                                <span class="value"><?= $prop->ZoneDisplay ?></span>
                                                </div>
                                            <?php } ?>
                                             <?php if(isset($prop->Size->Lot) && $prop->Size->Lot > 0){ ?>
                                                
                                            <div class="detail-row">
                                                <span class="label">Lot Size:</span>
                                                <span class="value">
                                                    <?php
                                                        echo number_format($prop->Size->Lot) . ' ft² / ' . number_format($prop->Size->Lot / 10.764, 0) . ' mt²';
                                                    ?>
                                                </span>
                                            </div>
                                            <?php } ?>
                                            <div class="detail-row">
                                                <span class="label">Under Construction:</span>
                                                <span class="value"><?php if($prop->Construction){ echo 'Yes'; }else{ echo 'No'; } ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Foreclosure:</span>
                                                <span class="value"><?php if($prop->Foreclosure){ echo 'Yes'; }else{ echo 'No'; } ?></span>
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
                        <h2>Ready for a tour or need more info?</h2>
                        <p class="cta-subtitle">Contact our experienced agents today for an exclusive viewing and personalized consultation.</p>
                    </div>
                    
                    <div class="row">
                        <!-- Contact Form -->
                        <div class="col-lg-6 col-md-12">
                            <div class="contact-card">
                                <h4>Schedule Your Private Tour</h4>
                                
                                <div class="urgency-text">
                                    <i class="bi bi-clock"></i>
                                    <strong>Limited Time:</strong> This exclusive property is generating high interest. Secure your viewing today!
                                </div>
                                
                                <form method="post" action="dist/inc/process/contact_submit.php" id="info_form" class="contact-form">
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
                                        Send Request
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
                                    <h5>Contact Us</h5>
                                    <div class="agent-info">
                                        <?php 
                                        // Display office contact information from site_contact
                                        $contact_displayed = false;
                                        if(isset($site_contact->ContactInfo['office_info']) && !empty($site_contact->ContactInfo['office_info'])) {
                                            foreach($site_contact->ContactInfo['office_info'] as $contact) {
                                                
                                                if($contact['type'] == 'phone') {
                                                    echo '<div class="contact-in">';
                                                    $contact_displayed = true;
                                                    if(!empty($contact['title'])) {
                                                        echo '<strong>' . htmlspecialchars($contact['title']) . '</strong><br>';
                                                    }
                                                    if(!empty($contact['description'])) {
                                                        echo '<span>' . htmlspecialchars($contact['description']) . '</span><br>';
                                                    }
                                                    $phone_clean = preg_replace('/[^0-9+]/', '', $contact['val']);
                                                    echo '<a href="tel:' . htmlspecialchars($phone_clean) . '">' . htmlspecialchars($contact['val']) . '</a><br>';
                                                    echo '</div>';
                                                }
                                                
                                            }
                                            // Display emails if available
                                            foreach($site_contact->ContactInfo['office_info'] as $contact) {
                                                if($contact['type'] == 'email') {
                                                    $contact_displayed = true;
                                                    echo '<a href="mailto:' . htmlspecialchars($contact['val']) . '">' . htmlspecialchars($contact['val']) . '</a><br>';
                                                }
                                            }
                                        }
                                        
                                        // Fallback to contact_page if office_info is empty
                                        if(!$contact_displayed && isset($site_contact->ContactInfo['contact_page']) && !empty($site_contact->ContactInfo['contact_page'])) {
                                            foreach($site_contact->ContactInfo['contact_page'] as $contact) {
                                                if($contact['type'] == 'phone') {
                                                    $contact_displayed = true;
                                                    if(!empty($contact['title'])) {
                                                        echo '<strong>' . htmlspecialchars($contact['title']) . '</strong><br>';
                                                    }
                                                    $phone_clean = preg_replace('/[^0-9+]/', '', $contact['val']);
                                                    echo '<a href="tel:' . htmlspecialchars($phone_clean) . '">' . htmlspecialchars($contact['val']) . '</a><br>';
                                                }
                                            }
                                            foreach($site_contact->ContactInfo['contact_page'] as $contact) {
                                                if($contact['type'] == 'email') {
                                                    echo '<a href="mailto:' . htmlspecialchars($contact['val']) . '">' . htmlspecialchars($contact['val']) . '</a><br>';
                                                }
                                            }
                                        }
                                        
                                        // Final fallback if no contact info available
                                        if(!$contact_displayed) { ?>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
    <script src="<?= $assets_prefix ?>/dist/plugins/owl/owl.carousel.min.js"></script>
    <script src="<?= $assets_prefix ?>/dist/js/listing.js"></script>
    
    <script>
        // Initialize Leaflet Map with dynamic coordinates
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            const lat = parseFloat(mapContainer.dataset.lat) || 20.6598;
            const lng = parseFloat(mapContainer.dataset.lng) || -105.2257;
            
            // Create map
            const map = L.map('map').setView([lat, lng], 15);
            
            // Add OpenStreetMap tile layer
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            
            // Add marker
            const marker = L.marker([lat, lng]).addTo(map);
            
            // Optional: Add popup with property address
            const address = '<?= htmlspecialchars($prop->zone ?? "Marina Vallarta") ?>, <?= htmlspecialchars($prop->city ?? "Puerto Vallarta") ?>';
            marker.bindPopup(address).openPopup();
        }
    </script>

</body>
</html>