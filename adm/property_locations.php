<?php
include 'base.php';

$show_alert = 0;
$note_head = '';
$note_txt = '';

$page_type = 15;
$state_id = $parent_id = 23;
$city_id = $town_id = 0;
$dtype = 'city';
$build_link = 'sid='.$state_id.'&cid=';

if(isset($_GET['sid'])){
  $state_id = $_GET['sid'];
  $parent_id = $state_id;
  $build_link = 'sid='.$state_id.'&cid=';
}
if(isset($_GET['cid'])){
  $city_id = $_GET['cid'];
  $dtype = 'town';
  $parent_id = $city_id;
  $build_link = 'sid='.$state_id.'&cid='.$city_id.'&tid=';
}
if(isset($_GET['tid'])){
  $town_id = $_GET['tid'];
  $dtype = 'area';
  $parent_id = $town_id;
  $build_link = '';
}

$locations = new Listings();
//$locations->getStates();

$db_table = $tb_id = $tb_field = '';
switch($dtype){
  case 'city':
    $db_table = 'locations_cities';
    $tb_id = 'city_id';
    $tb_field = 'location';
    $par_id = 'state_id';
    break;
  case 'town':
    $db_table = 'locations_towns';
    $tb_id = 'town_id';
    $tb_field = 'town_name';
    $par_id = 'city_id';
    break;
  case 'area':
    $db_table = 'locations_areas';
    $tb_id = 'area_id';
    $tb_field = 'area_name';
    $par_id = 'town_id';
    break;
}

$type_info = array('tb'=>$db_table,'id'=>$tb_id,'fi'=>$tb_field,'par'=>$par_id);

function locDetails($dtype){
  global $type_info;
  return $type_info;
}


if(isset($_POST['dd']) && isset($_POST['dtype'])){

    $db = locDetails($_POST['dtype']);

    if($db['tb'] != ''){
      // Delete location
      $delit = new SqlIt("DELETE FROM ".$db['tb']." WHERE ".$db['id']." = ?","delete",array($_POST['dd']));
      if($delit){

        if($_POST['dtype'] == 'town'){
          $_POST['dtype'] = 'county';
        }
        // reassign the properties
        if(isset($_POST['reas']) && $_POST['reas'] > 0){
          new SqlIt("UPDATE property_list SET ".$_POST['dtype']." = ? WHERE ".$_POST['dtype']." = ?","update",array($_POST['reas'],$_POST['dd']));
        }

        $show_alert = 'success';
        $note_head = 'Deleted';
        $note_txt = 'The '.$dtype.' has been deleted successfully and all properties have been reassigned.';
      }
    }




}


// ADD LOCATIONS
if(isset($_POST['add_location']) && isset($_POST['dtype'])){
    $db = locDetails($_POST['dtype']);

    if($db['tb'] != ''){
      // Add new property type
      new SqlIt("INSERT INTO ".$db['tb']." (".$db['fi'].",".$db['par'].") VALUES (?,?)","insert",array($_POST['dname'],$_POST['pid']));

      $show_alert = 'success';
      $note_head = 'Success';
      $note_txt = 'The '.$dtype.' has been added successfully.';
    }
}

if($dtype == 'city'){
  $locations->getAdmCities($state_id);
  $loc_list = $locations->AdmCities;
  $locations->getStates();
  $locations->getCities($state_id);
  $reassign = $locations->Cities;
  $page_desc = 'Cities in '.$locations->States[$state_id];
}

if($dtype == 'town'){
  $locations->getAdmTowns($city_id);
  $loc_list = $locations->AdmTowns;
  $locations->getStates();
  $locations->getCities($state_id);
  $locations->getTowns($city_id);
  $reassign = $locations->Towns;
  $page_desc = 'Towns in '.$locations->Cities[$city_id];
}

