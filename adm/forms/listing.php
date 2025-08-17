<form action="" method="post" id="new_listing">
    <input type="hidden" name="property_id" value="<?= $prop->PropertyId ?>">
    <div id="img_list">
      <?php
      if(!empty($prop->Photos)){
        $o=0;
        foreach($prop->Photos as $key=>$val){
          echo '<input type="hidden" name="images['.$o.']" class="img-input" value="'.$val[1].'" />';
          $o++;
        }
      }
      ?>
    </div>
    <div class="card shadow-3" id="info">
        <h4 class="card-title">Listing <b>Information</b></h4>
        <div class="card-body">
            <div class="row">
                <div class="col-2 mb-2">
                    <div class="form-group">
                        <label>Visibility</label>
                        <select name="is_visible" class="form-control">
                            <option <?php if($prop->IsVisible == 0){ echo 'selected'; } ?> value="0">Not visible</option>
                            <option <?php if($prop->IsVisible == 1){ echo 'selected'; } ?> value="1">Visible</option>
                        </select>
                    </div>
                </div>
                <div class="col-2 mb-2">
                    <div class="form-group">
                        <label>Listing Status</label>
                        <select name="pr_status" class="form-control">
                            <option <?php if($prop->Status == 'active'){ echo 'selected'; } ?> value="active">Active</option>
                            <option <?php if($prop->Status == 'pending'){ echo 'selected'; } ?> value="pending">Pending</option>
                            <option <?php if($prop->Status == 'sold'){ echo 'selected'; } ?> value="sold">Sold</option>
                        </select>
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <div class="form-group">
                        <label>Listing Type</label>
                        <select name="listing_type" class="form-control">
                            <option value="0" selected>-- Select --</option>
                            <?php foreach($properties->ListingTypes as $key=>$ty){ ?>
                                <option <?php if($prop->ListTypeId == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ty ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="row">
               <div class="col-3">
                    <div class="form-group">
                        <label>MLS #</label>
                        <input type="text" name="mls" class="form-control" value="<?= $prop->MLS ?>">
                        <!--<small class="form-text">In cases where various units are available for the same property, simply separate the individual MLS #s with a comma. This will allow this property to be displayed for all units.</small>-->
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Zoning Type</label>
                        <select name="zone_type" class="form-control">
                            <option value="0" selected>-- Select --</option>
                            <?php foreach($properties->ZoneTypes as $key=>$ty){ ?>
                                <option <?php if($prop->ZoningId == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ty ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Property Type</label>
                        <select name="property_type" class="form-control" id="ptype">
                            <option value="0" selected>-- Select --</option>
                            <?php foreach($properties->PropertyTypes as $key=>$ty){ ?>
                                <option <?php if($prop->PropertyTypeId == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ty['desc'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                  <div id="sub_types_select">
                      <?php if($edit_list == 1 && !empty($properties->PropertyTypes[$prop->PropertyTypeId]['subs'])){ ?>
                       <div class="form-group">
                         <label>Select property sub type</label>
                         <select name="sub_id" class="form-control">
                             <option value="0" selected>-- Select --</option>
                             <?php foreach($properties->PropertyTypes[$prop->PropertyTypeId]['subs'] as $kk=>$vv){ if($kk == $prop->PropertySubTypeId){ $sel = 'selected'; }else{ $sel = ''; } ?>
                             <option <?= $sel ?> value="<?= $kk ?>"><?= $vv ?></option>
                          <?php } ?>
                          </select>
                       </div>
                    <?php } ?>
                  </div>
                </div>


            </div>
            <hr>
            <div class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label style="display: inline-block; margin-right: 13px;">Is this property in foreclosure?</label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input type="radio" name="is_foreclosure" class="form-check-input" <?php if($prop->Foreclosure == 0){ echo 'checked'; } ?> value="0">No</label>
                        </div>
                        <div class="form-group form-check form-check-inline">
                            <label class="form-check-label"><input type="radio" name="is_foreclosure" class="form-check-input" <?php if($prop->Foreclosure == 1){ echo 'checked'; } ?> value="1">Yes</label>
                        </div>
                        <small class="form-text"><em>If this is checked it will be added to the foreclosure search on website.</em></small>
                    </div>
                </div>
                <div class="col-1">
                  <div class="vertical-div"></div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label style="display: inline-block; margin-right: 13px;">Is this property in construction?</label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input type="radio" name="is_construction" class="form-check-input" <?php if($prop->Construction == 0){ echo 'checked'; } ?> value="0">No</label>
                        </div>
                        <div class="form-group form-check form-check-inline">
                            <label class="form-check-label"><input type="radio" name="is_construction" class="form-check-input" <?php if($prop->Construction == 1){ echo 'checked'; } ?> value="1">Yes</label>
                        </div>
                        <small class="form-text"><em>If this is checked it will be added to the construction search on website.</em></small>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <input type="hidden" name="third_bath" value="0" />
    <input type="hidden" name="fourth_bath" value="0" />


    <!-- LISTING DESCRIPTION -->
    <div class="card shadow-3" id="details">
        <h4 class="card-title">Listing <b>Details</b>
          <div class="new-feature">
            <span class="new">new</span> Add information in Spanish.
          </div>
        </h4>
        <div class="card-body">
           <div class="row">
             <div class="col-2">
                  <div class="form-group">
                     <label>Month Built/Completed</label>
                     <select name="month_built" class="form-control">
                       <option value="0" selected>-- Select --</option>
                       <option value="1" <?php if($prop->MonthBuilt == 1){ echo 'selected'; } ?>>January</option>
                       <option value="2" <?php if($prop->MonthBuilt == 2){ echo 'selected'; } ?>>February</option>
                       <option value="3" <?php if($prop->MonthBuilt == 3){ echo 'selected'; } ?>>March</option>
                       <option value="4" <?php if($prop->MonthBuilt == 4){ echo 'selected'; } ?>>April</option>
                       <option value="5" <?php if($prop->MonthBuilt == 5){ echo 'selected'; } ?>>May</option>
                       <option value="6" <?php if($prop->MonthBuilt == 6){ echo 'selected'; } ?>>June</option>
                       <option value="7" <?php if($prop->MonthBuilt == 7){ echo 'selected'; } ?>>July</option>
                       <option value="8" <?php if($prop->MonthBuilt == 8){ echo 'selected'; } ?>>August</option>
                       <option value="9" <?php if($prop->MonthBuilt == 9){ echo 'selected'; } ?>>September</option>
                       <option value="10" <?php if($prop->MonthBuilt == 10){ echo 'selected'; } ?>>October</option>
                       <option value="11" <?php if($prop->MonthBuilt == 11){ echo 'selected'; } ?>>November</option>
                       <option value="12" <?php if($prop->MonthBuilt == 12){ echo 'selected'; } ?>>December</option>
                     </select>
                  </div>
              </div>
              <div class="col-2">
                   <div class="form-group">
                      <label>Year Built/Completed</label>
                      <input type="number" name="year_built" class="form-control" placeholder="<?= date('Y') ?>" value="<?= $prop->YearBuilt ?>">
                   </div>
                   <div class="form-group">
                      <label>comments:</label>
                      <input type="text" name="year_notes" class="form-control" placeholder="released 8 months after purchase" value="<?= $prop->YearNotes ?>">
                   </div>
               </div>
               <div class="col-2">
                   <div class="form-group">
                      <label>Bedrooms</label>
                      <input type="number" class="form-control" name="bedrooms" value="<?= $prop->Bedrooms ?>" placeholder="# of bedrooms">
                   </div>
               </div>
               <div class="col-2">
                  <div class="form-group">
                    <label>Room Type</label>
                    <select name="room_type" class="form-control">
                      <option value="bed" <?php if($prop->RoomType == 'bed'){ echo 'selected'; } ?>>Bedroom(s)</option>
                      <option value="studio" <?php if($prop->RoomType == 'studio'){ echo 'selected'; } ?>>Studio</option>
                      <option value="loft" <?php if($prop->RoomType == 'loft'){ echo 'selected'; } ?>>Loft</option>
                    </select>
                  </div>
               </div>
               <div class="col-2">
                   <div class="form-group">
                      <label>Full bathrooms</label>
                      <input type="number" class="form-control" name="full_bath" value="<?= $prop->Bathrooms ?>" placeholder="# of baths">
                   </div>
               </div>

               <div class="col-2">
                   <div class="form-group">
                      <label>1/2 bathrooms</label>
                      <input type="number" class="form-control" name="half_bath" value="<?= $prop->HalfBaths ?>" placeholder="# of half baths">
                   </div>
               </div>


           </div>
           <hr>
           <div class="row">
               <div class="col-9" style="padding-right: 50px;">

                 <ul class="nav nav-tabs" id="myTab" role="tablist">
                   <li class="nav-item" role="presentation">
                     <a class="nav-link active" id="en_tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="true">English</a>
                   </li>
                   <li class="nav-item" role="presentation">
                     <a class="nav-link" id="es_tab" data-toggle="tab" href="#es" role="tab" aria-controls="es" aria-selected="false">Español</a>
                   </li>
                  </ul>

                  <div class="tab-content" id="myTabContent">
                   <div class="tab-pane fade show active" id="en" role="tabpanel" aria-labelledby="en_tab">
                    <div class="row">
                      <div class="col-5">
                          <div class="form-group">
                              <label>Display Status</label>
                              <input type="text" name="display_status" class="form-control" value="<?= $prop->DisplayStatus ?>">
                              <small class="form-text">(Examples: summer sale, last minute deal, etc)</small>
                          </div>
                      </div>
                      <div class="col-7">
                        <div class="form-group">
                            <label>Listing Title</label>
                            <input type="text" name="title" class="form-control" value="<?= $prop->PropertyTitle ?>">
                            <small class="form-text">This will be displayed on the listing search and all lists of properties on the website. It should be enriched and enticing for the users to want to view it. As well will be used in google search results to attempt to generate more leads to your website. </small>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                        <label>Listing Description</label>
                        <textarea name="desc" class="form-control" rows="5" style="min-height: 190px;"><?= $prop->PropertyDesc ?></textarea>
                        <small class="form-text">This should be the selling point for the house. Put the client in the picture. </small>
                    </div>
                  </div>


                  <div class="tab-pane fade" id="es" role="tabpanel" aria-labelledby="es_tab">
                   <div class="row">
                     <div class="col-5">
                         <div class="form-group">
                             <label>Display Status (ES)</label>
                             <input type="text" name="display_status_es" class="form-control" value="<?= $prop->DisplayStatusEs ?>">
                             <small class="form-text">(Examples: summer sale, last minute deal, etc)</small>
                         </div>
                     </div>
                     <div class="col-7">
                       <div class="form-group">
                           <label>Listing Title (ES)</label>
                           <input type="text" name="title_es" class="form-control" value="<?= $prop->PropertyTitleEs ?>">
                           <small class="form-text">This will be displayed on the listing search and all lists of properties on the website. It should be enriched and enticing for the users to want to view it. As well will be used in google search results to attempt to generate more leads to your website. </small>
                       </div>
                     </div>
                   </div>

                   <div class="form-group">
                       <label>Listing Description (ES)</label>
                       <textarea name="desc_es" class="form-control" rows="5" style="min-height: 190px;"><?= $prop->PropertyDescEs ?></textarea>
                       <small class="form-text">This should be the selling point for the house. Put the client in the picture. </small>
                   </div>
                 </div>
                </div>
              </div>
                <div class="col-3">
                   <div class="card bg-light p-3 text-center" style="border: 1px solid #ddd">
                     <div class="row">
                       <div class="col-6">
                         <div class="form-group">
                             <label>Total ft&#178;</label>
                             <input type="text" name="size_ft" class="form-control text-center" value="<?= $prop->Size->Ft ?>">
                         </div>
                       </div>
                       <div class="col-6">
                         <div class="form-group">
                             <label>Total mt&#178;</label>
                             <input type="text" name="size_mt" class="form-control text-center" value="<?= $prop->Size->Mt ?>">
                         </div>
                       </div>
                     </div>

                     <div class="row">
                       <div class="col-6">
                         <div class="form-group">
                           <label>Lot size ft&#178;</label>
                           <input type="text" name="size_lot" class="form-control text-center" value="<?= $prop->Size->Lot ?>">
                         </div>
                       </div>
                       <div class="col-6">
                         <div class="form-group">
                           <label>Lot size mt&#178;</label>
                           <input type="text" name="size_lot_mt" class="form-control text-center" value="<?= $prop->Size->LotMt ?>">
                         </div>
                       </div>
                     </div>



                        <div class="form-group">
                            <label>Units</label>
                            <input type="text" name="size_units" class="form-control text-center" value="<?= $prop->Size->Units ?>">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Virtual tour</label>
                        <input type="text" name="virtual_tour" class="form-control" placeholder="http://urltovirtualtour.here" value="<?= $prop->VirtualTour ?>">
                        <small class="form-text">Please make sure to input the full url or embed code here.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- PRICING DETAILS -->
    <div class="card shadow-3" id="pricing">
        <h4 class="card-title">Pricing <b>Details</b></h4>
        <input type="hidden" name="price_desc[]" class="form-control" id="pdesc">

        <div class="card-body">
           <p>Here you can add various prices to the same listing. Whether it be because of different units in the same building available or you would like to add a closing fee. Simply input the title of the price along with a brief description. The main price displayed will be the lowest price type either for sale or for rent depending on listing type.</p>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label>Price Title</label>
                        <input type="text" name="price_name[]" class="form-control" id="pname">
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label>Price amount</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ti-money"></i></span>
                            <input type="text" name="price_amt[]" class="form-control" id="pamt">
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>Currency</label>
                        <select name="price_curr[]" id="pcurr" class="form-control">
                          <option value="mxn">MXN</option>
                          <option value="usd" selected>USD</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Price type</label>
                        <select name="price_type[]" class="form-control" id="pricetype">
                            <option value="0">-- Select --</option>
                            <?php foreach($properties->PricingTypes as $key=>$val){ ?>
                            <option value="<?= $key ?>"><?= $val ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-outline btn-info btn-sm text-center add-price" data-provide="tooltip" title="Add pricing to listing"><i class="ti-plus"></i></button>
                </div>
            </div>
        </div>
            <h5 class="card-title">Current pricing displayed on this listing</h5>
            <div class="card-body" id="prices_to_add">
            <?php if(!empty($prop->Prices) && $edit_list == 1){ foreach($prop->Prices as $key=>$pr){?>
              <input type="hidden" name="price_desc[]" class="form-control" value="<?= $pr['desc'] ?>">
            <div class="row prices" id="price<?= $key ?>">
                <div class="col-3">
                    <div class="form-group">
                        <label>Price Title</label>
                        <input type="text" name="price_name[]" class="form-control" value="<?= $pr['name'] ?>">
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label>Price amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                              <span>$</span>
                            </span>
                            <input type="text" name="price_amt[]" class="form-control" value="<?= $pr['amt'] ?>">
                        </div>
                    </div>
                </div>
                <div class="col-2">
                  <div class="form-group">
                      <label>Currency</label>
                      <select name="price_curr[]" class="form-control">
                        <option value="mxn" <?php if($pr['curr'] == 'mxn'){ echo 'selected'; } ?>>MXN</option>
                        <option value="usd" <?php if($pr['curr'] == 'usd'){ echo 'selected'; } ?>>USD</option>
                      </select>
                  </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Price type</label>
                        <select name="price_type[]" class="form-control">
                            <option value="0">-- Select --</option>
                            <?php foreach($properties->PricingTypes as $kk=>$val){ ?>
                            <option <?php if($kk == $pr['type']){ echo 'selected'; } ?> value="<?= $kk ?>"><?= $val ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-1">
                    <button type="button" data-id="<?= $key ?>" class="btn btn-sm btn-outline btn-danger text-center delete-price" data-provide="tooltip" title="Delete this pricing from listing"><i class="ti-trash"></i></button>
                </div>
                </div>

            <?php } }else{ ?>
            <p><em>Please add pricing above, click on the plus icon to add more than one.</em></p>
            <?php } ?>
            </div>

    </div>

    <input type="hidden" name="unit_num" class="form-control" value="<?= $prop->Location->UnitNum ?>">
    <!-- LISTING LOCATION -->
    <div class="card shadow-3" id="location">
        <h4 class="card-title">Listing <b>Location</b></h4>
        <div class="card-body">
            <div class="row">
                <div class="col-9">
                    <div class="form-group">
                        <label>Street address</label>
                        <input type="text" name="address" class="form-control" value="<?= $prop->Location->Address ?>">
                    </div>
                </div>
            </div>
            <div class="row">
               <div class="col-3">
                  <div class="form-group">
                      <label>State</label>
                      <select name="state" class="form-control" id="nj_states">
                          <?php foreach($properties->States as $key=>$ss){ ?>
                          <option <?php if($prop->Location->State == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ss ?></option>
                          <?php } ?>
                      </select>
                  </div>
               </div>
               <div class="col-3">
                   <div class="form-group">
                       <label>Municipality</label>
                       <select name="city" class="form-control" id="nj_city">
                           <?php foreach($properties->Cities as $key=>$ss){ ?>
                           <option <?php if($prop->Location->City == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ss ?></option>
                           <?php } ?>
                       </select>
                   </div>
               </div>
               <div class="col-3">
                  <div class="form-group">
                      <label>City</label>
                      <select name="county" class="form-control select2" id="nj_county">
                          <option value="0" selected>-- Select --</option>
                          <?php foreach($properties->Towns as $key=>$ss){ ?>
                          <option <?php if($prop->Location->County == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ss ?></option>
                          <?php } ?>
                      </select>
                  </div>

                </div>

                <div class="col-3">
                   <div class="form-group">
                       <label>Area</label>
                       <select name="area" class="form-control select2" id="nj_areas">
                          <option value="0" selected>-- Select --</option>
                           <?php if($prop->Location->Area == ''){ echo '<option value="Undefined">- Select -</option>'; }
                           foreach($properties->Areas as $key=>$ss){ ?>
                           <option <?php if($prop->Location->Area == $key){ echo 'selected'; } ?> value="<?= $key ?>"><?= $ss ?></option>
                           <?php } ?>
                       </select>
                   </div>
                </div>
            </div>
            <div class="row">
               <div class="col-3">
                   <div class="form-group">
                       <label>Zip code</label>
                       <input type="text" name="zip" class="form-control" value="<?= $prop->Location->Zip ?>">
                   </div>
               </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="<?= $prop->Location->Longitude ?>">
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="<?= $prop->Location->Latitude ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- LISTING FEATURES -->
    <div class="card shadow-3" id="features">
        <h4 class="card-title">Listing <b>Features</b> <div class="new-feature">
          <span class="new">new</span> Spanish version added. Drag features to order.
        </div>
        <div class="clearfix"></div>
      </h4>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <p>Here you can add additional information and features of this property. To add a new feature simply input the name or title of the feature then the value (the feature itself). <span class="text-primary">You can order by grabbing the row you would like to move and dropping it into the correct order to be displayed on website.</span></p>



                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="en_features" data-toggle="tab" href="#en_fe" role="tab" aria-controls="en_fe" aria-selected="true">English</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" id="es_features" data-toggle="tab" href="#es_fe" role="tab" aria-controls="es_fe" aria-selected="false">Español</a>
                      </li>
                     </ul>

                     <div class="tab-content" id="myTabContent">


                      <div class="tab-pane fade show active" id="en_fe" role="tabpanel" aria-labelledby="en_features">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 200px">Feature name</th>
                                    <th>Feature value</th>
                                    <th style="width: 95px;"></th>
                                    <th style="width: 55px;"></th>
                                </tr>
                            </thead>
                            <tbody id="tb_features">
                               <tr class="bg-pale-secondary" id="add_new_feature">
                                    <td><input type="text" name="feature_name[]" class="form-control" placeholder="Feature name"></td>
                                    <td><input type="text" name="feature_value[]" class="form-control" placeholder="Feature value"></td>
                                    <td colspan="2"><button type="button" class="btn btn-pure btn-primary" id="add_feature" data-provide="tooltip" title="Add this feature"><i class="ti-plus"></i></button></td>
                                </tr>
                                <?php if($edit_list != 0){  if(!empty($prop->Features)){ foreach($prop->Features as $key=>$val){ ?>
                                <tr class="sort-it" data-id="<?= $key ?>">
                                    <td>
                                      <input type="hidden" name="feature_order[]" value="0" />
                                      <input type="text" name="feature_name[]" class="form-control" placeholder="Feature name" value="<?= $key ?>"></td>
                                    <td><input type="text" name="feature_value[]" class="form-control" placeholder="Feature value" value="<?= $val ?>"></td>
                                    <td><button type="button" class="btn btn-pure btn-danger delete-tr" data-provide="tooltip" title="Delete this feature"><i class="ti-trash"></i></button></td>
                                    <td><i class="ti-move"></i></td>
                                </tr>
                              <?php } } } ?>
                            </tbody>
                        </table>
                      </div>


                      <div class="tab-pane fade" id="es_fe" role="tabpanel" aria-labelledby="es_features">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 200px">Feature name (ES)</th>
                                    <th>Feature value (ES)</th>
                                    <th style="width: 95px;"></th>
                                    <th style="width: 55px;"></th>
                                </tr>
                            </thead>
                            <tbody id="tb_features_es">
                               <tr class="bg-pale-secondary" id="add_new_feature_es">
                                    <td><input type="text" name="feature_name_es[]" class="form-control" placeholder="Feature name"></td>
                                    <td><input type="text" name="feature_value_es[]" class="form-control" placeholder="Feature value"></td>
                                    <td colspan="2"><button type="button" class="btn btn-pure btn-primary" id="add_feature_es" data-provide="tooltip" title="Add this feature"><i class="ti-plus"></i></button></td>
                                </tr>
                                <?php if($edit_list != 0){  if(!empty($prop->FeaturesEs)){ foreach($prop->FeaturesEs as $key=>$val){ ?>
                                <tr>
                                    <td><input type="text" name="feature_name_es[]" class="form-control" placeholder="Feature name" value="<?= $key ?>"></td>
                                    <td><input type="text" name="feature_value_es[]" class="form-control" placeholder="Feature value" value="<?= $val ?>"></td>
                                    <td><button type="button" class="btn btn-pure btn-danger delete-tr" data-provide="tooltip" title="Delete this feature"><i class="ti-trash"></i></button></td>
                                    <td><i class="ti-move"></i></td>
                                </tr>
                              <?php } } } ?>
                            </tbody>
                        </table>
                      </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


   <!-- LISTING IMAGES -->
  <div class="card shadow-3" id="photos">
      <h4 class="card-title">Listing <b>Images</b></h4>
      <div class="card-body">
          <div class="row">
              <div class="col-md-12">
                 <p>Here you can add a full image gallery to this listing. The first image will appear in the list and search results. The rest will be visible through a gallery on the listing page.</p>
                  <div id="add_imgs" data-provide="dropzone" data-url="assets/inc/process/upload_img.php?lid=<?= $lid ?>"></div>
              </div>

              <ul id="imgs_contain" class="sortable dropzone-previews">



                <?php
                if(!empty($prop->Photos)){
                  foreach($prop->Photos as $key=>$val){ ?>
                    <li class="sort-me" id="img<?= $key ?>" data-name="<?= $val[1] ?>">
                      <div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                          <div class="dz-img-contain">
                            <img src="<?= $uploads_folder.$val[0].'/'.$val[1] ?>" data-dz-thumbnail />
                          </div>
                          <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                          <div class="dz-success-mark"><span>✔</span></div>
                          <div class="dz-error-mark"><span>✘</span></div>
                          <div class="dz-error-message"><span data-dz-errormessage></span></div>
                          <div data-dz-remove class="btn btn-default removePicFromAlbum del-img" data-id="<?= $key ?>" title="delete image">
                            delete
                          </div>
                        </div>
                      </div>
                    </li>
                <?php  } } ?>



              </ul>
          </div>
      </div>
  </div>

  <div class="preview" style="display:none;">
    <li class="sort-me">
      <div class="dz-preview dz-file-preview">
        <div class="dz-details">
          <img data-dz-thumbnail />
          <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
          <div class="dz-success-mark"><span>✔</span></div>
          <div class="dz-error-mark"><span>✘</span></div>
          <div class="dz-error-message"><span data-dz-errormessage></span></div>
          <div data-dz-remove class="btn btn-default removePicFromAlbum" title="delete image">
            delete
          </div>
        </div>
      </div>
    </li>
  </div>

    <hr>

    <button type="submit" name="update_listing" class="btn btn-info btn-lg pull-right submit-form"><i class="fa fa-save"></i> Save property information</button>
    <div class="clearfix"></div>
    <br><br>

</form>
