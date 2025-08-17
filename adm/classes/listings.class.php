<?php
class Listings{
    public $ListCnt;
    public $List = array();
    public $PropertyTypes = array();
    public $ListingTypes = array();
    public $ZoneTypes = array();
    public $States = array();
    public $Cities = array();
    public $Towns = array();
    public $Areas = array();
    public $Features = array();
    public $FeaturesEs = array();
    public $PricingTypes = array();

    public $AdmCities;
    public $AdmTowns;
    public $AdmAreas;


    public function __construct(){

    }

    public function generateForm($area=0,$town=0,$city=0){
        $this->getListTypes();
        $this->getPropertyTypes();
        $this->getStates();
        $this->getCities($city);
        $this->getTowns($town);
        $this->getAreas($area);
        $this->getZones();
        $this->getFeatures();
        $this->getPriceTypes();

    }

    public function getListings(){

        $getList = new SqlIt("
        SELECT
            p.property_title,
            p.address,
            p.property_id,
            p.pr_status,
            p.is_visible,
            p.mls_num,
            t.type_name,
            tt.type_desc,
            p.is_featured,
            l.location,
            p.room_type,
            COUNT(i.photo_id) as img_cnt
        FROM property_list as p
        LEFT JOIN property_list_types as t ON p.pr_list_type_id = t.list_type_id
        LEFT JOIN property_types as tt ON p.pr_type_id = tt.pr_type_id
        LEFT JOIN locations_cities as l ON p.city = l.city_id
        LEFT JOIN property_photos as i ON p.property_id = i.property_id
        GROUP BY p.property_id ORDER BY p.property_id ","select",array());

        $this->ListCnt = $getList->NumResults;


        if($getList->NumResults > 0){
            foreach($getList->Response as $gl){
                $dd = new stdClass;
                $dd->property_title = $gl->property_title;
                $dd->address = $gl->address;
                $dd->type = $gl->type_name;
                $dd->ptype = $gl->type_desc;
                $dd->status = $gl->pr_status;
                $dd->visible = $gl->is_visible;
                $dd->img_cnt = $gl->img_cnt;
                $dd->mls = $gl->mls_num;
                $dd->location = $gl->location;
                $dd->featured = $gl->is_featured;
                $dd->room_type = $gl->room_type;


                $dd->vis_icon = 'ti-eye text-black';
                $dd->vis_txt = '<i class="ti-eye text-danger"></i> Make invisible';
                $dd->vis_txts = 'Make invisible';
                if($gl->is_visible == 0){
                    $dd->vis_icon = 'ti-eye text-secondary';
                    $dd->vis_txt = '<i class="ti-eye text-success"></i> Make visible';
                    $dd->vis_txts = 'Make visible';
                }

                $dd->feat_icon = 'ti-star text-secondary';
                $dd->feat_txt = '<i class="ti-star text-warning"></i> Set as featured listings';
                if($gl->is_featured == 1){
                    $dd->feat_icon = 'ti-star text-warning';
                    $dd->feat_txt = '<i class="ti-star text-secondary"></i> Remove from featured listings';
                }

                $dd->st_txt = '<i class="fa fa-dot-circle-o text-danger"></i> Inactivate listing';
                if($gl->pr_status != 'active'){
                    $dd->st_txt = '<i class="fa fa-dot-circle-o text-success"></i> Activate listing';
                }

                $this->List[$gl->property_id] = $dd;
            }
        }
    }


    public function getPropertyTypes(){
        $getPr = new SqlIt("SELECT pt.*, ps.*, pt.pr_type_id as tid FROM property_types as pt LEFT JOIN property_types_sub as ps ON pt.pr_type_id = ps.pr_type_id ORDER BY type_desc","select",array());


        if($getPr->NumResults > 0){
            foreach($getPr->Response as $gg){
                if(!isset($this->PropertyTypes[$gg->tid])){
                    $this->PropertyTypes[$gg->tid]['desc'] = $gg->type_desc;
                    $this->PropertyTypes[$gg->tid]['desc_es'] = $gg->type_desc_es;
                    if(!isset($this->PropertyTypes[$gg->tid])){
                      $this->PropertyTypes[$gg->tid]['subs'] = array();
                      $this->PropertyTypes[$gg->tid]['subs_es'] = array();
                    }
                }
                $this->PropertyTypes[$gg->tid]['subs'][$gg->sub_id] = $gg->sub_desc;
                $this->PropertyTypes[$gg->tid]['subs_es'][$gg->sub_id] = $gg->sub_desc_es;

            }
            //print_me($this->PropertyTypes);
        }
    }


    public function getListTypes(){
        $getPr = new SqlIt("SELECT * FROM property_list_types ORDER BY type_name","select",array());

        if($getPr->NumResults > 0){
            foreach($getPr->Response as $gg){
                $this->ListingTypes[$gg->list_type_id] = $gg->type_name;
            }
        }
    }


    public function getZones(){
        $getPr = new SqlIt("SELECT * FROM property_zones ORDER BY zone_id","select",array());

        if($getPr->NumResults > 0){
            foreach($getPr->Response as $gg){
                $this->ZoneTypes[$gg->zone_id] = $gg->zone_name;
                $this->ZoningTypes[$gg->zone_id]['en'] = $gg->zone_name;
                $this->ZoningTypes[$gg->zone_id]['es'] = $gg->zone_name_es;
            }
        }
    }



    public function getAreas($town_id=0){
      $this->Areas = array();
      if($town_id == 0){
         $this->Areas = array(0,'Select Town');
      }else{
         $getAreas = new SqlIt("SELECT * FROM locations_areas WHERE town_id = ? ORDER BY area_name ASC","select",array($town_id));
         if($getAreas->NumResults > 0){
            foreach($getAreas->Response as $gs){
               $this->Areas[$gs->area_id] = utf8_encode($gs->area_name);
            }
         }
      }
    }




    public function getTowns($city_id=1810){
      $this->Towns = array();
      if($city_id == 0){
         $this->Towns = array(0,'Select Municipality');
      }else{
         $getTowns = new SqlIt("SELECT * FROM locations_towns WHERE city_id = ? ORDER BY town_name ASC","select",array($city_id));
         if($getTowns->NumResults > 0){
            foreach($getTowns->Response as $gs){
               $this->Towns[$gs->town_id] = utf8_encode($gs->town_name);
            }
         }
      }
    }



    public function getCities($state_id=23){
      $this->Cities = array();
      if($state_id == 0){
         $this->Cities = array(0,'Select state');
      }else{
         $getCities = new SqlIt("SELECT * FROM locations_cities WHERE state_id = ? ORDER BY location ASC","select",array($state_id));
         if($getCities->NumResults > 0){
            foreach($getCities->Response as $gs){
               $this->Cities[$gs->city_id] = utf8_encode($gs->location);
            }
         }
      }
    }



    public function getStates(){
      $this->States = array();
      $getStates = new SqlIt("SELECT * FROM locations_states ORDER BY state ASC","select",array());
      if($getStates->NumResults > 0){
         foreach($getStates->Response as $gs){
            $this->States[$gs->state_id] = utf8_encode($gs->state);
         }
      }

    }


    public function getFeatures(){
        /*$features = array(
            'Heat/AC'=>'Both',
            'Exterior'=>'',
            'Interior'=>'',
            'Laundry Room'=>'No',
            'Parking'=>'2 car garage, Off Street',
            'Stories'=>'1',
            'Living Room'=>'1',
            'Pool'=>'No',
            'Spa'=>'No',
            'Kitchen'=>'',
            'Flooring'=>'',
            'Property Style'=>'',
            'Water Heater'=>'Yes',
            'Bathtubs/Showers'=>''
        );*/

        $this->Features = array();
    }


    public function getPriceTypes(){
        $getPr = new SqlIt("SELECT * FROM property_pricing_types","select",array());
        foreach($getPr->Response as $pp){
            $this->PricingTypes[$pp->pr_type_id] = $pp->price_type;
            $this->PriceTypes[$pp->pr_type_id]['en'] = $pp->price_type;
            $this->PriceTypes[$pp->pr_type_id]['es'] = $pp->price_type_es;

        }
    }


    // Get cities for admin
    public function getAdmCities($state_id=0){
      $this->AdmCities = array();
      $getCities = new SqlIt("
      SELECT l.*, COUNT(p.property_id) AS props
      FROM locations_cities AS l
      LEFT JOIN property_list AS p ON l.city_id = p.city
      WHERE state_id = ?
      GROUP BY city_id ORDER BY location ASC","select",array($state_id));
      if($getCities->NumResults > 0){
        foreach($getCities->Response as $gs){
           $this->AdmCities[$gs->city_id]['name'] = utf8_encode($gs->location);
           $this->AdmCities[$gs->city_id]['cnt'] = $gs->props;
           $this->AdmCities[$gs->city_id]['featured'] = $gs->is_featured;
        }
      }
    }


    // Get towns for admin
    public function getAdmTowns($city_id=0){
      $this->AdmTowns = array();
      $getTowns = new SqlIt("
      SELECT l.*, COUNT(p.property_id) AS props
      FROM locations_towns AS l
      LEFT JOIN property_list AS p ON l.town_id = p.county
      WHERE city_id = ?
      GROUP BY town_id ORDER BY town_name ASC","select",array($city_id));
      if($getTowns->NumResults > 0){
        foreach($getTowns->Response as $gs){
           $this->AdmTowns[$gs->town_id]['name'] = utf8_encode($gs->town_name);
           $this->AdmTowns[$gs->town_id]['cnt'] = $gs->props;
           $this->AdmTowns[$gs->town_id]['featured'] = $gs->is_featured;
        }
      }
    }


    // Get areas for admin
    public function getAdmAreas($town_id=0){
      $this->AdmAreas = array();
      $getAreas = new SqlIt("
      SELECT l.*, COUNT(p.property_id) AS props
      FROM locations_areas AS l
      LEFT JOIN property_list AS p ON l.area_id = p.area
      WHERE town_id = ?
      GROUP BY area_id ORDER BY area_name ASC","select",array($town_id));
      if($getAreas->NumResults > 0){
        foreach($getAreas->Response as $gs){
           $this->AdmAreas[$gs->area_id]['name'] = utf8_encode($gs->area_name);
           $this->AdmAreas[$gs->area_id]['cnt'] = $gs->props;
           $this->AdmAreas[$gs->area_id]['featured'] = $gs->is_featured;
        }
      }
    }


}


class Listing{
    public $PropertyId;
    public $IsVisible;
    public $IsFeatured = 0;
    public $Status;
    public $DisplayStatus;
    public $PropertyTitle;
    public $PropertyDesc;
    public $DisplayStatusEs;
    public $PropertyTitleEs;
    public $PropertyDescEs;
    public $PropertyTypeId;
    public $PropertySubTypeId;
    public $ListTypeId;
    public $ZoningId;
    public $MLS;
    public $Bedrooms;
    public $Bathrooms;
    public $RoomType;
    public $HalfBaths;
    public $ThirdBaths;
    public $FourthBaths;
    public $YearBuilt;
    public $ReleaseNotes = '';
    public $VirtualTour;
    public $Foreclosure;
    public $Construction;
    public $Size;
    public $Location;
    public $Type = 'budd';
    public $Photos = array();
    public $Features = array();
    public $FeaturesEs = array();
    public $Prices = array();
    public $OpenHouses = array();
    public $RoomInfo = array();


    public function __construct($lid = 0, $type = 'budd'){

        //Define children of the following objects

        $this->Location = new stdClass;
        $this->Location->Address = '';
        $this->Location->UnitNum = '';
        $this->Location->City = 1810; // Solidaridad default
        $this->Location->Area = '';
        $this->Location->State = 23; // Quintana Roo default
        $this->Location->County = 25; // PDC default
        $this->Location->CityName = '';
        $this->Location->AreaName = '';
        $this->Location->StateName = '';
        $this->Location->CountyName = '';
        $this->Location->Zip = '';
        $this->Location->Longitude = '';
        $this->Location->Latitude = '';
        $this->Location->MapEmbed = '';

        $this->Size = new stdClass;
        $this->Size->Ft = 0;
        $this->Size->Mt = 0;
        $this->Size->Lot = 0;
        $this->Size->Units = 0;

        $this->Foreclosure = 0;
        $this->Construction = 0;


        if($lid != 0){
            if($type == 'budd'){
                $getProp = new SqlIt("SELECT p.*, a.area_name, c.location, s.state as state_name, t.town_name, e.*
                  FROM property_list as p
                  LEFT JOIN property_list_es AS e ON p.property_id = e.property_id
                  LEFT JOIN locations_areas as a ON a.area_id = p.area
                  LEFT JOIN locations_cities as c ON c.city_id = p.city
                  LEFT JOIN locations_states as s ON s.state_id = p.state
                  LEFT JOIN locations_towns as t ON t.town_id = p.county
                  WHERE p.property_id = ?","select",array($lid));
                if($getProp->NumResults != 0){
                    $pp = $getProp->Response[0];
                    $this->PropertyId = $lid;
                    $this->IsVisible = $pp->is_visible;
                    $this->Status = $pp->pr_status;
                    $this->DisplayStatus = $pp->pr_display_status;
                    $this->PropertyTitle = $pp->property_title;
                    $this->PropertyDesc = $pp->property_desc;
                    $this->DisplayStatusEs = $pp->prop_display;
                    $this->PropertyTitleEs = $pp->prop_title;
                    $this->PropertyDescEs = $pp->prop_desc;
                    $this->PropertyTypeId = $pp->pr_type_id;
                    $this->PropertySubTypeId = $pp->pr_sub_type_id;
                    $this->ListTypeId = $pp->pr_list_type_id;
                    $this->ZoningId = $pp->zoning_id;
                    $this->MLS = $pp->mls_num;
                    $this->Bedrooms = $pp->bedrooms;
                    $this->Bathrooms = $pp->bathrooms;
                    $this->TotalBaths = $pp->bathrooms;
                    if($pp->half_baths > 0){
                      $half_baths = $pp->half_baths * .5;
                      $this->TotalBaths = $this->Bathrooms + $half_baths;
                    }
                    $this->RoomType = $pp->room_type;
                    $this->HalfBaths = $pp->half_baths;
                    $this->ThirdBaths = $pp->third_baths;
                    $this->FourthBaths = $pp->fourth_baths;
                    $this->YearBuilt = $pp->year_built;
                    $this->ReleaseNotes = $pp->release_notes;
                    $this->MonthBuilt = $pp->month_built;
                    $this->YearNotes = $pp->release_notes;
                    $this->VirtualTour = $pp->virtual_tour;
                    $this->Forclosure = $pp->is_foreclosure;
                    $this->Construction = $pp->is_construction;
                    $this->IsFeatured = $pp->is_featured;

                    $this->Location->Address = $pp->address;
                    $this->Location->UnitNum = $pp->unit_num;
                    $this->Location->City = $pp->city;
                    $this->Location->Area = $pp->area;
                    $this->Location->State = $pp->state;
                    $this->Location->County = $pp->county;
                    $this->Location->CityName = $pp->location;
                    if($pp->area_name != 'Undefined'){
                      $this->Location->AreaName = $pp->area_name;
                    }
                    $this->Location->StateName = $pp->state_name;
                    if($pp->town_name != 'Undefined'){
                      $this->Location->CountyName = $pp->town_name;
                    }
                    $this->Location->Zip = $pp->zip;
                    $this->Location->Longitude = $pp->longitude;
                    $this->Location->Latitude = $pp->latitude;
                    $this->Location->MapEmbed = $pp->map_embed;

                    if(floor($pp->size_sq_ft) == $pp->size_sq_ft){
                        $this->Size->Ft = floor($pp->size_sq_ft);
                    }else{
                        $this->Size->Ft = $pp->size_sq_ft;
                    }

                    if(floor($pp->size_sq_mt) == $pp->size_sq_mt){
                        $this->Size->Mt = floor($pp->size_sq_mt);
                    }else{
                        $this->Size->Mt = $pp->size_sq_mt;
                    }

                    if(floor($pp->size_lot) == $pp->size_lot){
                        $this->Size->Lot = floor($pp->size_lot);
                    }else{
                        $this->Size->Lot = $pp->size_lot;
                    }

                    if(floor($pp->size_lot_mt) == $pp->size_lot_mt){
                        $this->Size->LotMt = floor($pp->size_lot_mt);
                    }else{
                        $this->Size->LotMt = $pp->size_lot_mt;
                    }

                    if(floor($pp->size_units) == $pp->size_units){
                        $this->Size->Units = floor($pp->size_units);
                    }else{
                        $this->Size->Units = $pp->size_units;
                    }


                    // get features to display
                    $getF = new SqlIt("SELECT * FROM property_features WHERE property_id = ? ORDER BY forder ASC","select",array($this->PropertyId));
                    if($getF->NumResults != 0){
                        foreach($getF->Response as $gf){
                          if($gf->lang == 'en'){
                            $this->Features[$gf->feature_name] = $gf->feature_val;
                          }elseif($gf->lang == 'es'){
                            $this->FeaturesEs[$gf->feature_name] = $gf->feature_val;
                          }
                        }
                    }

                    // get prices to display
                    $getP = new SqlIt("SELECT * FROM property_pricing WHERE property_id = ?","select",array($this->PropertyId));
                    if($getP->NumResults != 0){
                        foreach($getP->Response as $gf){
                            $pp = array();
                            $pp['name'] = $gf->price_name;
                            $pp['desc'] = $gf->price_desc;
                            $pp['amt'] = $gf->price_amt;
                            $pp['type'] = $gf->price_type;
                            $pp['curr'] = $gf->price_currency;
                            $this->Prices[$gf->price_id] = $pp;
                        }
                    }

                    // get images to display
                    $getI = new SqlIt("SELECT * FROM property_photos WHERE property_id = ? AND (img_type = ? OR img_type = ?) ORDER BY img_order ASC","select",array($this->PropertyId,'listing_gallery','gallery'));
                    if($getI->NumResults != 0){
                        foreach($getI->Response as $gf){
                            $this->Photos[$gf->photo_id] = array($gf->img_folder,$gf->img_name);
                            $this->PhotosDisplay[] = $gf->img_folder.'/'.$gf->img_name;
                        }
                    }
                }
            }
        }
    }


    public function addListing($post){
        if(isset($post['update_listing'])){

            if(!isset($post['sub_id'])){
                $post['sub_id'] = 0;
            }

            // Add new listing
            $addNew = new SqlIt("INSERT INTO property_list (
                pr_status,
                pr_list_type_id,
                pr_display_status,
                pr_type_id,
                mls_num,
                size_sq_ft,
                size_sq_mt,
                size_lot,
                size_units,
                address,
                unit_num,
                city,
                state,
                zip,
                county,
                area,
                bedrooms,
                bathrooms,
                half_baths,
                third_baths,
                fourth_baths,
                year_built,
                month_built,
                release_notes,
                zoning_id,
                property_title,
                property_desc,
                is_visible,
                virtual_tour,
                is_foreclosure,
                is_construction,
                pr_sub_type_id,
                longitude,
                latitude,
                room_type
              )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)","insert",array(
                $post['pr_status'],
                $post['listing_type'],
                $post['display_status'],
                $post['property_type'],
                $post['mls'],
                $post['size_ft'],
                $post['size_mt'],
                $post['size_lot'],
                $post['size_units'],
                $post['address'],
                $post['unit_num'],
                $post['city'],
                $post['state'],
                $post['zip'],
                $post['county'],
                $post['area'],
                $post['bedrooms'],
                $post['full_bath'],
                $post['half_bath'],
                $post['third_bath'],
                $post['fourth_bath'],
                $post['year_built'],
                $post['month_built'],
                $post['year_notes'],
                $post['zone_type'],
                $post['title'],
                $post['desc'],
                $post['is_visible'],
                $post['virtual_tour'],
                $post['is_foreclosure'],
                $post['is_construction'],
                $post['sub_id'],
                $post['longitude'],
                $post['latitude'],
                $post['room_type']
            ));

            if($addNew){
                // Get property ID
                $property_id = $addNew->LastID;


                // Add spanish descriptive text to property
                if($post['display_status_es'] != '' || $post['title_es'] != ''){
                  // add the spanish version
                  $add = new SqlIt("
                    INSERT INTO property_list_es
                      (property_id,prop_title,prop_desc,prop_display,lang)
                    VALUES
                      (?,?,?,?,?)","insert",array($property_id,$post['title_es'],$post['desc_es'],$post['display_status_es'],'es'));
                }



                    // Add pricing details
                    if(isset($post['price_amt'][0])){
                        foreach($post['price_name'] as $key=>$val){ if($post['price_amt'][$key] > 0){
                            new SqlIt("
                            INSERT INTO property_pricing (
                                price_name,
                                price_desc,
                                price_amt,
                                price_type,
                                price_currency,
                                property_id)
                            VALUES (?,?,?,?,?,?)","insert",array(
                                $post['price_name'][$key],
                                $post['price_desc'][$key],
                                $post['price_amt'][$key],
                                $post['price_type'][$key],
                                $post['price_curr'][$key],
                                $property_id
                            ));
                        }
                        }
                    }

                    // Add property feeatures
                    if(isset($post['feature_name'][1]) && $post['feature_name'][1] != ''){
                        foreach($post['feature_name'] as $key=>$val){
                            if($post['feature_value'][$key] != ''){
                                new SqlIt("
                                INSERT INTO property_features (
                                    feature_name,
                                    feature_val,
                                    property_id,
                                    lang)
                                VALUES (?,?,?,?)","insert",array(
                                    $post['feature_name'][$key],
                                    $post['feature_value'][$key],
                                    $property_id,
                                    'en'
                                ));
                            }
                        }
                    }


                    // Add spanish version of property info
                    if(isset($post['feature_name_es'][1]) && $post['feature_name_es'][1] != ''){
                        foreach($post['feature_name_es'] as $key=>$val){
                            if($post['feature_value_es'][$key] != ''){
                                new SqlIt("
                                INSERT INTO property_features (
                                    feature_name,
                                    feature_val,
                                    property_id,
                                    lang)
                                VALUES (?,?,?,?)","insert",array(
                                    $post['feature_name_es'][$key],
                                    $post['feature_value_es'][$key],
                                    $property_id,
                                    'es'
                                ));
                            }
                        }
                    }


                    // Add images to property
                    if(isset($post['images']) && !empty($post['images'])){
                      $destination = "uploads/listings/".$property_id."/";

                      if(!file_exists($destination)) {
                          mkdir($destination, 0777, true);
                      }
                      if(!file_exists($destination.'thumbs/')) {
                          mkdir($destination.'thumbs/', 0777, true);
                      }
                      new SqlIt("DELETE FROM property_photos WHERE property_id = ?","delete",array($property_id));
                      foreach($post['images'] as $kk=>$vv){
                        new SqlIt("INSERT INTO property_photos (
                          img_name,
                          property_id,
                          img_folder,
                          img_order
                        ) VALUES (?,?,?,?)","insert",array(
                          $vv,$property_id,'listings/'.$property_id,$kk
                        ));



                       $org_name = 'uploads/listings/temp/'.$vv;
                       $img_name = basename($org_name);

                      if( rename( $org_name , $destination.$vv )){
                        // echo 'moved';
                      } else {
                       //echo 'failed';
                     }

                     $org_thumb_name = 'uploads/listings/temp/thumbs/'.$vv;
                     $img_thumb_name = basename($org_thumb_name);

                    if( rename( $org_thumb_name , $destination.'thumbs/'.$vv )){
                      // echo 'moved';
                    } else {
                     //echo 'failed';
                   }
                    }
                    }

                    return $property_id;
            }else{
                return 0;
            }
        }
    }

    public function updateListing($post){
        if(isset($post['property_id']) && $post['property_id'] > 0){

            if(!isset($post['sub_id'])){
                $post['sub_id'] = 0;
            }

            if(!is_numeric($post['county']) && strlen($post['county']) > 2){
              // add the county
              $addCounty = new SqlIt("INSERT INTO locations_towns (city_id,town_name) VALUES (?,?)","insert",array($post['city'],$post['county']));
              if($addCounty){
                $post['county'] = $addCounty->LastID;
              }
            }

            if(!is_numeric($post['area']) && strlen($post['area']) > 2){
              // add the area
              $addArea = new SqlIt("INSERT INTO locations_areas (town_id,area_name) VALUES (?,?)","insert",array($post['county'],$post['area']));
              if($addArea){
                $post['area'] = $addArea->LastID;
              }
            }

            // Update listing
            $updateProp = new SqlIt("UPDATE property_list SET
                pr_status=?,
                pr_list_type_id=?,
                pr_display_status=?,
                pr_type_id=?,
                mls_num=?,
                size_sq_ft=?,
                size_sq_mt=?,
                size_lot=?,
                size_lot_mt=?,
                size_units=?,
                address=?,
                unit_num=?,
                city=?,
                state=?,
                zip=?,
                county=?,
                area=?,
                bedrooms=?,
                bathrooms=?,
                half_baths=?,
                third_baths=?,
                fourth_baths=?,
                year_built=?,
                month_built=?,
                release_notes=?,
                zoning_id=?,
                property_title=?,
                property_desc=?,
                is_visible=?,
                virtual_tour=?,
                is_foreclosure=?,
                is_construction=?,
                pr_sub_type_id=?,
                longitude=?,
                latitude=?,
                room_type=?
            WHERE property_id = ?","update",array(
                $post['pr_status'],
                $post['listing_type'],
                $post['display_status'],
                $post['property_type'],
                $post['mls'],
                $post['size_ft'],
                $post['size_mt'],
                $post['size_lot'],
                $post['size_lot_mt'],
                $post['size_units'],
                $post['address'],
                $post['unit_num'],
                $post['city'],
                $post['state'],
                $post['zip'],
                $post['county'],
                $post['area'],
                $post['bedrooms'],
                $post['full_bath'],
                $post['half_bath'],
                $post['third_bath'],
                $post['fourth_bath'],
                $post['year_built'],
                $post['month_built'],
                $post['year_notes'],
                $post['zone_type'],
                $post['title'],
                $post['desc'],
                $post['is_visible'],
                $post['virtual_tour'],
                $post['is_foreclosure'],
                $post['is_construction'],
                $post['sub_id'],
                $post['longitude'],
                $post['latitude'],
                $post['room_type'],
                $post['property_id']

            ));

            if($updateProp){
                // First delete all previously saved pricing to reinsert
                new SqlIt("DELETE FROM property_pricing WHERE property_id = ?","delete",array($post['property_id']));

                // Add pricing details
                if(isset($post['price_amt'][0])){
                    foreach($post['price_name'] as $key=>$val){ if($post['price_amt'][$key] > 0){
                        new SqlIt("
                        INSERT INTO property_pricing (
                            price_name,
                            price_desc,
                            price_amt,
                            price_type,
                            price_currency,
                            property_id)
                        VALUES (?,?,?,?,?,?)","insert",array(
                            $post['price_name'][$key],
                            $post['price_desc'][$key],
                            $post['price_amt'][$key],
                            $post['price_type'][$key],
                            $post['price_curr'][$key],
                            $post['property_id']
                        ));
                    }
                                                              }
                }


                if($post['display_status_es'] != '' || $post['title_es'] != ''){
                  // add the spanish version
                  $add = new SqlIt("
                    INSERT INTO property_list_es
                      (property_id,prop_title,prop_desc,prop_display,lang)
                    VALUES
                      (?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                      prop_title = VALUES(prop_title),
                      prop_desc  = VALUES(prop_desc),
                      prop_display = VALUES(prop_display) ","insert",array($post['property_id'],$post['title_es'],$post['desc_es'],$post['display_status_es'],'es'));
                }


                // First delete all previously saved features to reinsert
                new SqlIt("DELETE FROM property_features WHERE property_id = ?","delete",array($post['property_id']));

                // Add property feeatures
                if(isset($post['feature_name'][0])){
                  $jj = 0;
                    foreach($post['feature_name'] as $key=>$val){
                        if($post['feature_name'][$key] != '' && $post['feature_value'][$key] != ''){
                            if($post['feature_value'][$key] != ''){
                                new SqlIt("
                                INSERT INTO property_features (
                                    feature_name,
                                    feature_val,
                                    property_id,
                                    lang,
                                    forder)
                                VALUES (?,?,?,?,?)","insert",array(
                                    $post['feature_name'][$key],
                                    $post['feature_value'][$key],
                                    $post['property_id'],
                                    'en',
                                    $jj
                                ));
                            }
                        }
                        $jj++;
                    }
                }


                // Add spanish version of property info
                if(isset($post['feature_name_es'][1]) && $post['feature_name_es'][1] != ''){
                  $jj = 1;
                    foreach($post['feature_name_es'] as $key=>$val){
                        if($post['feature_value_es'][$key] != ''){
                            new SqlIt("
                            INSERT INTO property_features (
                                feature_name,
                                feature_val,
                                property_id,
                                lang,
                                forder)
                            VALUES (?,?,?,?,?)","insert",array(
                                $post['feature_name_es'][$key],
                                $post['feature_value_es'][$key],
                                $post['property_id'],
                                'es',
                                $jj
                            ));
                        }
                        $jj++;
                    }
                }



                // Add images to property
                if(isset($post['images']) && !empty($post['images'])){
                  $destination = "uploads/listings/".$property_id."/";

                  if(!file_exists($destination)) {
                      mkdir($destination, 0777, true);
                  }

                  foreach($post['images'] as $kk=>$vv){

                    new SqlIt("UPDATE property_photos SET img_order = ? WHERE img_name = ? AND property_id = ?","update",array($kk,$vv,$post['property_id']));
                  }
                }



                return $post['property_id'];

            }else{
                return 0;
            }
        }
    }
}


