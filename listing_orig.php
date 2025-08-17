<?php
$listing = 1;
$ppage = 2;

require('base.php');

$site = new Site();
if(isset($_GET['prop_id']) && $_GET['prop_id'] > 0){
    if(isset($_GET['type'])){
        if($_GET['type'] == 'budd'){
            $prop = new Listing($_GET['prop_id']);

            $prop->PropTypeDisplay = $listings->PropertyTypes[$prop->PropertyTypeId]['desc'];
            $prop->ZoneDisplay = $listings->ZoneTypes[$prop->ZoningId];
            $prop->PropTypeSubDisplay = (!empty($listings->PropertyTypes[$prop->PropertyTypeId]['subs'])) ? ', '.$listings->PropertyTypes[$prop->PropertyTypeId]['subs'][$prop->PropertySubTypeId] : '';

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
}
?>

<!doctype html>
<html lang="en">
  <head>
     <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="dist/plugins/Justified.js/justifiedGallery.min.css">
    <link href="dist/plugins/swipebox/src/css/swipebox.min.css" rel="stylesheet" />

    <link href="dist/plugins/responsive/responsive.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/responsive/yamm.css" rel="stylesheet" type="text/css" media="all" />

   <link href="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.css" rel="stylesheet" />

    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <title>Property <?= $prop->PropertyTitle ?></title>
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
                <li class="breadcrumb-item mobile-hide" onclick="goBack()"><?= $prop->Location->CityName ?> Properties</li>
                <li class="breadcrumb-item fake-link" onclick="goBack()">Search Results</li>
                <li class="breadcrumb-item active"  aria-current="page">Property</li>
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

                    <!-- detail sale type -->
                    <div class="prop-det d-flex d-flex">
                       <div class="icon">
                         <svg xmlns="http://www.w3.org/2000/svg" width="10.797" height="22" viewBox="0 0 10.797 22">
                           <path id="Icon_material-attach-money" data-name="Icon material-attach-money" d="M15.292,14.156c-2.408-.721-3.182-1.467-3.182-2.628,0-1.332,1.071-2.261,2.864-2.261a2.359,2.359,0,0,1,2.651,2.567h2.344a4.75,4.75,0,0,0-3.4-4.657V4.5H13.383V7.14a4.529,4.529,0,0,0-3.712,4.412c0,2.823,2.026,4.229,4.985,5.048,2.651.733,3.182,1.809,3.182,2.946,0,.843-.52,2.188-2.864,2.188-2.185,0-3.044-1.124-3.161-2.567H9.48a4.856,4.856,0,0,0,3.9,4.681V26.5h3.182V23.872a4.282,4.282,0,0,0,3.712-4.339C20.277,16.062,17.7,14.877,15.292,14.156Z" transform="translate(-9.48 -4.5)" fill="#fff"/>
                          </svg>

                       </div>
                       <div class="val flex-fill  flex-fill "><?= $prop->DisplayStatus ?></div>
                    </div>
                    <!-- detail sale type end -->

                    <!-- detail zone zone -->
                    <div class="prop-det d-flex">
                       <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16.872" height="16.872" viewBox="0 0 16.872 16.872">
                           <path id="Icon_material-map" data-name="Icon material-map" d="M20.9,4.5l-.15.028-5.005,1.94L10.124,4.5,4.837,6.281a.472.472,0,0,0-.337.45V20.9a.464.464,0,0,0,.469.469l.15-.028,5.005-1.94,5.624,1.968,5.286-1.781a.472.472,0,0,0,.337-.45V4.969A.464.464,0,0,0,20.9,4.5Zm-5.155,15-5.624-1.978V6.375l5.624,1.978Z" transform="translate(-4.5 -4.5)" fill="#fff"/>
                        </svg>

                       </div>
                       <div class="val flex-fill "><?= $prop->ZoneDisplay ?></div>
                    </div>
                    <!-- detail zone zone end -->

                    <?php if($prop->Bedrooms >= 1 && $prop->Bathrooms >= 1){ ?>
                     <!-- detail bedrooms -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20.4" height="13.6" viewBox="0 0 20.4 13.6">
                             <path id="Icon_ionic-md-bed" data-name="Icon ionic-md-bed" d="M10.062,16.252a2.721,2.721,0,1,0-2.784-2.72A2.75,2.75,0,0,0,10.062,16.252Zm11.13-5.44H13.77V17.16H6.354V9H4.5V22.6H6.354V19.88H23.046V22.6H24.9V14.44A3.668,3.668,0,0,0,21.192,10.812Z" transform="translate(-4.5 -9)" fill="#fff"/>
                           </svg>
                        </div>
                        <div class="val flex-fill "><?= $prop->Bedrooms ?> <?php echo $prop->Bedrooms == 1 ? 'bedroom' : 'bedrooms'; ?></div>
                     </div>
                     <!-- detail bedrooms end -->

                     <!-- detail bathrooms -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="21.429" height="18.75" viewBox="0 0 21.429 18.75">
                           <path id="Icon_awesome-bath" data-name="Icon awesome-bath" d="M20.424,11.625H3.348V5.6a1.339,1.339,0,0,1,2.47-.717A2.844,2.844,0,0,0,6.1,8.357a.5.5,0,0,0,.021.688l.474.474a.5.5,0,0,0,.71,0l3.977-3.977a.5.5,0,0,0,0-.71l-.474-.474a.5.5,0,0,0-.688-.021,2.84,2.84,0,0,0-2.686-.643,3.347,3.347,0,0,0-6.1,1.9v6.027H1a1,1,0,0,0-1,1v.67a1,1,0,0,0,1,1h.335v1.339a4.007,4.007,0,0,0,1.339,2.994V20a1,1,0,0,0,1,1h.67a1,1,0,0,0,1-1v-.335H16.071V20a1,1,0,0,0,1,1h.67a1,1,0,0,0,1-1V18.637a4.007,4.007,0,0,0,1.339-2.994V14.3h.335a1,1,0,0,0,1-1v-.67A1,1,0,0,0,20.424,11.625Z" transform="translate(0 -2.25)" fill="#fff"/>
                          </svg>

                        </div>
                        <div class="val flex-fill "><?= $prop->Bathrooms ?> <?php echo $prop->Bathrooms == 1 ? 'bathroom' : 'bathrooms'; ?></div>
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
                        <div class="val flex-fill "><?= number_format((int)$prop->Size->Ft).' ft&sup2;'; ?></div>
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
                        <div class="val flex-fill "><?= number_format((int)$prop->Size->Lot).' acres'; ?></div>
                     </div>
                     <?php } ?>
                     <!-- detail Lot size end -->


                     <?php if($prop->Size->Units > 0){ ?>
                      <!-- detail units size -->
                      <div class="prop-det d-flex">
                         <div class="icon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen-exit" viewBox="0 0 16 16">
                             <path d="M5.5 0a.5.5 0 0 1 .5.5v4A1.5 1.5 0 0 1 4.5 6h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5zm5 0a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 10 4.5v-4a.5.5 0 0 1 .5-.5zM0 10.5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 6 11.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zm10 1a1.5 1.5 0 0 1 1.5-1.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4z"/>
                            </svg>
                         </div>
                         <div class="val flex-fill "><?= number_format((int)$prop->Size->Units).' units'; ?></div>
                      </div>
                      <?php } ?>
                      <!-- detail units size end -->


                     <!-- detail property type -->
                     <div class="prop-det d-flex">
                        <div class="icon">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20.492" height="21.757" viewBox="0 0 20.492 21.757">
                            <path id="Icon_awesome-map-signs" data-name="Icon awesome-map-signs" d="M20.305,3.6,18.572,1.758a1.244,1.244,0,0,0-.906-.4H11.527V.68a.661.661,0,0,0-.64-.68H9.606a.661.661,0,0,0-.64.68v.68H2.241a.992.992,0,0,0-.961,1.02v3.4A.992.992,0,0,0,2.241,6.8H17.666a1.245,1.245,0,0,0,.906-.4l1.733-1.84A.71.71,0,0,0,20.305,3.6ZM8.966,21.077a.661.661,0,0,0,.64.68h1.281a.661.661,0,0,0,.64-.68V16.317H8.966ZM18.251,9.519H11.527V8.159H8.966v1.36H2.827a1.245,1.245,0,0,0-.906.4L.188,11.758a.71.71,0,0,0,0,.962l1.733,1.84a1.244,1.244,0,0,0,.906.4H18.251a.992.992,0,0,0,.961-1.02v-3.4A.992.992,0,0,0,18.251,9.519Z" transform="translate(0)" fill="#fff"/>
                          </svg>


                        </div>
                        <div class="val flex-fill "><?= $prop->PropTypeDisplay ?></div>
                     </div>
                     <!-- detail property type end -->




                  </div>

                  <p class="price">
                       <?php
                       if($prop->Type == 'budd'){
                          if(count($prop->Prices) > 1){ echo '<small>starting at</small>'; }
                          $i=0; foreach($prop->Prices as $pp){
                             if($i == 0){
                                echo '<small>$</small>'.number_format($pp['amt']).' <small>US</small>';
                                if($pp['name'] != ''){
                                   echo '<span class="pname">'.$pp['name'].'</span>';
                                }
                             } $i++;
                          }
                       }else{
                          echo '$'.number_format($prop->Prices[0]);
                       }
                       ?>
                   </p>
                  <button class="btn btn-show-call collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#call_form" aria-expanded="false" aria-controls="call_form">
                    Request Details
                  </button>


                   <!-- start request form -->
                   <div class="filterContent defaultTab sidebarWidget collapse" id="call_form">
                       <form method="post" action="#" id="info_form">
                           <div class="form-head">Interested in this property?</div>
                           <div class="form-txt">Request this property online or call us at <b><?= $phone_mia ?></b>.</div>
                           <div class="form-body">
                              <div class="form-group">
                                   <label>Full name <span>*</span></label>
                                   <input type="text" name="full_name" class="form-control" required>
                              </div>
                              <div class="form-group">
                                   <label>Phone number<span>*</span></label>
                                   <input type="text" name="phone" class="form-control" required>
                              </div>
                              <div class="form-group">
                                   <label>Email <span>*</span></label>
                                   <input type="email" name="email" class="form-control" required>
                              </div>

                              <div class="form-group">
                                   <label>Notes</label>
                                   <textarea name="message" class="form-control"></textarea>
                              </div>
                              <button type="submit" name="send_info_form" class="btn">Request information</button>
                           </div>
                       </form><!-- end form -->
                   </div><!-- end quick search widget -->




                <div class="prop-location">
                   <h4>Location</h4>
                   <p>The exact location is given upon information requests. Please send us your information for detailed information regarding this property.</p>
                   <p class="address"><i class="fa fa-map-marker"></i> <?= $prop->Location->Address.'<br />'.$prop->Location->CityName.', '.$prop->Location->StateName.'<br />Mexico '.$prop->Location->Zip ?></p>
                   <div id="map" class="mapSmall"></div>
                </div>


               </div>
               <!-- end sidebar -->

                <!-- start content -->
                <div class="col-lg-9 col-md-9 order-2 order-xs-1">
                   <div class="property-content">


                     <div class="prop-info mobile-show">
                       <h1><?= $prop->PropertyTitle ?></h1>
                       <div class="desc">
                           <p><?= $prop->PropertyDesc ?></p>
                           <?php if(isset($prop->PropertyDesc2)){ echo '<p>'.$prop->PropertyDesc2.'</p>'; } ?>
                       </div>
                     </div>

                     <!-- PHOTO GALLERY --->
                     <div class="overview" id="photo_gallery">
                       <?php if(!empty($prop->PhotosDisplay)){
                         foreach($prop->PhotosDisplay as $pp){ ?>
                           <a href="<?= $prop_img_url.$pp ?>" class="swipe-img"  rel="gallery1">
                             <img src="<?= $prop_img_url.$pp ?>" alt="<?= $prop->PropertyTitle ?>">
                           </a>
                         <?php } }else{ ?>
                         <p><em>There are no images available for this listing. Please contact us directly to set up a visit!</em></p>
                         <?php } ?>
                     </div>


                     <?php  if(!empty($prop->PhotosDisplay)){ ?>
                     <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                      <!--<ol class="carousel-indicators">
                        <?php $i=1;
                          foreach($prop->PhotosDisplay as $pp){ if($i==1){ $cl = ' class="active"'; }else{ $cl = ''; } ?>
                          <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $i ?>" <?= $cl ?>>
                            <img src="<?= $prop_img_url.$pp ?>" width="50">
                          </li>
                        <?php $i++; } ?>
                      </ol>-->
                      <div class="carousel-inner">
                        <?php
                          foreach($prop->PhotosDisplay as $pp){ ?>
                            <div class="carousel-item active">
                              <img src="<?= $prop_img_url.$pp ?>" class="d-block w-100" alt="<?= $prop->PropertyTitle ?>">
                            </div>
                          <?php } ?>
                      </div>
                      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                      </a>
                    </div>
                  <?php } ?>


                     <!-- PHOTO GALLERY END -->


                      <div class="prop-info mobile-hide">
                        <h1><?= $prop->PropertyTitle ?></h1>
                        <div class="desc">
                            <p><?= $prop->PropertyDesc ?></p>
                            <?php if(isset($prop->PropertyDesc2)){ echo '<p>'.$prop->PropertyDesc2.'</p>'; } ?>
                        </div>
                      </div>




                      <h4>PROPERTY DETAILS</h4>
                      <table class="amentitiesTable features">
                         <tbody>
                            <?php if($prop->IsFeatured == 1){ ?>
                            <tr class="featured"><td class="wi" colspan="2"><span><i class="fa fa-star"></i> Featured Listing</span></td></tr>
                            <?php } ?>
                            <tr><td class="wi">Zoning Type </td><td><span><?= $prop->PropTypeDisplay ?></span></td></tr>
                            <tr><td class="wi">Contract Type </td><td><span><?= $prop->DisplayStatus ?></span></td></tr>
                            <tr><td class="wi">Area </td><td><span><?= $prop->Location->County.' County'; ?></span></td></tr>
                            <?php if($prop->Size->Ft > 0){ ?>
                            <tr><td class="wi">Size </td><td><span><?= number_format($prop->Size->Ft) ?> sqft</span></td></tr>
                            <?php } ?>
                         </tbody>
                      </table>
                      <table class="amentitiesTable features">
                         <tbody>
                            <tr><td class="wi">Bedrooms </td><td><span><?= $prop->Bedrooms ?></span></td></tr>
                            <tr><td class="wi">Bathrooms </td><td><span><?= $prop->Bathrooms ?></span></td></tr>
                            <tr><td class="wi">Year Built </td><td><span><?= $prop->YearBuilt ?></span></td></tr>
                            <tr><td class="wi">MLS # </td><td><span><?= $prop->MLS ?></span></td></tr>
                         </tbody>
                      </table>

                      <div class="clearfix"></div>


                      <?php if(!empty($prop->Prices) && count($prop->Prices) > 1){ ?>
                      <div id="pricing_options">
                          <h4>COST OPTIONS</h4>
                          <div class="divider thin"></div>

                          <?php $j=1; foreach($prop->Prices as $pp){ ?>
                          <div class="row">
                              <div class="col-md-8">
                                  <h6><?= $pp['name'] ?></h6>
                                  <p><?= $pp['desc'] ?></p>
                              </div>
                              <div class="col-md-4">
                                  <span class="cost">$<?= number_format($pp['amt']) ?></span>
                                  <span class="type"><?= $listings->PricingTypes[$pp['type']] ?></span>
                              </div>
                          </div>
                          <?php if($j != count($prop->Prices)){ ?>
                          <div class="divider thin dashed"></div>
                          <?php }  $j++; } ?>
                      </div>
                      <?php } ?>

                      <?php if(!empty($prop->RoomInfo)){ ?>
                      <h4>PROPERTY ROOMS</h4>
                      <div class="divider thin"></div>
                      <div class="row">
                          <div class="col-md-6">
                              <table class="amentitiesTable">
                                  <?php $i=0; foreach($prop->RoomInfo as $ii=>$vv){ if($i == (count($prop->RoomInfo) / 2)){ echo '</table></div><div class="col-md-6"><table class="amentitiesTable">'; } ?>
                                  <tr>
                                      <td class="wi"><?= $vv[0] ?></td>
                                      <td><?= $vv[1] ?></td>
                                  </tr>
                                  <?php $i++; } ?>
                              </table>
                          </div>
                      </div><br><br>
                      <?php } ?>

                      <?php if(!empty($prop->Features)){ ?>
                      <h4>PROPERTY FEATURES</h4>

                      <table class="amentitiesTable features">
                         <tbody>


                          <?php if($prop->Type == 'budd'){
                             $feature_cnt = count($prop->Features);
                             $cnt = 0;
                             foreach($prop->Features as $ii=>$vv){ if($cnt == ceil($feature_cnt/ 2)){ echo '</tbody></table><table class="amentitiesTable features"><tbody>'; } ?>
                          <tr>
                              <td class="wi"><?= $ii ?></td>
                              <td><?= $vv ?></td>
                          </tr>
                          <?php $cnt++; } }else{ foreach($prop->Features as $ii=>$vv){ ?>
                          <tr>
                              <td class="wi"><?= $ii ?></td>
                              <td><?php foreach($vv as $v){ ?>
                              <table class="inner-table">
                                  <tr>
                                      <td><?= $v[0] ?></td>
                                      <td class="smm"><?= $v[1] ?></td>
                                  </tr>
                              </table>
                              <?php } ?></td>
                          </tr>
                          <?php }} ?>
                          </tbody>
                      </table>
                      <div class="clearfix"></div>
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
    <script src="dist/plugins/Justified.js/jquery.justifiedGallery.min.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.js"></script>
    <script src="dist/plugins/swipebox/src/js/jquery.swipebox.js"></script>
    <script src="dist/js/listing.js"></script>



  </body>
  </html>