if($dtype == 'area'){
  $locations->getAdmAreas($town_id);
  $loc_list = $locations->AdmAreas;
  $locations->getStates();
  $locations->getCities($state_id);
  $locations->getTowns($city_id);
  $locations->getAreas($town_id);
  $reassign = $locations->Areas;
  $page_desc = 'Areas in '.$locations->Towns[$town_id];
}


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
                <a class="btn btn-outline btn-info" href="listing.php"><i class="ti-plus"></i> Add a new listing</a>
            </div>
        </div>
    </header>

    <!-- Main container -->
    <main class="main-container">
        <div class="main-content">


            <h1 class="header-title">
                <strong>Property Locations</strong>
                <small class="pt-0 mb-4">These are the property locations that are available on the listing section. These locations are also used to filter search results on the main Kiin Realty website. Please read the instructions below to modify a specific location type. </small>
            </h1>

            <div class="row">
                <div class="col-6">
                    <div class="card shadow-3">
                        <h4 class="card-title">View / Modify <?= $page_desc ?></h4>
                        <form action="" method="get" id="show_locs">
                          <div class="card-body">

                              <ol class="how-to-locations">
                                <li>
                                  Update <b>state</b> to view / modify cities.<br />
                                  <select name="sid" id="dstate" data-type="state" class="form-control d-inline-block update-loctype">
                                    <?php foreach($locations->States as $kk=>$ss){
                                      if($state_id == $kk){ $sel = 'selected'; }else{ $sel = ''; }
                                      echo '<option value="'.$kk.'" '.$sel.'>'.$ss.'</option>';
                                      } ?>
                                  </select>
                                  <span class="loading-locs load-states"><span class="popover-loader-lg d-inline-block ml-2" role="status" aria-hidden="true"></span></span>
                                  <?php if($dtype != 'city'){ ?>
                                    <a href="property_locations.php?sid=<?= $state_id ?>" class="d-block link mt-1"><i class="ti-arrow-left"></i> Return to list of cities</a>
                                  <?php } ?>
                                </li>
                                <?php if(isset($_GET['cid'])){ ?>
                                  <li>
                                    Update <b>city</b> to view / modify towns.<br />
                                    <select name="cid" id="dcity" data-type="city" class="form-control d-inline-block update-loctype">
                                      <?php foreach($locations->Cities as $kk=>$ss){
                                        if($city_id == $kk){ $sel = 'selected'; }else{ $sel = ''; }
                                        echo '<option value="'.$kk.'" '.$sel.'>'.$ss.'</option>';
                                        } ?>
                                    </select>
                                    <span class="loading-locs load-cities"><span class="popover-loader-lg d-inline-block ml-2" role="status" aria-hidden="true"></span></span>
                                    <?php if($dtype == 'area'){ ?>
                                      <a href="property_locations.php?sid=<?= $state_id ?>&cid=<?= $city_id ?>" class="d-block link mt-1"><i class="ti-arrow-left"></i> Return to list of towns</a>
                                    <?php } ?>
                                  </li>
                                  <?php } ?>

                                <?php if(isset($_GET['tid'])){ ?>
                                  <li>
                                    Select <b>town</b> to view / modify areas.<br />
                                    <select name="tid" id="dtown" data-type="town" class="form-control d-inline-block update-loctype">
                                      <?php foreach($locations->Towns as $kk=>$ss){
                                        if($town_id == $kk){ $sel = 'selected'; }else{ $sel = ''; }
                                        echo '<option value="'.$kk.'" '.$sel.'>'.$ss.'</option>';
                                        } ?>
                                    </select>
                                    <span class="loading-locs load-towns"><span class="popover-loader-lg d-inline-block ml-2" role="status" aria-hidden="true"></span></span>
                                  </li>
                                <?php } ?>
                                <li>

                                Do one of the following actions:
                                <ul>
                                  <li>Add a new <?= $dtype ?>. <small>This will be visible in property location settings (admin), property page &amp; listing filters (website) </small></li>
                                  <li>Edit <?= $dtype ?> name.</li>
                                  <li>Click on the <?= $dtype ?> to view sub locations.</li>
                                  <li>Delete <?= $dtype ?>. <small>Please note that this <?= $dtype ?> might be assigned to properties. Before deleting you will be asked to assign a new <?= $dtype ?> to properties.</small></li>
                                  <li>Add <?= $dtype ?> to navigation menu. Simply click on the <span class="is-featured disabled d-inline-block"><i class="ti-check"></i></span> icon to enable or disable.</li>
                                </ul>
                              </li>
                            </ol>

                              <hr>

                          </div>
                        </form>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow-3">
                        <div class="card-body">
                            <table class="table table-striped" id="tb_locs">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th><?= ucfirst($dtype) ?></th>
                                        <th>Properties</th>
                                        <th>Menu</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                      <td colspan="4">
                                        <form action="" method="post" id="dform">
                                          <input type="hidden" name="dtype" value="<?= $dtype ?>" />
                                          <input type="hidden" name="pid" value="<?= $parent_id ?>" />
                                          <input type="hidden" name="add_location" value="1" />
                                          <input type="text" name="dname" class="form-control" id="loc_name" placeholder="Add new <?= $dtype ?>">
                                        </form>
                                      </td>
                                      <td><button type="button" class="btn btn-outline-info btn-block add-loc" data-pid="<?= $type_info['par'] ?>" data-type="<?= $dtype ?>"><i class="ti-save"></i></button></td>
                                    </tr>
                                    <?php if(!empty($loc_list)){ foreach($loc_list as $kk=>$li){ ?>
                                    <tr>
                                        <td><?= $kk ?></td>
                                        <td id="<?= $kk ?>">
                                          <?php if($dtype != 'area'){ ?>
                                          <a href="property_locations.php?<?= $build_link.$kk ?>"><?= $li['name'] ?></a>
                                        <?php }else{ echo $li['name']; } ?></td>
                                        <td><?= $li['cnt'] ?></td>
                                        <td><?php if($li['featured'] == 1){ ?>
                                          <span class="is-featured yes" data-id="<?= $kk ?>" data-type="<?= $dtype ?>" data-toggle="tooltip" title="Remove from website nav menu"><i class="ti-check"></i></span>
                                        <?php }else{ ?>
                                          <span class="is-featured no" data-id="<?= $kk ?>" data-type="<?= $dtype ?>" data-toggle="tooltip" title="Add to website nav menu"><i class="ti-check"></i></span>
                                        <?php } ?></td>
                                        <td class="text-right table-actions">
                                            <a class="table-action hover-primary edit-kk" data-toggle="popover" title="Edit <?= $dtype ?> name" href="javascript:void(0)" data-name="<?= $li['name'] ?>" data-type="<?= $dtype ?>" data-id="<?= $kk ?>"><i class="ti-pencil"></i></a>
                                            <a class="table-action hover-danger delete-kk" href="#" data-cnt="<?= $li['cnt'] ?>" data-id="<?= $kk ?>"><i class="ti-trash"></i></a>
                                        </td>
                                    </tr>
                                  <?php } }else{ ?>
                                      <td colspan="4">
                                        <div class="no-res">
                                          <p>There are no associated <?= strtolower($page_desc) ?> in database. Please add a new <?= $dtype ?> to begin.</p>
                                        </div>
                                      </td>
                                  <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!--/.main-content -->


        <?php include 'assets/inc/footer.php'; ?>

    </main>
    <!-- END Main container -->


    <!-- DELETE MODAL -->
    <div class="modal modal-top fade" id="delete_prop" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Removal <span class="yes-cnt">/ Reassign <?= ucfirst($dtype) ?></span></h5>
                    <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="dd" value="0" id="dd">
                    <input type="hidden" name="dtype" id="ddtype" value="<?= $dtype ?>">

                    <div class="modal-body">
                      <div class="reassign-info">

                        <p>There are <b class="delete-cnt">0</b> properties related to this <?= $dtype ?>. Please assign a new <?= $dtype ?>:</p>
                        <div class="form-group">
                          <label>Select <?= $dtype ?> to reassign</label>
                          <select name="reas" class="form-control reas-select">
                            <option value="0" selected>-- select <?= $dtype ?> --</option>
                            <?php if(!empty($reassign)){ foreach($reassign as $kk=>$as){
                              echo '<option value="'.$kk.'">'.$as.'</option>';
                            } } ?>
                          </select>
                        </div>

                      </div>

                        <p>Are you sure you would like to delete this <?= $dtype ?>? This action cannot be undone.</p>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-bold btn-outline btn-danger">Confirm <span class="yes-cnt">&amp; Reassign</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="assets/js/core.min.js" data-provide="sweetalert"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="assets/js/edit-locations.js"></script>

</body>

</html>
