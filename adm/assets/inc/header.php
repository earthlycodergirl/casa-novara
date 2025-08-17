<!-- Preloader -->
    <div class="preloader">
      <div class="spinner-dots">
        <span class="dot1"></span>
        <span class="dot2"></span>
        <span class="dot3"></span>
      </div>
    </div>


    <!-- Sidebar -->
    <aside class="sidebar sidebar-icons-right sidebar-icons-boxed sidebar-expand-lg sidebar-dark sidebar-color-info">
      <header class="sidebar-header">

        <span class="logo">
          <a href="index"><img src="assets/img/logo-white.png" alt="logo"></a>
        </span>
        <span class="sidebar-toggle-fold"></span>
      </header>

      <nav class="sidebar-navigation">
        <ul class="menu">

          <li class="menu-category">KIIN Website</li>

          <li class="menu-item <?php if($page_type == 1){ echo 'active'; } ?>">
            <a class="menu-link" href="listings.php">
              <span class="icon fa fa-list"></span>
              <span class="title">Listings</span>
            </a>
          </li>

        <li class="menu-item <?php if($page_type == 3){ echo 'active'; } ?>">
            <a class="menu-link" href="inquiries.php">
              <span class="icon ti-id-badge"></span>
              <span class="title">Inquiries</span>
            </a>
          </li>
          <li class="menu-item <?php if($page_type == 4){ echo 'active'; } ?>">
            <a class="menu-link" href="blog-list.php">
              <span class="icon ti-layout"></span>
              <span class="title">Blog</span>
            </a>
          </li>

          <li class="menu-item <?php if($page_type == 9){ echo 'active'; } ?>">
            <a class="menu-link" href="testimonials.php">
              <span class="icon ti-comment"></span>
              <span class="title">Testimonials</span>
            </a>
          </li>


          <li class="menu-category">Content Manager</li>

          <li class="menu-item <?php if($page_type == 15){ echo 'active'; } ?>">
            <a class="menu-link" href="property_locations.php">
              <span class="icon ti-map"></span>
              <span class="title">Property Locations
                <!-- <span class="badge rounded-pill bg-success text-dark">new</span> -->
              </span>
            </a>
          </li>

          <li class="menu-item <?php if($page_type == 16){ echo 'active'; } ?>">
            <a class="menu-link" href="pricing-types.php">
              <span class="icon ti-money"></span>
              <span class="title">Pricing Types</span>
            </a>
          </li>

          <li class="menu-item <?php if($page_type == 5){ echo 'active'; } ?>">
            <a class="menu-link" href="property-types.php">
              <span class="icon ti-layers-alt"></span>
              <span class="title">Property Types</span>
            </a>
          </li>
          <li class="menu-item <?php if($page_type == 10){ echo 'active'; } ?>">
            <a class="menu-link" href="property-sub-types.php">
              <span class="icon ti-layers-alt"></span>
              <span class="title">Property Sub Types</span>
            </a>
          </li>
          <li class="menu-item <?php if($page_type == 6){ echo 'active'; } ?>">
            <a class="menu-link" href="listing-types.php">
              <span class="icon ti-announcement"></span>
              <span class="title">Listing Types</span>
            </a>
          </li>
          <li class="menu-item <?php if($page_type == 7){ echo 'active'; } ?>">
            <a class="menu-link" href="property-zones.php">
              <span class="icon ti-target"></span>
              <span class="title">Property Zones</span>
            </a>
          </li>

          <li class="menu-category">Administration</li>


          <li class="menu-item <?php if($page_type == 8){ echo 'active'; } ?>">
            <a class="menu-link" href="users.php">
              <span class="icon ti-user"></span>
              <span class="title">Users</span>
            </a>
          </li>

          <li class="menu-item <?php if($page_type == 17){ echo 'active'; } ?>">
            <a class="menu-link" href="contact_info.php">
              <span class="icon ti-email"></span>
              <span class="title">Contact Info</span>
            </a>
          </li>


        </ul>
      </nav>

    </aside>
    <!-- END Sidebar -->
