<div class="mob-filter-head">
     <h5>Filter Results</h5>

     <button id="close_adv" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
           <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
         </svg>
     </button>
     <button id="up_adv" class="btn">update results</button>
 </div>

<form action="listings2" method="get" id="search_sidebar">
    <?php
    $advfilters = 0;
    $dont_add = array('areas','min_beds','max_beds','min_baths','max_baths','adv_property_types','price_min','price_max','counties','adv_property_subs');
    $dont_count = array('sqft','built_before','built_after','zips');
    if(isset($_GET['page'])){
      foreach($_GET as $inp=>$val){
        if(in_array($inp,$dont_count)){
            if(is_array($val) && $val[0] != 0){
                $advfilters++;
            }elseif(!is_array($val) && $val != 0 && $val != ''){
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
    <?php if($advfilters != 0){ ?>
    <div id="adv_filters" style="margin-bottom: 22px;">
        <h5 class="open-sans bold">Advanced filters applied</h5>
        <?php if($listings->SearchParams->SqFt != 0){ ?>
        <div class="adv-filter">
            <span class="remove-filter" data-id="sqft" data-val="<?= $listings->SearchParams->SqFt ?>" data-type="str"><i class="fa fa-close"></i></span> <?= number_format($listings->SearchParams->SqFt) ?> or more sq ft
        </div>
        <?php } if(!empty($listings->SearchParams->Zips) && $listings->SearchParams->Zips[0] != 0){ foreach($listings->SearchParams->Zips as $zz){ ?>
        <div class="adv-filter">
            <span class="remove-filter" data-id="zips" data-val="<?= $zz ?>" data-type="arr"><i class="fa fa-close"></i></span> Properties in zip <b><?= $zz ?></b>
        </div>
        <?php } } if(!empty($listings->SearchParams->Cities) && $listings->SearchParams->Cities[0] != 0){ foreach($listings->SearchParams->Cities as $zz){ ?>
        <div class="adv-filter">
            <span class="remove-filter" data-id="cities" data-val="<?= $zz ?>" data-type="arr"><i class="fa fa-close"></i></span> Properties in <b><?= $zz ?></b>
        </div>
        <?php } } if(!empty($listings->SearchParams->Counties) && $listings->SearchParams->Counties[0] != 0){ foreach($listings->SearchParams->Counties as $zz){ ?>
        <div class="adv-filter">
            <span class="remove-filter" data-id="counties" data-val="<?= $zz ?>" data-type="arr"><i class="fa fa-close"></i></span> Properties in <b><?= $zz ?></b>
        </div>
        <?php } } ?>
    </div>
    <?php } ?>

    <div id="list_filters">
      <?php if(empty($listings->SearchParams->Cities) || $listings->SearchParams->Cities[0] == 0){
        $cities = $listings->getLocations2();
         ?>
      <div class="list-filter">
          <h5>Cities</h5>
          <?php
          if(!empty($cities)){ ksort($cities);
             foreach($cities as $key=>$cc){ ?>
          <div class="checkbox">
             <input type="checkbox" class="redo-form" name="cities[]" id="cities<?= $key ?>" value="<?= $key ?>">
             <label for="cities<?= $key ?>"><?= $cc['loc'] ?> <small>(<?= $cc['cnt'] ?>)</small></label>
          </div>
        <?php } } ?>
      </div>
    <?php } ?>


      <?php if(!empty($adv_search->SidebarTowns)){ ?>
      <div class="list-filter">
          <h5>Towns</h5>
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
        <?php } } } ?>
      </div>
    <?php } ?>

      <?php if(!empty($listings->SearchParams->Counties)){
         $adv_search->getAreas($listings->SearchParams->Counties);
         ?>
        <div class="list-filter">
            <h5>Areas</h5>
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

        <div class="list-filter">
            <h5>Property Type</h5>
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
        <!-- END OF PROPERTY TYPES -->


        <?php if(!empty($listings->SearchParams->PropTypes)){ ?>
        <div class="list-filter">
            <h5>Property Sub Type</h5>
            <?php
            if(!empty($property_types)){
               foreach($property_types as $pp=>$tt){
                  if(in_array($pp,$listings->SearchParams->PropTypes)){
                    if(!empty($tt['subs'])){
                      foreach($tt['subs'] as $kk=>$ss){
                        if(in_array($kk,$listings->SearchParams->PropSubTypes)){ $sel = 'checked'; }else{ $sel = ''; }
                        if(isset($listings->SideBar['pstypes'][$kk])){
                        ?>
                      <div class="checkbox">
                          <input type="checkbox" class="pstypes" id="pstypes<?= $kk ?>" <?= $sel ?> class="redo-form" name="adv_property_subs[]" value="<?= $kk ?>">
                          <label for="pstypes<?= $kk ?>"><?= $ss.' <small>('.$listings->SideBar['pstypes'][$kk].')</small>' ?></label>
                          <div class="clearfix"></div>
                      </div>
                  <?php }
                      }
                    }
                   }
                   } } ?>
        </div>
      <?php } ?>
        <!-- END OF PROPERTY SUB TYPES -->


        <!-- BEDROOM COUNTER -->
        <div class="list-filter" style="margin-top: 32px;">
            <h5>Beds &amp; Baths</h5>
            <div class="counter-container">
               <div class="row">
                  <div class="col-5">
                     <div class="title">Min Beds</div>
                  </div>
                  <div class="col-7">
                     <div class="incremental-input input-group" id="bed_input">
                       <span class="input-group-btn">
                         <button type="button" class="btn btn-white btn-increment" data-type="minus" data-input="min_be" data-operator="-">-</button>
                       </span>
                       <input type="text" class="form-control" id="min_be" name="min_beds" aria-label="Min number of bedrooms" value="<?= $listings->SearchParams->MinBeds ?>">
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
                     <div class="title">Min Baths</div>
                  </div>
                  <div class="col-7">
                    <div class="incremental-input input-group" id="bath_input">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-white btn-increment" data-type="minus" data-input="min_ba" data-operator="-">-</button>
                      </span>
                      <input type="text" class="form-control" id="min_ba" name="min_baths" aria-label="Min number of bathrooms" value="<?= $listings->SearchParams->MinBaths ?>">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-white btn-increment" data-type="plus" data-input="min_ba" data-operator="+">+</button>
                      </span>
                    </div>
                  </div>
               </div>
            </div>



        </div>
        <!-- END OF BEDROOMS & BATHROOMS -->




        <?php

        $price_r = array(round($price_range[0]),round($price_range[1]));
        if($listings->SearchParams->MinPrice != 0 && $listings->SearchParams->MinPrice != ''){
            $price_r[0] = $listings->SearchParams->MinPrice;
        }
        if($listings->SearchParams->MaxPrice != 0 && $listings->SearchParams->MaxPrice != ''){
            $price_r[1] = $listings->SearchParams->MaxPrice;
        }
        ?>

        <div class="list-filter" id="prange" data-min="<?= str_replace(',','',$price_range[0]) ?>" data-max="<?= str_replace(',','',$price_range[1]) ?>">
            <h5 class="min-max-header">Price Range</h5>
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
               <span class="label-min">$<?= number_format($price_range[0]) ?> <small>usd</small></span>
               <span class="label-max">$<?= number_format($price_range[1]) ?> <small>usd</small></span>
            </div>
        </div>
    </div>
</form>
