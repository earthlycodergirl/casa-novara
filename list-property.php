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

    <title><?= $meta['list']['title'] ?></title>
    <meta name="description" content="<?= $meta['list']['desc'] ?>" />
    <meta name="robots" content="index" />
    <link rel="canonical" href="<?= $link_list[$lang] ?>">


    <meta property="og:title" content="<?= $meta['list']['title'] ?>">
    <meta property="og:description" content="<?= $meta['list']['desc'] ?>">
    <meta property="og:image" content="https://kiinrealty.com/dist/img/social.jpg">
    <meta property="og:url" content="<?= $link_list[$lang] ?>">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:site_name" content="Kiin Realty">
    <meta name="twitter:image:alt" content="<?= $meta['list']['title'] ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="dist/css/main.css" type="text/css" />


    <?php include 'dist/inc/favicon.php'; ?>
  </head>
  <body class="<?= $mobile_class ?> list-page">

    <?php require('dist/inc/nav_top.php') ?>

    <div class="container-fluid list-bg">

      <?php require('dist/inc/nav.php') ?>

      <div class="list-head container">
        <div class="row">
          <div class="col-sm-8" id="get_quote">
            <h3 class="pb-4"><?= $lan['li']['h3'] ?></h3>
            <form action="" method="post" id="list_prop">
              <div id="sell_prop_form">

                <div class="row step-1 step">
                  <div class="col-sm-9">
                    <input type="text" name="address" class="form-control" placeholder="<?= $lan['li']['form'][0] ?>" />
                  </div>
                  <div class="col-sm-3">
                    <button type="button" data-type="2" class="next-btn"><?= $lan['li']['next'] ?></button>
                  </div>
                </div>

                <div class="row step-2 step">
                  <div class="col-sm-6 colu fname">
                    <input type="text" name="full_name" class="form-control" placeholder="<?= $lan['li']['form'][1] ?>" />
                  </div>
                  <div class="col-sm-3 colu stype">
                    <select name="seller_type" class="form-control select">
                      <option selected><?= $lan['li']['form'][2] ?></option>
                      <option><?= $lan['li']['form'][3] ?></option>
                    </select>
                  </div>
                  <div class="col-sm-3 colu">
                    <button type="button" data-type="3" class="next-btn"><?= $lan['li']['next'] ?></button>
                  </div>
                </div>

                <div class="row step-3 step">
                  <div class="col-sm-4 colu">
                    <input type="text" name="email" class="form-control" placeholder="<?= $lan['li']['form'][4] ?>" />
                  </div>
                  <div class="col-sm-4 colu">
                    <input type="text" name="phone" class="form-control" placeholder="<?= $lan['li']['form'][5] ?>" />
                  </div>
                  <div class="col-sm-4 colu">
                    <button type="button" data-type="4" class="next-btn"><?= $lan['li']['form'][6] ?></button>
                  </div>
                </div>


                <div class="row success-message">
                  <div class="col">
                    <p><?= $lan['li']['success'] ?></p>
                  </div>
                </div>

              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="container list-middle">
      <div class="row">
        <div class="col-md-6 col-lg-5">
          <img src="dist/img/list-prop-bg.jpg" class="img-fluid check-left" alt="Sell your Property Mexico" />
        </div>
        <div class="col-md-6 col-lg-7 list-right">
          <div class="d-flex">
            <div>
              <div class="icon-svg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" width="15.27" height="27" viewBox="0 0 15.27 27">
                  <path id="Icon_material-attach-money" data-name="Icon material-attach-money" d="M17.7,16.35c-3.4-.885-4.5-1.8-4.5-3.225,0-1.635,1.515-2.775,4.05-2.775,2.67,0,3.66,1.275,3.75,3.15h3.315A5.979,5.979,0,0,0,19.5,7.785V4.5H15V7.74c-2.91.63-5.25,2.52-5.25,5.415,0,3.465,2.865,5.19,7.05,6.2,3.75.9,4.5,2.22,4.5,3.615,0,1.035-.735,2.685-4.05,2.685-3.09,0-4.305-1.38-4.47-3.15H9.48c.18,3.285,2.64,5.13,5.52,5.745V31.5h4.5V28.275c2.925-.555,5.25-2.25,5.25-5.325C24.75,18.69,21.1,17.235,17.7,16.35Z" transform="translate(-9.48 -4.5)" fill="#5ac100"/>
                </svg>
              </div>
            </div>
            <div class="flex-grow-1">
              <h5><?= $lan['li']['point1'][0] ?></h5>
              <p><?= $lan['li']['point1'][1] ?></p>
            </div>
          </div>

          <div class="d-flex">
            <div>
              <div class="icon-svg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" width="31.98" height="27.937" viewBox="0 0 31.98 27.937">
                  <path id="Shape" d="M15.468,27.811a42.509,42.509,0,0,1-7.525-4.963,1.276,1.276,0,0,1-.176-1.779,1.237,1.237,0,0,1,1.755-.179,41.417,41.417,0,0,0,6.487,4.36,42.293,42.293,0,0,0,6.469-4.36,1.236,1.236,0,0,1,1.755.181,1.275,1.275,0,0,1-.177,1.778,43.263,43.263,0,0,1-7.5,4.959,1.235,1.235,0,0,1-1.093,0ZM.225,21.962A1.276,1.276,0,0,1,.533,20.2L15.254,9.772A1.235,1.235,0,0,1,16.7,9.78l3.951,2.869V11.627A1.256,1.256,0,0,1,21.9,10.363h4.99a1.256,1.256,0,0,1,1.247,1.264v6.345l3.253,2.216a1.274,1.274,0,0,1,.34,1.754,1.241,1.241,0,0,1-1.037.559,1.227,1.227,0,0,1-.695-.215l-3.805-2.591a1.269,1.269,0,0,1-.552-1.049V12.892H23.143V15.11a1.268,1.268,0,0,1-.678,1.125,1.235,1.235,0,0,1-1.3-.1L15.96,12.357l-14,9.916a1.238,1.238,0,0,1-1.738-.311Zm15.12-1.736a.627.627,0,0,1-.624-.632V18.329a.627.627,0,0,1,.624-.632h1.247a.627.627,0,0,1,.624.632v1.264a.627.627,0,0,1-.624.632ZM1.474,15.171A12.767,12.767,0,0,1,.043,9.449a10.936,10.936,0,0,1,1.8-5.924A7.873,7.873,0,0,1,8.652,0a10.1,10.1,0,0,1,7.36,3.2A10.1,10.1,0,0,1,23.373,0a7.874,7.874,0,0,1,6.813,3.525,10.935,10.935,0,0,1,1.795,5.924,1.248,1.248,0,1,1-2.495,0c0-1.925-1.1-6.921-6.113-6.921A7.442,7.442,0,0,0,18.688,4.16a7.724,7.724,0,0,0-1.623,1.7,1.225,1.225,0,0,1-2.109,0,7.686,7.686,0,0,0-1.62-1.7A7.44,7.44,0,0,0,8.652,2.528c-5.015,0-6.114,5-6.114,6.921A10.25,10.25,0,0,0,3.7,14.029a1.272,1.272,0,0,1-.549,1.7,1.231,1.231,0,0,1-.562.137A1.246,1.246,0,0,1,1.474,15.171Z" transform="translate(0)" fill="#5ac100"/>
                </svg>
            </div>
          </div>
          <div class="flex-grow-1">
            <h5><?= $lan['li']['point2'][0] ?></h5>
            <p><?= $lan['li']['point2'][1] ?></p>
          </div>
        </div>


          <div class="d-flex">
            <div>
              <div class="icon-svg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" width="26.833" height="31.667" viewBox="0 0 26.833 31.667">
                  <path id="Icon_awesome-user-tie" data-name="Icon awesome-user-tie" d="M12.417,14.833a7.261,7.261,0,0,0,7.1-7.417A7.261,7.261,0,0,0,12.417,0a7.261,7.261,0,0,0-7.1,7.417A7.261,7.261,0,0,0,12.417,14.833Zm5.31,1.889-2.65,11.09L13.3,19.932l1.774-3.245H9.756l1.774,3.245-1.774,7.88-2.65-11.09A7.629,7.629,0,0,0,0,24.475v2.41a2.724,2.724,0,0,0,2.661,2.781H22.172a2.724,2.724,0,0,0,2.661-2.781v-2.41a7.629,7.629,0,0,0-7.106-7.753Z" transform="translate(1 1)" fill="none" stroke="#5ac100" stroke-width="2"/>
                </svg>
              </div>
            </div>
            <div class="flex-grow-1">
              <h5><?= $lan['li']['point3'][0] ?></h5>
              <p><?= $lan['li']['point3'][1] ?></p>
            </div>
            </div>

          <div class="row">
            <div class="col">
              <a href="#get_quote" class="btn btn-lg btn-dark"><?= $lan['li']['eval'] ?></a>
            </div>
          </div>

        </div>
      </div>
    </div>



    <?php require('dist/inc/foot.php') ?>








    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="dist/js/list-prop.js"></script>


  </body>
</html>
