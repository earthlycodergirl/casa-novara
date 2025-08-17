<?php
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


// ENGLISH PAGES
$link_home['en'] = '/';
$link_about['en'] = 'about';
$link_contact['en'] = 'contact-us';
$link_blog['en'] = 'mexico-real-estate/';
$link_list['en'] = 'list-property';
$link_property['en'] = 'listing/';
$link_map['en'] = 'listings-map';
$link_properties['en'] = '/listings';
$link_terms['en'] = 'terms-and-conditions';
$link_privacy['en'] = 'privacy-policy';
$link_ptypes['en'] = array('listings/commercial','listings/residential','listings/lots-land');
$link_loctypes['en'] = array('city'=>'listings/cities','town'=>'listings/towns','area'=>'listings/areas');
$current_link['en'] = $link_home['en'];

// SPANISH PAGES
$link_home['es'] = '/es/';
$link_about['es'] = 'es/quienes-somos';
$link_contact['es'] = 'es/contacto';
$link_blog['es'] = 'es/bienes-raices-mexico/';
$link_list['es'] = 'es/vende-tu-propiedad';
$link_property['es'] = 'es/propiedad/';
$link_map['es'] = 'es/mapa';
$link_properties['es'] = 'es/propiedades';
$link_terms['es'] = 'es/terminos';
$link_privacy['es'] = 'es/privacidad';
$link_ptypes['es'] = array('es/propiedades/comercial','es/propiedades/residencial','es/propiedades/lotes');
$link_loctypes['es'] = array('city'=>'es/ciudades','town'=>'es/pueblos','area'=>'es/areas');
$current_link['es'] = $link_home['es'];

$site_url = '/';
$base_href = '/';
$nav_class = $logo_class = '';

$phones_mia = $social_mia = array();
$phone_mia = '';

$lang = 'en';
if(isset($_GET['lang'])){
  $lang = $_GET['lang'];
}


// prefix definitions
$prop_img_url = '/images/';
$blog_img_url = '/images/blog/';

require('adm/classes/sql.class.php');
require('adm/classes/site.class.php');
require_once('langs/'.$lang.'.php');
require_once('langs/meta.php');

// Language files



// get all the contact information for display and use on webpage
$site_contact = new Site();
$site_contact->getContact();
$site_contact->getNav();

// get phones
if(!empty($site_contact->ContactInfo['contact_page'])){
  foreach($site_contact->ContactInfo['contact_page'] as $cc){
    if($cc['type'] == 'phone'){
      $phones_mia[] = $cc['val'];
    }
  }
  $phone_mia = $phones_mia[0];
}

// get social
if(!empty($site_contact->ContactInfo['social'])){
  foreach($site_contact->ContactInfo['social'] as $cc){
    $dd = array();
    $dd['icon'] = $cc['icon'];
    $dd['val'] = $cc['val'];

    $social_mia[] = $dd;
  }
}

// Check if to include listing class
if(isset($show_listings) && $show_listings == 1){
    include 'dist/classes/publist.class.php';

    $listings = new SiteListings();
    $property_types = $listings->getPropertyTypes();
    $listing_types = $listings->getListTypes();

    if(isset($build_search) && $build_search == 1){
        $price_range = $listings->getPriceRange();
        //print_r($price_range);
    }
}

include 'adm/classes/listings.class.php';

$listings2 = new Listings;
$listings2->getPropertyTypes();

if(isset($listing) && $listing == 1){
    //include 'adm/classes/listings.class.php';

    $listings = new Listings;
    $listings->getPropertyTypes();
    $listings->getZones();
    $listings->getPriceTypes();
}



$is_mobile = false;
// Check if mobile device
function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
, $_SERVER["HTTP_USER_AGENT"]);
}
if(isMobileDevice()){
    $mobile_class = 'is_mobile';
    $is_mobile = true;
}
else {
    $mobile_class = 'no_mobile';
}

function print_me($array){
    echo '<pre>';
    print_r($array);
    echo "</pre>";
}

// function to get the first $count words from text
function get_words($sentence, $count = 4) {
  preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
  return $matches[0];
}




?>
