<?php
session_start();
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

// create the spanish version of the website
$current_li = basename($_SERVER['REQUEST_URI']);
$current_li2 = basename($_SERVER['QUERY_STRING']);
//print_r($current_li);
//print_r($current_li2);
$get_url = explode('?',$current_li);
if(isset($get_url[1])){
$current_link['en'] = $link_properties['en'].'?'.$get_url[1];
$current_link['es'] = $link_properties['es'].'?'.$get_url[1];
}



$site = new Site();

$adv_search = new AdvSearch();
$adv_search->getLocations();
$adv_search->getMinMax();

$listing_cities = $listings->getLocations2();

if(isset($_GET['dsearch'])){
  $_GET['property_type'] = $_GET['beds'] = $_GET['baths'] = 0;
  //$_GET['page'] = 1;
  if(!isset($_GET['page'])){
    $_GET['page'] = 1;
  }
  $_GET['search_type'] = 'basic';
  if($_GET['dsearch'] == 'for-sale'){
    $_GET['list_type'] = 3;
  }elseif($_GET['dsearch'] == 'rentals'){
    $_GET['list_type'] = 2;
  }elseif($_GET['dsearch'] == 'for-lease'){
    $_GET['list_type'] = 1;
  }else{
    $_GET['list_type'] = 0;
  }

}

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

// get destinations
if(isset($_GET['location']) && $_GET['location'] > 0){
  require_once('dist/inc/locations.php');
}

