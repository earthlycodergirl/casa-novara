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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />

    <title>Mexico Real Estate FAQ</title>

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
              <h1>Mexico Real Estate FAQ</h1>
              <h2>Here you will find a list of the most frequently asked questions.</h2>

              <div id="budd_blog">
              <?php if(1 == 3){ foreach($site->Blog as $bl){ ?>
                <h3><a href="blog/<?= $bl->blog_id ?>/"><?= $bl->title ?></a></h3>
                <p><?php echo strip_tags(substr($bl->content, 0, strpos($bl->content, ' ', 300))).'...' ?> <a href="blog/<?= $bl->blog_id ?>/" class="blog-link">Read full article</a></p>
              <?php } }else{ ?>
                <div class="no-results">
                  <h5>Nothing here.</h5>
                  <p>We apologize but we are busy selling homes and haven't uploaded our frequently asked questions yet. Please check back soon!</p>
                </div>
              <?php } ?>
              </div>
          </div>
        </div>
      </div>
    </section>


    <?php require('dist/inc/foot.php') ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script>var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})</script>
  </body>
</html>