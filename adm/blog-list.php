<?php
include 'base.php';

$page_type = 4;

$show_alert = 0;
$note_head = '';
$note_txt = '';


if(isset($_GET['dd'])){
    //delete listing
    new SqlIt("DELETE FROM site_blog WHERE blog_id = ?","delete",array($_GET['dd']));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The article has been deleted successfully.';
}

$getEntries = new SqlIt("SELECT * FROM site_blog ORDER BY blog_id DESC","select",array());


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
          <a class="btn btn-outline btn-info" href="blog-entry.php"><i class="ti-plus"></i> Add a new blog entry</a>
        </div>
      </div>
    </header>

    <!-- Main container -->
    <main class="main-container">
      <div class="main-content">


        <h1 class="header-title">
            <strong>Current Articles</strong>
            <small class="pt-0 mb-4">Here you will find a list of all the articles available online through your personal blog. Click on any below to view or edit. </small>
        </h1>

        <div class="card shadow-3">
            <div class="card-body">
                <table class="table table-striped" data-provide="datatables">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Lang</th>
                            <th>Title</th>
                            <th>Posted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Lang</th>
                            <th>Title</th>
                            <th>Posted</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if($getEntries->NumResults > 0){ foreach($getEntries->Response as $li){ ?>
                        <tr>
                            <td><?= $li->blog_id ?></td>
                            <td><?= $li->lang ?></td>
                            <td><?= $li->title ?></td>
                            <td><?= date('d/M/Y',strtotime($li->posted)) ?></td>
                            <td class="text-right table-actions">
                                <a class="table-action hover-primary" data-provide="tooltip" title="Edit blog entry" href="blog-entry.php?bid=<?= $li->blog_id ?>"><i class="ti-pencil"></i></a>
                                <a class="table-action hover-danger delete-entry" data-provide="tooltip" title="Delete blog entry" href="#" data-id="<?= $li->blog_id ?>"><i class="ti-trash"></i></a>
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
            <h5 class="modal-title">Confirm article removal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="blog-list.php" method="get">
              <input type="hidden" name="dd" value="0" id="dd">
          <div class="modal-body">
              <p><b>Are you sure you would like to permanently delete this blog entry?</b> You will not be able to recover it once this is done. Please confirm your decision.</p>

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
    <script src="assets/js/blog.js"></script>

  </body>
</html>
