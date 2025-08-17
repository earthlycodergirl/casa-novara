<?php
include 'base.php';
require_once('classes/site.class.php');

$show_alert = 0;
$note_head = '';
$note_txt = '';

$page_type = 17;

$con = new Site();

if(isset($_GET['dd'])){

    // Delete property type
    new SqlIt("DELETE FROM site_contact WHERE contact_id = ?","delete",array($_GET['dd']));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The contact information has been deleted successfully.';
}

if(isset($_POST['update_product'])){

    // Update property type
    new SqlIt("UPDATE site_contact SET price_type=? WHERE pr_type_id = ?","update",array($_POST['price_type'],$_POST['price_id']));

    $show_alert = 'success';
    $note_head = 'Success';
    $note_txt = 'The price id has been updated successfully.';
}

if(isset($_POST['add_product'])){

    // Add new property type
    new SqlIt("INSERT INTO property_pricing_types (price_type) VALUES (?)","insert",array($_POST['price_type']));

    $show_alert = 'success';
    $note_head = 'Success';
    $note_txt = 'The pricing type has been added successfully.';
}

$con->getContact();


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
                <strong>Administrative Contact Information</strong>
                <small class="pt-0 mb-4">This section dynamically sets the contact information both on the website and behind the scenes. Update any information below to update immediately.</small>
            </h1>

            <div class="row">
              <div class="col-6">
              <?php $o=55; foreach($con->ContactInfo as $key=>$val){ if($o == 56){ echo '</div><div class="col-6">'; }

                switch($key){
                  case 'contact_page':
                    $title = 'Contact Page <small>(website)</small>';
                    $desc = 'This is the contact information displayed to the public on the website.';
                    $type_form = 'select';
                    break;
                  case 'contact_emails':
                    $title = 'Contact Form Notification <small>(administrative)</small>';
                    $desc = 'These are the administrative emails that will be notified of contact form submission via the website.';
                    $type_form = 'email';
                    break;
                  case 'request_emails':
                    $title = 'Property Request Notification <small>(administrative)</small>';
                    $desc = 'These are the administrative emails that will be notified of a property information request.';
                    $type_form = 'email';
                    break;
                  case 'social':
                    $title = 'Social Media <small>(website)</small>';
                    $desc = 'These are the administrative emails that will be notified of contact form submission via the website.';
                    $type_form = 'select';
                    break;
                }
                ?>

                    <div class="card shadow-3">
                        <h4 class="card-title big"><?= $title ?></h4>
                        <div class="card-body">
                          <p><?= $desc ?></p>
                            <div class="contact-wrap main-wrap" id="new_<?= $key ?>">
                              <div class="row copy-me">
                                  <!-- CONTACT PAGE -->
                                  <?php if($type_form == 'select'){ ?>

                                      <div class="col-3 pr-0">
                                        <div class="form-group">
                                          <label>Type</label>
                                          <?php if($key == 'social'){ ?>
                                            <input type="hidden" name="c_type" id="ctype_<?= $o ?>" value="link">
                                            <select name="c_display" id="cdisplay_<?= $o ?>" class="form-control">
                                              <option value="facebook">Facebook</option>
                                              <option value="instagram">Instagram</option>
                                              <option value="youtube">YouTube</option>
                                              <option value="linkedin">LinkedIn</option>
                                              <option value="vimeo">Vimeo</option>
                                              <option value="twitter">Twitter</option>
                                              <option value="reddit">Reddit</option>
                                            </select>
                                          <?php }else{ ?>
                                          <input type="hidden" name="cdisplay" id="cdisplay_<?= $o ?>" value="" />
                                          <select name="c_type" id="ctype_<?= $o ?>" class="form-control">
                                            <option value="email">Email</option>
                                            <option value="phone">Phone</option>
                                            <option value="link">Link</option>
                                          </select>
                                          <?php } ?>
                                        </div>
                                      </div>

                                      <div class="col-7 pr-0">
                                          <div class="form-group">
                                              <label>Value</label>
                                              <input type="text" class="form-control" id="cval_<?= $o ?>" name="c_value">
                                          </div>
                                      </div>
                                      <div class="col-2">
                                          <button type="button" class="btn btn-info add-contact" data-type="<?= $key ?>" data-id="<?= $o ?>" style="margin-top: 27px;"><i class="ti-plus"></i></button>
                                      </div>

                                  <?php }else{ ?>
                                    <div class="col-8 mb-2">
                                      <input type="hidden" name="c_display" id="cdisplay_<?= $o ?>" value="">
                                      <input type="hidden" name="c_type" id="ctype_<?= $o ?>" value="<?= $type_form ?>" />
                                      <div class="input-group copy-me">
                                        <span class="input-group-addon"><i class="ti-<?= $type_form ?>"></i></span>
                                        <input type="text" name="c_value" id="cval_<?= $o ?>" class="form-control" />
                                      </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-info add-contact" data-type="<?= $key ?>" data-id="<?= $o ?>"><i class="ti-plus"></i></button>
                                    </div>
                                  <?php } ?>
                                    <!-- CONTACT PAGE END -->



                                </div>
                                <div class="errors" id="err_<?= $key ?>"></div>
                              </div>

                              <hr class="mt-2 mb-2" />

                            <div class="contact-content" id="<?= $key ?>">

                            <?php if(!empty($con->ContactInfo[$key])){ foreach($con->ContactInfo[$key] as $kk=>$ll){
                              if($ll['type'] == 'social'){
                                $icon = 'email';
                              }elseif($ll['type'] == 'phone'){
                                $icon = 'mobile';
                              }else{
                                $icon = $ll['type'];
                              }
                              ?>

                              <div class="contact-wrap" id="wrap_<?= $ll['id'] ?>">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="ti-<?= $icon ?>"></i></span>
                                  <input type="text" class="form-control" name="c_value" id="cval_<?= $ll['id'] ?>" value="<?= $ll['val'] ?>">
                                  <button type="button" class="btn btn-info-outline update-contact" data-id="<?= $ll['id'] ?>"><i class="ti-save"></i></button>
                                  <button type="button" class="btn btn-danger-outline del-contact" data-id="<?= $ll['id'] ?>"><i class="ti-trash"></i></button>
                                </div>
                              </div>
                          <?php } } ?>
                        </div>
                    </div>
                </div>



          <?php  $o++; } ?>
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
                    <h5 class="modal-title">Confirm contact information removal</h5>
                    <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
                </div>
                <form action="contact_info.php" method="get">
                    <input type="hidden" name="dd" value="0" id="dd">
                    <div class="modal-body">
                        <p><b>Are you sure you would like to permanently delete this contact information?</b> You will not be able to recover this once deleted. Please confirm this action.</p>

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
    <script src="assets/js/contact-info.js"></script>

</body>

</html>
