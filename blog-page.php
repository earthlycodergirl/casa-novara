<?php
require('base.php');
$nav_class = $logo_type = 'dark';

if(isset($_GET['blog_id'])){
  $site = new Site();
  $site->getArtPub($_GET['blog_id']);
}else{
  header('location: /blog/');
}

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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <title><?= $site->Art['title'] ?></title>
    <meta name="description" content="<?= substr(strip_tags($site->Art['content']),0,200) ?>" />
    <meta name="robots" content="index" />
    <link rel="canonical" href="https://miarealty.com.mx/<?= $link_blog[$lang].'/'.$site->Art['id'] ?>">


    <meta property="og:title" content="<?= $site->Art['title'] ?>">
    <meta property="og:description" content="<?= substr(strip_tags($site->Art['content']),0,200) ?>">
    <meta property="og:image" content="https://miarealty.com.mx/dist/img/social.jpg">
    <meta property="og:url" content="https://miarealty.com.mx/<?= $link_blog[$lang].'/'.$site->Art['id'] ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="MIA Realty">
    <meta name="twitter:image:alt" content="<?= $site->Art['title'] ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <?php include 'dist/inc/favicon.php'; ?>

  </head>
  <body class="<?= $mobile_class ?> about-page">
    <?php require('dist/inc/nav_top.php') ?>
    <div class="inner-head dark">
      <?php require('dist/inc/nav.php') ?>
    </div>

    <section id="blog_page">
      <div class="container">
        <div class="row">
          <div class="col-sm-3">
            <img src="<?= $blog_img_url ?>main/<?= $site->Art['img'] ?>" class="img-fluid" alt="<?= $site->Art['title'] ?>">
            <p class="author"><?= $site->Art['author'] ?></p>
            <p class="posted"><?= date('M d, Y',strtotime($site->Art['date'])) ?></p>

            <h5 class="mt-5 share">Share the news:</h5>
            <ul class="social-buttons">
              <li class="button__share button__share--facebook"><a href="javascript:void(window.open('https://www.facebook.com/sharer.php?u=' + encodeURIComponent(document.location) + '?t=' + encodeURIComponent(document.title),'_blank'))"><i class="bi bi-facebook"></i></a></li>
              <li class="button__share button__share--googleplus"><a href="javascript:void(window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.location),'_blank'))"><i class="bi bi-google"></i></a></li>
              <li class="button__share button__share--twitter"><a href="javascript:void(window.open('https://twitter.com/share?url=' + encodeURIComponent(document.location) + '&amp;text=' + encodeURIComponent(document.title) + '&amp;via=miarealty.com.mx&amp;hashtags=miarealty','_blank'))"><i class="bi bi-twitter"></i></a></li>
              <!-- optional Twitter username of content author (don’t include “@”)
              optional Hashtags appended onto the tweet (comma separated. don’t include “#”) -->
              <li class="button__share button__share--linkedin"><a href="javascript:void(window.open('https://www.linkedin.com/shareArticle?url=' + encodeURIComponent(document.location) + '&amp;title=' + encodeURIComponent(document.title),'_blank'))"><i class="bi bi-linkedin"></i></a></li>
              <!-- can add &mini=true -->
              <!-- <li class="button__share button__share--reddit"><a href="javascript:void(window.open('http://reddit.com/submit?url=' + encodeURIComponent(document.location) + '&amp;title=' + encodeURIComponent(document.title),'_blank'))"><i class="bi bi-reddit"></i></a></li> -->
            </ul>
          </div>
          <div class="col-sm-9">
              <h1><?= $site->Art['title'] ?></h1>
              <?= $site->Art['content'] ?>
          </div>
        </div>

        <hr />
        <div class="row">

          <div class="col-md-5">
            <?php if(!empty($site->Prev)){ ?>
              <a href="blog/<?= $site->Prev['id'] ?>" class="d-block nav-btns">
                <div class="row">
                  <div class="col-md-1">
                    <i class="bi bi-chevron-left"></i>
                  </div>
                  <div class="col-md-2">
                    <img src="<?= $blog_img_url ?>main/thumbs/<?= $site->Prev['img'] ?>" class="img-fluid" alt="<?= $site->Prev['title'] ?>">
                  </div>
                  <div class="col-md-9">
                    <h5><small class="d-block"><?= $lan['bl']['prev'] ?></small> <?= $site->Prev['title'] ?></h5>
                  </div>
                </div>
              </a>
            <?php } ?>
          </div>

          <div class="col-md-2">
            <div class="clearfix">

            </div>
          </div>

          <div class="col-md-5">
            <?php if(!empty($site->Next)){ ?>
              <a href="blog/<?= $site->Next['id'] ?>" class="d-block nav-btns text-end">
                <div class="row">

                  <div class="col-md-9">
                    <h5><small class="d-block"><?= $lan['bl']['next'] ?></small> <?= $site->Next['title'] ?></h5>
                  </div>
                  <div class="col-md-2">
                    <img src="<?= $blog_img_url ?>main/thumbs/<?= $site->Next['img'] ?>" class="img-fluid" alt="<?= $site->Next['title'] ?>">
                  </div>
                  <div class="col-md-1">
                    <i class="bi bi-chevron-right"></i>
                  </div>
                </div>
              </a>
            <?php } ?>
          </div>

        </div>
      </div>
    </section>

    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  </body>
</html>