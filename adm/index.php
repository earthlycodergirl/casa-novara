<?php
$firstpage = 1;
require('base.php');
require('assets/inc/quotes.php');
if(isset($_GET['timeout'])){
    $message = '<div class="alert alert-warning text-center"><b>Your session has expired.</b><br>Please login again.</div>';
}
if(isset($_GET['nosess'])){
    $message = '<div class="alert alert-warning text-center"><b>You are not logged in.</b><br>Please login to access this section.</div>';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <base href="<?= $base_href ?>">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>KIIN Realty - ADM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">
    <!-- Styles -->
    <link href="assets/css/core.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/mia.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    <?php
    if(isset($_POST['login'])){
      $login = new Login();
      $login->logIn($_POST['user'],$_POST['pass']);
      $message = $login->Message;
      if($login->Login == 1){
 ?>
        <script>
        setTimeout(function(){document.location.href = "<?= $login->Redirect ?>"},500);
        </script>
      <?php
      }
    }
    ?>
    <!--[if lte IE 9]>
    <link href="pages/css/ie9.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script type="text/javascript">
        window.onload = function() {
            // fix for windows 8
            if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="assets/css/windows.chrome.fix.css" />'
        }
    </script>
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    <link rel="icon" href="assets/img/favicon.png">
  </head>
  <body>
      <!-- START PAGE-CONTAINER -->
      <div class="login-wrapper ">
          <!-- START Login Background Pic Wrapper-->
          <div class="bg-pic">
             <!-- START Background Pic-->
             <img src="assets/img/login-bg.jpg" alt="kiin realty" class="lazy">
             <!-- END Background Pic-->
             <!-- START Background Caption-->
             <div class="bg-caption pull-bottom sm-pull-bottom text-white p-l-20 m-b-20">
                  <h2 class="semi-bold text-white">KIIN REAL ESTATE CMS.</h2>
                  <p>You have reached a restricted area of KIIN REALTY © <?= date('Y') ?>.<br /> Please return to the main website <a href="https://kiinrealty.com">by clicking here</a>.
                  </p>
             </div>
             <!-- END Background Caption-->
          </div>
          <!-- END Login Background Pic Wrapper-->
          <!-- START Login Right Container-->
          <div class="login-container bg-white smaller">
             <div class="p-l-50 m-l-20 p-r-50 m-r-20 p-t-50 m-t-30 sm-p-l-15 sm-p-r-15 sm-p-t-40">
                     <img src="assets/img/logo-black.png" alt="logo" data-src="assets/img/logo-black.png" data-src-retina="assets/img/logo_2x.png" width="150" height="21">
                     <p class="p-t-35">Login to access KIIN listing administration</p>
                     <?= $message ?>
                     <!-- START Login Form -->
                     <form id="form-login" class="p-t-5" role="form" action="" method="post">
                          <!-- START Form Control-->
                          <div class="form-group form-group-default">
                              <label>Username</label>
                              <div class="controls">
                                  <input type="text" name="user" placeholder="username" class="form-control" required>
                              </div>
                          </div>
                          <!-- END Form Control-->
                          <!-- START Form Control-->
                          <div class="form-group form-group-default">
                              <label>Password</label>
                              <div class="controls">
                                  <input type="password" class="form-control" name="pass" placeholder="password" required>
                              </div>
                          </div>
                          <!-- START Form Control-->
                          <div class="row">
                              <div class="col-md-6 no-padding">
                                  <div class="checkbox ">
                                      <input type="checkbox" value="1" id="checkbox1">
                                      <label for="checkbox1">Stay signed in</label>
                                  </div>
                              </div>
                              <div class="col-md-6 text-right">
                                  <a href="mailto:info@grfreedom.com" class="help-txt small">Need some help?<span>Contact technical support</span></a>
                              </div>
                          </div>
                          <!-- END Form Control-->
                          <button class="btn btn-primary btn-cons m-t-10" name="login" type="submit">Login</button>
                     </form>
                     <!--END Login Form-->
                     <div class="pull-bottom sm-pull-bottom">
                        <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                           <div class="quote">
                              <div class="small-quoted">Small doses of inspiration</div>
                              <div class="quoted"><?= $quoted ?></div>
                              <div class="quoter"><?= $quoter ?></div>
                           </div>
                        </div>
                          <div class="m-b-30 p-r-80 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                              <div class="col-sm-12 no-padding m-t-10">
                                  <p><small> Designed &amp; developed with <i class="fa fa-heart-o"></i> by <a href="https://coders.design" class="text-info">Coders.Design</a></small>
                                  </p>
                              </div>
                          </div>
                     </div>
             </div>
          </div>
          <!-- END Login Right Container-->
      </div>
    <!-- END PAGE CONTAINER -->
    <!-- BEGIN VENDOR JS -->
    <!-- Scripts -->
    <script src="assets/js/core.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="assets/plugin/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script>
        $(function() {
            $('#form-login').validate()
        })
    </script>
  </body>
</html>
