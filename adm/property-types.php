<?php
include 'base.php';

$show_alert = 0;
$note_head = '';
$note_txt = '';

$page_type = 5;

$properties = new Listings();

if(isset($_GET['dd']) && $_GET['dd'] > 0){

    // Delete property type
    new SqlIt("DELETE FROM property_types WHERE pr_type_id = ?","delete",array(strip_tags($_GET['dd'])));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The property type has been deleted successfully.';
}

if(isset($_POST['update_product'])){

    if(strlen($_POST['property_type']) > 3){

    }
    // Update property type
    new SqlIt("UPDATE property_types SET type_desc=?, type_desc_es=? WHERE pr_type_id = ?","update",array($_POST['property_type'],$_POST['property_type_es'],$_POST['property_id']));

    $show_alert = 'success';
    $note_head = 'Success';
    $note_txt = 'The property has been updated successfully.';
}

if(isset($_POST['add_product'])){

    // Add new property type
    new SqlIt("INSERT INTO property_types (type_desc,type_desc_es) VALUES (?,?)","insert",array($_POST['product_type'],$_POST['product_type_es']));

    $show_alert = 'success';
    $note_head = 'Success';
    $note_txt = 'The property has been added successfully.';
}

$properties = new Listings();
$properties->getPropertyTypes();


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
                <strong>Property Types</strong>
                <small class="pt-0 mb-4">These are the property types that are displayed in the search filters and applied to each individual listing. You can easily edit or delete those here. </small>
            </h1>

            <div class="row">
                <div class="col-6">
                    <div class="card shadow-3">
                        <h4 class="card-title">Add a new property type</h4>
                        <div class="card-body">
                            <form action="property-types.php" method="post">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Property Type <small>(ENGLISH)</small></label>
                                            <input type="text" class="form-control" name="product_type">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Property Type <small>(SPANISH)</small></label>
                                            <input type="text" class="form-control" name="product_type_es">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-12 text-right">
                                        <button type="submit" name="add_product" class="btn btn-info"><i class="ti-plus"></i> Add property type</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <?php foreach($properties->PropertyTypes as $kk=>$ll){ ?>
                            <div class="kk-edit" id="kk_<?= $kk ?>">
                                <form action="property-types.php" method="post">
                                   <input type="hidden" name="property_id" value="<?= $kk ?>">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Property Type <small>(ENGLISH)</small></label>
                                                <input type="text" name="property_type" class="form-control" value="<?= $ll['desc'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Property Type <small>(SPANISH)</small></label>
                                                <input type="text" name="property_type_es" class="form-control" value="<?= $ll['desc_es'] ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-12 text-right">
                                            <button type="submit" name="update_product" class="btn btn-info"><i class="ti-save"></i> Update property type</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow-3">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>English</th>
                                        <th>Español</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if(!empty($properties->PropertyTypes)){ foreach($properties->PropertyTypes as $kk=>$li){ ?>
                                    <tr>
                                        <td>
                                            <?= $kk ?>
                                        </td>
                                        <td>
                                            <?= $li['desc'] ?>
                                        </td>
                                        <td>
                                            <?= $li['desc_es'] ?>
                                        </td>
                                        <td class="text-right table-actions">
                                            <a class="table-action hover-primary edit-kk" href="#" data-id="<?= $kk ?>"><i class="ti-pencil"></i></a>
                                            <a class="table-action hover-danger delete-kk" href="#" data-id="<?= $kk ?>"><i class="ti-trash"></i></a>

                                        </td>
                                    </tr>
                                    <?php } } ?>
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
                    <h5 class="modal-title">Confirm property type removal</h5>
                    <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
                </div>
                <form action="property-types.php" method="get">
                    <input type="hidden" name="dd" value="0" id="dd">
                    <div class="modal-body">
                        <p><b>Are you sure you would like to permanently delete this property type?</b> You will not be able to recover this once deleted. Please confirm this action.</p>

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
    <script src="assets/js/edit-types.js"></script>

</body>

</html>
