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
  //$_GET['page'] = 1;
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

// get destinations
if(isset($_GET['location']) && $_GET['location'] > 0){
  switch($_GET['location']){
    case 1808:
      $dest_head = 'Real Estate in Cancun, Mexico';
      $dest_txt = 'Cancun, Fifty years of Premier tourism development in the Mexican Caribbean. Home to the international airport of Cancun and a Hotel zone unlike any other. Stretching out around the lagoon of Nichupté making it a premier tourism destination for world-class entertainment. Putting the Mexican Caribbean at the forefront of international investment. Whether they are beachfront condominiums or gated residential communities in the downtown area, Cancun is rich in opportunity.';
      $show_dest = 1;
      $dest_class = 'cancun-bg';
      break;
    case 1810:
      $dest_head = 'Real Estate in Playa del Carmen, Mexico';
      $dest_txt = 'Playa del Carmen, The beating heart of the Riviera Maya, Famous for its pedestrian boulevard the ‘Fifth Av.’ where you find world class shopping, fine dining and all the local flavors you are looking for. Always within walking distance from the beach. You are sure to find high value return investment opportunities. Whether they are beachfront condominiums, or american style villas in private residential eco-communities like Playacar, Mayakoba and many others.';
      $show_dest = 1;
      $dest_class = 'playa-bg';
      break;
    case 1811:
      $dest_head = 'Real Estate in Tulum, Mexico';
      $dest_txt = 'Tulum, the youngest in terms of developments in the Riviera Maya. You will find that Tulum offers an eco-chic feel, with the most beautiful beaches in the Riviera. Michelin starred fine dining, combined with a jungle-like hotel zone. Downtown Tulum is home to art venues and an eco-friendly lifestyle, showcasing some of the newest and most modern constructions.';
      $show_dest = 1;
      $dest_class = 'tulum-bg';
      break;
    case 2440:
      $dest_head = 'Real Estate in Puerto Morelos, Mexico';
      $dest_txt = 'Puerto Morelos, A hidden gem in terms of investment that offers a real ‘small-town feel’ with a lot of room for growth. The bay area provides some of the best snorkeling and fresh seafood restaurants in the whole of the Riviera Maya. World renown for its mesoamerican barrier reef system. Puerto Morelos is pristine with protected mangrove forests surrounding it.';
      $show_dest = 1;
      $dest_class = 'puerto-bg';
      break;
    case 1809:
      $dest_head = 'Real Estate in Cozumel, Mexico';
      $dest_txt = 'The island of Cozumel, A world-class destination, famous for its turquoise waters. Home to some of the best scuba diving in the world. This small town ‘big city feel’ provides you with the most modern services. You will find this to be one of the most visited islands in the Caribbean. Offering excellent return on real estate ventures. Modern condominiums and new construction are plentiful. Being that the largest cruise ships visit the ports of this beautiful island, you can be sure to attract the international attention you seek for your ventures in the Caribbean.';
      $show_dest = 1;
      $dest_class = 'cozumel-bg';
      break;
  }
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
//print_me($listings);
if(isset($listings->SearchParams->Cities[0])){
  $city_id = $listings->SearchParams->Cities[0];
  $adv_search->getTowns($city_id);
}else{
  $city_id = 0;
}

print_me($_GET);
//print_me($property_types);
print_me($listings->SearchParams);
?>

<!doctype html>
<html lang="en">
  <head>
    <base href="<?= $base_href ?>">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link href="dist/plugins/responsive/responsive.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/responsive/yamm.css" rel="stylesheet" type="text/css" media="all" />
    <link href="dist/plugins/nuslider/jquery.nouislider.min.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <title>MIA Realty</title>

    <?php include 'dist/inc/favicon.php'; ?>

  </head>
  <body class="<?= $mobile_class ?>">

    <?php require('dist/inc/nav_top.php') ?>

    <div class="inner-head <?= $dest_class ?>">
      <?php require('dist/inc/nav.php') ?>
      <div class="container">
        <div class="row">
          <div class="col">
            <nav style="--bs-breadcrumb-divider: ' > ';" aria-label="breadcrumb" class="breadcrumbs">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Listings in <?= utf8_encode($listings->SearchParams->CityName) ?></li>
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
                       <?php include 'dist/inc/forms/search_sidebar2.php'; ?>
                       <!-- end form -->
                   </div>
                   <!-- end quick search widget -->
               </div>
                <div class="col-lg-9 col-md-9" id="listings">
                   <div class="row">
                      <div class="col-5">
                        <h1 id="propertyTitle">
                             <button type="button" class="listing-btn" id="show_adv">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
                                </svg>
                             </button>
                           <span class="mobile-hide"><?= $listings->Pagination->TotalResults ?> properties
                             <?php if($city_id > 0){
                             echo 'listed in '.utf8_encode($listings->SearchParams->CityName);
                           }else{
                             echo 'found';
                           }
                           ?></span></h1>
                      </div>
                      <div class="col-7 text-end">
                        <span class="order-list">Order by</span>
                        <select id="order_li" class="select">
                          <option value="featured" <?php if($listings->SearchParams->OrderBy == 'featured'){ echo 'selected'; } ?>>Featured</option>
                          <option value="price_high" <?php if($listings->SearchParams->OrderBy == 'price_high'){ echo 'selected'; } ?>>Price high to low</option>
                          <option value="price_low" <?php if($listings->SearchParams->OrderBy == 'price_low'){ echo 'selected'; } ?>>Price low to high</option>
                          <option value="size_high" <?php if($listings->SearchParams->OrderBy == 'size_high'){ echo 'selected'; } ?>>Size high to low</option>
                          <option value="size_low" <?php if($listings->SearchParams->OrderBy == 'size_low'){ echo 'selected'; } ?>>Size low to high</option>
                          <option value="alpha" <?php if($listings->SearchParams->OrderBy == 'alpha'){ echo 'selected'; } ?>>Name A to Z</option>
                          <option value="alpha_desc" <?php if($listings->SearchParams->OrderBy == 'alpha_desc'){ echo 'selected'; } ?>>Name Z to A</option>
                        </select>

                        <span class="view-as">View as</span>

                        <a href="javascript:void(0)" class="listing-btn active">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                             <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                           </svg>
                        </a>

                        <a href="listings-map<?= str_replace('listings','',$get) ?>" class="listing-btn">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                             <path fill-rule="evenodd" d="M12.166 8.94C12.696 7.867 13 6.862 13 6A5 5 0 0 0 3 6c0 .862.305 1.867.834 2.94.524 1.062 1.234 2.12 1.96 3.07A31.481 31.481 0 0 0 8 14.58l.208-.22a31.493 31.493 0 0 0 1.998-2.35c.726-.95 1.436-2.008 1.96-3.07zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                             <path fill-rule="evenodd" d="M8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                           </svg>
                        </a>

                      </div>
                   </div>

                  <hr />
                   <?php if(!empty($listings->List[0])){ foreach($listings->List[0] as $ll=>$ff){ ?>

                   <!-- LISTING DIV -->

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="propertyItem" data-lat="<?= $ff->PropLocation->Latitude ?>" data-lng="<?= $ff->PropLocation->Longitude ?>" data-price="<?= $ff->PropCosts ?>" data-details-url="<?= $site_url ?>listing/<?= $ff->PropId ?>" itemscope="" itemtype="http://schema.org/Product" itemid="mls#:<?= $ff->MLS ?>">
                                <link itemprop="additionalType" href="http://www.productontology.org/id/Real_estate">
                                <meta itemprop="url" content="<?= $site_url ?>listing/<?= $ff->PropId ?>">
                                <div class="propertyContent row">
                                    <div class="col-md-4 col-sm-4">
                                        <div onclick="location.href='listing/<?= $ff->PropId ?>'" class="propertyImgLink" style="--background: url('<?= $prop_img_url.$ff->PropThumb ?>')" data-acmw="USE-THUMB">
                                           <?php if($ff->IsFeatured == 1){ ?>
                                           <span class="featured" data-bs-toggle="tooltip" title="Featured property">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                               <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                              </svg>
                                           </span>
                                           <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 rowText">
                                       <div class="row">
                                           <div class="col-md-12" itemscope="" itemtype="http://schema.org/PostalAddress">

                                               <a href="listing/<?= $ff->PropId ?>" class="hover-title">
                                                   <h4 itemprop="name"><?= $ff->PropTitle ?></h4>
                                                </a>
                                                <p class="location" itemprop="streetAddress" content="<?= $ff->PropLocation->Address ?>">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                                     <path fill-rule="evenodd" d="M12.166 8.94C12.696 7.867 13 6.862 13 6A5 5 0 0 0 3 6c0 .862.305 1.867.834 2.94.524 1.062 1.234 2.12 1.96 3.07A31.481 31.481 0 0 0 8 14.58l.208-.22a31.493 31.493 0 0 0 1.998-2.35c.726-.95 1.436-2.008 1.96-3.07zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                                     <path fill-rule="evenodd" d="M8 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                   </svg>
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

                                       <p class="desc" itemprop="description">
                                          <?= substr($ff->PropDesc,0,250).'...' ?>
                                          <a class="read-more" href="listing/<?= $ff->PropId ?>">Read more</a>
                                       </p>

                                       <table border="1" class="propertyDetails">
                                           <tr>
                                               <?php if($ff->PropSize->SqFt != 0){ $sqft = number_format((int)$ff->PropSize->SqFt).' ft&sup2;'; }else{ $sqft = 'NA'; } ?>
                                               <td><?= $sqft ?></td>
                                               <?php if($ff->PropType == 20){ ?>
                                                  <td><?= $ff->PropSize->Bedrooms ?> Beds</td>
                                                  <td><?= $ff->PropSize->Bathrooms ?> Baths</td>
                                               <?php } ?>
                                               <td class="mobile-hide"><a class="propertyType" href="listings/property_type/<?= $ff->PropType ?>"><?= $property_types[$ff->PropType]['desc'] ?></a></td>
                                               <!--<td><?= $listing_types[$ff->SaleType] ?></td>-->
                                           </tr>
                                       </table>






                                        <div class="row">
                                           <div class="col-6">
                                              <a href="listing/<?= $ff->PropId ?>" class="property-btn">View Property</a>
                                           </div>

                                           <div class="col-6">
                                              <div class="text-right" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                                  <p class="price" itemprop="price">$<?= number_format($ff->PropCosts) ?> <small>USD</small></p>
                                                  <meta itemprop="priceCurrency" content="USD">
                                                   <meta itemprop="category" content="Real Estate: Homes for Sale">
                                                   <span itemprop="seller" itemscope="" itemtype="http://schema.org/RealEstateAgent">
                                                       <meta itemprop="name" content="MIA Real Estate">
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
                          <h3>No properties found</h3>
                          <p>Uh oh! Looks like we don't have any properties available for your search criteria. This does not mean we can't find it for you! <a href="<?= $link_contact ?>">Contact us</a> today and tell us what you are looking for. We can 99.9% guarantee that we will find exactly what you are seeking!</p>
                        </div>
                        <div class="col-sm-3">
                          <div class="shrug">
                            ¯\_(ツ)_/¯
                          </div>
                        </div>
                      </div>

                    </div>
                  <?php } ?>




                    <?php if(!empty($listings->List[1])){ foreach($listings->List[1] as $ll=>$ff){ ?>
                   <!-- LISTING DIV -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="propertyItem" data-lat="<?= $ff->PropLocation->Latitude ?>" data-lng="<?= $ff->PropLocation->Longitude ?>" data-price="<?= $ff->PropCosts ?>" data-details-url="<?= $site_url ?>listingup/<?= $ff->PropId ?>" itemscope="" itemtype="http://schema.org/Product" itemid="mls#:<?= $ff->MLS ?>">
                                <link itemprop="additionalType" href="http://www.productontology.org/id/Real_estate">
                                <meta itemprop="url" content="<?= $site_url ?>listing/<?= $ff->PropId ?>">
                                <div class="propertyContent row">
                                    <div class="col-lg-5 col-md-4 col-sm-4">
                                        <a class="propertyType" href="listings/property_type/<?= $ff->PropType ?>"><?= $ff->PropType ?></a>
                                        <a href="listingup/<?= $ff->PropId ?>" class="propertyImgLink" data-acmw="USE-THUMB">
                                            <img class="propertyImg img-responsive" src="<?= $ff->PropThumb ?>" alt="<?= $ff->PropTitle ?>" itemprop="image" />
                                        </a>
                                    </div>
                                    <div class="col-lg-7 rowText">
                                       <div class="row" itemprop="name" content="<?= $ff->PropLocation->Address ?>">
                                           <div class="col-md-9" itemscope="" itemtype="http://schema.org/PostalAddress">
                                               <a href="listingup/<?= $ff->PropId ?>" class="hover-title">
                                                   <h4 itemprop="streetAddress"><?= $ff->PropLocation->Address ?></h4>
                                                </a><br/>
                                               <p class="location">
                                                   <span itemprop="addressLocality"><?= $ff->PropLocation->City ?></span>,
                                                   <span itemprop="addressRegion"><?= $ff->PropLocation->State ?></span>
                                                   <span itemprop="postalCode"><?= $ff->PropLocation->Zip ?></span>
                                                </p>

                                           </div>
                                           <div class="col-md-3 text-right" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                               <p class="price" itemprop="price">$<?= number_format($ff->PropCosts) ?></p>
                                               <meta itemprop="priceCurrency" content="USD">
                                                <meta itemprop="category" content="Real Estate: Homes for Sale">
                                                <span itemprop="seller" itemscope="" itemtype="http://schema.org/RealEstateAgent">
                                                    <meta itemprop="name" content="Budd Rall Real Estate">
                                                </span>
                                                <p class="forSale"><?php if($ff->SaleType !== 'Rental'){ echo 'For sale'; }else{ echo 'For rent'; } ?></p>
                                           </div>
                                       </div>


                                        <p class="desc open-sans" itemprop="description"><?= substr($ff->PropDesc,0,250).'...' ?> <a class="read-more" href="listingup/<?= $ff->PropId ?>">Read more</a></p>
                                        <p class="mls_num text-right">MLS #: <span itemprop="productID"><?= $ff->MLS ?></span></p>
                                        <table border="1" class="propertyDetails">
                                            <tr>
                                               <?php if($ff->PropSize->SqFt != 0){ $sqft = $ff->PropSize->SqFt.' sqft'; }else{ $sqft = 'NA'; } ?>
                                                <td><img src="images/icon-area.png" alt="" style="margin-right:7px;" /><?= $sqft ?></td>
                                                <td><img src="images/icon-bed.png" alt="" style="margin-right:7px;" /><?= $ff->PropSize->Bedrooms ?> Beds</td>
                                                <td><img src="images/icon-drop.png" alt="" style="margin-right:7px;" /><?= $ff->PropSize->Bathrooms ?> Baths</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END OF LISTING DIV -->
                  <?php }} if($no_results == 0){ ?>
                    <ul class="pageList">
                    <?= $listings->Pagination->LinksDisplay ?>
                    </ul>
                  <?php } ?>
                </div>
                <!-- START SIDEBAR -->

            </div>
        </div>
        <!-- end container -->
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
            <p>Refreshing listings...</p>
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
           <h5 class="modal-title" id="advanced_searchLabel">Advanced Search</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <p>We are currently working on this section. Please check back soon to be able to do an advanced Real Estate search.</p>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           <!--<button type="button" class="btn btn-primary">Update Results</button>-->
         </div>
       </div>
     </div>
  </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="dist/plugins/responsive/respond.js"></script>
    <script src="dist/plugins/jquery.mask/jquery.mask.min.js"></script>
    <script src="dist/plugins/nuslider/jquery.nouislider.min.js"></script>
    <script src="dist/plugins/increment/input.increment.js"></script>
    <script src="dist/js/list-search2.js"></script>


  </body>
  </html>
