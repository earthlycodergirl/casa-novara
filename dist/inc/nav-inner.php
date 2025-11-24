<nav class="navbar navbar-expand-md navbar-light bg-white">
  <div class="container-fluid">
  <a class="navbar-brand" href="<?= $link_prefix ?>/">
      <img src="<?= $assets_prefix ?>/dist/img/logo-black.png" alt="Casa Novara Group" width="300" onerror="this.style.display='none'">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavInner" aria-controls="mainNavInner" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="color:#111"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainNavInner">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?= $link_prefix ?>/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?= $link_prefix ?>/about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?= $link_prefix ?>/listings">Properties</a>
        </li>
        <!-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-dark" href="#" id="propertiesDropdownInner" role="button" data-bs-toggle="dropdown" aria-expanded="false">Properties</a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="propertiesDropdownInner">

            <li><h6 class="dropdown-header">By Type</h6></li>
            <li><a class="dropdown-item" href="listings/residential">Residential</a></li>
            <li><a class="dropdown-item" href="listings/commercial">Commercial</a></li>
            <li><a class="dropdown-item" href="listings/lots-land">Lots & Land</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header">Popular Locations</h6></li>
            <li><a class="dropdown-item" href="listings/cancun/all">Cancún</a></li>
            <li><a class="dropdown-item" href="listings/playa-del-carmen/all">Playa del Carmen</a></li>
            <li><a class="dropdown-item" href="listings/tulum/all">Tulum</a></li>
            <li><a class="dropdown-item" href="listings/puerto-morelos/all">Puerto Morelos</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="listings">View All Properties</a></li>
          </ul>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?= $link_prefix ?>/real-estate-news/">Blog</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="<?= $link_prefix ?>/contact-us">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
