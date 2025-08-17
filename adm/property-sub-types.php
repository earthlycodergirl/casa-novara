<?php
include 'base.php';

$show_alert = 0;
$note_head = '';
$note_txt = '';

$page_type = 10;
$parent_id = 0;


if(isset($_GET['dd'])){

    // Get parent property type for display
    $getParent = new SqlIt("SELECT pr_type_id FROM property_types_sub WHERE sub_id = ?","select",array($_GET['dd']));
    $parent_id = $getParent->Response[0]->pr_type_id;

    // Delete property type
    new SqlIt("DELETE FROM property_types_sub WHERE sub_id = ?","delete",array($_GET['dd']));

    $show_alert = 'success';
    $note_head = 'Deleted';
    $note_txt = 'The property sub type has been deleted successfully.';
}

if(isset($_POST['update_product'])){

    // Get parent property type for display
    $getParent = new SqlIt("SELECT pr_type_id FROM property_types_sub WHERE sub_id = ?","select",array($_POST['property_id']));
    $parent_id = $getParent->Response[0]->pr_type_id;

    // Update property type
    new SqlIt("UPDATE property_types_sub SET sub_desc=?,sub_desc_es=? WHERE sub_id = ?","update",array($_POST['property_type'],$_POST['property_type_es'],$_POST['property_id']));

    $show_alert = 'success';
    $note_head = 'Success';
    $note_txt = 'The property has been updated successfully.';
}

if(isset($_POST['add_product'])){

    // Add new property type
    new SqlIt("INSERT INTO property_types_sub (sub_desc,sub_desc_es,pr_type_id) VALUES (?,?,?)","insert",array($_POST['product_type'],$_POST['product_type_es'],$_POST['type_id']));

    $show_alert = 'success';
    $note_head = 'Success';
    $note_txt = 'The property has been added successfully.';

    $parent_id = $_POST['type_id'];
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
                <strong>Property Sub Types</strong>
                <small class="pt-0 mb-4">These are the property sub types that are associated with property types and displayed in the search filters applied to each individual listing. You can easily edit or delete those here. </small>
            </h1>

            <div class="row">
                <div class="col-6">
                    <div class="card shadow-3">
                        <h4 class="card-title">Select Parent Property Type</h4>
                        <div class="card-body">
                            <form action="property-sub-types.php" method="post">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                           <label>Select parent property type to add, edit and delete sub types</label>
                                           <select name="type_id" class="form-control" id="par_type">
                                               <option value="0" selected> -- Select property type --</option>
                                               <?php foreach($properties->PropertyTypes as $kk=>$ll){ if($parent_id == $kk){ $sel = 'selected'; }else{ $sel = ''; } ?>
                                               <option <?= $sel ?> value="<?= $kk ?>"><?= $ll['desc'] ?></option>
                                               <?php } ?>
                                           </select>
                                       </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Property Sub Type <small>(ENGLISH)</small></label>
                                            <input type="text" class="form-control" name="product_type">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Property Sub Type <small>(SPANISH)</small></label>
                                            <input type="text" class="form-control" name="product_type_es">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-12 text-right">
                                        <button type="submit" name="add_product" class="btn btn-info"><i class="ti-plus"></i> Add property sub type</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <?php foreach($properties->PropertyTypes as $kk=>$ll){ if(!empty($ll['subs'])){ foreach($ll['subs'] as $xx=>$yy){ ?>
                            <div class="kk-edit" id="kk_<?= $xx ?>">
                                <form action="property-sub-types.php" method="post">
                                   <input type="hidden" name="property_id" value="<?= $xx ?>">

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Property Sub Type <small>(ENGLISH)</small></label>
                                                <input type="text" name="property_type" class="form-control" value="<?= $yy ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Property Sub Type <small>(SPANISH)</small></label>
                                                <input type="text" name="property_type_es" class="form-control" value="<?= $ll['subs_es'][$xx] ?>">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-12 text-right">
                                            <button type="submit" name="update_product" class="btn btn-info"><i class="ti-save"></i> Update property sub type</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php } } } ?>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow-3">
                        <div class="card-body">
                            <?php if(!empty($properties->PropertyTypes)){ foreach($properties->PropertyTypes as $kk=>$li){ ?>
                                <div class="sub-type-table <?php if($parent_id == $kk){ echo 'show-me'; } ?>" id="parent_<?= $kk ?>">
                                    <p>You are viewing Sub Types of <b><?= $li['desc'] ?></b></p>
                                    <table class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>English</th>
                                            <th>Spanish</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if(!empty($li['subs'])){ foreach($li['subs'] as $xx=>$yy){ ?>
                                        <tr>
                                            <td>
                                                <?= $xx ?>
                                            </td>
                                            <td>
                                                <?= $yy ?>
                                            </td>
                                            <td><?= utf8_encode($li['subs_es'][$xx]) ?></td>
                                            <td class="text-right table-actions">
                                                <a class="table-action hover-primary edit-kk" href="#" data-id="<?= $xx ?>"><i class="ti-pencil"></i></a>
                                                <a class="table-action hover-danger delete-kk" href="#" data-id="<?= $xx ?>"><i class="ti-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php } }else{ ?>
                                        <tr>
                                            <td colspan="3">
                                                There are no subtypes registered to this property type
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } } if($parent_id == 0){ ?>
                            <div class="row starter">
                                <div class="col-12"><br>
                                    <p class="lead text-center">Please select a property type on the left to view all sub types associated.</p>
                                </div>
                            </div>
                          <?php } ?>
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
                    <h5 class="modal-title">Confirm sub type removal</h5>
                    <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
                </div>
                <form action="property-sub-types.php" method="get">
                    <input type="hidden" name="dd" value="0" id="dd">
                    <div class="modal-body">
                        <p><b>Are you sure you would like to permanently delete this property sub type?</b> You will not be able to recover this once deleted. Please confirm this action.</p>

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

    <script>
    app.ready(function(){
        $("#par_type").on('change',function(){
            console.log($(this).val());
            $('.sub-type-table').hide();
            $('.starter').hide();
            $('#parent_'+$(this).val()).slideDown();
        });
    });
    </script>

</body>

</html>
