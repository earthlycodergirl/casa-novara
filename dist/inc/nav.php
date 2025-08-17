<nav class="navbar navbar-expand-md <?= $nav_class ?>" id="main_nav">
  <div class="container">
    <a class="navbar-brand" href="<?= $link_home[$lang] ?>">
        <img src="dist/img/logo.webp" alt="Kiin Realty" width="170" height="127">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">
        <svg viewBox="0 0 100 80" width="40" height="40">
          <rect width="100" height="10"></rect>
          <rect y="30" width="100" height="10"></rect>
          <rect y="60" width="100" height="10"></rect>
        </svg>
      </span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><?= $lan['nav']['loc'] ?>
            <svg class="bi" width="15" height="15" fill="currentColor">
              <use xlink:href="dist/bi/bootstrap-icons.svg#chevron-down"/>
            </svg>
          </a>
          <ul class="dropdown-menu">

            <?php if(!empty($site_contact->NavDests)){
              foreach($site_contact->NavDests as $xx => $yy){
                if(!empty($yy)){
                  foreach($yy as $aa => $bb){
                    echo '<li><a class="dropdown-item" href="'.$link_loctypes[$lang][$xx].'/'.$bb['link'].'/'.$bb['id'].'">'.$bb['title'].' <span>'.$lan['ho']['real'].'</span></a></li>';
                  }
                }
              }
            } ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= $link_properties[$lang] ?>/all"><?= $lan['nav']['all'] ?></a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><?= $lan['nav']['ptypes'] ?>
            <svg class="bi" width="15" height="15" fill="currentColor">
              <use xlink:href="dist/bi/bootstrap-icons.svg#chevron-down"/>
            </svg>
          </a>
          <ul class="dropdown-menu">
            <?php if(!empty($listings2->PropertyTypes)){
              foreach($listings2->PropertyTypes as $xx=>$pp){
                switch($xx){
                  case 20:
                    $link = 'listings/residential';
                    $link_es = 'es/propiedades/residencial';
                    break;
                  case 3:
                    $link = 'listings/commercial';
                    $link_es = 'es/propiedades/comercial';
                    break;
                  case 21:
                    $link = 'listings/lots-land';
                    $link_es = 'es/propiedades/lotes';
                    break;
                }
                if($lang == 'en'){
                  $ptype = $pp['desc'];
                  $clink = $link;
                }else{
                  $ptype = $pp['desc_es'];
                  $clink = $link_es;
                }

                ?>
                  <li><a class="dropdown-item" href="<?= $clink ?>"><?= $ptype ?></a></li>
            <?php } } ?>

            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= $link_properties[$lang] ?>/all"><?= $lan['nav']['all'] ?></a></li>
          </ul>
        </li>
        <!--<li class="nav-item">
          <a class="nav-link" href="<?= $link_properties[$lang] ?>lots-land/all"><?= $lan['nav']['lot'] ?></a>
        </li>-->
        <!--<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Resources
            <svg class="bi" width="15" height="15" fill="currentColor">
              <use xlink:href="dist/bi/bootstrap-icons.svg#chevron-down"/>
            </svg>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="mexico-real-estate/">Blog</a></li>
            <li><a class="dropdown-item" href="real-estate-faq">FAQ</a></li>
          </ul>
        </li>-->
        <li class="nav-item">
          <a class="nav-link" href="<?= $link_list[$lang] ?>"><?= $lan['nav']['lis'] ?></a>
        </li>
        <li class="nav-item mobile-show">
          <a class="nav-link" href="<?= $link_about[$lang] ?>"><?= $lan['nav']['abo'] ?></a>
        </li>
        <li class="nav-item mobile-show">
          <a class="nav-link" href="<?= $link_contact[$lang] ?>"><?= $lan['nav']['con'] ?></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
