<?php

$ppage = 5;

require('base.php');

?>



<!doctype html>

<html lang="en">

  <head>

    <base href="<?= $base_href ?>" >

    <!-- Required meta tags -->

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <title><?= $meta['contact']['title'] ?></title>

    <meta name="description" content="<?= $meta['about']['desc'] ?>" />

    <meta name="robots" content="index" />

    <link rel="canonical" href="https://kiinrealty.com/<?= $link_contact[$lang] ?>">





    <meta property="og:title" content="<?= $meta['contact']['title'] ?>">

    <meta property="og:description" content="<?= $meta['contact']['desc'] ?>">

    <meta property="og:image" content="https://kiinrealty.com/dist/img/social.jpg">

    <meta property="og:url" content="https://kiinrealty.com/<?= $link_contact[$lang] ?>">

    <meta name="twitter:card" content="summary_large_image">



    <meta property="og:site_name" content="Kiin Realty">

    <meta name="twitter:image:alt" content="<?= $meta['contact']['title'] ?>">



    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />







    <?php include 'dist/inc/favicon.php'; ?>

  </head>

  <body class="<?= $mobile_class ?> contact-page">



    <?php require('dist/inc/nav_top.php') ?>



    <div class="container-fluid list-bg">



      <?php require('dist/inc/nav.php') ?>



      <div class="container container-sm">

        <div class="row">



          <div class="col-sm-4 contact-side">

            <h1><?= $lan['con']['h1'] ?></h1>

            <p><?= $lan['con']['p'] ?></p>





            <?php if(!empty($social_mia)){ 
              
              foreach($social_mia as $ss){ 
                  $display = '';
                ?>

              <div class="con">

                <a href="<?= $ss['val'] ?>" target="_blank" rel="nofollow"><i class="fa fa-<?= $ss['icon'] ?>"></i>

                  <?php

                  $display = explode('/',$ss['val']);
                  if($ss['icon'] != 'youtube'){
                    echo end($display);
                  }else{
                    echo 'YouTube';
                  }

                  ?>

                </a>

              </div>

            <?php } }

            if(!empty($site_contact->ContactInfo['contact_page'])){

              foreach($site_contact->ContactInfo['contact_page'] as $cc){

                if($cc['type'] == 'email'){ ?>

                  <div class="con">

                    <a href="mailto:<?= $cc['val'] ?>"><i class="fa fa-envelope"></i> <?= $cc['val'] ?></a>

                  </div>

                <?php }elseif($cc['type'] == 'phone'){ ?>

                  <div class="con">

                    <i class="fa fa-phone"></i> <?= $cc['val'] ?>

                  </div>

                <?php }else{ ?>

                  <div class="con">

                    <i class="fa fa-link"></i> <?= $cc['val'] ?>

                  </div>

                <?php } } } ?>

          </div>



          <div class="col-sm-8">

            <div id="contact_form">

              <div class="success-message">

                <i class="fa fa-check"></i>

                <h5><?= $lan['con']['h5'] ?></h5>

                <p><?= $lan['con']['p2'] ?></p>

              </div>

              <form action="" method="post" id="con_form" class="needs-validation" novalidate>

                <div class="form-group" id="name">

                  <label><?= $lan['con']['form'][0] ?> <span class="text-danger">*</span></label>

                  <input type="text" name="name" class="form-control" placeholder="<?= $lan['con']['form'][0] ?>" required />

                </div>

                <div class="form-group" id="email">

                  <label><?= $lan['con']['form'][1] ?> <span class="text-danger">*</span></label>

                  <input type="email" name="email" class="form-control" placeholder="me@myemail.com" required />

                </div>

                <div class="form-group" id="message">

                  <label><?= $lan['con']['form'][2] ?> <span class="text-danger">*</span></label>

                  <textarea name="message" class="form-control" rows="5" required></textarea>

                </div>

                <div class="input-group mb-4">

                  <span class="input-group-text" id="captcha"></span>

                  <input type="text" class="form-control" placeholder="<?= $lan['con']['form'][3] ?>" required id="cpatchaTextBox"/>

                </div>

                <div class="form-group text-end">

                  <button type="submit" name="send_contact" class="btn btn-dark"><?= $lan['con']['form'][4] ?></button>

                </div>

              </form>

            </div>

          </div>



        </div>

      </div>







    </div>





    <?php require('dist/inc/foot.php') ?>







    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <script src="dist/js/contact.js"></script>





  </body>

</html>

