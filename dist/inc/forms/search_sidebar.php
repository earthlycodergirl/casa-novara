<div class="mob-filter-head">
     <h5><?= $lan['side']['filter'] ?></h5>

     <button id="close_adv" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
           <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
         </svg>
     </button>
     <button id="up_adv" class="btn"><?= $lan['side']['update'] ?></button>
 </div>

<form action="<?= $link_properties[$lang] ?>" method="get" id="search_sidebar">
    <?php
    $advfilters = 0;
    $dont_add = array('areas','min_beds','max_beds','min_baths','max_baths','adv_property_types','price_min','price_max','counties','adv_property_subs','dsearch','search_type','order_by','beds','baths','cities','lang','currency','studio','zips','mls_num','mls_submit','dev_type','counties[]');
    $dont_count = array('sqft','built_before','built_after','zips','mls_num','dev_type');
    if(isset($_GET['page'])){
      foreach($_GET as $inp=>$val){

        if(in_array($inp,$dont_count)){

            if(is_array($val) && $val[0] != 0){
                $advfilters++;
            }elseif(!is_array($val) && $val != 0 && $val != ''){
                $advfilters++;
            }elseif($inp == 'mls_num' && ($val != '' || $val != 0)){
              $advfilters++;
            }
        }
        if(!in_array($inp,$dont_add)){
            if(is_array($val)){
            foreach($val as $ll=>$nn){  ?>
                <input type="hidden" name="<?= $inp ?>[<?= $ll ?>]" value="<?= $nn ?>">
                <?php } }else{ ?>
                <input type="hidden" name="<?= $inp ?>" value="<?= $val ?>">
         <?php } } } } ?>

    <input type="hidden" name="order_by" id="order_by" value="<?= $listings->SearchParams->OrderBy ?>" />
    <input type="hidden" name="search_type" value="advanced" />



    <?php if($advfilters != 0){ ?>
    <div id="adv_filters mb-3">
        <h5 class="open-sans bold">Advanced filters</h5>
        <?php if($listings->SearchParams->SqFt != 0){ ?>
        <div class="adv-filter shadow-sm">
            <input type="hidden" name="sqft" value="<?= $listings->SearchParams->SqFt ?>">
            <span class="remove-filter" data-id="sqft" data-val="<?= $listings->SearchParams->SqFt ?>" data-type="str"><i class="bi bi-x"></i></span> <?= number_format($listings->SearchParams->SqFt) ?>+ ft&sup2;
        </div>
        <?php } if($listings->SearchParams->BuiltBefore != 0){ ?>
        <div class="adv-filter shadow-sm" title="remove filter">
            <input type="hidden" name="built_before" value="<?= $listings->SearchParams->BuiltBefore ?>">
            <span class="remove-filter" data-id="built_before" data-val="<?= $listings->SearchParams->BuiltBefore ?>" data-type="str"><i class="bi bi-x"></i></span> Built before <b><?= $listings->SearchParams->BuiltBefore ?></b>
        </div>
        <?php } if($listings->SearchParams->BuiltAfter != 0){ ?>
        <div class="adv-filter shadow-sm" title="remove filter">
            <input type="hidden" name="built_after" value="<?= $listings->SearchParams->BuiltAfter ?>">
            <span class="remove-filter" data-id="built_after" data-val="<?= $listings->SearchParams->BuiltAfter ?>" data-type="str"><i class="bi bi-x"></i></span> Built after <b><?= $listings->SearchParams->BuiltAfter ?></b>
        </div>
        <?php } if(!empty($listings->SearchParams->Zips) && $listings->SearchParams->Zips[0] != 0){ foreach($listings->SearchParams->Zips as $zz){ ?>
        <div class="adv-filter shadow-sm" title="remove filter">
            <input type="hidden" name="zips[]" value="<?= $zz ?>">
            <span class="remove-filter" data-id="zips" data-val="<?= $zz ?>" data-type="arr"><i class="bi bi-x"></i></span> Zip code <b><?= $zz ?></b>
        </div>
      <?php } } if($listings->SearchParams->MLS != ''){ ?>
        <div class="adv-filter shadow-sm" title="remove filter">
            <span class="remove-filter" data-id="mls_num" data-val="<?= $listings->SearchParams->MLS ?>" data-type="str"><i class="bi bi-x"></i></span> MLS Lookup <b><?= $listings->SearchParams->MLS ?></b>
        </div>
      <?php } 
      if($listings->SearchParams->DevType != '' && $listings->SearchParams->DevType != 'after'){ ?>
        <div class="adv-filter shadow-sm" title="remove filter">
            <span class="remove-filter" data-id="dev-type" data-val="<?= $listings->SearchParams->DevType ?>" data-type="str"><i class="bi bi-x"></i></span> <?= $listings->SearchParams->DevType ?></b>
        </div>
      <?php } ?> 

    </div>
    <?php } ?>



    <div id="list_filters">
      <?php if(empty($listings->SearchParams->Cities) || $listings->SearchParams->Cities[0] == 0 || count($listings->SearchParams->Cities) > 1){
        $cities = $listings->getLocations2();
        if($listings->SearchParams->SearchBy != 'zip'){
         ?>
      <div class="list-filter">
          <h5><?= $lan['side']['cities'] ?></h5>
          <?php
          if(!empty($cities)){ ksort($cities);
             foreach($cities as $key=>$cc){
               if(in_array($key,$listings->SearchParams->Cities)){ $sel = 'checked'; }else{ $sel = ''; }
               if(isset($listings->SideBar['cities'][$key])){
                ?>
          <div class="checkbox">
             <input type="checkbox" class="redo-form" name="cities[]" <?= $sel ?> id="cities<?= $key ?>" value="<?= $key ?>">
             <label for="cities<?= $key ?>"><?= $cc['loc'] ?> <small>(<?= $cc['cnt'] ?>)</small></label>
          </div>
        <?php } } } ?>
      </div>
    <?php } } ?>


      <?php if(!empty($adv_search->SidebarTowns) && !empty($listings->SideBar['counties'])){ ?>
      <div class="list-filter">
          <h5><?= $lan['side']['towns'] ?></h5>
          <?php
          if(!empty($adv_search->SidebarTowns)){ ksort($adv_search->SidebarTowns);
             foreach($adv_search->SidebarTowns as $key=>$cc){
                if(in_array($key,$listings->SearchParams->Counties)){ $sel = 'checked'; }else{ $sel = ''; }
                if(isset($listings->SideBar['counties'][$key])){
                ?>
          <div class="checkbox">
             <input type="checkbox" class="redo-form" name="counties[]" id="towns<?= $key ?>" value="<?= $key ?>" <?= $sel ?>>
             <label for="towns<?= $key ?>"><?= $cc ?> <small>(<?= $listings->SideBar['counties'][$key] ?>)</small></label>
          </div>
        <?php }
       } } ?>
      </div>
    <?php } ?>

      <?php if(!empty($listings->SearchParams->Counties) && !empty($listings->SideBar['areas'])){
         $adv_search->getAreas($listings->SearchParams->Counties);
         ?>
        <div class="list-filter">
            <h5><?= $lan['side']['areas'] ?></h5>
            <?php
            if(!empty($adv_search->SidebarAreas)){ ksort($adv_search->SidebarAreas);
               foreach($adv_search->SidebarAreas as $key=>$cc){
                  if(in_array($key,$listings->SearchParams->Areas)){ $sel = 'checked'; }else{ $sel = ''; }
                  if(isset($listings->SideBar['areas'][$key])){
                  ?>
            <div class="checkbox">
                <input type="checkbox" class="redo-form" name="areas[]" id="areas<?= $key ?>" value="<?= $key ?>" <?= $sel ?>>
                <label for="areas<?= $key ?>"><?= $cc.' ('.$listings->SideBar['areas'][$key].')' ?></label>
            </div>
         <?php } } } ?>
        </div>
      <?php } ?>
        <!-- END OF CITY FILTER -->
        <?php if(!empty($listings->SideBar['ptypes'])){ ?>
        <div class="list-filter">
            <h5><?= $lan['side']['ptypes'] ?></h5>
            <?php
            if(!empty($property_types)){
               foreach($property_types as $pp=>$tt){
                  if(in_array($pp,$listings->SearchParams->PropTypes)){ $sel = 'checked'; }else{ $sel = ''; }
                  if(isset($listings->SideBar['ptypes'][$pp])){
                  ?>
                <div class="checkbox">
                    <input type="checkbox" class="ptypes" id="ptypes<?= $pp ?>" <?= $sel ?> class="redo-form" name="adv_property_types[]" value="<?= $pp ?>">
                    <label for="ptypes<?= $pp ?>"><?= $tt['desc'].' <small>('.$listings->SideBar['ptypes'][$pp].')</small>' ?></label>
                    <div class="clearfix"></div>
                </div>
            <?php } } } ?>
        </div>
      <?php } ?>
        <!-- END OF PROPERTY TYPES -->


        <?php if(!empty($listings->SearchParams->PropTypes)){
          $subs = array();
          if(!empty($property_types)){
             foreach($property_types as $pp=>$tt){
                if(in_array($pp,$listings->SearchParams->PropTypes)){
                  if(!empty($tt['subs'])){
                    foreach($tt['subs'] as $yy=>$zz){
                      $subs[$yy] = $zz;
                    }
                  }
                 }
                }
              }
              if(!empty($subs)){
           ?>
            <div class="list-filter">
                <h5><?= $lan['side']['pstypes'] ?></h5>
                <?php
                foreach($subs as $kk=>$ss){
                if(in_array($kk,$listings->SearchParams->PropSubTypes)){ $sel = 'checked'; }else{ $sel = ''; }
                      if(isset($listings->SideBar['pstypes'][$kk])){
                        if($ss == ''){
                          $ss = $lan['adv']['other'];
                        }
                      ?>
                    <div class="checkbox">
                        <input type="checkbox" class="pstypes" id="pstypes<?= $kk ?>" <?= $sel ?> class="redo-form" name="adv_property_subs[]" value="<?= $kk ?>">
                        <label for="pstypes<?= $kk ?>"><?= $ss.' <small>('.$listings->SideBar['pstypes'][$kk].')</small>' ?></label>
                        <div class="clearfix"></div>
                    </div>
                <?php } } ?>
            </div>
      <?php }}  ?>
        <!-- END OF PROPERTY SUB TYPES -->


        <?php
        $dont_show_beds = 0;
        if(count($listings->SearchParams->PropTypes) == 1 && $listings->SearchParams->PropTypes[0] == 21){
          $dont_show_beds = 1;
        } if($dont_show_beds == 0){ ?>
        <!-- BEDROOM COUNTER -->
        <div class="list-filter" style="margin-top: 32px;">
            <h5><?= $lan['side']['bb'] ?></h5>
            <div class="studio-check">
              <div class="checkbox">

                <input type="checkbox" name="studio" id="check_studio" <?php if($listings->SearchParams->Studio == 1){ echo 'checked'; } ?> value="1" />
                <label for="check_studio"><?= $lan['props']['studios'] ?></label>
              </div>
            </div>
            <div class="loft-check">
              <div class="checkbox">

                <input type="checkbox" name="loft" id="check_loft" <?php if($listings->SearchParams->Loft == 1){ echo 'checked'; } ?> value="1" />
                <label for="check_loft"><?= $lan['props']['loft'] ?></label>
              </div>
            </div>
            <div class="counter-container">
               <div class="row">
                  <div class="col-5">
                     <div class="title"><?= $lan['side']['bb_a'][0] ?></div>
                  </div>
                  <div class="col-7">
                     <div class="incremental-input input-group" id="bed_input">
                       <span class="input-group-btn">
                         <button type="button" class="btn btn-white btn-increment" data-type="minus" data-input="min_be" data-operator="-">-</button>
                       </span>
                       <input type="text" class="form-control" id="min_be" name="min_beds" aria-label="Min number of bedrooms" value="<?php if($listings->SearchParams->MinBeds == ''){ echo '0'; }else{ echo $listings->SearchParams->MinBeds; } ?>">
                       <span class="input-group-btn">
                         <button type="button" class="btn btn-white btn-increment" data-type="plus" data-input="min_be" data-operator="+">+</button>
                       </span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="counter-container">
               <div class="row">
                  <div class="col-5">
                     <div class="title"><?= $lan['side']['bb_a'][1] ?></div>
                  </div>
                  <div class="col-7">
                    <div class="incremental-input input-group" id="bath_input">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-white btn-increment" data-type="minus" data-input="min_ba" data-operator="-">-</button>
                      </span>
                      <input type="text" class="form-control" id="min_ba" name="min_baths" aria-label="Min number of bathrooms" value="<?php if($listings->SearchParams->MinBaths == ''){ echo '0'; }else{ echo $listings->SearchParams->MinBaths; } ?>">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-white btn-increment" data-type="plus" data-input="min_ba" data-operator="+">+</button>
                      </span>
                    </div>
                  </div>
               </div>
            </div>



        </div>
        <!-- END OF BEDROOMS & BATHROOMS -->
        <?php } ?>




        <?php

        $price_r = array(round($price_range[0]),round($price_range[1]));
        if($listings->SearchParams->MinPrice != 0 && $listings->SearchParams->MinPrice != ''){
            $price_r[0] = $listings->SearchParams->MinPrice;
        }
        if($listings->SearchParams->MaxPrice != 0 && $listings->SearchParams->MaxPrice != ''){
            $price_r[1] = $listings->SearchParams->MaxPrice;
        }
        //print_me($price_r);
        $min_price = str_replace(',','',$price_r[0]) * $curr;
        $max_price = str_replace(',','',$price_r[1]) * $curr;
        ?>


        <div class="list-filter">
          <h5 class="min-max-header mb-3"><?= $lan['side']['prange'] ?></h5>

          <div class="row min-max-price">
            <div class="col-12">
              <div class="input-group mb-3">
                <span class="input-group-text">Min $</span>
                <input type="text" class="form-control money" name="price_min" id="minpr" value="<?= $listings->SearchParams->MinPrice ?>">
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
                  <li><a class="dropdown-item other" href="#">Other</a></li>
                </ul>
              </div>
            </div>

            <div class="col-12">
              <div class="input-group mb-3">
                <span class="input-group-text">Max $</span>
                <input type="text" class="form-control money" name="price_max" id="maxpr" value="<?= $listings->SearchParams->MaxPrice ?>">
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
                  <li><a class="dropdown-item other" href="#">Other</a></li>
                </ul>
              </div>
            </div>


            <div class="col-12 update-sidebar text-end">
              <button type="button" id="upit" class="btn btn-sm"><i class="bi bi-arrow-clockwise"></i> Update Price</button>
            </div>
          </div>
        </div>




        <!-- <div class="list-filter" id="prange" data-min="<?= str_replace(',','',$price_r[0]) ?>" data-max="<?= str_replace(',','',$price_r[1]) ?>">
            <h5 class="min-max-header"><?= $lan['side']['prange'] ?></h5>
            <div class="min-max-inputs row">
                <div class="priceInput col-5">
                   <input type="text" name="price_min" value="<?= str_replace(',','',$price_r[0]) ?>" id="price_min" class="priceInput" />
                </div>
                <div class="col-2 text-center">to</div>
                <div class="priceInput col-5 text-end">
                   <input type="text" name="price_max" value="<?= str_replace(',','',$price_r[1]) ?>" id="price_max" class="priceInput text-end" />
                </div>
            </div>
            <div class="priceSlider"></div>
            <div class="priceSliderLabel">
               <span class="label-min">$<?= number_format($min_price) ?> <small><?= $curr_desc ?></small></span>
               <span class="label-max">$<?= number_format($max_price) ?> <small><?= $curr_desc ?></small></span>
            </div>
        </div> -->
    </div>
</form>
