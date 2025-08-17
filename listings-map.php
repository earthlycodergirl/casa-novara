<?php
$show_listings = 1;
$build_search = 1;
$no_results = 0;
$get = 'listings';
$ppage = 2;
$show_dest = 0;
$dest_class = 'bg-listings';

require('base.php');

$site = new Site();

$adv_search = new AdvSearch();
$adv_search->getLocations();
$adv_search->getMinMax();

$listing_cities = $listings->getLocations2();

if(isset($_GET['dsearch'])){
  $_GET['property_type'] = $_GET['beds'] = $_GET['baths'] = 0;
  $_GET['page'] = 1;
  $_GET['search_type'] = 'basic';
  if($_GET['dsearch'] == 'for-sale'){
    $_GET['list_type'] = 3;
  }elseif($_GET['dsearch'] == 'rentals'){
    $_GET['list_type'] = 2;
  }elseif($_GET['dsearch'] == 'for-lease'){
    $_GET['list_type'] = 1;
  }else{

  }
  $_GET['list_type'] = 0;
}

if(!isset($_GET['page']) && isset($_GET['search_type'])){
  $_GET['page'] = 1;
}

$_GET['map'] = 1;

// get variables for map
if(isset($_GET['page'])){
    $i = 0;

    $search = $listings->SearchParams;
    foreach($_GET as $kk=>$vv){
      if($kk != 'map'){
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
    }
    $listings = new SiteListings($_GET);
}else{
    $listings = new SiteListings(0);
}
//print_me($listings);
if(isset($listings->SearchParams->Cities[0])){
  $city_id = $listings->SearchParams->Cities[0];
  $adv_search->getTowns($city_id);
}else{
  $city_id = 0;
}

//print_me($_GET);
//print_me($property_types);
//print_me($listings->SearchParams);

$withLat = array();
$mapCnt = 0;

if(!empty($listings->List[0])){
    foreach($listings->List[0] as $list){
        if($list->PropLocation->Latitude > 0){
            $withLat[] = $list->PropId;
            $mapCnt++;
        }

    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <base href="<?= $base_href ?>">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title><?= $meta['map']['title'].utf8_encode($listings->SearchParams->CityName) ?>, Mexico</title>
    <meta name="robots" content="index" />
    <link rel="canonical" href="https://kiinrealty.com/<?= $link_map[$lang].$listings->SearchParams->City ?>">


    <meta property="og:title" content="<?= $meta['map']['title'].utf8_encode($listings->SearchParams->CityName) ?>, Mexico">
    <meta property="og:description" content="<?php echo str_replace('[CITY]',utf8_encode($listings->SearchParams->CityName),$meta['map']['title']) ?>, Mexico">
    <meta property="og:image" content="https://kiinrealty.com/dist/img/social.jpg">
    <meta property="og:url" content="https://kiinrealty.com/<?= $link_map[$lang].$listings->SearchParams->City ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="Kiin Realty">
    <meta name="twitter:image:alt" content="<?= $meta['map']['title'].utf8_encode($listings->SearchParams->CityName) ?>, Mexico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link href="dist/plugins/responsive/responsive.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/responsive/yamm.css" rel="stylesheet" type="text/css" media="all" />
    <?php if($mobile_class == 'is_mobile'){ ?>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
    <?php } ?>
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />



    <?php include 'dist/inc/favicon.php'; ?>

  </head>
  <body class="<?= $mobile_class ?>">

    <?php require('dist/inc/nav_top.php') ?>

    <div class="inner-head maps-list">
      <?php require('dist/inc/nav.php') ?>


    </div>

    <section class="properties map-properties">
        <div class="container-fluid pe-0">



            <div class="row">
              <div class="col-lg-4 col-md-5" id="listings">


                <div class="row mobile-filters">
                   <div class="col-md-2 col-3 d-inline-block">
                      <button type="button" class="listing-btn" id="show_adv">
                         <i class="bi bi-funnel"></i>
                      </button>
                   </div>
                   <div class="col-md-10 col-8 float-end text-end d-inline-block">

                     <select id="order_li" class="select mobile-hide">
                       <option value="featured" <?php if($listings->SearchParams->OrderBy == 'featured'){ echo 'selected'; } ?>><?= $lan['map']['filter'][0] ?></option>
                       <option value="price_high" <?php if($listings->SearchParams->OrderBy == 'price_high'){ echo 'selected'; } ?>><?= $lan['map']['filter'][1] ?></option>
                       <option value="price_low" <?php if($listings->SearchParams->OrderBy == 'price_low'){ echo 'selected'; } ?>><?= $lan['map']['filter'][2] ?></option>
                       <option value="size_high" <?php if($listings->SearchParams->OrderBy == 'size_high'){ echo 'selected'; } ?>><?= $lan['map']['filter'][3] ?></option>
                       <option value="size_low" <?php if($listings->SearchParams->OrderBy == 'size_low'){ echo 'selected'; } ?>><?= $lan['map']['filter'][4] ?></option>
                       <option value="alpha" <?php if($listings->SearchParams->OrderBy == 'alpha'){ echo 'selected'; } ?>><?= $lan['map']['filter'][5] ?></option>
                       <option value="alpha_desc" <?php if($listings->SearchParams->OrderBy == 'alpha_desc'){ echo 'selected'; } ?>><?= $lan['map']['filter'][6] ?></option>
                     </select>

                     <a href="<?= $link_properties[$lang].str_replace('listings','',$get) ?>" class="btn btn-link d-inline-block fake-link">
                        <i class="bi bi-list"></i> <?= $lan['map']['show_list'] ?>
                     </a>

                     <button class="btn btn-light mobile-show d-inline-block close-list">
                        <i class="bi bi-x"></i>
                     </button>

                   </div>
                   <div class="clearfix">

                   </div>
                </div>

                <div class="filterContent defaultTab sidebarWidget" id="side_filters">
                  <?php include 'dist/inc/forms/search_sidebar.php'; ?>
                </div>

                <div class="row listings-wrap">
                  <div class="owl-carousel">
                 <?php if(!empty($listings->List[0])){
                   $lat = $lon = '';
                   $coor_cnt = 0;
                   foreach($listings->List[0] as $ll=>$ff){

                     if($ff->PropLocation->Latitude != '' && $ff->PropLocation->Longitude != ''){
                       if($coor_cnt == 0){
                         $lat = $ff->PropLocation->Latitude;
                         $lon = $ff->PropLocation->Longitude;
                       }
                       ?>

                 <!-- LISTING DIV -->



                  <div class="col-md-6 item">
                      <div class="propertyItem shadow-sm" data-lat="<?= $ff->PropLocation->Latitude ?>" data-lng="<?= $ff->PropLocation->Longitude ?>" data-price="<?= $ff->PropCosts ?>" data-id="<?= $ff->PropId ?>" data-details-url="<?= $site_url.'/'.$link_property[$lang].$ff->PropId ?>" itemscope="" itemtype="http://schema.org/Product" itemid="mls#:<?= $ff->MLS ?>" id="prop<?= $ff->PropId ?>">

                          <link itemprop="additionalType" href="http://www.productontology.org/id/Real_estate">
                          <meta itemprop="url" content="<?= $site_url.'/'.$link_property[$lang].$ff->PropId ?>">
                          <div class="propertyContent">
                            <span class="pdesc" style="display: none;"><?= substr($ff->PropDesc,0,250).'...' ?></span>
                            <div onclick="location.href='<?= $link_property[$lang].$ff->PropId ?>'" class="propertyImgLink" style="--background: url('<?= $prop_img_url.$ff->PropThumb ?>')" data-acmw="USE-THUMB">
                              <span class="map-prop-img" data-val="<?= $prop_img_url.$ff->PropThumb ?>"></span>
                            </div>
                               <div class="prop-map-item">
                                 <a href="<?= $link_property[$lang].$ff->PropId ?>" class="hover-title mobile-hide">
                                   <h4 itemprop="name" class="map-prop-title mp-title"><?= $ff->PropTitle ?></h4>
                                </a>
                                <a href="<?= $link_property[$lang].$ff->PropId ?>" class="hover-title mobile-show">
                                  <h4 itemprop="name" class="map-prop-title"><?= (strlen($ff->PropTitle) > 50) ? substr($ff->PropTitle,0,50).'...' : $ff->PropTitle ?></h4>
                               </a>
                                 <table border="1" class="propertyDetails mobile-hide">
                                   <tr>
                                     <?php if($ff->PropSize->SqFt != 0){ $sqft = number_format((int)$ff->PropSize->SqFt).' ft&sup2;'; echo '<td class="map-prop-sqft">'.$sqft.'</td>'; } ?>


                                     <?php if($ff->PropType == 20){ ?>
                                        <td class="map-prop-beds"><?= $ff->PropSize->Bedrooms.' '.$lan['map']['beds'] ?></td>
                                        <td class="map-prop-baths"><?= $ff->PropSize->Bathrooms.' '.$lan['map']['baths'] ?></td>
                                     <?php } ?>
                                   </tr>
                                 </table>
                                 <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                    <p class="price" itemprop="price">$<?= number_format($ff->PropCosts) ?> <small>USD</small></p>
                                    <meta itemprop="priceCurrency" content="USD">
                                     <meta itemprop="category" content="Real Estate: Homes for Sale">
                                     <span itemprop="seller" itemscope="" itemtype="http://schema.org/RealEstateAgent"><meta itemprop="name" content="MIA Real Estate">
                                     </span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>


                  <!-- END OF LISTING DIV -->
                <?php $coor_cnt++; }} ?>
              </div>
                  <span class="coordinates" data-lon="<?= $lon ?>" data-lat="<?= $lat ?>"></span></div>
                <?php }elseif(empty($listings->List[1])){ $no_results = 1; ?>
                  <div class="no-results">
                    <div class="row">

                      <div class="col-sm-9">
                        <h3><?= $lan['map']['nores_h'] ?></h3>
                        <p><?= $lan['map']['nores_t'] ?></p>
                      </div>
                      <div class="col-sm-3">
                        <div class="shrug">
                          ¯\_(ツ)_/¯
                        </div>
                      </div>
                    </div>

                  </div>
                <?php } ?>

              </div>

              <div class="col-lg-8 col-md-7 ps-0">
                <div id="list_map"></div>
              </div>

              </div>
          </div>
          <!-- end container -->
      </section>
      <!-- end main content -->


      <!-- Loading modal -->
      <div class="modal modal-center fade" id="loading_modal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-mini">
          <div class="modal-content">

            <div class="modal-body text-center">
              <img src="dist/img/loading.gif" class="img-fluid align-center" alt="loading">
              <br>
              <p><?= $lan['map']['refresh'] ?></p>
            </div>

          </div>
        </div>
      </div>



      <!-- modal for mobile filters -->
      <div class="modal fade" id="mobile_filters"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="mobile_filtersLabel">Filter Results</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Update Results</button>
            </div>
          </div>
        </div>
     </div>



     <!-- modal for advanced search -->
     <div class="modal fade" id="advanced_search"  tabindex="-1" aria-labelledby="advanced_searchLabel" aria-hidden="true">
       <div class="modal-dialog modal-fullscreen">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="advanced_searchLabel"><?= $lan['map']['adv_h'] ?></h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
              <p><?= $lan['map']['adv_t'] ?></p>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $lan['map']['adv_close'] ?></button>
             <!--<button type="button" class="btn btn-primary">Update Results</button>-->
           </div>
         </div>
       </div>
    </div>



      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
      <script src="dist/plugins/responsive/respond.js"></script>
      <script src="dist/plugins/jquery.mask/jquery.mask.min.js"></script>
      <script src="dist/plugins/increment/input.increment.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/lozad.js/1.16.0/lozad.min.js" integrity="sha512-21jyjW5+RJGAZ563i/Ug7e0AUkY7QiZ53LA4DWE5eNu5hvjW6KUf9LqquJ/ziLKWhecyvvojG7StycLj7bT39Q==" crossorigin="anonymous"></script>
      <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCpkmiSbSbOLeGs8PNRWoKmw76i_A1y_d8"></script><!-- google maps -->
      <script src="dist/js/markerclusterer.js"></script>
      <?php if($mobile_class == 'is_mobile'){ ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
      <?php } ?>
      <script src="dist/js/list-map.js"></script> <!-- map script -->
      <script src="dist/js/list-search.js"></script>
      <script>
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();
      });
      </script>


    </body>
    </html>
