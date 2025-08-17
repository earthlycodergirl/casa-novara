<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(1);
session_start();
/*if(isset($_POST['username'])){
   if($_POST['username'] != 'budd_adm' && $_POST['password'] != 'BudRel$$2018'){
      header('location: index.php?fail=1');
   }else{
      $_SESSION['logged'] = 1;
   }
}
if(!isset($_SESSION['logged'])){
    header('location: index.php');
}*/
// Basic definitions for all pages
$site_title = 'Kiin Realty - Admin';
$homepage_url = 'dashboard.php';
$lang = 'en';
$apage = 0;
$uploads_folder = 'uploads/';
$base_href = '/adm/';
$message = '';
$listings_url = 'https://kiinrealty.com/listing/';
// Classes to include accross all files
include 'classes/sql.class.php';
include 'classes/users.class.php';
include 'classes/listings.class.php';
include 'classes/login.class.php';

function print_me($array){
    echo '<pre>';
    print_r($array);
    echo "</pre>";
}
/******* --- LOGIN VALIDATION --- ******** */
$user = new User('get',$_SESSION['user']);
if($user->Error == 1 && !isset($firstpage)){
    // redirect to login page
    ?>
    <script>
        window.location.replace("<?= $user->RedirectUrl ?>");
    </script>
    <?php
    header('location: '.$user->Return);
}elseif(!isset($firstpage)){
    // reset the session so user does not get logged out
    $_SESSION['user'] = $user->UserId;
}
/******* --- END OF VALIDATION --- ********/
?>
