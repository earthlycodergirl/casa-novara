<div class="search-listings">
  <div class="container mt-0 pt-0 px-0 px-sm-3">
    <div class="row">
      <form method="get" action="<?= $link_properties[$lang] ?>" id="update_se">
         <input type="hidden" name="search_type" value="basic">
         <input type="hidden" name="page" value="1">
         <input type="hidden" name="beds" value="<?= $listings->SearchParams->MinBeds ?>">
         <input type="hidden" name="baths" value="<?= $listings->SearchParams->MinBaths ?>">
         <input type="hidden" name="list_type" value="0" />
      <div class="col search-box">
        <div class="row">

          <div class="col-sm-4 col-10 pe-0 pe-sm-3">
            <div class="param">
              <label class="mobile-hide"><?= $lan['up']['locs'] ?></label>
              <div class="select">
                 <select name="location" id="location" class="form-control select-mia">
                     <option value="0"><?= $lan['bas']['where'][1] ?></option>
                     <?php  if(!empty($listing_cities)){ foreach($listing_cities as $kk=>$vv){ if($kk == $city_id){ $sel = 'selected'; }else{ $sel = ''; } ?>
                     <option value="<?= $kk ?>" <?= $sel ?>><?= utf8_encode($vv['loc']).', Mexico ('.$vv['cnt'].')' ?></option>
                     <?php } } ?>
                 </select>
              </div>
            </div>
          </div>

          <div class="col-2 mobile-hide">
            <label><?= $lan['bas']['ptypes'][0] ?></label>
            <select name="property_type" id="propertyType" class="form-control select-mia">
                <option value="0"><?= $lan['bas']['ptypes'][1] ?></option>
                <?php foreach($property_types as $pp=>$tt){ $desc = $tt['desc']; if($lang == 'es'){ $desc = $tt['desc_es']; } ?>
                <option value="<?= $pp ?>"><?= $desc ?></option>
                <?php } ?>
            </select>
          </div>

          <div class="col-2 mobile-hide">
            <div class="currency">
              <label><?= $lan['up']['currs'] ?></label>
              <?php if($listings->SearchParams->Currency == ''){ $listings->SearchParams->Currency = 'usd'; } ?>
              <select name="currency" class="select-mia form-control" id="curr">

                <option value="mxn" <?php if($listings->SearchParams->Currency == 'mxn'){ echo 'selected'; } ?>>MXN</option>
                <option value="usd" <?php if($listings->SearchParams->Currency == 'usd'){ echo 'selected'; } ?>>USD</option>
              </select>
            </div>
          </div>


          <div class="col-2 ps-0 ps-sm-3">
            <button type="submit" name="update_form" id="button_update"><span class="mobile-hide"><?= $lan['up']['search'] ?></span>
              <span class="mobile-show">
                <i class="bi bi-search"></i>
              </span>
            </button>
          </div>

          <div class="col-2 mobile-hide">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#advanced_search">
               <i class="bi bi-gear"></i> <?= $lan['up']['adv'] ?></a>
          </div>

          <div class="col-3 mobile-show">
            <div class="currency">
              <select class="select-mia form-control" id="curr_mob">
                <option value="mxn" <?php if($listings->SearchParams->Currency == 'mxn'){ echo 'selected'; } ?>>MXN</option>
                <option value="usd" <?php if($listings->SearchParams->Currency == 'usd'){ echo 'selected'; } ?>>USD</option>
              </select>
            </div>
          </div>
          <div class="col-6 mobile-show">
            <a href="javascript:void(0)" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#advanced_search">
               <?= $lan['up']['adv'] ?>
             </a>
          </div>
        </div>

      </div>
      </form>


    </div>
  </div>
</div>