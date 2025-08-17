<?php
require('base.php');

$page_type = 1;

$show_alert = 0;
$note_head = '';
$note_txt = '';

$properties = new Listings();

if(isset($_GET['dd'])){
    //delete listing
    new SqlIt("DELETE FROM property_list WHERE property_id = ?","delete",array($_GET['dd']));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The property has been deleted successfully.';
}

if(isset($_POST['delete_all']) && $_POST['delete_all'] == 1){

    if(isset($_POST['delete_ids']) && count($_POST['delete_ids']) > 0){
        foreach($_POST['delete_ids'] as $id){    
            if($id > 0){
                //delete listing
                new SqlIt("DELETE FROM property_list WHERE property_id = ?","delete",array($id));
            }
        }
        $show_alert = 'success';
        $note_head = 'Deleted';
        $note_txt = 'The properties have been deleted successfully.';
    }

    
}

$properties->getListings();

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
    <link href="assets/css/mia.css" rel="stylesheet">
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
          <a class="btn btn-outline btn-info" href="listing.php"><i class="ti-plus"></i> Add a new listing</a>
        </div>
      </div>
    </header>

    <!-- Main container -->
    <main class="main-container">
      <div class="main-content">


        <h1 class="header-title">
            <strong>Current Custom Listings</strong>
            <small class="pt-0 mb-4">Here you will find a list of all the listings added manually. Click on any below to view or edit. </small>
        </h1>

        <div class="card shadow-3 listings-tb">
            <div class="card-body">
                <div id="multi_action"></div>
                <table class="table table-striped" data-provide="datatables" data-order="[[ 1, &quot;desc&quot; ]]">
                    <thead class="thead-light">
                        <tr>
                            <th data-orderable="false" class="sorting_disabled"><input type="checkbox" name="select_all" value="on" id="select_all"></th>
                            <!-- <th>ID</th> -->
                            <th>ID</th>
                            <th>Listing</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Featured</th>
                            <th class="text-center">Visible</th>
                            <th width="100" data-orderable="false" class="sorting_disabled"></th>
                        </tr>
                    </thead>
                    <tfoot class="thead-light">
                        <tr>
                            <th></th>
                            <!-- <th>ID</th> -->
                            <th>ID</th>
                            <th>Listing</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Featured</th>
                            <th class="text-center">Visible</th>

                            <th width="100"></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if(!empty($properties->List)){ foreach($properties->List as $kk=>$li){ ?>
                        <tr class="<?= $li->status ?>">
                            <td data-orderable="false"><input type="checkbox" value="<?= $kk ?>" name="select_id"></td>
                            <!-- <td><?= $kk ?></td> -->
                            <td><?= $kk ?></td>
                            <td><a href="listing.php?lid=<?= $kk ?>"><?= $li->property_title ?> <?php if($li->room_type != 'bed'){ echo '<span class="badge badge-pill bg-secondary">'.$li->room_type.'</span>'; } ?></a><small><?= $li->address ?></small><small style="color: #262626"><?= $li->mls ?></small>
                              <span class="ext-link"><a href="https://kiinrealty.com/listing/<?= $kk ?>" class="link-me" target="_blank" title="see listing on website"><i class="ti-export"></i></a></span>
                            </td>
                            <td><small><?= $li->location ?></small></td>
                            <td><small><?= $li->ptype ?></small></td>
                            <td class="text-center <?= strtolower($li_status) ?>">
                            <small class="<?= strtolower($li_status) ?>"><span id="stat_<?= $kk ?>"><?= $li->status ?></span></small>
                            </td>

                            
                            <td class="text-center">
                                <span id="tr_feat_<?= $kk ?>"><i class="<?= $li->feat_icon ?>"></i></span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="update-visibility" id="tr_vis_<?= $kk ?>" data-id="<?= $kk ?>" data-rel="<?= $li->visible ?>">
                                    <i class="<?= $li->vis_icon ?>" data-provide="tooltip" data-placement="left" title="<?= $li->vis_txts ?>"></i>
                                </a>
                            </td>
                            <td class="text-right table-actions">
                                
                                <div class="dropdown table-action">
                                  <span class="dropdown-toggle no-caret hover-primary" data-toggle="dropdown"><i class="ti-more-alt rotate-90"></i></span>
                                  <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item link-me" href="listing.php?lid=<?= $kk ?>"><i class="ti-pencil" data-toggle="tooltip" title="Edit listing details"></i> Edit Listing</a>
                                  <a class="dropdown-item hover-danger delete-listing" data-toggle="tooltip" title="Delete listing" href="#" data-id="<?= $kk ?>"><i class="ti-trash"></i> Delete Listing</a>
                                    <a href="https://kiinrealty.com/listing/<?= $kk ?>" class="dropdown-item link-me" target="_blank" title="see listing on website"><i class="ti-link text-info"></i> View Listing</a>
                                    <!-- update featured listing -->
                                    <a class="dropdown-item update-featured show-me" id="btn_feat_<?= $kk ?>" href="#" data-id="<?= $kk ?>" data-rel="<?= $li->featured ?>"><?= $li->feat_txt ?></a>

                                    <!-- update listing status -->
                                    <a class="dropdown-item update-status <?php if($li->status != 'pending'){ echo 'show-me'; } ?>" href="#" data-id="<?= $kk ?>" data-rel="pending"><i class="ti-bookmark text-warning"></i> Set status PENDING</a>
                                    <a class="dropdown-item update-status <?php if($li->status != 'sold'){ echo 'show-me'; } ?>" href="#" data-id="<?= $kk ?>" data-rel="sold"><i class="ti-bookmark text-danger"></i> Set status SOLD</a>
                                    <a class="dropdown-item update-status <?php if($li->status != 'active'){ echo 'show-me'; } ?>" href="#" data-id="<?= $kk ?>" data-rel="active"><i class="ti-bookmark text-success"></i> Set status ACTIVE</a>



                                    <!-- update visibility -->
                                    <a class="dropdown-item update-visibility" id="btn_vis_<?= $kk ?>" href="#" data-id="<?= $kk ?>" data-rel="<?= $li->visible ?>"><?= $li->vis_txt ?></a>
                                  </div>
                                </div>
                              </td>
                        </tr>
                        <?php } } ?>
                    </tbody>

                </table>
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
          <form action="listings.php" method="get">
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


    <!-- DELETE ALL MODAL -->
    <div class="modal modal-top fade" id="delete_all" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm multiple listing removal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="listings.php" method="post">
              <input type="hidden" name="delete_all" value="1" id="delete_all_inp">
              <div id="delete_ids">

              </div>
          <div class="modal-body">
              <p><b>Are you sure you would like to permanently delete the property listings selected?</b> You will not be able to recover the information associated once this is done. You can also change the visibility by clicking on the eye icon next to the listing. Please confirm this action.</p>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel removal</button>
            <button type="submit" class="btn btn-bold btn-outline btn-danger">Confirm removal</button>
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