class PubListings{
    public $ListCnt;
    public $List = array();
    public $PropertyTypes = array();
    public $ListingTypes = array();
    public $ZoneTypes = array();
    public $Cities = array();
    public $Agencies = array();
    public $BuyCom = array('high'=>0,'low'=>0);
    public $SellCom = array('high'=>0,'low'=>0);

    public function __construct($type='ADM'){
        /**
        // $type: ADM/PUB - whether the list being generated is for admin or public display
        **/

        if($type == 'PUB'){
            $this->getListings();
        }elseif($type == 'ADM'){
            $this->getListingsAdm();
        }
    }

    public function getListingsAdm(){
        $getList = new SqlIt("SELECT listing_id,listing_number,agency_name,agency_phone,agency_agent,property_group,card_format,book_section,days_on_market,status_change,price_original,price_current,parcel_num,county,city,buy_broker_com,sell_broker_com FROM listings_upload","select",array());

        if($getList->NumResults > 0){
            foreach($getList->Response as $gi){
                $this->List[$gi->listing_id] = $gi;
                $this->PropertyTypes[] = $gi->book_section;
                $this->ListingTypes[] = $gi->property_group;
                $this->ZoneTypes[] = $gi->county;
                $this->Cities[] = $gi->city;
                $this->Agencies[] = $gi->agency_name;
                if($this->BuyCom['low'] == 0){
                    $this->BuyCom['low'] = $gi->buy_broker_com;
                    $this->SellCom['low'] = $gi->sell_broker_com;
                }
                if($gi->buy_broker_com > $this->BuyCom['high']){
                    $this->BuyCom['high'] = $gi->buy_broker_com;
                }
                if($gi->sell_broker_com > $this->SellCom['high']){
                    $this->SellCom['high'] = $gi->sell_broker_com;
                }
                if($gi->buy_broker_com < $this->BuyCom['low']){
                    $this->BuyCom['low'] = $gi->buy_broker_com;
                }
                if($gi->sell_broker_com < $this->SellCom['low']){
                    $this->SellCom['low'] = $gi->sell_broker_com;
                }
            }
        }
    }
}

?>
