<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Listings — Map</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link href="dist/css/cng_base.css" rel="stylesheet">
        <link href="dist/css/cng.css" rel="stylesheet">

        <!-- Leaflet CSS (free, easy map) -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

        
    </head>
    <body class="listings-map-page">

    <?php require 'dist/inc/nav-inner.php';?>

    <!-- Listings filter ribbon (copied and extended) -->
    <section class="listings-filter" aria-label="Search and filters">
        <div class="filter-ribbon">
            <div class="container-fluid">
                <div class="filter-row">
                    <!-- Search box -->
                    <div class="filter-item filter-search">
                        <div class="search-input-wrap">
                            <svg class="search-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM10 15a5 5 0 110-10 5 5 0 010 10z"></path></svg>
                            <input id="ls-search" class="form-control" type="text" placeholder="Search locations, neighborhoods, or keywords" autocomplete="off">
                        </div>

                        <div class="search-suggestions" aria-hidden="true">
                            <ul></ul>
                        </div>
                    </div>

                    <!-- For Sale / Rent -->
                    <div class="filter-item filter-dropdown" data-filter="sale">
                        <button class="btn btn-outline-secondary btn-filter" type="button">For sale</button>
                        <div class="dropdown-panel">
                            <ul>
                                <li data-value="sale">For sale</li>
                                <li data-value="rent">For rent</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Price range -->
                    <div class="filter-item filter-dropdown" data-filter="price">
                        <button class="btn btn-outline-secondary btn-filter" type="button">Any Price</button>
                        <div class="dropdown-panel">
                            <ul>
                                <li data-value="any">Any Price</li>
                                <li data-value="0-100k">Under $100k</li>
                                <li data-value="100k-300k">$100k–$300k</li>
                                <li data-value="300k-600k">$300k–$600k</li>
                                <li data-value="600k+">$600k+</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Property types -->
                    <div class="filter-item filter-dropdown filter-types" data-filter="types">
                        <button class="btn btn-outline-secondary btn-filter" type="button">Property Types</button>
                        <div class="dropdown-panel types-panel">
                            <div class="types-grid">
                                <label class="type" data-value="Residential"><input type="checkbox"><span class="type-icon">🏠</span><span class="type-label">Residential</span></label>
                                <label class="type" data-value="Townhomes"><input type="checkbox"><span class="type-icon">🏘️</span><span class="type-label">Townhomes</span></label>
                                <label class="type" data-value="Co-op"><input type="checkbox"><span class="type-icon">🏢</span><span class="type-label">Co-op</span></label>
                                <label class="type" data-value="Multi-family"><input type="checkbox"><span class="type-icon">👥</span><span class="type-label">Multi-family</span></label>
                                <label class="type" data-value="Condos"><input type="checkbox"><span class="type-icon">🏬</span><span class="type-label">Condos</span></label>
                                <label class="type" data-value="Commercial"><input type="checkbox"><span class="type-icon">🏢</span><span class="type-label">Commercial</span></label>
                                <label class="type" data-value="Manufactured"><input type="checkbox"><span class="type-icon">🚚</span><span class="type-label">Manufactured</span></label>
                                <label class="type" data-value="Land"><input type="checkbox"><span class="type-icon">🌾</span><span class="type-label">Land</span></label>
                                <label class="type" data-value="Other"><input type="checkbox"><span class="type-icon">⋯</span><span class="type-label">Other</span></label>
                            </div>
                            <div class="types-actions">
                                <button class="btn btn-sm btn-primary btn-apply">Apply</button>
                                <button class="btn btn-sm btn-link btn-clear">Clear</button>
                            </div>
                        </div>
                    </div>

                    <!-- Beds -->
                    <div class="filter-item filter-dropdown" data-filter="beds">
                        <button class="btn btn-outline-secondary btn-filter" type="button">All Beds</button>
                        <div class="dropdown-panel pills-panel">
                            <ul>
                                <li><label class="pill"><input type="radio" name="beds" value="any" checked>All beds</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="1">1</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="2">2</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="3">3</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="4">4</label></li>
                                <li><label class="pill"><input type="radio" name="beds" value="5+">5+</label></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Baths -->
                    <div class="filter-item filter-dropdown" data-filter="baths">
                        <button class="btn btn-outline-secondary btn-filter" type="button">All Baths</button>
                        <div class="dropdown-panel pills-panel">
                            <ul>
                                <li><label class="pill"><input type="radio" name="baths" value="any" checked>All baths</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="1">1</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="2">2</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="3">3</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="4">4</label></li>
                                <li><label class="pill"><input type="radio" name="baths" value="5+">5+</label></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Mobile toggle -->
                    <div class="filter-item filter-toggle d-md-none">
                        <button class="btn btn-outline-secondary btn-filter" type="button" data-action="toggle-filters">Filters</button>
                    </div>

                    
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid split-wrap">
        <div class="row gx-3">
            <div class="col-md-6 list-column" id="list-column">
                <div class="search-results mb-2">
                    <!-- Replace the previous results-count + buttons block with this toolbar -->
                    <div class="properties-controls d-flex align-items-center justify-content-end">
                      <div class="results-left">
                        <div class="results-count" style="margin-right: 20px;">
                          Showing <span class="results-number text-bold">0</span> results
                        </div>
                      </div>

                      <div class="results-actions d-flex align-items-center">
                        <!-- keep visual order: result count on left, controls on right -->
                        <div class="view-toggle btn-group" role="group" aria-label="View toggle">
                          <button type="button" class="btn btn-outline-secondary btn-sm view-list active" data-view="list">List</button>
                          <button type="button" class="btn btn-outline-secondary btn-sm view-map" data-view="map">Map</button>
                        </div>
                      </div>
                    </div>
                    <div class="no-results" style="display:none; padding:24px; text-align:center; color:#666">No properties match your filters. Try adjusting your search.</div>
                </div>

                <!-- Properties list -->
                <section class="properties-list" aria-label="Property results">
                    <div class="properties-wrap" data-min-width="1400">
                        <div class="properties-grid">
                            <?php
                            // Test dataset for properties with lat/lng in Yucatan Peninsula
                            $test_properties = [
                                ['id'=>1,'title'=>'Cozy Apartment','image'=>'dist/img/side-3.jpeg','status'=>'Active','listing'=>'sale','price'=>1250000,'price_range'=>'600k+','types'=>['Condos','Beachfront'],'beds'=>4,'baths'=>3,'sqft'=>4447,'location'=>'Playa del Carmen','postal'=>'77710','area'=>'Beachfront','lat'=>20.6283,'lng'=>-87.0739],
                                ['id'=>2,'title'=>'Townhome Retreat','image'=>'dist/img/side-5.jpeg','status'=>'Active','listing'=>'sale','price'=>425000,'price_range'=>'300k-600k','types'=>['Townhomes','Residential'],'beds'=>3,'baths'=>2,'sqft'=>1800,'location'=>'Tulum','postal'=>'77780','area'=>'City','lat'=>20.2110,'lng'=>-87.4653],
                                ['id'=>3,'title'=>'Luxury Condo','image'=>'dist/img/side-4.jpeg','status'=>'Sold','listing'=>'sale','price'=>950000,'price_range'=>'600k+','types'=>['Condos'],'beds'=>2,'baths'=>2,'sqft'=>1450,'location'=>'Cancún','postal'=>'77500','area'=>'Beachfront','lat'=>21.1619,'lng'=>-86.8515],
                                ['id'=>4,'title'=>'Rural Estate','image'=>'dist/img/side-6.jpeg','status'=>'Construction','listing'=>'sale','price'=>320000,'price_range'=>'300k-600k','types'=>['Multi-family','Land'],'beds'=>4,'baths'=>3,'sqft'=>5600,'location'=>'Valladolid','postal'=>'97780','area'=>'Rural','lat'=>20.6767,'lng'=>-88.1986],
                                ['id'=>5,'title'=>'Co-op Central','image'=>'dist/img/side-5.jpeg','status'=>'Active','listing'=>'rent','price'=>2200,'price_range'=>'0-100k','types'=>['Co-op','Residential'],'beds'=>1,'baths'=>1,'sqft'=>650,'location'=>'Merida','postal'=>'97000','area'=>'City','lat'=>20.9674,'lng'=>-89.5926],
                                ['id'=>6,'title'=>'Manufactured Home','image'=>'dist/img/side-3.jpeg','status'=>'Active','listing'=>'sale','price'=>85000,'price_range'=>'0-100k','types'=>['Manufactured'],'beds'=>2,'baths'=>1,'sqft'=>900,'location'=>'Puerto Morelos','postal'=>'77580','area'=>'Rural','lat'=>20.8571,'lng'=>-86.8879],
                                ['id'=>7,'title'=>'Commercial Lot','image'=>'dist/img/side-4.jpeg','status'=>'Active','listing'=>'sale','price'=>450000,'price_range'=>'300k-600k','types'=>['Commercial','Land'],'beds'=>0,'baths'=>0,'sqft'=>12000,'location'=>'Playa del Carmen','postal'=>'77710','area'=>'City','lat'=>20.6296,'lng'=>-87.0739],
                                ['id'=>8,'title'=>'Other Property','image'=>'dist/img/side-6.jpeg','status'=>'Active','listing'=>'rent','price'=>3500,'price_range'=>'100k-300k','types'=>['Other'],'beds'=>5,'baths'=>4,'sqft'=>3200,'location'=>'Chichen Itza','postal'=>'97751','area'=>'Rural','lat'=>20.6829,'lng'=>-88.5686],
                            ];

                            foreach($test_properties as $p):
                                $types_attr = implode(',', $p['types']);
                            ?>
                            <div class="property-col">
                                <a class="property-card" href="#" data-id="<?= $p['id'] ?>" data-lat="<?= $p['lat'] ?>" data-lng="<?= $p['lng'] ?>" data-listing="<?= $p['listing'] ?>" data-price="<?= $p['price'] ?>" data-price-range="<?= $p['price_range'] ?>" data-types="<?= htmlspecialchars($types_attr) ?>" data-beds="<?= $p['beds'] ?>" data-baths="<?= $p['baths'] ?>" data-sqft="<?= $p['sqft'] ?>" data-location="<?= htmlspecialchars($p['location']) ?>" data-area="<?= $p['area'] ?>" data-status="<?= $p['status'] ?>">
                                    <div class="prop-image" style="background-image:url('<?= $p['image'] ?>');">
                                        <div class="prop-badge"><?= htmlspecialchars($p['status']) ?></div>
                                        <div class="prop-action" aria-hidden="true">✉</div>
                                    </div>
                                    <div class="prop-body">
                                        <div class="prop-price"><?= $p['listing'] === 'rent' ? '$'.number_format($p['price']).' /mo' : '$'.number_format($p['price']) ?></div>
                                        <div class="prop-meta"><?= $p['beds'] ?> bd <span class="sep">&middot;</span> <?= $p['baths'] ?> ba <span class="sep">&middot;</span> <?= number_format($p['sqft']) ?> sqft</div>
                                        <div class="prop-location"><?= htmlspecialchars($p['location']) ?>, <?= htmlspecialchars($p['postal']) ?></div>
                                        <div class="prop-type"><?= htmlspecialchars(implode(', ', $p['types'])) ?> — <?= htmlspecialchars($p['area']) ?></div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-6 map-column" id="map-column">
                <div id="mapid"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="dist/js/cng_listings_filters.js"></script>
    <script src="dist/js/cng_listings_map.js"></script>

    </body>
</html>
