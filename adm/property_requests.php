<?php
include 'base.php';

$page_type = 3;

$show_alert = 0;
$note_head = '';
$note_txt = '';



if(isset($_GET['dd'])){
    //delete listing
    new SqlIt("DELETE FROM property_requests WHERE request_id = ?","delete",array($_GET['dd']));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The request has been deleted successfully.';
}

$reqs = new SqlIt("SELECT e.*, p.property_title FROM property_requests as e LEFT JOIN property_list as p ON e.property_id = p.property_id ORDER BY request_id DESC","select",array());

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

      </div>
    </header>

    <!-- Main container -->
    <main class="main-container">
      <div class="main-content">


        <h1 class="header-title">
            <strong>Property Requests</strong>
            <small class="pt-0 mb-4">Here is a list of all requests received through the website. Click on any request to contact or view property.</small>
        </h1>

        <div class="card shadow-3">
            <div class="card-body">
                <table class="table table-striped" data-provide="datatables">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Property</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Notes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot class="thead-light">
                        <tr>
                          <th>ID</th>
                          <th>Date</th>
                          <th>Property</th>
                          <th>Client</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Notes</th>
                          <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if($reqs->NumResults > 0){ foreach($reqs->Response as $li){ ?>
                        <tr>
                            <td><?= $li->request_id ?></td>
                            <td><?= date('d/M/y g:i a',strtotime($li->request_timestamp)) ?></td>
                            <td><a href="<?= $listing_url.$li->property_id ?>" target="_blank"><?= $li->property_title ?></a></td>
                            <td><?= $li->testimonial ?></td>
                            <td><?= $li->test_user ?></td>
                            <td><?php if(date('Y',strtotime($li->test_date)) > 2013){ echo date('d/M/y',strtotime($li->test_date)); }else{ echo 'NA'; } ?></td>
                            <td class="text-right table-actions">
                                <a class="table-action hover-primary" data-provide="tooltip" title="Edit testimonial" href="#" data-toggle="modal" data-target="#edit_test_<?= $li->test_id ?>"><i class="ti-pencil"></i></a>
                                <a class="table-action hover-danger delete-entry" data-provide="tooltip" title="Delete testimonial" href="#" data-id="<?= $li->test_id ?>"><i class="ti-trash"></i></a>
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
    <div class="modal modal-top fade" id="delete_entry" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm testimonial removal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="testimonials.php" method="get">
              <input type="hidden" name="dd" value="0" id="dd">
          <div class="modal-body">
              <p><b>Are you sure you would like to permanently delete this testimonial?</b> You will not be able to recover it once this is done. Please confirm your decision.</p>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel removal</button>
            <button type="submit" class="btn btn-bold btn-outline btn-danger">Confirm removal</button>
          </div>
            </form>
        </div>
      </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal modal-top fade" id="add_test" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add new testimonial</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="testimonials.php" method="post">
          <div class="modal-body">
              <p>Here you can easily add a new testimonial to your website. By default the latest testimonials are added to the inner pages testimonial carousel. We highly recommend adding a name and date of the testimonial to boost credibility.</p>

              <div class="row">
                  <div class="col-7">
                      <div class="form-group">
                          <label>Client name</label>
                          <input type="text" name="test_name" class="form-control" placeholder="John Doe">
                      </div>
                  </div>
                  <div class="col-5">
                      <div class="form-group">
                          <label>Posted date</label>
                          <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                              <input type="text" name="test_data" data-provide="datepicker" class="form-control" placeholder="mm/dd/yyyy">
                          </div>
                      </div>
                  </div>
              </div>

              <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                          <label>Testimonial</label>
                          <textarea name="testimonial" class="form-control" rows="7"></textarea>
                      </div>
                  </div>
              </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="add_testimonial" class="btn btn-bold btn-outline btn-info">Save testimonial</button>
          </div>
        </form>
        </div>
      </div>
    </div>


    <?php if($getEntries->NumResults > 0){ foreach($getEntries->Response as $li){ ?>
    <!-- EDIT MODAL -->
    <div class="modal modal-top fade" id="edit_test_<?= $li->test_id ?>" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit testimonial</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="testimonials.php" method="post">
              <input type="hidden" name="test_id" value="<?= $li->test_id ?>">
              <div class="modal-body">
                  <p>Here you can easily add a new testimonial to your website. By default the latest testimonials are added to the inner pages testimonial carousel. We highly recommend adding a name and date of the testimonial to boost credibility.</p>

                  <div class="row">
                      <div class="col-7">
                          <div class="form-group">
                              <label>Client name</label>
                              <input type="text" name="test_name" class="form-control" placeholder="John Doe" value="<?= $li->test_user ?>">
                          </div>
                      </div>
                      <div class="col-5">
                          <div class="form-group">
                              <label>Posted date</label>
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                  <input type="text" name="test_data" data-provide="datepicker" class="form-control" placeholder="mm/dd/yyyy" value="<?php if(date('Y',strtotime($li->test_date)) > 2013){ echo date('m/d/Y',strtotime($li->test_date)); } ?>">
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-12">
                          <div class="form-group">
                              <label>Testimonial</label>
                              <textarea name="testimonial" class="form-control" rows="7"><?= $li->testimonial ?></textarea>
                          </div>
                      </div>
                  </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="update_testimonial" class="btn btn-bold btn-outline btn-info">Save testimonial</button>
              </div>
            </form>
        </div>
      </div>
    </div>
    <?php } } ?>


    <!-- Scripts -->
    <script src="assets/js/core.min.js" data-provide="sweetalert"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="assets/js/testimonials.js"></script>

  </body>
</html>
