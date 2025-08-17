<?php
function compareByName($a, $b) {
  return strcmp($a["name"], $b["name"]);
}

?>
    <input type="hidden" name="search_type" id="stype" value="advanced">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="location" value="0" />

    <h4 class="h4-div"><?= $lan['adv']['stype_h'] ?></h4>
    <p class="form-helper"><?= $lan['adv']['stype_t'] ?></p>

    <div class="form-group col-md-6">
        <select name="search_by" class="formDropdown sm-drop form-control shadow-sm" id="search_type">
          <?php foreach($lan['adv']['stype_ops'] as $key=>$val){
            $sel = '';
            if($key == 'all'){
              $sel = 'selected';
            }
            echo '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
          } ?>

        </select>
    </div>

    <!-- City search -->
    <?php
    $lan_ci = translateAdv('city');
    $lan_ar = translateAdv('area');
    $lan_to = translateAdv('town');
    $lan_zi = translateAdv('zip');
    ?>
    <div class="adv-search-drop" id="city">
       <div class="row">
           <div class="col-md-12">
               <h5><?= $lan_ci[0] ?> <small><?= $lan_ci[1] ?></small></h5>
           </div>
       </div>
        <div class="row">
            <?php if(!empty($adv_search->SCities)){ ?>
            <div class="col-md-4 col-sm-6">
                <div class="form-check">
                    <input type="checkbox" name="cities[]" id="ci0" value="0" id="all_cities" class="form-check-input show-all-inp" checked>
                    <label class="form-check-label"><?= $lan_ci[2] ?></label>
                </div>
            </div>

            <!--   ksort($adv_search->SCities);  -->
            <?php
            foreach($adv_search->SCities as $key=>$cc){
              ?>
                <div class="col-md-4 col-sm-6">
                    <div class="form-check">
                        <input type="checkbox" name="cities[]" id="ci<?= $key ?>" class="form-check-input" value="<?= $key ?>">
                        <label for="ci<?= $key ?>" class="form-check-label"><?= $cc['name'].' ('.$cc['cnt'].')' ?></label>
                    </div>
                </div>
            <?php } }else{ ?>
            <div class="alert alert-warning"><h4><?= $lan_ci[3] ?></h4><p><?= $lan_ci[4] ?></p></div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-12">
               <hr>
                <p class="open-sans"><?= $lan_ci[5] ?></p>
            </div>
        </div>
    </div>
    <!-- End of city search -->



    <!-- Area/Town search -->
    <div class="adv-search-drop" id="area">
       <div class="row">
           <div class="col-md-12">
               <h5><?= $lan_ar[0] ?> <small><?= $lan_ar[1] ?></small></h5>
           </div>
       </div>
        <div class="row">
            <?php //print_me($adv_search->DAreas);
            if(!empty($adv_search->DAreas)){
              $arr_cnt = ceil((count($adv_search->SAreas) + count($adv_search->DAreas) * 4) / 4);
              $dareas = array();
              foreach($adv_search->DAreas as $xx=>$yy){

                $dareas[$xx] = utf8_encode($adv_search->SCities[$xx]['name']);
              }

              uasort($dareas, 'compareByName');


              $e = 0;
              $cll = 0;

              ?>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="areas[]" value="0" id="all_areas" checked>
                    <label class="form-check-label" for="all_areas"><?= $lan_ar[2] ?></label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3 col-sm-6">
            <?php foreach($dareas as $ww=>$zz){
              $dd = $adv_search->DAreas[$ww];
              usort($dd, 'compareByName');
              if($e == 0){ $cl = 'class="first"'; }else{ $cl = ''; }
              if($ww == 1810 && $cll == 0){
                echo '</div><div class="col-md-3 col-sm-6">';
                $cll = 1;
                $cl = 'class="first"';
                $e = 0;
              }
              echo '<h4 '.$cl.'>'.$zz.'</h4>';

              foreach($dd as $key=>$cc){
                if($e == $arr_cnt){ $e = 1;
                  $cc['parent'] = utf8_encode($cc['parent']);
                  echo '</div><div class="col-md-3 col-sm-6">'; } if(strtolower($cc['name']) != 'undefined'){ ?>
                  <div class="form-check">
                      <input type="checkbox" class="form-check-input" name="areas[]" id="ar<?= $cc['id'] ?>" value="<?= $cc['id'] ?>">
                      <label class="form-check-label" for="ar<?= $cc['id'] ?>"><?= $cc['name'].' ('.$cc['cnt'].')' ?>
                        <?php if(strtolower($cc['parent']) != strtolower(utf8_encode($zz))){ ?> <small><?= $cc['parent'] ?></small>
                      <?php } ?>
                      </label>
                  </div>
            <?php $e++; } } $e+5; } echo '</div>'; }else{ ?>
          </div>
          <div class="alert alert-warning"><h4><?= $lan_ar[3] ?></h4><p><?= $lan_ar[4] ?></p></div>
          <?php } ?>


        <div class="row">
            <div class="col-md-12">
               <hr>
                  <p class="open-sans"><?= $lan_ar[5] ?></p>
            </div>
        </div>
    </div>
    </div>
    <!-- End of Area/Town search -->



    <!-- County search -->
    <div class="adv-search-drop" id="county">
       <div class="row">
           <div class="col-md-12">
               <h5 class="open-sans bold"><?= $lan_to[0] ?> <small><?= $lan_to[1] ?></small></h5>
           </div>
       </div>
        <div class="row">
            <?php
            if(!empty($adv_search->DCounties)){
              $arr_cnt = ceil((count($adv_search->DCounties) + count($adv_search->DCounties) * 4) / 4);
              $dcounties = array();
              foreach($adv_search->DCounties as $xx=>$yy){
                $dcounties[$xx] = utf8_encode($adv_search->SCities[$xx]['name']);
              }

              uasort($dcounties, 'compareByName');


              $e = 0;
              $cll = 0;
              ?>
            <div class="col-sm-6 mb-3">
                <div class="form-check">
                    <input type="checkbox" name="counties[]" class="form-check-input" value="0" id="all_counties" checked>
                    <label class="input-check-label" for="all_counties"><?= $lan_to[2] ?></label>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-3 col-sm-6">
            <?php foreach($dcounties as $ww=>$zz){
              $dd = $adv_search->DCounties[$ww];
              usort($dd, 'compareByName');
              if($e == 0){ $cl = 'class="first"'; }else{ $cl = ''; }
              if($ww == 1810 && $cll == 0){
                echo '</div><div class="col-md-3 col-sm-6">';
                $cll = 1;
                $cl = 'class="first"';
                $e = 0;
              }
              echo '<h4 '.$cl.'>'.$zz.'</h4>';

              foreach($dd as $key=>$cc){
                if($e == $arr_cnt){ $e = 1;
                  echo '</div><div class="col-md-3 col-sm-6">'; } if(strtolower($cc['name']) != 'undefined'){ ?>
                  <div class="form-check">
                      <input type="checkbox" class="form-check-input" name="counties[]" id="co<?= $cc['id'] ?>" value="<?= $cc['id'] ?>">
                      <label class="form-check-label" for="co<?= $cc['id'] ?>"><?= $cc['name'].' ('.$cc['cnt'].')' ?>
                        <?php if(strtolower($cc['parent']) != strtolower($zz)){ ?> <small><?= $cc['parent'] ?></small>
                      <?php } ?>
                      </label>
                  </div>
            <?php $e++; } } $e+5; } echo '</div>'; }else{ ?>
          </div>
          <div class="alert alert-warning"><h4><?= $lan_ar[3] ?></h4><p><?= $lan_ar[4] ?></p></div>
          <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-12">
               <hr>
                <p class="open-sans"><?= $lan_to[5] ?></p>
            </div>
        </div>
    </div>
    <!-- End of County search -->



    <!-- Zip code search -->
    <div class="adv-search-drop" id="zip">
       <div class="row">
           <div class="col-md-12">
               <h5 class="open-sans bold"><?= $lan_zi[0] ?> <small><?= $lan_zi[1] ?></small></h5>
           </div>
       </div>
        <div class="row">
            <?php if(!empty($adv_search->SZips)){  ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="form-check">
                    <input type="checkbox" name="zips[]" class="form-check-input" value="0" id="all_zips" checked>
                    <label class="form-check-label" for="all_zips"><?= $lan_zi[2] ?></label>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php foreach($adv_search->SZips as $key=>$cc){ ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="form-check">
                        <input type="checkbox" name="zips[]" class="form-check-input" id="zi<?= $key ?>" value="<?= $key ?>">
                        <label class="form-check-label" for="zi<?= $key ?>"><?= $cc['name'].' ('.$cc['cnt'].')' ?></label>
                    </div>
                </div>
            <?php } }else{ ?>
            <div class="alert alert-warning"><h4><?= $lan_zi[3] ?></h4><p><?= $lan_zi[4] ?></p></div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-12">
               <hr>
                <p class="open-sans"><?= $lan_zi[5] ?></p>
            </div>
        </div>
    </div>
    <!-- End of Zip code search -->


    <!-- MLS search -->
    <div class="adv-search-drop" id="mls">
       <div class="row">
           <div class="col-md-12">
               <h5 class="open-sans bold"><?= $lan['adv']['mls_h'] ?> <small class="mb-0"><?= $lan['adv']['mls_t'] ?></small></h5>
           </div>
       </div>
       <br>
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="mls_num" class="form-control" placeholder="Ex. 234190">
                    <button type="submit" name="mls_submit" class="buttonColor smaller btn btn-primary"><?= $lan['adv']['search'] ?></button>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <p class="open-sans"><?= $lan['adv']['mls_d'] ?></p>
            </div>
        </div>
    </div>
    <!-- MLS search end -->


    <div id="non_mls">

        <h4 class="h4-div"><?= $lan['adv']['filt_h'] ?></h4>
        <p class="form-helper"><?= $lan['adv']['filt_t'] ?></p>

        <!-- PROPERTY TYPES -->
        <div class="row">
            <div class="col-md-6">
                <div class="checkbox-box">
                    <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][0] ?></h5>
                    <div class="row" id="prop_types">
                        <?php if(!empty($property_types)){ ?>
                        <div class="col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" id="all_ptypes" class="ptypes form-check-input" name="adv_property_types[]" data-id="all_ptypes" value="0" checked>
                                <label class="form-check-label" for="all_ptypes"><?= $lan['adv']['all'] ?></label>
                            </div>
                        </div>
                        <?php foreach($property_types as $pp=>$tt){ ?>
                        <div class="col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="ptypes form-check-input" name="adv_property_types[]" data-id="<?= $pp ?>" id="in<?= $pp ?>" value="<?= $pp ?>">
                                <label class="form-check-label" for="in<?= $pp ?>"><?= $tt['desc'] ?></label>
                            </div>
                        </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>

            <!-- Property sub types -->
            <div class="col-md-6">
                <div class="checkbox-box" id="prop_subs">
                    <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][1] ?></h5>

                    <!-- subtype select message -->
                    <div class="row" id="sub_message">
                        <div class="col-lg-1 col-md-2 col-sm-3 text-center">
                            <i class="bi bi-arrow-left"></i>
                        </div>
                        <div class="col-lg-11 col-md-10 col-sm-9">
                            <p><?= $lan['adv']['stypes_t'] ?></p>
                        </div>
                    </div>
                    <!-- subtype select message end -->

                    <!-- subtype display boxes -->
                    <div id="sub_results">
                        <?php
                         if(!empty($property_types)){
                          foreach($property_types as $pp=>$tt){ ?>
                        <div class="subtype" id="sub<?= $pp ?>">
                            <div class="checkbox-header"><?= $tt['desc'] ?></div>
                            <div class="row">

                            <?php if(!empty($tt['subs'])){
                              echo '<div class="col-12">';
                              $o=0;
                              $arrcnt = count($tt['subs']);
                              $new_cnt = ceil($arrcnt / 2);
                              foreach($tt['subs'] as $kk=>$vv){
                                if($arrcnt > 4){
                                  if($o == 0){ echo '</div><div class="col-sm-6">'; }
                                  if($o == $new_cnt){ echo '</div><div class="col-sm-6">'; }
                                }
                                ?>
                            <div class="form-check">
                                <input type="checkbox" name="adv_property_subs[]" id="sub<?= $kk ?>" data-id="<?= $kk ?>" value="<?= $kk ?>" class="form-check-input">
                                <label class="form-check-label" for="sub<?= $kk ?>"><?= $vv ?></label>
                            </div>
                            <?php $o++; } echo '</div>'; }else{ ?>
                            <div class="form-check">
                                <label><?= $lan['adv']['stypes_no'] ?></label>
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                        <?php } } ?>
                    </div>
                    <!-- subtype display boxes end -->

                </div>
            </div>
        </div>
        <!-- PROPERTY TYPES end -->

        <!-- PROPERTY FILTERS -->

        <div class="row">

             <!-- Bed count selection -->
              <div class="col-md-3">
                <div class="form-filter">
                  <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][2] ?></h5>
                  <div class="row">
                      <div class="col-6">
                          <div class="form-group">
                              <label>min</label>
                              <select name="min_beds" class="formDropdown form-control">
                                  <option value="0" selected><?= $lan['adv']['any'] ?></option>
                                  <option value="555">Studio</option>
                                  <option value="666">Loft</option>
                                  <?php if(!empty($adv_search->MinMax)){
                                          for($i=$adv_search->MinMax['beds'][0];$i<=$adv_search->MinMax['beds'][1];$i++){ if($i > 0){
                                              echo '<option>'.$i.'</option>';
                                          } } } ?>
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label>max</label>
                              <select name="max_beds" class="formDropdown form-control">
                                  <option value="0" selected><?= $lan['adv']['any'] ?></option>
                                  <?php if(!empty($adv_search->MinMax)){
                                          for($i=$adv_search->MinMax['beds'][0];$i<=$adv_search->MinMax['beds'][1];$i++){ if($i > 0){
                                              echo '<option>'.$i.'</option>';
                                          } } } ?>
                              </select>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
              <!-- Bed count selection end -->

              <!-- Bathroom count selection -->
              <div class="col-md-3">
                <div class="form-filter">
                  <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][3] ?></h5>
                  <div class="row">
                      <div class="col-6">
                          <div class="form-group">
                              <label>min</label>
                              <select name="min_baths" class="formDropdown form-control">
                                  <option value="0" selected><?= $lan['adv']['any'] ?></option>
                                  <?php if(!empty($adv_search->MinMax)){
                                          for($i=$adv_search->MinMax['baths'][0];$i<=$adv_search->MinMax['baths'][1];$i++){ if($i > 0){
                                              echo '<option>'.$i.'</option>';
                                          } } } ?>
                              </select>
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label>max</label>
                              <select name="max_baths" class="formDropdown form-control">
                                  <option value="0" selected><?= $lan['adv']['any'] ?></option>
                                  <?php if(!empty($adv_search->MinMax)){
                                          for($i=$adv_search->MinMax['baths'][0];$i<=$adv_search->MinMax['baths'][1];$i++){ if($i > 0){
                                              echo '<option>'.$i.'</option>';
                                        }  } } ?>
                              </select>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
              <!-- Bathroom count selection end -->

              <!-- Square feet selection -->
              <div class="col-md-3">
                <div class="form-filter">
                  <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][4] ?></h5>
                  <div class="form-group">
                    <label><?= $lan['adv']['min_sq'] ?></label>
                    <select name="sqft" class="formDropdown form-select">
                        <option value="0"><?= $lan['adv']['any'] ?></option>
                        <option value="1000">1,000+</option>
                        <option value="1500">1,500+</option>
                        <option value="2000">2,000+</option>
                        <option value="2500">2,500+</option>
                        <option value="3000">3,000+</option>
                        <option value="3500">3,500+</option>
                        <option value="4000">4,000+</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- Square feet selection end -->

          </div>



        <div class="row">


           <!-- Price range selection -->
            <div class="col-md-4">
              <div class="form-filter">
               <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][5] ?></h5>
                <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                            <label>min</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" name="price_min" class="form-control money" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                            <label>max</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" name="price_max" class="form-control money" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- Price range selection end -->

            <!-- Built from range selection -->
            <div class="col-md-6">
              <div class="form-filter">
                 <h5 class="open-sans bold"><?= $lan['adv']['filt_heads'][6] ?></h5>
                  <div class="row pt-3 pb-4">
                      <div class="col-5">

                          <div class="form-check">
                            <input type="radio" name="dev_type" class="form-check-input devlev" value="construction">
                            <label><?= $lan['adv']['built'][0] ?></label>
                          </div>
                      </div>
                      <div class="col-3">

                          <div class="form-check">
                              <input type="radio" name="dev_type" class="form-check-input devlev" value="built">
                              <label><?= $lan['adv']['built'][1] ?></label>
                          </div>
                      </div>
                      <div class="col-4">
                        <div class="form-check">
                            <input type="radio" name="dev_type" class="form-check-input devlev" value="after">
                            <label><?= $lan['adv']['built'][2] ?></label>
                        </div>
                         <div class="form-group collapse" id="after_drop">
                           <input type="number" class="form-control" name="built_after" placeholder="<?= date('Y') ?>" id="bu_after" />
                          </div>
                      </div>
                  </div>
              </div>
            <!-- Built from range selection end -->
            </div>
        </div>

    </div>
