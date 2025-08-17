<?php
include 'base.php';
$ltype = 'all';
$message = '';
$success = 0;
$mess = '';
$show_alert = 0;
$note_head = '';
$note_txt = '';
$page_type = 8;
if(isset($_GET['dd']) && $_GET['dd'] > 0){
  new SqlIt("DELETE FROM users WHERE uid = ?","delete",array($_GET['dd']));
  new SqlIt("DELETE FROM permissions_users WHERE user_id = ?","delete",array($_GET['dd']));
  $show_alert = 'success';
  $note_head = 'Deleted';
  $note_txt = 'The user has been deleted successfully.';
}

if(isset($_GET['deactivate']) && $_GET['deactivate'] > 0){
  $de = $_GET['deactivate'];
  new SqlIt("UPDATE users SET active = ? WHERE uid = ?","update",array(0,$de));
  $mess = '<div class="alert alert-warning bordered">El usuario ha sido <b>desactivado</b> con exito.<button class="close" data-dismiss="alert"></button></div>';
  $show_alert = 'success';
  $note_head = 'Deactivated';
  $note_txt = 'The user has been deactivated successfully. They will no longer be able to access their account.';
}

if(isset($_GET['activate']) && $_GET['activate'] > 0){
  $de = $_GET['activate'];
  new SqlIt("UPDATE users SET active = ? WHERE uid = ?","update",array(1,$de));
  $mess = '<div class="alert alert-success bordered">El usuario ha sido <b>activado</b> con exito.<button class="close" data-dismiss="alert"></button></div>';
  $show_alert = 'success';
  $note_head = 'Activated';
  $note_txt = 'The user has been activated successfully. They are now able to login to their account.';
}

if(isset($_POST['add-user'])){
      new SqlIt("INSERT INTO users (username,password,name, email, position,active) VALUES (?,?,?,?,?,?)","insert",array($_POST['username'],md5($_POST['password']),$_POST['name'],$_POST['email'],$_POST['position'],$_POST['active']));
    $getId = new SqlIt("SELECT uid FROM users ORDER BY uid DESC LIMIT 1","select",array());
    $id = $getId->Response[0]->uid;
    if(isset($_POST['perm'])){
        foreach($_POST['perm'] as $perm){
            new SqlIt("INSERT INTO permissions_users (user_id,pid) VALUES (?,?)","insert",array($id,$perm));
        }
    }
    $show_alert = 'success';
    $note_head = 'Added';
    $note_txt = 'The user has been added successfully.';
}
if(isset($_POST['edit-user'])){
      new SqlIt("UPDATE users SET username=?,name=?, email=?, position=?,active=? WHERE uid = ?","update",array($_POST['username'],$_POST['name'],$_POST['email'],$_POST['position'],$_POST['active'],$_POST['user_id']));
    if($_POST['password'] != ''){
        new SqlIt("UPDATE users SET password = ? WHERE uid=?","update",array(md5($_POST['password']),$_POST['user_id']));
    }
    $show_alert = 'success';
    $note_head = 'Updated';
    $note_txt = 'The user has been updated successfully.';
}
$getUsers = new SqlIt("SELECT * FROM users ORDER BY name","select",array());
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="utf-8" />
        <title><?= $site_title ?></title>
        <meta name="robots" content="noindex" />
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">
        <!-- Plugins -->
        <link href="assets/plugin/select2/dist/css/select2.min.css" rel="stylesheet" />
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
            <a class="btn btn-outline btn-info" href="javascript:void(0);" data-toggle="modal" data-target="#addUser"><i class="ti-plus"></i> Add a new user</a>
          </div>
        </div>
      </header>
      <!-- Main container -->
      <main class="main-container">
        <div class="main-content">
          <h1 class="header-title">
              <strong>Administrative Users</strong>
              <small class="pt-0 mb-4">This is the list of users currently registered in your system. They all have access to the listings as well as the website elements.</small>
          </h1>
          <div class="card shadow-3">
              <div class="card-body">
                  <table class="table table-striped" data-provide="datatables">
                      <thead class="thead-light">
                          <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Active</th>
                            <th>Actions</th>
                          </tr>
                      </thead>
                      <tfoot class="thead-light">
                          <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Active</th>
                            <th>Actions</th>
                          </tr>
                      </tfoot>
                      <tbody>
                      <?php foreach($getUsers->Response as $user){ ?>
                          <tr>
                              <td><?= $user->username ?></td>
                              <td><?= $user->name ?></td>
                              <td><?= $user->email ?></td>
                              <td><?= $user->position ?></td>
                              <td>
                                  <?php if($user->active == 1){ ?>
                                      <i class="fa fa-check text-success"></i>
                                      <a href="users/deactivate/<?= $user->uid ?>" class="label text-success">Deactivate</a>
                                      <?php }else{ ?>
                                      <i class="fa fa-ban text-danger"></i>
                                      <a href="users/activate/<?= $user->uid ?>" class="label text-danger">Activate</a>
                                  <?php } ?>
                              </td>
                              <td class="text-right table-actions">
                                  <a data-toggle="modal" data-target="#editU<?= $user->uid ?>" class="table-action hover-primary" data-provide="tooltip" title="Edit user" href="#"><i class="ti-pencil"> </i></a>
                                  <a class="table-action hover-danger delete-entry" data-provide="tooltip" title="Delete user" href="#" data-id="<?= $user->uid ?>"><i class="ti-trash"></i></a>
                              </td>
                          </tr>
                          <?php } ?>
                  </tbody>
              </table>
          </div>
        </div>
    </div>
    <!-- END PAGE CONTENT WRAPPER -->
  </div>
  <!-- END PAGE CONTAINER -->
  <?php include 'assets/inc/footer.php'; ?>
 </main>
 <!-- DELETE MODAL -->
 <div class="modal modal-top fade" id="delete_entry" tabindex="-1" aria-hidden="true" style="display: none;">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title">Confirm user removal</h5>
         <button type="button" class="close" data-dismiss="modal">
           <span aria-hidden="true">×</span>
         </button>
       </div>
       <form action="users.php" method="get">
           <input type="hidden" name="dd" value="0" id="dd">
       <div class="modal-body">
           <p><b>Are you sure you would like to permanently delete this user?</b> You will not be able to recover it once this is done. Please confirm your decision.</p>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel removal</button>
         <button type="submit" class="btn btn-bold btn-outline btn-danger">Confirm removal</button>
       </div>
         </form>
     </div>
   </div>
 </div>
