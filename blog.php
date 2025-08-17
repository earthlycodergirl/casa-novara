<?php
require('base.php');
$nav_class = $logo_type = 'dark';

$site = new Site();
$site->getBlog(0);

if($lang == 'en'){
  $current_link['es'] = $link_blog['es'];
}else{
  $current_link['en'] = $link_blog['en'];
}

?>

<!doctype html>
<html lang="en">
  <head>
     <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Riviera Maya Real Estate Info - MIA Realty</title>
    <meta name="description" content="<?= $meta['blog']['desc'] ?>" />
    <meta name="robots" content="index" />
    <link rel="canonical" href="https://kiinrealty.com/<?= $link_blog[$lang] ?>">


    <meta property="og:title" content="<?= $site->Art['title'] ?>">
    <meta property="og:description" content="<?= substr(strip_tags($site->Art['content']),0,200) ?>">
    <meta property="og:image" content="https://kiinrealty.com/dist/img/social.jpg">
    <meta property="og:url" content="https://kiinrealty.com/<?= $link_blog[$lang] ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="Kiin Realty">
    <meta name="twitter:image:alt" content="<?= $site->Art['title'] ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <?php include 'dist/inc/favicon.php'; ?>

  </head>
  <body class="<?= $mobile_class ?> about-page">
    <?php require('dist/inc/nav_top.php') ?>
    <div class="inner-head dark">
      <?php require('dist/inc/nav.php') ?>
    </div>

    <section id="blog_list">
      <div class="container">
        <div class="row">
          <div class="col">
              <h1><?= $lan['bl']['h1'] ?></h1>
              <h2><?= $lan['bl']['h2'] ?></h2>


              <?php if(!empty($site->Blog)){  ?>
                <div id="budd_blog">
                  <?php foreach($site->Blog as $bl){ ?>
                <div class="article bg-body rounded shadow-sm">
                  <a href="blog/<?= $bl->blog_id ?>/">
                    <?php if($bl->main_img != ''){ ?>
                  <img src="images/blog/main/<?= $bl->main_img ?>" alt="<?= $bl->title ?>" class="img-fluid">
                <?php } ?>
                  <h3><?= $bl->title ?></h3>
                  <p><?php
                  if(strlen($bl->content) > 200){
                    //echo strip_tags(substr($bl->content, 0, strpos($bl->content, ' ')), 200).'...';
                    //echo preg_replace('/\s+?(\S+)?$/', '', substr($bl->content, 0, 201));
                    echo substr(strip_tags($bl->content),0,200);
                  }else{
                    echo strip_tags(substr($bl->content, 0));
                  }
                   ?></p>
                   <span class="read-art"><?= $lan['bl']['read'] ?></span>
                  </a>
                </div>

              <?php } ?></div><?php }else{ ?>
                <div class="no-results">
                  <h5><?= $lan['bl']['h5'] ?></h5>
                  <p><?= $lan['bl']['p'] ?></p>
                </div>
              <?php } ?>

          </div>
        </div>
      </div>
      <div style="height: 80px"></div>
    </section>

    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
    $(document).ready(function(){
      // $("#budd_blog").masonry({
      //   // options...
      //   itemSelector: ".article",
      //   columnWidth: 200,
      //   percentPosition: true
      // });
      // init Masonry
      var $grid = $('#budd_blog').masonry({
          itemSelector: ".article",

          percentPosition: true
        // options...
      });
      // layout Masonry after each image loads
      $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');
      });
    })

    </script>

  </body>
</html>