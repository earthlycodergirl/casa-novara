<nav class="navbar fixed-top navbar-dark bg-dark" id="top_bar">
  <div class="container">
    <div class="navbar-nav" id="navbarText">
      <span class="navbar-text">
        <?= $phone_mia ?>
      </span>
    </div>
    <ul class="nav justify-content-end">
      <li class="nav-item top-link d-none d-sm-inline-block">
        <a class="nav-link" href="<?= $link_about[$lang] ?>"><?= $lan['nav']['abo'] ?></a>
      </li>
      <li class="nav-item top-link d-none d-sm-inline-block">
        <a class="nav-link" href="<?= $link_contact[$lang] ?>"><?= $lan['nav']['con'] ?></a>
      </li>
      <!-- <li class="nav-item dropdown top-link">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
          </svg>
          <?= $lan['nav']['log'] ?></a>
          <ul class="dropdown-menu login-drop" aria-labelledby="dropdownMenuButton">
            <li>
              <p>
                <b><?= $lan['nav']['log'] ?></b>
                <?= $lan['nav']['log_txt'] ?>
              </p>
            </li>
          </ul>
      </li> -->
      <li class="nav-item <?php echo $lang == 'en' ? 'active' : ''; ?> lang-select m1 divide-me">
        <a class="nav-link <?php echo $lang == 'en' ? 'active' : ''; ?>" href="<?= $current_link['en'] ?>">EN</a>
      </li>
      <li class="nav-item <?php echo $lang == 'es' ? 'active' : ''; ?> lang-select">
        <a class="nav-link <?php echo $lang == 'es' ? 'active' : ''; ?>" href="<?= $current_link['es'] ?>">ES</a>
      </li>
    </ul>
  </div>
</nav>