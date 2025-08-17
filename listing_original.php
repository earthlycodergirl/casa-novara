<?php
session_start();
$listing = 1;
$ppage = 2;
$curr = 1;
$curr_desc = 'USD';

require('base.php');

$site = new Site();
$list = new Listings();
if(isset($_GET['prop_id']) && $_GET['prop_id'] > 0){
    if(isset($_GET['type'])){
        if($_GET['type'] == 'budd'){
            $prop = new Listing($_GET['prop_id']);


            $prop->PropTypeDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['desc'];
            $prop->ZoneDisplay = $listings->ZoningTypes[$prop->ZoningId][$lang];
            $prop->PropTypeSubDisplay = (!empty($listings->PropertyTypes[$prop->PropertyTypeId]['subs'])) ? $listings->PropertyTypes[$prop->PropertyTypeId]['subs'][$prop->PropertySubTypeId] : '';

            if($lang == 'es'){
              $prop->PropTypeDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['desc_es'];
              $prop->PropTypeSubDisplay = (!empty($listings->PropertyTypes[$prop->PropertyTypeId]['subs_es'])) ? $listings->PropertyTypes[$prop->PropertyTypeId]['subs_es'][$prop->PropertySubTypeId] : '';
            }

            if($prop->IsVisible != 1){
                header('location: /'.$base_href);
            }
            //printme($prop);
        }
        if($_GET['type'] == 'up'){
            $prop = new Listing($_GET['prop_id'],'up');
            $thumb = $prop->PropThumb;
        }
    }
    $list->getPriceTypes();
    $price_types = $list->PriceTypes;
}

//print_me($prop);

$month_arr = array(
  1=>'Jan',
  2=>'Feb',
  3=>'Mar',
  4=>'Apr',
  5=>'May',
  6=>'Jun',
  7=>'Jul',
  8=>'Aug',
  9=>'Sep',
  10=>'Oct',
  11=>'Nov',
  12=>'Dec',
);

if(isset($_SESSION['currency']) && $_SESSION['currency'] != 'usd'){
  require_once('dist/inc/process/get_currency.php');
  $curr_desc = strtoupper($_SESSION['currency']);
  $_SESSION['currency'] = $_SESSION['currency'];
}


?>

