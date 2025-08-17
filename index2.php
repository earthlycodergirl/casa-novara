<?php
$show_listings = 1;
$build_search = 1;
$ppage = 1;

require('base.php');

$listings->getFeatured(6);
$listing_cities = $listings->getLocations2();
$adv_search = new AdvSearch();
$adv_search->getMinMax();
//print_me($adv_search);
?>

<!doctype html>
<html lang="en">
  <head>
    <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $meta['home']['title'] ?></title>
    <meta name="description" content="<?= $meta['home']['desc'] ?>" />
    <meta name="robots" content="index" />
    <link rel="canonical" href="<?= $link_home[$lang] ?>">


    <meta property="og:title" content="<?= $meta['home']['title'] ?>">
    <meta property="og:description" content="<?= $meta['home']['desc'] ?>">
    <meta property="og:image" content="/dist/img/social.jpg">
    <meta property="og:url" content="<?= $link_home[$lang] ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="MIA Realty">
    <meta name="twitter:image:alt" content="<?= $meta['home']['title'] ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" defer="defer">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" defer="defer" />
    <link rel="stylesheet" href="dist/css/home-search.css" type="text/css" />


    <?php include 'dist/inc/favicon.php'; ?>
  </head>
  <body class="<?= $mobile_class ?> home-page">

    <?php require('dist/inc/nav_top.php') ?>

    <div class="container-fluid home-bg">

      <?php require('dist/inc/nav.php') ?>

      <div class="home-head container">
        <h1><?= $lan['ho']['h1'] ?></h1>
        <h3><?= $lan['ho']['h3'] ?></h3>

        <?php require('dist/inc/forms/search_home.php') ?>
      </div>

    </div>

    <div class="container home-middle">
      <div class="row">
        <div class="col-md-6 col-lg-5">
          <img src="dist/img/side-home-2.jpg" class="img-fluid check-left" alt="MIA Realty" />
        </div>
        <div class="col-md-6 col-lg-7 text-left text-md-end">
          <h2><?= $lan['ho']['h2'] ?></h2>
          <p class="ps-md-5 ms-md-5"><?= $lan['ho']['p'] ?> </p>
          <p class="ps-md-5 ms-md-5"><?= $lan['ho']['p1'] ?></p>
          <p class="ps-md-5 ms-md-5"><?= $lan['ho']['p2'] ?></p>
          <p><?= $lan['ho']['p3'] ?></p>
          <a href="<?= $link_about[$lang] ?>" class="yellow-li"><?= $lan['ho']['a'] ?></a>
        </div>
      </div>

      <div class="row mt-5 pt-md-5">
        <div class="col">
          <h2 class="sub"><?= $lan['ho']['h22'] ?></h2>
          <h5 class="italic"><?= $lan['ho']['h5'] ?></h5>
        </div>
      </div>
    </div>


    <!-- FEATURED PROPERTIES -->
    <div class="container-fluid leafy">
      <div id="home_destinations" class="container">
        <div class="row">
          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/cancun/all" class="destination cancun-d">
              <h2>Cancun</h2>
              <h3>Cancun <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/puerto-morelos/all" class="destination pm-d">
              <h2>Puerto Morelos</h2>
              <h3>Puerto Morelos <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/playa-del-carmen/all" class="destination pdc-d">
              <h2>Playa del Carmen</h2>
              <h3>PDC <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/tulum/all" class="destination tulum-d">
              <h2>Tulum</h2>
              <h3>Tulum <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/akumal/all" class="destination akumal-d">
              <h2>Akumal</h2>
              <h3>Akumal <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/cozumel/all" class="destination im-d">
              <h2>Cozumel</h2>
              <h3>Cozumel <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/puerto-aventuras/all" class="destination pa-d">
              <h2>Puerto Aventuras</h2>
              <h3>Puerto Aventuras <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>

          <div class="col-md-4 col-lg-3 col-sm-6 p-0">
            <a href="<?= $link_properties[$lang] ?>/bacalar/all" class="destination bacalar-d">
              <h2>Bacalar</h2>
              <h3>Bacalar <?= $lan['ho']['real'] ?></h3>
            </a>
          </div>
        </div>
      </div>
    </div>


    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
    <script src="dist/plugins/jquery.mask/jquery.mask.min.js"></script>
    <script src="dist/js/script.js"></script>
    <script src="dist/js/index.js"></script>
    <script src="dist/js/search-home.js"></script>


  </body>
</html>
