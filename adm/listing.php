<?php
include 'base.php';

$show_alert = 0;
$note_head = '';
$note_txt = '';

$page_type = 1;

$edit_list = 0;
$lid = 0;
$page_title = 'Add new';
$page_text = 'Here you can easily add a new listing to your webpage. Simply fill out the form below and do not forget to save the information. You will be able to add images in the next step.';
$preview_btn = '';


if(isset($_GET['lid'])){
    $edit_list = 1;
    $lid = $_GET['lid'];
    $page_title = 'Edit';
    $page_text = 'Here you can easily edit the current. Simply update the form below and do not forget to save the information. You can also easily add or remove images from the gallery. These images will update automatically.';
    $preview_btn = '<a href="https://kiinrealty.com/listing/'.$_GET['lid'].'" target="_blank" class="btn btn-info text-end">Preview Listing <i class="ti-link"></i></a>';
}


$properties = new Listings();
$prop = new Listing($lid);
$properties->generateForm($prop->Location->County,$prop->Location->City,$prop->Location->State);

if(isset($_POST['update_listing'])){

    if($_POST['property_id'] > 0){
        $save = $prop->updateListing($_POST);
    }else{
        $save = $prop->addListing($_POST);
    }
    $properties->generateForm($prop->Location->County,$prop->Location->City,$prop->Location->State);


    if($save != 0){
        $lid = $save;
        $edit_list = 1;
        $show_alert = 'success';
        $note_head = 'Success';
        $note_txt = '';
        $page_title = 'Edit';
        $page_text = 'Here you can easily edit the current. Simply update the form below and do not forget to save the information. You can also easily add or remove images from the gallery. These images will update automatically.';
        $prop = new Listing($lid);
    }else{
        $show_alert = 'error';
        $note_head = 'Uh Oh!';
        $note_txt = 'The listing has not been saved successfully. We encountered an error in the information submitted. If the problem persists, please contact your system administrator.';
    }
}

//print_me($properties);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $site_title ?></title>
    <meta name="robots" content="noindex" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">

    <!-- Plugins -->
    <link href="assets/plugin/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Styles -->
    <link href="assets/css/core.min.css" rel="stylesheet">
    <link href="assets/css/app.min.css" rel="stylesheet">
    <link href="assets/css/mia.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.13.0/dist/sweetalert2.min.css" integrity="sha256-g63UuGJzNKJaeNzy1f7N4V59R3+DZamET2Fg0cXAGDQ=" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    <link rel="icon" href="assets/img/favicon.png">
  </head>

  <body data-alert="<?= $show_alert ?>" data-txt="<?= $note_txt ?>" data-head="<?= $note_head ?>" data-prop="<?= $prop->PropertyId ?>">

    <?php include 'assets/inc/header.php'; ?>

    <header class="topbar topbar-inverse">
      <div class="topbar-left">
        <button class="topbar-btn sidebar-toggler">☰</button>
        <a class="topbar-btn" href="listings.php" title="" data-provide="tooltip" data-placement="bottom" data-original-title="Return to all listings"><i class="ti-arrow-left"></i></a>
        <div class="topbar-divider"></div>
        <a class="topbar-btn fs-16" href="#" title="" data-provide="fullscreen tooltip" data-placement="bottom" data-original-title="Toggle fullscreen">
          <i class="ion-android-expand fullscreen-default"></i>
          <i class="ion-android-contract fullscreen-active"></i>
        </a>
      </div>

      <div class="topbar-right">
        <div class="nav-page">
         <span class="nav-explain">Jump to section:</span>
         <a class="topbar-btn active" href="#info">Information</a>
          <a class="topbar-btn" href="#pricing">Pricing</a>
          <a class="topbar-btn" href="#details">Details</a>
          <a class="topbar-btn" href="#location">Location</a>
          <a class="topbar-btn" href="#features">Features</a>
          <?php if($edit_list == 1){ ?>
          <a class="topbar-btn" href="#photos">Images</a>
          <?php } ?>
        </div>
      </div>
    </header>
    <!-- Main container -->
    <main class="main-container">

      <div class="main-content">

        <div class="row">
          <div class="col-9">
            <h1 class="header-title">
                <strong><?= $page_title ?> Listing</strong>
            </h1>
          </div>
          <div class="col-3 text-right">
            <?= $preview_btn ?>
          </div>
        </div>


        <p class="pt-0 mb-4" style="font-size: 15px;"><?= $page_text ?></p>

        <?php include 'forms/listing.php'; ?>


      </div><!--/.main-content -->


     <?php include 'assets/inc/footer.php'; ?>

    </main>
    <!-- END Main container -->


    <!-- Loading modal -->
    <div class="modal modal-center fade" id="loading_modal" tabindex="-1" style="padding-right: 17px; display: block;">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">

          <div class="modal-body text-center">
            <img src="assets/img/loader.gif" class="img-fluid align-center" alt="loading">
            <br>
            <p>Loading...</p>
          </div>

        </div>
      </div>
    </div>


    <!-- Scripts -->
    <script src="assets/js/core.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.13.0/dist/sweetalert2.all.min.js" integrity="sha256-J9avsZWTdcAPp1YASuhlEH42nySYLmm0Jw1txwkuqQw=" crossorigin="anonymous"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/plugin/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/js/script.min.js"></script>

    <script src="assets/js/listing.js"></script>

  </body>
</html>
