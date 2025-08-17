<?php
require('base.php');
$nav_class = $logo_type = 'dark';

?>

<!doctype html>
<html lang="en">
  <head>
     <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Uh oh! This page is not available on our website. Please visit our homepage or contact us today to find what you are looking for.">

    <meta name="robots" content="index" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <title>Uh oh! 404 - Kiin Realty</title>
    <meta name="robots" content="noindex" />
    <?php include 'dist/inc/favicon.php'; ?>
  </head>
  <body class="<?= $mobile_class ?> about-page">
    <?php require('dist/inc/nav_top.php') ?>
    <div class="inner-head dark">
      <?php require('dist/inc/nav.php') ?>
    </div>


    <section class="about-head">
      <div class="container">
        <div class="row">
          <div class="col-sm-9">
            <h1><?= $lan['404']['h1'] ?></h1>
            <h2>Uh oh!</h2>
            <p><?= $lan['404']['p'] ?></p>
            <a href="<?= $link_contact[$lang] ?>"><?= $lan['404']['a'][0] ?></a>
            <a href="<?= $link_home[$lang] ?>"><?= $lan['404']['a'][1] ?></a>
          </div>
        </div>

      </div>
    </section>





    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  </body>
</html>