<!doctype html>
<html lang="en">
  <head>
     <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?></title>
    <meta name="robots" content="index" />
    <link rel="canonical" href="<?= $link_property[$lang].$prop->PropertyId ?>">
    <meta name="description" content="<?= ($lang == 'es') ? substr($prop->PropertyDescEs,0,155).'...' : substr($prop->PropertyDesc,0,155).'...' ?>">


    <meta property="og:title" content="<?= ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?>">
    <meta property="og:description" content="<?= ($lang == 'es') ? substr($prop->PropertyDescEs,0,155).'...' : substr($prop->PropertyDesc,0,155).'...' ?>">
    <meta property="og:image" content="<?= $prop_img_url.reset($prop->PhotosDisplay) ?>">
    <meta property="og:url" content="https://miarealty.com.mx/<?= $link_property[$lang].$prop->PropertyId ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="MIA Realty">
    <meta name="twitter:image:alt" content="<?= ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="dist/plugins/responsive/responsive.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/responsive/yamm.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/owl/owl.carousel.css" rel="stylesheet" type="text/css"/>
    <link href="dist/plugins/owl/owl.transitions.css" rel="stylesheet" type="text/css"/>
    <?php if($prop->Location->Latitude != ''){ ?>
       <link href="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.css" rel="stylesheet" />
     <?php } ?>
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />


    <script>
      function goBack() {
          window.history.back();
      }
    </script>
    <?php include 'dist/inc/favicon.php'; ?>
  </head>
  <body class="<?= $mobile_class ?>" data-lat="<?= $prop->Location->Latitude ?>" data-lon="<?= $prop->Location->Longitude ?>">

    <?php require('dist/inc/nav_top.php') ?>

    <div class="inner-head bg-listing" style="--background-image:
    linear-gradient(to bottom, rgba(0,0,0, 0.50), rgba(0,0,0, 0.60)), url('<?= $prop_img_url.reset($prop->PhotosDisplay) ?>')">
      <?php require('dist/inc/nav.php') ?>
      <div class="container">
        <div class="row">
          <div class="col-sm-8">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
              <ol class="breadcrumb">
                <li class="breadcrumb-item mobile-hide" onclick="goBack()"><?= $prop->Location->CityName ?> <?= $lan['prop']['prop'] ?></li>
                <li class="breadcrumb-item fake-link"><a href="<?= $link_properties[$lang] ?>?location=<?= $prop->Location->City ?>&dsearch=all&baths=0&beds=0&property_type=0&search_type=basic&list_type=0"><?= $lan['prop']['sres']?></a></li>
                <li class="breadcrumb-item active"  aria-current="page"><?= $lan['prop']['prop'] ?></li>
              </ol>
            </nav>
          </div>

          <div class="col-4 text-end position-relative mobile-hide">
             <div class="title-box">
                <h3><?= $prop->Location->AreaName ?></h3>
                <h2><?= $prop->Location->CountyName.', '.$prop->Location->StateName ?></h2>
             </div>
          </div>
        </div>
      </div>

    </div>


    <!-- start main content section -->
    <section class="properties" id="property_page">
        <div class="container">
            <div class="row">


               <!-- start sidebar -->
               <div class="col-lg-3 col-md-3 order-1 order-xs-2">
                  <!--<div class="prop-type">
                    <?= isset($prop->Features['Property Style']) ? $prop->Features['Property Style'] : $prop->DisplayStatus; ?>
                 </div>-->
                  <div class="prop-dets">

                    <?php
                    if($lang == 'es'){
                      $dstatus = $prop->DisplayStatusEs;
                    }else{
                      $dstatus = $prop->DisplayStatus;
                    }
                    if($dstatus != ''){ ?>
                    <!-- detail sale type -->
                    <div class="prop-det d-flex d-flex">
                       <div class="icon">
                         <i class="bi bi-asterisk"></i>
                       </div>
                       <div class="val flex-fill  flex-fill "><?= $dstatus ?></div>
                    </div>
                    <?php } ?>
                    <!-- detail sale type end -->

                    <!-- detail zone zone -->
                    <div class="prop-det d-flex">
                       <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16.872" height="16.872" viewBox="0 0 16.872 16.872">
                           <path id="Icon_material-map" data-name="Icon material-map" d="M20.9,4.5l-.15.028-5.005,1.94L10.124,4.5,4.837,6.281a.472.472,0,0,0-.337.45V20.9a.464.464,0,0,0,.469.469l.15-.028,5.005-1.94,5.624,1.968,5.286-1.781a.472.472,0,0,0,.337-.45V4.969A.464.464,0,0,0,20.9,4.5Zm-5.155,15-5.624-1.978V6.375l5.624,1.978Z" transform="translate(-4.5 -4.5)" fill="#fff"/>
                        </svg>

                       </div>
                       <div class="val flex-fill "><?= $prop->PropTypeDisplay ?></div>
                    </div>
                    <!-- detail zone zone end -->

                    <?php if(isset($prop->PropTypeSubDisplay) && $prop->PropTypeSubDisplay != ''){ ?>
                      <!-- detail zone zone -->
                      <div class="prop-det d-flex">
                         <div class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16.872" height="16.872" viewBox="0 0 16.872 16.872">
                             <path id="Icon_material-map" data-name="Icon material-map" d="M20.9,4.5l-.15.028-5.005,1.94L10.124,4.5,4.837,6.281a.472.472,0,0,0-.337.45V20.9a.464.464,0,0,0,.469.469l.15-.028,5.005-1.94,5.624,1.968,5.286-1.781a.472.472,0,0,0,.337-.45V4.969A.464.464,0,0,0,20.9,4.5Zm-5.155,15-5.624-1.978V6.375l5.624,1.978Z" transform="translate(-4.5 -4.5)" fill="#fff"/>
                          </svg>

                         </div>
                         <div class="val flex-fill "><?= $prop->PropTypeSubDisplay ?></div>
                      </div>
                      <!-- detail zone zone end -->
                    <?php } ?>

                    <?php
                    if($prop->Bedrooms >= 1){ ?>
                     <!-- detail bedrooms -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20.4" height="13.6" viewBox="0 0 20.4 13.6">
                             <path id="Icon_ionic-md-bed" data-name="Icon ionic-md-bed" d="M10.062,16.252a2.721,2.721,0,1,0-2.784-2.72A2.75,2.75,0,0,0,10.062,16.252Zm11.13-5.44H13.77V17.16H6.354V9H4.5V22.6H6.354V19.88H23.046V22.6H24.9V14.44A3.668,3.668,0,0,0,21.192,10.812Z" transform="translate(-4.5 -9)" fill="#fff"/>
                           </svg>
                        </div>
                        <div class="val flex-fill "><?= $prop->Bedrooms ?> <?php echo $prop->Bedrooms == 1 ? $lan['prop']['beds'][1] : $lan['prop']['beds'][0]; ?></div>
                     </div>
                     <!-- detail bedrooms end -->

                   <?php }elseif($prop->RoomType != 'bed'){ ?>
                     <!-- detail bedrooms -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20.4" height="13.6" viewBox="0 0 20.4 13.6">
                             <path id="Icon_ionic-md-bed" data-name="Icon ionic-md-bed" d="M10.062,16.252a2.721,2.721,0,1,0-2.784-2.72A2.75,2.75,0,0,0,10.062,16.252Zm11.13-5.44H13.77V17.16H6.354V9H4.5V22.6H6.354V19.88H23.046V22.6H24.9V14.44A3.668,3.668,0,0,0,21.192,10.812Z" transform="translate(-4.5 -9)" fill="#fff"/>
                           </svg>
                        </div>
                        <div class="val flex-fill "><?= ucfirst($prop->RoomType) ?></div>
                     </div>
                     <!-- detail bedrooms end -->
                   <?php }
                    if($prop->TotalBaths >= 1){ ?>
                      <!-- detail bathrooms -->
                      <div class="prop-det d-flex">
                         <div class="icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="21.429" height="18.75" viewBox="0 0 21.429 18.75">
                            <path id="Icon_awesome-bath" data-name="Icon awesome-bath" d="M20.424,11.625H3.348V5.6a1.339,1.339,0,0,1,2.47-.717A2.844,2.844,0,0,0,6.1,8.357a.5.5,0,0,0,.021.688l.474.474a.5.5,0,0,0,.71,0l3.977-3.977a.5.5,0,0,0,0-.71l-.474-.474a.5.5,0,0,0-.688-.021,2.84,2.84,0,0,0-2.686-.643,3.347,3.347,0,0,0-6.1,1.9v6.027H1a1,1,0,0,0-1,1v.67a1,1,0,0,0,1,1h.335v1.339a4.007,4.007,0,0,0,1.339,2.994V20a1,1,0,0,0,1,1h.67a1,1,0,0,0,1-1v-.335H16.071V20a1,1,0,0,0,1,1h.67a1,1,0,0,0,1-1V18.637a4.007,4.007,0,0,0,1.339-2.994V14.3h.335a1,1,0,0,0,1-1v-.67A1,1,0,0,0,20.424,11.625Z" transform="translate(0 -2.25)" fill="#fff"/>
                           </svg>

                         </div>
                         <div class="val flex-fill "><?= $prop->TotalBaths ?> <?php echo $prop->TotalBaths == 1 ? $lan['prop']['baths'][1] : $lan['prop']['baths'][0]; ?></div>
                      </div>
                      <!-- detail bathrooms end -->
                    <?php } ?>

                     <?php if($prop->Size->Ft > 0){ ?>
                     <!-- detail square footage -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="22.473" height="22.473" viewBox="0 0 22.473 22.473">
                           <path id="Icon_awesome-expand" data-name="Icon awesome-expand" d="M0,9.674V3.454a1.2,1.2,0,0,1,1.2-1.2h6.22a.6.6,0,0,1,.6.6V4.859a.6.6,0,0,1-.6.6H3.21V9.674a.6.6,0,0,1-.6.6H.6A.6.6,0,0,1,0,9.674ZM14.447,2.852V4.859a.6.6,0,0,0,.6.6h4.214V9.674a.6.6,0,0,0,.6.6h2.007a.6.6,0,0,0,.6-.6V3.454a1.2,1.2,0,0,0-1.2-1.2h-6.22A.6.6,0,0,0,14.447,2.852ZM21.871,16.7H19.865a.6.6,0,0,0-.6.6v4.214H15.049a.6.6,0,0,0-.6.6v2.007a.6.6,0,0,0,.6.6h6.22a1.2,1.2,0,0,0,1.2-1.2V17.3A.6.6,0,0,0,21.871,16.7ZM8.026,24.121V22.115a.6.6,0,0,0-.6-.6H3.21V17.3a.6.6,0,0,0-.6-.6H.6a.6.6,0,0,0-.6.6v6.22a1.2,1.2,0,0,0,1.2,1.2h6.22A.6.6,0,0,0,8.026,24.121Z" transform="translate(0 -2.25)" fill="#fff"/>
                          </svg>

                        </div>
                        <div class="val flex-fill sizes"><?= number_format((int)$prop->Size->Ft).' ft&sup2;'.' / '.number_format((int)$prop->Size->Mt).' mt&sup2; <small>('.$lan['prop']['left_size'].')</small>'; ?></div>
                     </div>
                     <!-- detail square footage end -->
                    <?php } ?>


                    <?php if($prop->Size->Lot > 0){ ?>
                     <!-- detail Lot size -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-textarea" viewBox="0 0 16 16">
                           <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874V2.5zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5v3.563zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                          </svg>
                        </div>
                        <div class="val flex-fill sizes"><?= number_format((int)$prop->Size->Lot).' ft&sup2;'.' / '.number_format((int)$prop->Size->LotMt).' mt&sup2; <small>('.$lan['prop']['left_lot'].')</small>'; ?></div>
                     </div>
                     <?php } ?>
                     <!-- detail Lot size end -->



                  </div>

                  <p class="price">
                       <?php
                       $i=0; foreach($prop->Prices as $pp){
                          if($pp['type'] == 1 || $pp['type'] == 2){
                            $price = $pp['amt'];
                            $pname = $pp['name'];
                          }
                        }
                        $show_price = number_format(($price * $curr));
                        echo '<small>$</small>'.$show_price.'  <small>'.$curr_desc.'</small>';
                        if($pname != ''){
                           echo '<span class="pname">'.$pname.'</span>';
                        }

                       ?>
                   </p>
                  <button class="btn btn-show-call collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#call_form" aria-expanded="false" aria-controls="call_form">
                    <?= $lan['prop']['request'] ?>
                  </button>


                   <!-- start request form -->
                   <div class="filterContent defaultTab sidebarWidget collapse" id="call_form">
                       <form method="post" action="#" id="info_form" class="needs-validation" novalidate>
                         <input type="hidden" name="prop_id" id="pr" value="<?= $prop->PropertyId ?>">
                           <div class="form-head"><?= $lan['prop']['form_head'] ?></div>
                           <div class="form-txt"><?= $lan['prop']['form_txt'] ?></div>
                           <div class="form-body">
                              <div class="form-group" id="na_0">
                                   <label><?= $lan['prop']['form'][0][0] ?> <span>*</span></label>
                                   <input type="text" name="full_name" id="na" class="form-control" required>
                                   <div class="valid-feedback"><?= $lan['prop']['form'][5] ?></div>
                                   <div class="invalid-feedback"><?= $lan['prop']['form'][0][1] ?></div>
                              </div>
                              <div class="form-group">
                                   <label><?= $lan['prop']['form'][1][0] ?><span>*</span></label>
                                   <input type="text" name="phone" id="ph" class="form-control">
                              </div>
                              <div class="form-group" id="em_0">
                                   <label><?= $lan['prop']['form'][2][0] ?> <span>*</span></label>
                                   <input type="email" name="email" id="em" class="form-control" required>
                                   <div class="valid-feedback"><?= $lan['prop']['form'][5] ?></div>
                                   <div class="invalid-feedback"><?= $lan['prop']['form'][2][1] ?></div>
                              </div>

                              <div class="form-group">
                                   <label><?= $lan['prop']['form'][3][0] ?></label>
                                   <textarea name="message" id="no" class="form-control"></textarea>
                              </div>
                              <button type="submit" name="send_info_form" id="send_request" class="btn"><?= $lan['prop']['form'][4][0] ?></button>
                           </div>
                       </form><!-- end form -->
                   </div><!-- end quick search widget -->


                   <!-- success message for form submission -->
                   <div id="success_form">
                     <div class="success-out">
                       <i class="bi bi-check2"></i>
                       <h5><?= $lan['prop']['h5'] ?></h5>
                       <p><?= $lan['prop']['p'] ?></p>
                     </div>
                   </div>
                   <!-- end success message -->




                <div class="prop-location">
                   <h4><?= $lan['prop']['location'] ?></h4>
                   <?php if($prop->Location->Address == ''){ echo '<p>The exact location/address is given upon information requests.</p>'; } ?>
                   <p class="address"><i class="bi bi-geo-alt"></i> <?php if($prop->Location->Address != ''){ echo $prop->Location->Address.'<br />'; } echo $prop->Location->CityName.', '.$prop->Location->StateName.'<br />Mexico '.$prop->Location->Zip ?></p>

                   <div id="map" class="mapSmall"></div>
                </div>


               </div>
               <!-- end sidebar -->

                <!-- start content -->
                <div class="col-lg-9 col-md-9 order-2 order-xs-1">
                   <div class="property-content">


                     <div class="prop-info mobile-show">
                       <h1><?= ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?></h1>
                       <div class="desc">
                           <p><?= nl2br($prop->PropertyDesc) ?></p>
                           <?php if(isset($prop->PropertyDesc2)){ echo '<p>'.$prop->PropertyDesc2.'</p>'; } ?>
                       </div>
                     </div>

                     <!-- PHOTO GALLERY --->

                     <?php if(!empty($prop->PhotosDisplay)){ ?>


                       <div id="property-d-1" class="owl-carousel">
                         <?php foreach($prop->PhotosDisplay as $pp){  ?>
                             <div class="item"><img data-src="<?= $prop_img_url.$pp ?>" class="lozad" alt="<?= $prop->PropertyTitle ?>"/></div>
                           <?php } ?>
                       </div>
                       <div id="property-d-1-2" class="owl-carousel">
                         <?php foreach($prop->Photos as $pp){  ?>
                             <div class="item"><img src="<?= $prop_img_url.$pp[0].'/thumbs/'.$pp[1] ?>" alt="image"/></div>
                           <?php } ?>
                       </div>









                     <div id="property_carousel" class="carousel slide" data-bs-ride="carousel">

                      <div class="carousel-inner">
                        <?php $i = 0;
                          foreach($prop->PhotosDisplay as $pp){ if($i==0){ $cl = 'active'; }else{ $cl = ''; } ?>
                            <div class="carousel-item <?= $cl ?>">
                              <img data-src="<?= $prop_img_url.$pp ?>" class="d-block w-100 lozad" alt="<?= $prop->PropertyTitle ?>">
                            </div>
                          <?php $i++; } ?>
                      </div>
                      <ol class="carousel-indicators">
                        <?php $i=0;
                          foreach($prop->Photos as $pp){ if($i==0){ $cl = ' class="active"'; }else{ $cl = ''; } ?>
                          <li data-bs-target="#property_carousel" data-bs-slide-to="<?= $i ?>" <?= $cl ?>>
                            <img src="<?= $prop_img_url.$pp[0].'/thumbs/'.$pp[1] ?>" width="50">
                          </li>
                        <?php $i++; } ?>
                      </ol>
                      <a class="carousel-control-prev" href="#property_carousel" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden"><?= $lan['prop']['step'][0] ?></span>
                      </a>
                      <a class="carousel-control-next" href="#property_carousel" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden"><?= $lan['prop']['step'][1] ?></span>
                      </a>
                    </div>
                  <?php } ?>

                  <?php if($prop->VirtualTour != ''){
                    echo '<a href="'.$prop->VirtualTour.'" target="_blank" rel="nofollow" class="vtour"><img src="dist/img/icons/360.png" alt="virtual tour" /> See the virtual tour of this property</a>';
                  } ?>


                     <!-- PHOTO GALLERY END -->


                      <div class="prop-info mobile-hide">
                        <h1><?=  ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?></h1>
                        <div class="desc">
                            <p><?=  ($lang == 'es') ? nl2br($prop->PropertyDescEs) : nl2br($prop->PropertyDesc) ?></p>
                            <?php if(isset($prop->PropertyDesc2)){ echo '<p>'.$prop->PropertyDesc2.'</p>'; } ?>
                        </div>
                      </div>


                      <h4><?= $lan['prop']['h4'] ?></h4>

                      <table class="amentitiesTable features">
                         <tbody>
                            <?php if($prop->PropertyTypeId == 20 || strtolower($prop->PropTypeDisplay) == 'residential'){
                              if($prop->RoomType != 'bed'){ ?>
                                <tr><td class="wi">Room Type </td><td><span><?= ucfirst($prop->RoomType) ?></span></td></tr>
                              <?php }elseif($prop->Bedrooms >= 1){ ?>
                                <tr><td class="wi"><?= ucfirst($lan['prop']['beds'][0]) ?> </td><td><span><?= $prop->Bedrooms ?></span></td></tr>
                              <?php } ?>
                              <tr><td class="wi"><?= ucfirst($lan['prop']['baths'][0]) ?> </td><td><span><?= $prop->TotalBaths ?></span></td></tr>
                              <?php }
                              $hide_dev = 0;
                              if($prop->Construction == 1){
                                 $dtxt = $lan['prop']['ptype'][0];
                                 $devtxt = $lan['prop']['ptype'][1];
                               }elseif(strtolower($prop->PropTypeDisplay) == 'residential'){
                                 $dtxt = $lan['prop']['ptype'][2];
                                 $devtxt = $lan['prop']['ptype'][3];
                               }else{
                                 $dtxt = $lan['prop']['ptype'][4];
                                 $devtxt = $lan['prop']['ptype'][5];
                                 $hide_dev = 1;
                               }

                               if($prop->YearBuilt > 0 && $prop->YearBuilt != ''){ ?>
                                <tr>
                                  <td class="wi"><?= $dtxt ?> </td>
                                  <td>
                                  <?php if($prop->MonthBuilt > 0 && $prop->MonthBuilt != ''){ ?>
                                    <span><?= $lan['months'][$prop->MonthBuilt] ?></span>
                                  <?php } ?><span><?= $prop->YearBuilt ?></span>
                                  </td>
                                </tr>
                              <?php } if($hide_dev == 0){ ?>
                                <tr>
                                  <td class="wi"><?= $lan['prop']['dev'] ?></td>
                                  <td><?= $devtxt ?></td>
                                </tr>
                                <?php } ?>
                            <tr><td class="wi">MLS # </td><td><span><?= $prop->MLS ?></span></td></tr>
                         </tbody>
                      </table>

                      <table class="amentitiesTable features">
                         <tbody>
                            <?php if($prop->IsFeatured == 1){ ?>
                            <tr class="featured"><td class="wi" colspan="2"><span><i class="bi bi-star"></i> Featured Listing</span></td></tr>
                            <?php } ?>
                            <tr><td class="wi"><?= $lan['prop']['type'] ?> </td><td><span><?= $prop->PropTypeDisplay ?></span></td></tr>
                            <?php if($prop->PropTypeSubDisplay != ''){ ?>
                            <tr><td class="wi"><?= $lan['prop']['style'] ?> </td><td><span><?= $prop->PropTypeSubDisplay ?></span></td></tr>
                            <?php } ?>
                            <tr>
                              <td class="wi">Area </td>
                              <td><span>
                              <?php if($prop->Location->AreaName != ''){ echo $prop->Location->AreaName.', ';} echo $prop->Location->CountyName; ?></span></td></tr>
                            <?php if($prop->Size->Ft > 0 || $prop->Size->Mt > 0){ ?>
                            <tr><td class="wi"><?= $lan['prop']['size'] ?> </td><td>
                              <?php if($prop->Size->Ft > 0){ ?>
                                <span><?= number_format($prop->Size->Ft) ?> ft&sup2;</span>
                              <?php } if($prop->Size->Mt > 0){ ?>
                                / <span><?= number_format($prop->Size->Mt) ?> mt&sup2;</span>
                              <?php } ?>
                            </td></tr>
                            <?php } ?>
                         </tbody>
                      </table>

                      <div class="clearfix"></div>




                      <!-- Property Features -->
                      <?php
                      if($lang == 'es'){
                        $prop->Features = $prop->FeaturesEs;
                      }
                      if(!empty($prop->Features)){ ?>
                      <h4><?= $lan['prop']['features'] ?></h4>

                      <table class="amentitiesTable features">
                         <tbody>


                          <?php
                             $feature_cnt = count($prop->Features);
                             $cnt = 0;
                             foreach($prop->Features as $ii=>$vv){ if($cnt == ceil(($feature_cnt+1) / 2)){ echo '</tbody></table><table class="amentitiesTable features"><tbody>'; }

                             $val = '';
                             if(strtolower(trim($vv)) == "yes"){
                               $val = '<i class="bi bi-check2 text-success"></i>';
                             }elseif(strtolower(trim($vv)) == 'no'){
                               $val = '<i class="bi bi-x"></i>';
                             }else{ $val = $vv; } ?>
                            <tr>
                                <td class="wi"><?= $ii ?></td>
                                <td><?= $val ?></td>
                            </tr>
                          <?php $cnt++; } ?>
                          </tbody>
                      </table>
                      <div class="clearfix"></div>
                      <?php } ?>


                      <!-- Pricing and fee details -->
                      <?php if(!empty($prop->Prices) && count($prop->Prices) > 1){ ?>
                      <div id="pricing_options">
                          <h4><?= $lan['prop']['h42'] ?></h4>
                          <div class="divider thin"></div>
                          <table class="table pricing-tb">
                          <?php $j=1; foreach($prop->Prices as $pp){ if($pp['type'] != 1){
                            $show_fees = $pp['amt'] * $curr;
                            ?>

                            <tr>
                              <td class="wi"><?= $pp['name'] ?> <span class="type"><?= $price_types[$pp['type']][$lang] ?></span></td>
                              <td><span class="cost">$<?= number_format($show_fees).' <small>'.$curr_desc.'</small>'; ?></span>
                              </td>
                            </tr>


                          <?php if($j != count($prop->Prices)){ ?>
                          <div class="divider thin dashed"></div>
                        <?php }  $j++; } } ?>
                        </table>

                      </div>
                      <?php } ?>
                  </div>
                </div>
                <!-- end content -->


            </div><!-- end row -->

        </div><!-- end container -->
    </section>
    <!-- end main content -->


    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="dist/plugins/responsive/respond.js"></script>
    <?php if($prop->Location->Latitude != ''){ ?>
      <script src="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.js"></script>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
    <script src="dist/plugins/owl/owl.carousel.min.js"></script>
    <script src="dist/js/listing.js"></script>



  </body>
  </html>