<div class="modal fade modal-top" id="addUser" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add user</h5>
              <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body m-t-20">
                <?php include 'assets/inc/process/user.php'; ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<?php foreach($getUsers->Response as $user){ ?>
  <div class="modal fade fill-in editc" id="editU<?php echo $user->uid ?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title">Edit <span class="semi-bold">User</span></h3>
            </div>
            <div class="modal-body m-t-20">
              <?php include 'assets/inc/process/user_edit.php'; ?>
            </div>
        </div>
      </div>
  </div>
  <?php } ?>
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
   <script src="assets/js/core.min.js"  data-provide="sweetalert"></script>
   <script src="assets/js/app.min.js"></script>
   <script src="assets/plugin/select2/dist/js/select2.full.min.js"></script>
   <script src="assets/plugin/jquery-mask/jquery.mask.min.js"></script>
   <script src="assets/js/script.min.js"></script>
   <script src="assets/js/testimonials.js"></script>
        <!-- END CORE TEMPLATE JS -->
        <!-- BEGIN PAGE LEVEL JS -->
        <script>
            $(document).ready(function() {
                //Date change
                $('.applyBtn').on('click', function() {
                    $("#loading_modal").modal('show');
                    setTimeout(function() {
                        $("#dateSearch").submit();
                    }, 1000);
                });
                $('.editc').on('shown.bs.modal', function(e) {
                    $("#phone").mask("(999) 999-9999");
                });
                $('#addClient').on('shown.bs.modal', function(e) {
                    $("#phone").mask("(999) 999-9999");
                });
            });
            <?php if(isset($_GET['open'])){ ?>
            $("#addUser").modal('show');
            <?php } ?>
        </script>
        <!-- END PAGE LEVEL JS -->
    </body>
    </html>
