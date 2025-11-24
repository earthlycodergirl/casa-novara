<?php
include 'base.php';

$page_type = 11;

$show_alert = 0;
$note_head = '';
$note_txt = '';

if(isset($_POST['import_list'])){
    include 'assets/inc/process/upload_csv.php';
}


if(isset($_GET['dd'])){
    //delete listing
    new SqlIt("DELETE FROM listings_upload WHERE listing_id = ?","delete",array($_GET['dd']));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The property has been removed from the database successfully.';
}

$properties = new PubListings('ADM');

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

    <!-- Styles -->
    <link href="assets/css/core.min.css" rel="stylesheet">
    <link href="assets/css/app.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    <link rel="icon" href="assets/img/favicon.png">
  </head>

  <body data-alert="<?= $show_alert ?>" data-txt="<?= $note_txt ?>" data-head="<?= $note_head ?>">

    <?php include 'assets/inc/header.php'; ?>

    <header class="topbar">
      <div class="topbar-left">
        <button class="topbar-btn sidebar-toggler">☰</button>
        <a class="topbar-btn fs-16" href="#" title="" data-provide="fullscreen tooltip" data-placement="bottom" data-original-title="Toggle fullscreen">
          <i class="ion-android-expand fullscreen-default"></i>
          <i class="ion-android-contract fullscreen-active"></i>
        </a>
      </div>

      <div class="topbar-right">
        <div>
          <a class="btn btn-outline btn-info" href="#" data-target="#upload_list" data-toggle="modal"><i class="ti-upload"></i> Import new listing sheet</a>
        </div>
      </div>
    </header>

    <!-- Main container -->
    <main class="main-container">
      <div class="main-content">


        <h1 class="header-title">
            <strong>Uploaded Listings</strong>
            <small class="pt-0 mb-4">Here you will find a list of all the listings uploaded via CSV to your database. You cannot modify these listings but you can remove them. Feel free to search listings by MLS # or address. </small>
        </h1>

        <div class="card shadow-3">
            <div class="card-body">
                <table class="table table-striped" data-provide="datatables">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>MLS</th>
                            <th>Agency</th>
                            <th>Area</th>
                            <th>List Type</th>
                            <th>Prop Type</th>
                            <th>Price</th>
                            <th style="text-align:center;">SC*</th>
                            <th style="text-align:center;">BC*</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>MLS</th>
                            <th>Agency</th>
                            <th>Area</th>
                            <th>List Type</th>
                            <th>Prop Type</th>
                            <th>Price</th>
                            <th style="text-align:center;">SC*</th>
                            <th style="text-align:center;">BC*</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if(!empty($properties->List)){ foreach($properties->List as $kk=>$li){ ?>
                        <tr>
                            <td><?= $kk ?></td>
                            <td><?= $li->listing_number ?></td>
                            <td><?= $li->agency_name ?></td>
                            <td><?= $li->county ?></td>
                            <td><?= $li->property_group ?></td>
                            <td><?= $li->book_section ?></td>
                            <td>$<?= number_format($li->price_current,0) ?></td>
                            <td style="text-align:center;"><?= (float)$li->buy_broker_com ?></td>
                            <td style="text-align:center;"><?= (float)$li->sell_broker_com ?></td>
                            <td class="text-right table-actions">
                                <a class="table-action hover-primary listing-details" data-toggle="tooltip" title="See listing details" href="#"><i class="ti-list"></i></a>
                                <a class="table-action hover-danger delete-listing" data-toggle="tooltip" title="Remove listing" href="#" data-id="<?= $kk ?>"><i class="ti-trash"></i></a>
                              </td>
                        </tr>
                        <?php } } ?>
                    </tbody>

                </table>
                <div class="row">
                    <div class="col-md-12">
                        <p><b>*SC:</b> Seller Commision<br><b>*BC:</b> Buyer Commision</p>
                    </div>
                </div>
            </div>
          </div>


      </div><!--/.main-content -->


     <?php include 'assets/inc/footer.php'; ?>

    </main>
    <!-- END Main container -->


    <!-- DELETE MODAL -->
    <div class="modal modal-top fade" id="delete_prop" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm listing removal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="listings_uploaded.php" method="get">
              <input type="hidden" name="dd" value="0" id="dd">
          <div class="modal-body">
              <p><b>Are you sure you would like to permanently delete this property listing?</b> You will not be able to recover the information associated once this is done. If you would simply like to remove it from the public website, you can easily change the visibility by clicking on the eye icon next to the listing. Please confirm that you are deleting this permanently.</p>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel removal</button>
            <button type="submit" class="btn btn-bold btn-outline btn-danger">Confirm removal</button>
          </div>
            </form>
        </div>
      </div>
    </div>


    <!-- UPLOAD MODAL -->
    <div class="modal modal-top fade" id="upload_list" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Import new listing sheet</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form enctype="multipart/form-data" action="listings_uploaded.php" method="post">
              <input type="hidden" name="import_list" value="1">

          <div class="modal-body">
              <p><b>Maximum of 1000 listings allowed to be added at once.</b> To add listings from your broker affiliate website, simply upload the CSV file here. Please remember when exporting the list from broker website, you must select the option "<b>Toolkit CMA Export</b>" in order for the listings to add correctly. Any listing currently in the database will be updated automatically if in uploaded list.</p>

              <hr>

              <div class="form-group">
                <label>Select file (CSV) to import</label><br>
                <input type="file" name="filename">
              </div>

              <hr>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel import</button>
            <button type="submit" class="btn btn-bold btn-outline btn-info">Confirm &amp; import file now</button>
          </div>
            </form>
        </div>
      </div>
    </div>



    <!-- Scripts -->
    <script src="assets/js/core.min.js" data-provide="sweetalert"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="assets/js/listings.js"></script>
  </body>
</html>