// get variables for map
if(isset($_GET['page'])){
    $i = 0;

    $search = $listings->SearchParams;
    foreach($_GET as $kk=>$vv){
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
    $listings = new SiteListings($_GET);
}else{
    $listings = new SiteListings(0);
}


if(count($listings->SearchParams->Counties) > 0 && (count($listings->SearchParams->Cities) == 0 || $listings->SearchParams->Cities[0] == 0)){
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


//print_me($listings);
if(isset($listings->SearchParams->Cities[0])){
  $city_id = $listings->SearchParams->Cities[0];
  //echo count($listings->SearchParams->Cities);
  if(count($listings->SearchParams->Cities) > 1){
    $adv_search->getTowns($listings->SearchParams->Cities);
  }else{
    $adv_search->getTowns($city_id);
  }
  $city_url = $listings->SearchParams->CityName;
}else{
  $city_id = 0;
  $city_url = '';
}

//print_me($_REQUEST);
//print_me($adv_search);
//print_me($listings->SearchParams);
// print_me($listings->List);
?>

<!doctype html>
<html lang="en">
  <head>
    <base href="<?= $base_href ?>">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $meta['props']['title'].utf8_encode($listings->SearchParams->CityName) ?>, Mexico</title>
    <meta name="robots" content="index" />
    <link rel="canonical" href="https://kiinrealty.com/<?= $city_url ?>">


    <meta property="og:title" content="<?= $meta['props']['title'].utf8_encode($listings->SearchParams->CityName) ?>, Mexico">
    <meta property="og:description" content="<?php echo str_replace('[CITY]',utf8_encode($listings->SearchParams->CityName),$meta['props']['title']) ?>, Mexico">
    <meta property="og:image" content="https://kiinrealty.com/dist/img/social.jpg">
    <meta property="og:url" content="https://kiinrealty.com/<?= $city_url ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="Kiin Realty">
    <meta name="twitter:image:alt" content="<?= $meta['props']['title'].utf8_encode($listings->SearchParams->CityName) ?>, Mexico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link href="dist/plugins/responsive/responsive.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/responsive/yamm.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />


    <?php include 'dist/inc/favicon.php'; ?>

  </head>
  <body class="<?= $mobile_class ?>">

    <?php require('dist/inc/nav_top.php') ?>

    <div class="inner-head <?= $dest_class ?>">
      <?php require('dist/inc/nav.php') ?>
      <div class="container mobile-hide">
        <div class="row">
          <div class="col">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $link_home[$lang] ?>"><?= $lan['props']['home'] ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $lan['props']['listin'].utf8_encode($listings->SearchParams->CityName) ?></li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

    </div>


    <?php require_once('dist/inc/forms/search_update.php'); ?>


    <!-- start main content -->
    <section class="properties">
        <div class="container">
            <div class="row">
               <div class="col-lg-3 col-md-3">
                   <!-- start quick search widget -->
                   <div class="filterContent defaultTab sidebarWidget" id="side_filters">
                       <?php include 'dist/inc/forms/search_sidebar.php'; ?>
                       <!-- end form -->
                   </div>
                   <!-- end quick search widget -->
               </div>
                <div class="col-lg-9 col-md-9" id="listings">
                   <div class="row">
                      <div class="col-md-5 col-3">
                        <h1 id="propertyTitle">
                             <button type="button" class="listing-btn" id="show_adv">
                                <i class="bi bi-funnel"></i>
                             </button>
                           <span class="mobile-hide"><?= $listings->Pagination->TotalResults.$lan['props']['rest'][2] ?>
                             <?php if($city_id > 0){
                             echo $lan['props']['rest'][0].utf8_encode($listings->SearchParams->CityName);
                           }else{
                             echo $lan['props']['rest'][1];
                           }
                           ?></span></h1>
                      </div>
                      <div class="col-md-7 col-9 text-end">
                        <span class="order-list mobile-hide"><?= $lan['props']['btns'][0] ?></span>
                        <select id="order_li" class="select">
                          <option value="featured" <?php if($listings->SearchParams->OrderBy == 'featured'){ echo 'selected'; } ?>><?= $lan['map']['filter'][0] ?></option>
                          <option value="price_high" <?php if($listings->SearchParams->OrderBy == 'price_high'){ echo 'selected'; } ?>><?= $lan['map']['filter'][1] ?></option>
                          <option value="price_low" <?php if($listings->SearchParams->OrderBy == 'price_low'){ echo 'selected'; } ?>><?= $lan['map']['filter'][2] ?></option>
                          <option value="size_high" <?php if($listings->SearchParams->OrderBy == 'size_high'){ echo 'selected'; } ?>><?= $lan['map']['filter'][3] ?></option>
                          <option value="size_low" <?php if($listings->SearchParams->OrderBy == 'size_low'){ echo 'selected'; } ?>><?= $lan['map']['filter'][4] ?></option>

                        </select>

                        <span class="view-as mobile-hide"><?= $lan['props']['btns'][1] ?></span>

                        <a href="javascript:void(0)" class="listing-btn active">
                           <i class="bi bi-list"></i>
                        </a>

                        <a href="<?= $link_map[$lang].str_replace('listings','',$get) ?>" class="listing-btn">
                           <i class="bi bi-geo-alt"></i>
                        </a>

                      </div>
                   </div>



                  <hr class="mb-0 mb-sm-3" />
                  <div class="mobile-show">
                    <div class="fs-6 fw-italic text-muted lato my-2"><?= $listings->Pagination->TotalResults.$lan['props']['found'] ?></div>
                  </div>
                   <?php if(!empty($listings->List[0])){ foreach($listings->List[0] as $ll=>$ff){
                     // echo $curr_desc;
                     // echo $curr.'<br />';
                     // echo $ff->PropCostsMXN;
                     if($curr_desc == 'MXN' && ($ff->PropCostsMXN + 0) > 0){
                       $ff->PropCosts = $ff->PropCostsMXN;
                     }elseif($curr_desc == 'MXN'){
                       $ff->PropCosts = $ff->PropCosts * $curr;
                     } ?>

                   <!-- LISTING DIV -->

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="propertyItem" data-lat="<?= $ff->PropLocation->Latitude ?>" data-lng="<?= $ff->PropLocation->Longitude ?>" data-price="<?= $ff->PropCosts ?>" data-details-url="<?= $site_url.$link_property[$lang].$ff->PropId ?>" itemscope="" itemtype="http://schema.org/Product" itemid="mls#:<?= $ff->MLS ?>">
                                <link itemprop="additionalType" href="http://www.productontology.org/id/Real_estate">
                                <meta itemprop="url" content="<?= $site_url.$link_property[$lang].$ff->PropId ?>">
                                <div class="propertyContent row">
                                    <div class="col-md-4 col-sm-4 position-relative">
                                      <?php if($ff->DisplayStatus != ''){ ?>
                                        <div class="display-status">
                                          <?= $ff->DisplayStatus ?>
                                        </div>
                                      <?php } ?>
                                        <div onclick="location.href='<?= $link_property[$lang].$ff->PropId ?>'" class="propertyImgLink lozad" data-background-image="<?= $prop_img_url.$ff->PropThumb ?>" data-acmw="USE-THUMB">
                                           <?php if($ff->IsFeatured == 1){ ?>
                                           <span class="featured" data-bs-toggle="tooltip" title="Featured property">
                                              <i class="bi bi-star"></i>
                                           </span>
                                         <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 rowText">
                                       <div class="row">
                                           <div class="col-md-12" itemscope="" itemtype="http://schema.org/PostalAddress">

                                               <a href="<?= $link_property[$lang].$ff->PropId ?>" class="hover-title">
                                                   <h4 itemprop="name"><?= $ff->PropTitle ?></h4>
                                                </a>
                                                <p class="location" itemprop="streetAddress" content="<?= $ff->PropLocation->Address ?>">
                                                   <i class="bi bi-geo-alt"></i>
                                                   <?php $show_city = 1; if($ff->PropLocation->Area != ''){ $show_city = 0; ?>
                                                   <span itemprop="addressLocality"><?= $ff->PropLocation->Area ?></span>,
                                                   <?php } ?>
                                                   <span itemprop="addressRegion"><?= $ff->PropLocation->County ?></span>
                                                   <?php if($show_city == 1){ ?>
                                                    ,  <span itemprop="addressCity"><?= $ff->PropLocation->City ?></span>
                                                  <?php } ?>
                                                   <span itemprop="postalCode"><?= $ff->PropLocation->Zip ?></span>
                                                   <span class="mls_num text-right">MLS #: <span itemprop="productID"><?= $ff->MLS ?></span></span>
                                                </p>
                                           </div>
                                       </div>

                                       <p class="desc mobile-hide" itemprop="description">
                                          <?= substr(htmlentities($ff->PropDesc),0,250).'...' ?>
                                          <a class="read-more" href="<?= $ff->PropId ?>"><?= $lan['props']['more'] ?></a>
                                       </p>

                                       <table border="1" class="propertyDetails">
                                           <tr>
                                               <?php if($ff->PropSize->SqFt > 0){
                                               echo '<td>'.number_format((int)$ff->PropSize->SqFt).' ft&sup2;'.'</td>'; } if($ff->PropType == 20){
                                                 if($ff->PropSize->Studio == 1){ ?>
                                                   <td>Studio</td>
                                                <?php }elseif($ff->PropSize->Loft == 1){ ?>
                                                   <td>Loft</td>
                                                <?php }else{ ?>
                                                  <td><?= $ff->PropSize->Bedrooms.' '.$lan['map']['beds'] ?></td>
                                                <?php } ?>
                                                  <td><?= $ff->PropSize->TotalBaths.' '.$lan['map']['baths']  ?></td>
                                               <?php }else{ if($ff->PropSize->LotSize > 0){ echo '<td>'.number_format((int)$ff->PropSize->LotSize).' ft&sup2;';
                                                 if($ff->PropSize->LotSizeMt > 0){
                                                   echo ' / '.number_format((int)$ff->PropSize->LotSizeMt).' mt&sup2;';
                                                 }
                                                 echo '</td>'; } } ?>
                                               <td class="mobile-hide"><a class="propertyType" href="listings/property_type/<?= $ff->PropType ?>">
                                                 <?= $property_types[$ff->PropType]['desc'];  ?>
                                               </a></td>
                                               <!--<td><?= $listing_types[$ff->SaleType] ?></td>-->
                                           </tr>
                                       </table>






                                        <div class="row">
                                           <div class="col-6">
                                              <a href="<?= $link_property[$lang].$ff->PropId ?>" class="property-btn"><?= $lan['props']['view'] ?></a>
                                           </div>

                                           <div class="col-6">
                                              <div class="text-right" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                                  <p class="price" itemprop="price">$<?= number_format($ff->PropCosts) ?> <small><?= $curr_desc ?></small></p>
                                                  <meta itemprop="priceCurrency" content="USD">
                                                   <meta itemprop="category" content="Real Estate: Homes for Sale">
                                                   <span itemprop="seller" itemscope="" itemtype="http://schema.org/RealEstateAgent">
                                                       <meta itemprop="name" content="Kiin Real Estate">
                                                   </span>
                                              </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF LISTING DIV -->
                  <?php }}elseif(empty($listings->List[1])){ $no_results = 1; ?>
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




                <?php if($no_results == 0){ ?>
                    <ul class="pageList">
                    <?= $listings->Pagination->LinksDisplay ?>
                    </ul>
                  <?php } ?>
                </div>
                <!-- END LISTINGS -->

            </div>
        </div>
        <!-- end container -->



        <?php if($show_dest == 1){ ?>
          <div class="container-fluid show-dest <?= $dest_class ?>">

            <div class="container py-5">
              <div class="card">
                <div class="row g-0">
                  <div class="col-md-8">
                    <div class="card-body">
                      <h1 class="card-title"><?= $dest_head ?></h1>
                      <p class="card-text"><?= $dest_txt ?></p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <img src="dist/img/destinations/thumbs/<?= $dest_thumb ?>" alt="<?= $dest_head ?>" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>


    </section>
    <!-- end main content -->


    <?php require('dist/inc/foot.php') ?>

    <!-- Loading modal -->
    <div class="modal modal-center fade" id="loading_modal" tabindex="-1">
      <div class="modal-dialog modal-sm modal-mini">
        <div class="modal-content">

          <div class="modal-body text-center">
            <img src="dist/img/loading.gif" class="img-fluid align-center" alt="loading">
            <br>
            <p><?= $lan['map']['refresh'] ?>...</p>
          </div>

        </div>
      </div>
    </div>



   <!-- modal for advanced search -->
   <div class="modal fade" id="advanced_search"  tabindex="-1" aria-labelledby="advanced_searchLabel" aria-hidden="true">
     <div class="modal-dialog modal-fullscreen">
       <div class="modal-content">
         <form action="<?= $link_properties[$lang] ?>" method="get" id="adv_search_form">
           <div class="modal-header">
             <h5 class="modal-title" id="advanced_searchLabel"><?= $lan['map']['adv_h'] ?></h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
              <?php include('dist/inc/forms/search_advanced.php'); ?>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $lan['map']['adv_close'] ?></button>
             <button type="submit" class="btn btn-primary" name="search_advanced">Update Results</button>
           </div>
         </form>
       </div>
     </div>
  </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="dist/plugins/responsive/respond.js"></script>
    <script src="dist/plugins/increment/input.increment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lozad.js/1.16.0/lozad.min.js" integrity="sha512-21jyjW5+RJGAZ563i/Ug7e0AUkY7QiZ53LA4DWE5eNu5hvjW6KUf9LqquJ/ziLKWhecyvvojG7StycLj7bT39Q==" crossorigin="anonymous"></script>
    <script src="dist/plugins/jquery.mask/jquery.mask.min.js"></script>
    <script src="dist/js/adv-search.js"></script>
    <script src="dist/js/list-search.js"></script>


  </body>
  </html>
