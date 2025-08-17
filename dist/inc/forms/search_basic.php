<form method="get" action="<?= $link_properties[$lang] ?>" id="main_search_engine">
   <input type="hidden" name="search_type" value="basic">
   <input type="hidden" name="page" value="1">
    <div class="row">
        <div class="col-md-6 pe-md-0">
            <div class="formBlock select">
            <select name="location" id="location" class="form-control">
                <option selected value="0"><?= $lan['bas']['where'][0] ?></option>
                <option value="0"><?= $lan['bas']['where'][1] ?></option>
                <?php if(!empty($listing_cities)){ foreach($listing_cities as $kk=>$vv){ ?>
                <option value="<?= $kk ?>"><?= $vv['loc'].', Mexico ('.$vv['cnt'].')' ?></option>
                <?php } } ?>
            </select>
            </div>
        </div>

        <div class="col-md-5 pe-md-0">
          <div class="row px-md-1">

            <!-- Property type -->
            <?php if(!empty($property_types)){ ?>
             <div class="col-md-6 px-md-0 pe-md-1">
                 <div class="formBlock select">
                 <select name="property_type" id="propertyType" class="form-control">
                     <option value="0" selected><?= $lan['bas']['ptypes'][0] ?></option>
                     <option value="0"><?= $lan['bas']['ptypes'][1] ?></option>
                     <?php foreach($property_types as $pp=>$tt){ ?>
                     <option value="<?= $pp.'-'.$tt['desc_up'] ?>"><?= $tt['desc'] ?></option>
                     <?php } ?>
                 </select>
                 </div>
             </div>
             <?php } ?>


             <!-- minimum beds -->
            <div class="col-md-3 col-6 pe-1 px-md-0 pe-md-1 shower-1 res" data-rel="res">
                <div class="formBlock select show-1">
                <select name="beds" id="beds" class="form-control">
                    <option value="0" selected><?= $lan['bas']['beds'][0] ?></option>
                    <!--<option value="0"><?= $lan['bas']['beds'][1] ?></option>-->
                    <option value="888">Studio / Loft</option>
                    <?php for($i=1;$i<=$adv_search->MinMax['beds'][1];$i++){
                      echo '<option>'.$i.'</option>';
                     } ?>
                </select>
                </div>
            </div>

            <!-- minimum baths -->
            <div class="col-md-3 col-6 ps-0 px-md-0 pe-md-1 shower-2">
                <div class="formBlock select show-2">
                <select name="baths" id="baths" class="form-control">
                    <option value="0" selected><?= $lan['bas']['baths'][0] ?></option>
                    <!--<option value="0"><?= $lan['bas']['baths'][1] ?></option>-->
                    <?php for($i=1;$i<=$adv_search->MinMax['baths'][1];$i++){
                      echo '<option>'.$i.'</option>';
                     } ?>
                </select>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-1 ps-md-2 mobile-hide">
            <div class="formBlock">
                <button type="submit" class="search_btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                  </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="row justify-content-md-end">
      <div class="col-md-6 px-md-1 pt-md-1">
        <div class="row px-md-0 min-max-price w100">
          <div class="col-md-4 col-6 pe-md-1 pe-1 pl-0 pricer-1">
            <div class="formBlock price-1">
              <div class="input-group mb-1">
                <input type="text" class="form-control money" name="price_min" id="minpr" placeholder="Min Price">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                <ul class="dropdown-menu dropdown-menu-end min-drop">
                  <li><a class="dropdown-item" href="#">50,000</a></li>
                  <li><a class="dropdown-item" href="#">100,000</a></li>
                  <li><a class="dropdown-item" href="#">200,000</a></li>
                  <li><a class="dropdown-item" href="#">300,000</a></li>
                  <li><a class="dropdown-item" href="#">400,000</a></li>
                  <li><a class="dropdown-item" href="#">500,000</a></li>
                  <li><a class="dropdown-item" href="#">600,000</a></li>
                  <li><a class="dropdown-item" href="#">700,000</a></li>
                  <li><a class="dropdown-item" href="#">800,000</a></li>
                  <li><a class="dropdown-item" href="#">900,000</a></li>
                  <li><a class="dropdown-item" href="#">1,000,000</a></li>
                  <li><a class="dropdown-item" href="#">1,250,000</a></li>
                  <li><a class="dropdown-item" href="#">1,500,000</a></li>
                  <li><a class="dropdown-item" href="#">2,000,000</a></li>
                  <li><a class="dropdown-item" href="#">5,000,000</a></li>
                  <li><a class="dropdown-item other" href="#"><?= $lan['bas']['other'] ?></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-6 ps-md-0 ps-0 pr-0 pricer-2">
            <div class="formBlock price-2">
              <div class="input-group mb-1">
                <input type="text" class="form-control money" name="price_max" id="maxpr" placeholder="Max Price">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                <ul class="dropdown-menu dropdown-menu-end max-drop">
                  <li><a class="dropdown-item" href="#">50,000</a></li>
                  <li><a class="dropdown-item" href="#">100,000</a></li>
                  <li><a class="dropdown-item" href="#">200,000</a></li>
                  <li><a class="dropdown-item" href="#">300,000</a></li>
                  <li><a class="dropdown-item" href="#">400,000</a></li>
                  <li><a class="dropdown-item" href="#">500,000</a></li>
                  <li><a class="dropdown-item" href="#">600,000</a></li>
                  <li><a class="dropdown-item" href="#">700,000</a></li>
                  <li><a class="dropdown-item" href="#">800,000</a></li>
                  <li><a class="dropdown-item" href="#">900,000</a></li>
                  <li><a class="dropdown-item" href="#">1,000,000</a></li>
                  <li><a class="dropdown-item" href="#">1,250,000</a></li>
                  <li><a class="dropdown-item" href="#">1,500,000</a></li>
                  <li><a class="dropdown-item" href="#">2,000,000</a></li>
                  <li><a class="dropdown-item" href="#">5,000,000</a></li>
                  <li><a class="dropdown-item other" href="#"><?= $lan['bas']['other'] ?></a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-1 ps-md-2 px-0 mobile-show">
            <div class="formBlock">
                <button type="submit" class="search_btn">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                  </svg>
                </button>
            </div>
        </div>
          <div class="col-md-4 col-sm-6 text-sm-end mt-2 pe-0">
            <div class="white-link show-advanced" data-show="<?= $lan['bas']['adv'][0] ?>" data-hide="<?= $lan['bas']['adv'][1] ?>"><i class="bi bi-gear"></i> <?= $lan['bas']['adv'][0] ?></div>
          </div>
        </div>

      </div>
    </div>
</form>