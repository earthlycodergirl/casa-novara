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
    
    <!-- Custom styles for enhanced contact info -->
    <style>
        .office-section-card {
            background: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 20px;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .office-section-card:hover {
            border-color: #5a5c69;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .office-section-card h5 {
            color: #5a5c69;
            font-weight: 600;
            margin-bottom: 0.5rem;
            padding-bottom: 10px;
            border-bottom: 2px solid #e3e6f0;
        }
        
        .office-section-card h5 i {
            margin-right: 8px;
            color: #858796;
        }
        
        .contact-wrap.added {
            animation: slideIn 0.5s ease-in-out;
            background-color: #d4edda;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-check-input:checked {
            background-color: #25d366;
            border-color: #25d366;
        }
        
        .badge-success {
            background-color: #25d366;
        }
        
        /* Contact display styling */
        .contact-display-item {
            background: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
        }
        
        .contact-display-item h6 {
            margin-bottom: 8px;
            color: #5a5c69;
            font-weight: 600;
        }
        
        .contact-display-item .badge {
            font-size: 0.7rem;
            margin-left: 8px;
        }
        
        .contact-display-item small {
            color: #6e707e;
            font-style: italic;
        }
        
        /* Button styling */
        .btn-success {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        
        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
        }
        
        .btn-warning {
            background-color: #f6c23e;
            border-color: #f6c23e;
            color: #3a3b45;
        }
        
        /* Form spacing */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .office-section-card .form-control {
            border-color: #d1d3e2;
        }
        
        .office-section-card .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        /* 2x2 Grid Layout for Office Cards */
        @media (min-width: 768px) {
            .office-section-card {
                min-height: 400px;
            }
        }
        
        /* Responsive spacing */
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        /* Equal height cards for grid layout */
        .card.h-100 {
            height: 100% !important;
        }
        
        .card.h-100 .card-body {
            display: flex;
            flex-direction: column;
        }
    </style>
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
              <?php $o=55; foreach($con->ContactInfo as $key=>$val){ 

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
                    $desc = 'These are the social media links displayed on the website.';
                    $type_form = 'select';
                    break;
                  case 'office_info':
                    $title = 'Office Information <small>(website)</small>';
                    $desc = 'Office contact details, availability, and location information.';
                    $type_form = 'office';
                    break;
                }
                
                // Determine column classes based on card type
                if($key == 'contact_page' || $key == 'contact_emails') {
                  $col_class = 'col-md-6'; // First row, side by side
                } elseif($key == 'social' || $key == 'request_emails') {
                  $col_class = 'col-md-6'; // Second row, side by side  
                } else {
                  $col_class = 'col-12'; // Office info full width
                }
                ?>

                <div class="<?= $col_class ?> mb-4">
                  <div class="card shadow-3 h-100">
                      <h4 class="card-title big"><?= $title ?></h4>
                      <div class="card-body">
                        <p><?= $desc ?></p>
                      
                      <?php if($type_form == 'office'){ ?>
                        <!-- OFFICE INFO - SEPARATE CARDS -->
                        <div class="row">
                          <!-- PHONE NUMBERS CARD -->
                          <div class="col-md-6 col-lg-6 mb-3">
                            <div class="office-section-card">
                              <h5><i class="ti-mobile"></i> Phone Numbers</h5>
                              <p class="text-muted small">Add office phone numbers with WhatsApp support</p>
                              
                              <div class="contact-wrap main-wrap" id="new_phone_<?= $key ?>">
                                <div class="row copy-me">
                                  <div class="col-12">
                                    <div class="form-group">
                                      <label>Phone Number</label>
                                      <input type="text" class="form-control" id="cval_phone_<?= $o ?>" name="c_value" placeholder="+52 322 123-4567">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Title</label>
                                      <input type="text" class="form-control" id="ctitle_phone_<?= $o ?>" name="c_title" placeholder="e.g., Main Office">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Description</label>
                                      <input type="text" class="form-control" id="cdesc_phone_<?= $o ?>" name="c_description" placeholder="e.g., Mon-Fri 9AM-6PM">
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="form-check mb-3">
                                      <input class="form-check-input" type="checkbox" id="is_whatsapp_phone_<?= $o ?>" name="is_whatsapp">
                                      <label class="form-check-label" for="is_whatsapp_phone_<?= $o ?>">
                                        <i class="ti-check"></i> WhatsApp Number
                                      </label>
                                    </div>
                                    <button type="button" class="btn btn-success add-contact" data-type="<?= $key ?>" data-contact-type="phone" data-id="phone_<?= $o ?>">
                                      <i class="ti-plus"></i> Add Phone Number
                                    </button>
                                  </div>
                                </div>
                                <div class="errors" id="err_phone_<?= $key ?>"></div>
                              </div>
                              
                              <hr class="mt-3 mb-3" />
                              <div class="contact-content" id="phone_<?= $key ?>">
                                <!-- Phone numbers will be displayed here -->
                              </div>
                            </div>
                          </div>
                          
                          <!-- AVAILABILITY CARD -->
                          <div class="col-md-6 col-lg-6 mb-3">
                            <div class="office-section-card">
                              <h5><i class="ti-time"></i> Office Hours</h5>
                              <p class="text-muted small">Set your office availability and hours</p>
                              
                              <div class="contact-wrap main-wrap" id="new_availability_<?= $key ?>">
                                <div class="row copy-me">
                                  <div class="col-12">
                                    <div class="form-group">
                                      <label>Hours</label>
                                      <input type="text" class="form-control" id="cval_availability_<?= $o ?>" name="c_value" placeholder="Monday-Friday 9:00 AM - 6:00 PM">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Title</label>
                                      <input type="text" class="form-control" id="ctitle_availability_<?= $o ?>" name="c_title" placeholder="e.g., Business Hours">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Description</label>
                                      <input type="text" class="form-control" id="cdesc_availability_<?= $o ?>" name="c_description" placeholder="e.g., Call for appointments">
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <button type="button" class="btn btn-info add-contact" data-type="<?= $key ?>" data-contact-type="availability" data-id="availability_<?= $o ?>">
                                      <i class="ti-plus"></i> Add Hours
                                    </button>
                                  </div>
                                </div>
                                <div class="errors" id="err_availability_<?= $key ?>"></div>
                              </div>
                              
                              <hr class="mt-3 mb-3" />
                              <div class="contact-content" id="availability_<?= $key ?>">
                                <!-- Availability will be displayed here -->
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <!-- LOCATION CARD -->
                          <div class="col-md-12 mb-3">
                            <div class="office-section-card">
                              <h5><i class="ti-location-pin"></i> Office Location</h5>
                              <p class="text-muted small">Add office address and coordinates</p>
                              
                              <div class="contact-wrap main-wrap" id="new_location_<?= $key ?>">
                                <div class="row copy-me">
                                  <div class="col-12">
                                    <div class="form-group">
                                      <label>Address</label>
                                      <textarea class="form-control" id="cval_location_<?= $o ?>" name="c_value" rows="2" placeholder="Full office address"></textarea>
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Title</label>
                                      <input type="text" class="form-control" id="ctitle_location_<?= $o ?>" name="c_title" placeholder="e.g., Main Office">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Description</label>
                                      <input type="text" class="form-control" id="cdesc_location_<?= $o ?>" name="c_description" placeholder="e.g., Ground floor">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Latitude <small>(optional)</small></label>
                                      <input type="text" class="form-control" id="clat_location_<?= $o ?>" name="c_latitude" placeholder="20.6534">
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label>Longitude <small>(optional)</small></label>
                                      <input type="text" class="form-control" id="clong_location_<?= $o ?>" name="c_longitude" placeholder="-105.2253">
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <button type="button" class="btn btn-warning add-contact" data-type="<?= $key ?>" data-contact-type="location" data-id="location_<?= $o ?>">
                                      <i class="ti-plus"></i> Add Location
                                    </button>
                                  </div>
                                </div>
                                <div class="errors" id="err_location_<?= $key ?>"></div>
                              </div>
                              
                              <hr class="mt-3 mb-3" />
                              <div class="contact-content" id="location_<?= $key ?>">
                                <!-- Location will be displayed here -->
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      <?php } else { ?>
                        <!-- Original form for other contact types -->
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

                              <?php } else { ?>
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
                          <?php if(!empty($con->ContactInfo[$key])){ 
                            // Original display for non-office contact types
                            foreach($con->ContactInfo[$key] as $kk=>$ll){
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
                      <?php } ?>
                    </div>
                </div>
                </div>

                <!-- JavaScript to load existing office info items -->
                <?php if($key == 'office_info' && !empty($con->ContactInfo[$key])) { 
                  foreach($con->ContactInfo[$key] as $kk=>$ll){
                    $sectionId = $ll['type'] . '_' . $key;
                    ?>
                    <script>
                    $(document).ready(function(){
                      setTimeout(function(){
                        var existingItem = `<div class="contact-display-item" id="wrap_<?= $ll['id'] ?>">
                          <div class="row align-items-center">
                            <div class="col-9">
                              <h6 class="mb-1">
                                <i class="ti-<?= $ll['type'] == 'phone' ? 'mobile' : ($ll['type'] == 'availability' ? 'time' : 'location-pin') ?>"></i> 
                                <?= !empty($ll['title']) ? htmlspecialchars($ll['title']) : ucfirst($ll['type']) ?>
                                <?php if($ll['is_whatsapp'] == 1 && $ll['type'] == 'phone') { ?>
                                  <span class="badge badge-success">WhatsApp</span>
                                <?php } ?>
                              </h6>
                              <div class="mb-1"><strong><?= htmlspecialchars($ll['val']) ?></strong></div>
                              <?php if(!empty($ll['description'])) { ?>
                                <small class="text-muted d-block"><?= htmlspecialchars($ll['description']) ?></small>
                              <?php } ?>
                              <?php if($ll['type'] == 'location' && !empty($ll['meta'])) { ?>
                                <?php if(!empty($ll['meta']['latitude']) || !empty($ll['meta']['longitude'])) { ?>
                                  <div class="row mt-1">
                                    <?php if(!empty($ll['meta']['latitude'])) { ?>
                                      <div class="col-6"><small class="text-muted">Lat: <?= htmlspecialchars($ll['meta']['latitude']) ?></small></div>
                                    <?php } ?>
                                    <?php if(!empty($ll['meta']['longitude'])) { ?>
                                      <div class="col-6"><small class="text-muted">Lng: <?= htmlspecialchars($ll['meta']['longitude']) ?></small></div>
                                    <?php } ?>
                                  </div>
                                <?php } ?>
                              <?php } ?>
                            </div>
                            <div class="col-3 text-right">
                              <button type="button" class="btn btn-outline-primary btn-sm update-contact" data-id="<?= $ll['id'] ?>" title="Edit">
                                <i class="ti-pencil"></i>
                              </button>
                              <button type="button" class="btn btn-outline-danger btn-sm del-contact" data-id="<?= $ll['id'] ?>" title="Delete">
                                <i class="ti-trash"></i>
                              </button>
                            </div>
                          </div>
                        </div>`;
                        $('#<?= $sectionId ?>').append(existingItem);
                      }, 100);
                    });
                    </script>
                <?php } } ?>

              <?php $o++; } ?>
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