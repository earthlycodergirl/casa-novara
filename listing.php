<?php
session_start();
$listing = 1;
$ppage = 2;
$curr = 1;
$curr_desc = 'USD';

require('base.php');

$current_link['en'] = $link_property['en'].$_GET['prop_id'];
$current_link['es'] = $link_property['es'].$_GET['prop_id'];

$site = new Site();
$list = new Listings();
if(isset($_GET['prop_id']) && $_GET['prop_id'] > 0){
    if(isset($_GET['type'])){
        if($_GET['type'] == 'budd'){
            $prop = new Listing($_GET['prop_id']);

            if(isset($prop->PropertyId) && $prop->PropertyId > 0){


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
            }else{
                header('location: /'.$base_href);
                ?>
                <script>
                    window.location.href = "<?= $base_href ?>";
                </script>
                <?php
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

$fees = array();
// Calculate pricing to be displayed
if(!empty($prop->Prices)){
  foreach($prop->Prices as $pp){
    // if($pp['type'] == 1 || $pp['type'] == 2){
    //   $price = $pp['amt'];
    //   $pname = $pp['name'];
    // }

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
  $price = $fees[1]['amt'];
  $pname = $fees[1]['name'];
  $show_price = number_format($price);
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
    <meta property="og:url" content="https://kiinrealty.com/<?= $link_property[$lang].$prop->PropertyId ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="Kiin Realty">
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
        <div class="row mobile-hide">
          <div class="col-sm-8">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
              <ol class="breadcrumb">
                <li class="breadcrumb-item mobile-hide" onclick="goBack()"><?= $prop->Location->CityName ?> <?= $lan['prop']['prop'] ?></li>
                <li class="breadcrumb-item fake-link"><a href="<?= $link_properties[$lang] ?>?location=<?= $prop->Location->City ?>&dsearch=all&baths=0&beds=0&property_type=0&search_type=basic&list_type=0"><?= $lan['prop']['sres']?></a></li>
                <li class="breadcrumb-item active"  aria-current="page"><?= $lan['prop']['prop'] ?></li>
              </ol>
            </nav>
          </div>

          <div class="col-4 text-end position-relative ">
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
          <div class="row mobile-price">
            <div class="col-6">
              <a class="search-res" href="<?= $link_properties[$lang] ?>?location=<?= $prop->Location->City ?>&dsearch=all&baths=0&beds=0&property_type=0&search_type=basic&list_type=0">
                <i class="bi bi-arrow-left"></i> <?= $lan['prop']['sres']?>
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
            <div class="row">


               <!-- start sidebar -->
               <div class="col-lg-3 col-md-3 order-1 order-xs-2">
                  <!--<div class="prop-type">
                    <?= isset($prop->Features['Property Style']) ? $prop->Features['Property Style'] : $prop->DisplayStatus; ?>
                 </div>-->

                  <div class="prop-dets mobile-hide">

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

                    <?php if($listings->ZoningTypes[$prop->ZoningId][$lang] != ''){ ?>
                    <!-- detail zone zone -->
                    <div class="prop-det d-flex">
                       <div class="icon">
                        <i class="bi bi-geo"></i>
                       </div>
                       <div class="val flex-fill "><?= $listings->ZoningTypes[$prop->ZoningId][$lang] ?></div>
                    </div>
                    <!-- detail zone zone end -->
                    <?php } ?>

                    <?php if(isset($prop->PropTypeSubDisplay) && $prop->PropTypeSubDisplay != ''){ ?>
                      <!-- detail zone zone -->
                      <div class="prop-det d-flex">
                         <div class="icon">
                          <i class="bi bi-door-open"></i>
                         </div>
                         <div class="val flex-fill "><?= $prop->PropTypeSubDisplay ?></div>
                      </div>
                      <!-- detail zone zone end -->
                    <?php }
                    if(!$is_mobile){
                      require 'dist/inc/bedrooms.php';
                    }
                    ?>


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
                   <?php if($prop->Location->Address == ''){ echo '<p>'.$lan['prop']['loc_desc'].'</p>'; } ?>
                   <p class="address"><i class="bi bi-geo-alt"></i> <?php if($prop->Location->Address != ''){ echo $prop->Location->Address.'<br />'; } echo $prop->Location->CityName.', '.$prop->Location->StateName.'<br />Mexico '.$prop->Location->Zip ?></p>

                   <div id="map" class="mapSmall"></div>
                </div>


               </div>
               <!-- end sidebar -->

                <!-- start content -->
                <div class="col-lg-9 col-md-9 order-2 order-xs-1">
                   <div class="property-content">

                     <!-- PHOTO GALLERY --->
                     <?php if(!empty($prop->PhotosDisplay)){ ?>
                       <div id="image_holder">
                         <div id="property-d-1" class="owl-carousel">
                           <?php foreach($prop->PhotosDisplay as $pp){  ?>
                               <div class="item"><img src="<?= $prop_img_url.$pp ?>" alt="image"/></div>
                             <?php } ?>
                         </div>
                         <div id="property-d-1-2" class="owl-carousel">
                           <?php foreach($prop->Photos as $pp){  ?>
                               <div class="item"><img src="<?= $prop_img_url.$pp[0].'/thumbs/'.$pp[1] ?>" alt="image"/></div>
                             <?php } ?>
                         </div>
                       </div>
                     <?php } ?>

                  <?php if($prop->VirtualTour != ''){
                    echo '<a href="'.$prop->VirtualTour.'" target="_blank" rel="nofollow" class="vtour"><img src="dist/img/icons/360.png" alt="virtual tour" /> See the virtual tour of this property</a>';
                  } ?>


                     <!-- PHOTO GALLERY END -->


                      <div class="prop-info">
                        <h3 class="mobile-show m-block"><?= $prop->Location->CityName.', '.$prop->Location->StateName.' '.$prop->Location->Zip ?></h3>
                        <h1><?=  ($lang == 'es') ? $prop->PropertyTitleEs : $prop->PropertyTitle ?></h1>
                        <?php
                        if($is_mobile){
                          require 'dist/inc/bedrooms_mobile.php';
                        }
                        ?>
                        <div class="desc">
                            <p><?=  ($lang == 'es') ? nl2br($prop->PropertyDescEs) : nl2br($prop->PropertyDesc) ?></p>
                            <?php if(isset($prop->PropertyDesc2)){ echo '<p>'.$prop->PropertyDesc2.'</p>'; } ?>
                        </div>
                        <div class="mobile-show m-block toggle-desc">
                          <span class="show"><?= $lan['prop']['toggle'] ?></span>
                          <span class="hide"><?= $lan['prop']['toggle_hide'] ?></span>
                        </div>
                      </div>


                      <h4 class="toggle-tb" data-target="#prop_fe_tb">
                        <?= $lan['prop']['h4'] ?>
                        <i class="bi bi-chevron-down show"></i>
                        <i class="bi bi-chevron-up hide"></i>
                      </h4>
                      <div id="prop_fe_tb" class="table-tog">
                      <table class="amentitiesTable features">
                         <tbody>
                            <?php if($prop->PropertyTypeId == 20 || strtolower($prop->PropTypeDisplay) == 'residential'){
                              if($prop->RoomType != 'bed'){ ?>
                                <tr class="mobile-hide"><td class="wi">Room Type </td><td><span><?= ucfirst($prop->RoomType) ?></span></td></tr>
                              <?php }elseif($prop->Bedrooms >= 1){ ?>
                                <tr class="mobile-hide"><td class="wi"><?= ucfirst($lan['prop']['beds'][0]) ?> </td><td><span><?= $prop->Bedrooms ?></span></td></tr>
                              <?php } ?>
                              <tr class="mobile-hide"><td class="wi"><?= ucfirst($lan['prop']['baths'][0]) ?> </td><td><span><?= $prop->TotalBaths ?></span></td></tr>
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

                               if($prop->ReleaseNotes != ''){ ?>
                                 <tr>
                                   <td class="wi"><?= $lan['prop']['avail'] ?> </td>
                                   <td>
                                     <span><?= $prop->ReleaseNotes ?></span>
                                   </td>
                                 </tr>
                              <?php }else{

                               if($prop->YearBuilt > 0 && $prop->YearBuilt != '' && $prop->PropertyTypeId != 21){ ?>
                                <tr>
                                  <td class="wi"><?= $dtxt ?> </td>
                                  <td>
                                  <?php if($prop->MonthBuilt > 0 && $prop->MonthBuilt != ''){ ?>
                                    <span><?= $lan['months'][$prop->MonthBuilt] ?></span>
                                  <?php } ?><span><?= $prop->YearBuilt ?></span>
                                  </td>
                                </tr>
                              <?php } } if($hide_dev == 0){ ?>
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
                              <?php if($prop->Location->AreaName != ''){ echo $prop->Location->AreaName.', ';} echo $prop->Location->CountyName; ?></span></td>
                            </tr>
                            <?php if($prop->Size->Ft > 0 || $prop->Size->Mt > 0){ ?>
                            <tr><td class="wi"><?= $lan['prop']['size'] ?> </td><td>
                              <?php if($prop->Size->Ft > 0){ ?>
                                <span><?= number_format($prop->Size->Ft) ?> ft&sup2;</span>
                              <?php } if($prop->Size->Mt > 0){ ?>
                                / <span><?= number_format($prop->Size->Mt) ?> mt&sup2;</span>
                              <?php } ?>
                            </td></tr>
                            <?php } ?>

                            <?php if($prop->Size->Lot > 0){ ?>
                             <!-- detail Lot size -->
                             <tr><td class="wi"><?= ucfirst($lan['prop']['left_lot']) ?> </td><td>
                                <span><?= number_format($prop->Size->Lot) ?> ft&sup2;</span>
                               <?php if($prop->Size->LotMt > 0){ ?>
                                 / <span><?= number_format($prop->Size->LotMt) ?> mt&sup2;</span>
                               <?php } ?>
                               </td>
                             </tr>

                             <?php } ?>
                         </tbody>
                      </table>
                    </div>

                      <div class="clearfix"></div>




                      <!-- Property Features -->
                      <?php
                      if($lang == 'es'){
                        $prop->Features = $prop->FeaturesEs;
                      }
                      if(!empty($prop->Features)){ ?>
                        <h4 class="toggle-tb" data-target="#prop_fe_tb2">
                          <?= $lan['prop']['features'] ?>
                          <i class="bi bi-chevron-down show"></i>
                          <i class="bi bi-chevron-up hide"></i>
                        </h4>
                        <div id="prop_fe_tb2" class="table-tog">
                          <table class="amentitiesTable features">
                         <tbody>


                          <?php
                             $feature_cnt = count($prop->Features);
                             $cnt = 0;
                             foreach($prop->Features as $ii=>$vv){ if($cnt == ceil(($feature_cnt+1) / 2)){ echo '</tbody></table><table class="amentitiesTable features"><tbody>'; }

                             $val = '';
                             if(strtolower(trim($vv)) == "yes"){
                               $val = '<i class="bi bi-check text-success"></i>';
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
                    </div>
                      <div class="clearfix"></div>
                      <?php } ?>


                      <!-- Pricing and fee details -->
                      <?php if(!empty($prop->Prices) && count($prop->Prices) > 1){ ?>
                      <div id="pricing_options">
                        <h4 class="toggle-tb" data-target="#prop_fe_tb3">
                          <?= $lan['prop']['h42'] ?>
                          <i class="bi bi-chevron-down show"></i>
                          <i class="bi bi-chevron-up hide"></i>
                        </h4>
                        <div id="prop_fe_tb3" class="table-tog">
                          <table class="table pricing-tb">
                          <?php $j=1;
                          //print_me($prop->Prices);
                          foreach($fees as $kk=>$pp){ if($kk != 1){ ?>
                            <tr>
                              <td class="wi"><?= $price_types[$pp['type']][$lang] ?><span class="type"> <?= $pp['name'] ?></span></td>
                              <td><span class="cost">$<?= number_format($pp['amt']).' <small>'.$curr_desc.'</small>'; ?></span>
                              </td>
                            </tr>
                          <?php if($j != count($prop->Prices)){ ?>
                          <div class="divider thin dashed"></div>
                        <?php }  $j++; } } ?>
                        </table>
                      </div>

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
