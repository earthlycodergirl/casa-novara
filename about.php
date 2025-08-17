<?php
require('base.php');
$nav_class = $logo_type = 'dark';
$current_link = $link_about;

?>

<!doctype html>
<html lang="en">
  <head>
     <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $meta['about']['title'] ?></title>
    <meta name="description" content="<?= $meta['about']['desc'] ?>" />
    <meta name="robots" content="index" />
    <link rel="canonical" href="https://miarealty.com.mx/<?= $link_about[$lang] ?>">


    <meta property="og:title" content="<?= $meta['about']['title'] ?>">
    <meta property="og:description" content="<?= $meta['about']['desc'] ?>">
    <meta property="og:image" content="https://miarealty.com.mx/dist/img/social.jpg">
    <meta property="og:url" content="https://miarealty.com.mx/<?= $link_about[$lang] ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="MIA Realty">
    <meta name="twitter:image:alt" content="<?= $meta['about']['title'] ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />


    <?php include 'dist/inc/favicon.php'; ?>
  </head>
  <body class="<?= $mobile_class ?> about-page">
    <?php require('dist/inc/nav_top.php') ?>
    <div class="inner-head dark">
      <?php require('dist/inc/nav.php') ?>
    </div>


    <section class="about-head px-2">
      <div class="container">
        <div class="row">
          <div class="col-sm-5">
            <h1><?= $lan['ab']['h1'] ?></h1>
            <h2><?= $lan['ab']['h2'] ?></h2>
            <p><?= $lan['ab']['p'] ?></p>
            <a href="<?= $link_home[$lang] ?>"><?= $lan['ab']['btn'] ?></a>
          </div>
          <div class="col-sm-7 ps-4">
            <img src="dist/img/about-head.png" alt="Mia Realty" class="img-fluid" />
          </div>
        </div>

      </div>
    </section>


    <section class="about-middle px-2">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 pe-4">
            <img src="dist/img/about-middle.png" alt="Mia Realty - About us" class="img-fluid" />
          </div>
          <div class="col-sm-6">
            <h3><?= $lan['ab']['h3'] ?></h3>
            <h4><?= $lan['ab']['h4'] ?></h4>
            <p><?= $lan['ab']['p2'] ?></p>
            <p><?= $lan['ab']['p3'] ?></p>
          </div>
        </div>
      </div>
    </section>


    <section class="about-contact">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="card shadow-lg">
              <div class="card-body">
                <h5 class="card-title"><?= $lan['ab']['h5'] ?></h5>
                <p><?= $lan['ab']['p4'] ?></p>
                <form action="contact" method="post" id="footer_contact_form">
                  <div class="form-group">
                    <input type="text" class="form-control" name="full_name" placeholder="<?= $lan['ab']['form'][0] ?>">
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="<?= $lan['ab']['form'][1] ?>">
                  </div>
                  <div class="form-group">
                    <textarea name="message" class="form-control" rows="5" placeholder="<?= $lan['ab']['form'][2] ?>"></textarea>
                  </div>
                  <button type="submit" class="contact-btn" id="send_con"><?= $lan['ab']['send'] ?></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="dist/js/script.js"></script>

  </body>
</html>

