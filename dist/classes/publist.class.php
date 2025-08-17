<?php
class SiteListings{
    public $Featured;
    public $Pagination;
    public $Filters;
    public $SearchParams;
    public $List = array(0=>array(),1=>array());
    public $SideBar = array('areas'=>array(),'counties'=>array(),'ptypes'=>array(),'ltypes'=>array(),'pstypes'=>array());


    public function __construct($search=array(),$pag=1){
        if(!empty($search)){
            // $search variable is the post content from search form
            $this->SearchParams = new Search($search);
            $this->getListings($pag);
        }elseif($search == 0){
            $this->SearchParams = new Search(array('page'=>1));
            $this->getListings($pag);
        }
    }


    public function getListings($pag=1){
        global $get;
        global $lang;
        global $link_properties;

        $limit_query1 = '';
        $limit_query2 = '';
        $do_query1 = 1;
        $do_query2 = 1;

        $budd_query = $up_query = '';
        $budd_vars = $up_vars = array();

        //$final_query = array();
        if(!empty($this->SearchParams->ReturnBuddQuery['query'])){
          foreach($this->SearchParams->ReturnBuddQuery['query'] as $qq){
            if($qq != ''){
              //echo 'qq->'.$qq;
              $budd_query .= " ".$qq." ";
            }
          }

        }



        if(!empty($this->SearchParams->ReturnUpQuery['query'])){
            $up_query = implode(' ',$this->SearchParams->ReturnUpQuery['query']);
        }

        //Set up pagination for display
        $this->Pagination = new stdClass;
        $this->Pagination->Limit = $limit = 10;
        $this->Pagination->TotalResults = $total_res = 0;
        $this->Pagination->TotalPages = $total_pages = 1;
        $this->Pagination->CurrentPage = $current_page = $this->SearchParams->Page;
        $this->Pagination->Links['prev'] = '<li><a href="javascript:void(0)" class="disabled">&lt;</a></li>';
        $this->Pagination->Links['next'] = '<li><a href="javascript:void(0)" class="disabled">&gt;</a></li>';
        $this->Pagination->Links['pages'][] = '<li><a href="javascript:void(0)" class="current">1</a></li>';
        $this->Pagination->LinksDisplay = $this->Pagination->Links['prev'].implode('',$this->Pagination->Links['pages']).$this->Pagination->Links['next'];


        if($pag == 1){

            // get total number of results to be returnd in both queries
            $getPages = new SqlIt("SELECT COUNT(p.property_id) FROM property_list AS p LEFT JOIN property_pricing as pr ON p.property_id = pr.property_id WHERE is_visible = 1 ".$budd_query." GROUP BY p.property_id","select",$this->SearchParams->ReturnBuddQuery['vars']);

            //echo "SELECT COUNT(p.property_id) FROM property_list AS p LEFT JOIN property_pricing as pr ON p.property_id = pr.property_id WHERE is_visible = 1 ".$budd_query." GROUP BY p.property_id";


            /*$getPages2 = new SqlIt("SELECT COUNT(listing_id) FROM listings_upload WHERE (end_date >= NOW() OR end_date = '0000-00-00')".$up_query." GROUP BY listing_id","select",$this->SearchParams->ReturnUpQuery['vars']);*/


            $this->Pagination->TotalResults = $getPages->NumResults;
            //$this->Pagination->TotalResults = $this->Pagination->TotalResults + $getPages2->NumResults;
            $this->Pagination->TotalPages = ceil($this->Pagination->TotalResults / $this->Pagination->Limit);


            if($this->Pagination->TotalPages > 1){
                $conc = '&';
                if($get == 'listings'){
                    $conc = '?';
                }

                if($lang == 'es'){
                  $get = str_replace('listings','es/propiedades',$get);
                }

                // set previous and next links if available
                if($this->Pagination->CurrentPage > 1){
                    $this->Pagination->Links['prev'] = '<li><a href="'.$get.$conc.'page='.($this->Pagination->CurrentPage - 1).'" class="pag-btns">&lt;</a></li>';
                }
                if($this->Pagination->CurrentPage < $this->Pagination->TotalPages){
                    $this->Pagination->Links['next'] = '<li><a href="'.$get.$conc.'page='.($this->Pagination->CurrentPage + 1).'" class="pag-btns">&gt;</a></li>';
                }

                // Create pages to display links to pagination
                $o = 0;

                for($i=1;$i<=$this->Pagination->TotalPages;$i++){
                    if($i == $this->Pagination->CurrentPage){
                        $this->Pagination->Links['pages'][$o] = '<li><a href="javascript:void(0)" class="current">'.$i.'</a></li>';
                    }else{
                        $this->Pagination->Links['pages'][$o] = '<li><a href="'.$get.$conc.'page='.$i.'">'.$i.'</a></li>';
                    }
                    $o++;
                }
                $this->Pagination->LinksDisplay = $this->Pagination->Links['prev'].implode('',$this->Pagination->Links['pages']).$this->Pagination->Links['next'];
            }


            // Get normal pages as if only one query were being called
            $start = ($limit * $current_page) - $limit;
            $end = $limit * $current_page;
            $start1 = $end1 = $start2 = $end2 = $limit1 = $limit2 = 0;
            $res1_count = $getPages->NumResults;
            if($res1_count <= $start){
                $do_query1 = 0;
                $start2 = $start - $res1_count;
                $end2 = $end - $res1_count;
                $limit2 = $limit;
            }elseif($res1_count > $start && $res1_count <= $end){
                $start1 = $start;
                $end1 = $res1_count;
                $limit1 = $end1;
                $start2 = 0;
                $end2 = $end - $end1;
                $limit2 = abs($limit - $limit1);
            }elseif($res1_count > $end){
                $do_query2 = 0;
                $start1 = $start;
                $end1 = $end;
                $limit1 = $limit;
            }

            // generate limit queries
            //$limit_query1 = ' LIMIT '.$limit.' OFFSET '.$start1;
            //$limit_query2 = ' LIMIT '.$limit.' OFFSET '.$start2;
            if($this->SearchParams->MapList == 0){
              $limit_query1 = ' LIMIT '.$start1.','.$limit1;
              $limit_query2 = ' LIMIT '.$start2.','.$limit2;
            }

            } // end of if statement for pagination automation

        if($do_query1 == 1){
        // retrieve all local listings
        $getProps = new SqlIt("SELECT p.*, a.area_name, c.location, s.state as state_name, t.town_name,
            (SELECT price_amt
                FROM property_pricing
                WHERE property_id = p.property_id
                    AND (price_type = 1 OR price_type = 2)
                    AND price_currency = 'usd'
                ORDER BY price_amt ASC LIMIT 1
            ) as price,
                (SELECT price_amt
                    FROM property_pricing
                    WHERE property_id = p.property_id
                        AND (price_type = 1 OR price_type = 2)
                        AND price_currency = 'mxn'
                    ORDER BY price_amt ASC LIMIT 1
                ) as price_mxn,
            (SELECT CONCAT(img_folder,'/',img_name)
                FROM property_photos
                WHERE property_id = p.property_id
                ORDER BY img_order ASC LIMIT 1
            ) as thumb FROM property_list AS p
            LEFT JOIN property_pricing as pr ON p.property_id = pr.property_id
            LEFT JOIN locations_areas as a ON a.area_id = p.area
            LEFT JOIN locations_cities as c ON c.city_id = p.city
            LEFT JOIN locations_states as s ON s.state_id = p.state
            LEFT JOIN locations_towns as t ON t.town_id = p.county
            WHERE is_visible = 1 ".$budd_query." GROUP BY p.property_id ".$this->SearchParams->BuddOrder.", p.property_id ".$limit_query1,"select",$this->SearchParams->ReturnBuddQuery['vars']);

            // echo "SELECT p.*, a.area_name, c.location, s.state as state_name, t.town_name,
            //     (SELECT price_amt
            //         FROM property_pricing
            //         WHERE property_id = p.property_id
            //             AND (price_type = 1 OR price_type = 2)
            //             AND price_currency = 'usd'
            //         ORDER BY price_amt ASC LIMIT 1
            //     ) as price,
            //         (SELECT price_amt
            //             FROM property_pricing
            //             WHERE property_id = p.property_id
            //                 AND (price_type = 1 OR price_type = 2)
            //                 AND price_currency = 'mxn'
            //             ORDER BY price_amt ASC LIMIT 1
            //         ) as price_mxn,
            //     (SELECT CONCAT(img_folder,'/',img_name)
            //         FROM property_photos
            //         WHERE property_id = p.property_id
            //         ORDER BY img_order ASC LIMIT 1
            //     ) as thumb FROM property_list AS p
            //     LEFT JOIN property_pricing as pr ON p.property_id = pr.property_id
            //     LEFT JOIN locations_areas as a ON a.area_id = p.area
            //     LEFT JOIN locations_cities as c ON c.city_id = p.city
            //     LEFT JOIN locations_states as s ON s.state_id = p.state
            //     LEFT JOIN locations_towns as t ON t.town_id = p.county
            //     WHERE is_visible = 1 ".$budd_query." GROUP BY p.property_id ".$this->SearchParams->BuddOrder.", p.property_id ".$limit_query1;
            //   //  print_me($budd_query);
            //   print_me($this->SearchParams->ReturnBuddQuery['vars']);

            if($getProps->NumResults != 0){
                foreach($getProps->Response as $pp){
                    $this->List[0][] = new QuickList($pp->property_id,$pp);
                }
                if($this->SearchParams->CityName == ''){
                   $this->SearchParams->CityName = utf8_decode($getProps->Response[0]->location);
                }
            }

            // get the active locations
            $loc_query = $this->SearchParams->ReturnBuddQuery['query'];
            $loc_vars = $this->SearchParams->ReturnBuddQuery['vars'];

            $this->getSideBar($loc_query,$loc_vars);

        }


       /* if($do_query2 == 1){
            // retrieve all uploaded listings
            $getUpProps = new SqlIt("SELECT * FROM listings_upload WHERE (end_date >= NOW() OR end_date = '0000-00-00')".$up_query." ".$limit_query2,"select",$this->SearchParams->ReturnUpQuery['vars']);

            if($getUpProps->NumResults != 0){
                foreach($getUpProps->Response as $pp){
                    $this->List[1][] = new QuickList($pp->listing_id,$pp,'uploaded');
                }
            }
        }*/
    }



    public function getSideBar($loc_query,$loc_vars){
      $search_param = '';
      if($this->SearchParams->SearchBy != 'city'){
         if($this->SearchParams->SearchBy == 'areas'){
           $loc_query = array(' AND county = ? ');
           $loc_vars = array($this->SearchParams->Counties[0]);
           $getCounty = new SqlIt("SELECT town_id FROM locations_areas WHERE area_id = ?","select",array($this->SearchParams->Areas[0]));
           if($getCounty->NumResults == 1){
             $search_param = $getCounty->Response[0]->town_id;
             $loc_vars = array($search_param);
           }
         }else{
            if($this->SearchParams->Cities){
                $loc_query = array(' AND city = ? ');
                $loc_vars = array($this->SearchParams->Cities[0]);
            }
         }
         $o=0;
         if(count($this->SearchParams->Cities) > 1){
           foreach($this->SearchParams->Cities as $cc){
             if($o != 0){
               $loc_query[] = ' OR city = ? ';
             }
           $o++; }
           $loc_vars = $this->SearchParams->Cities;
         }else{
           if($this->SearchParams->SearchBy == 'all'){
             $loc_query = array();
             $loc_vars = array();
             $this->SearchParams->CityName = '';
           }
         }
      }

      $loc_query = implode(' ',$loc_query);


      $getLocs = new SqlIt("SELECT p.city,p.area,p.county,p.pr_list_type_id,p.pr_type_id,p.pr_sub_type_id FROM property_list as p LEFT JOIN property_pricing as pr ON p.property_id = pr.property_id WHERE is_visible = 1 ".$loc_query." GROUP BY p.property_id","select",$loc_vars);

      // echo "SELECT p.city,p.area,p.county,p.pr_list_type_id,p.pr_type_id,p.pr_sub_type_id FROM property_list as p LEFT JOIN property_pricing as pr ON p.property_id = pr.property_id WHERE is_visible = 1 ".$loc_query." GROUP BY p.property_id";
      // print_me($loc_vars);

      $cities = $areas = $counties = $ptypes = $ltypes = $pstypes = array();

      if($getLocs->NumResults > 0){
         foreach($getLocs->Response as $rr){
            $cities[] = $rr->city;
            if($this->SearchParams->SearchBy == 'counties'){
               if(in_array($rr->county,$this->SearchParams->Counties)){
                  $areas[] = $rr->area;
                  $ptypes[] = $rr->pr_type_id;
                  $pstypes[] = $rr->pr_sub_type_id;
                  $ltypes[] = $rr->pr_list_type_id;
               }
            }elseif($this->SearchParams->SearchBy == 'areas'){
               if(in_array($rr->county,$this->SearchParams->Counties)){
                  $areas[] = $rr->area;
               }
               if($search_param != ''){
                 $areas[] = $rr->area;
               }
               if(in_array($rr->area,$this->SearchParams->Areas)){
                  $ptypes[] = $rr->pr_type_id;
                  $ltypes[] = $rr->pr_list_type_id;
                  $pstypes[] = $rr->pr_sub_type_id;
               }
            }else{
               $areas[] = $rr->area;
               $ptypes[] = $rr->pr_type_id;
               $ltypes[] = $rr->pr_list_type_id;
               $pstypes[] = $rr->pr_sub_type_id;
            }

            $counties[] = $rr->county;

         }

         $this->SideBar['areas'] = array_count_values($areas);
         $this->SideBar['cities'] = array_count_values($cities);
         $this->SideBar['counties'] = array_count_values($counties);
         $this->SideBar['ptypes'] = array_count_values($ptypes);
         $this->SideBar['pstypes'] = array_count_values($pstypes);
         $this->SideBar['ltypes'] = array_count_values($ltypes);
         //print_me($this->SideBar);
      }
    }




    public function getFeatured($count = 4){
        $getF = new SqlIt("
        SELECT p.*, a.area_name, c.location, s.state as state_name, t.town_name,
            (SELECT price_amt
                FROM property_pricing
                WHERE property_id = p.property_id
                    AND (price_type = 1 OR price_type = 2)
                ORDER BY price_amt ASC LIMIT 1
            ) as price,
            (SELECT CONCAT(img_folder,img_name)
                FROM property_photos
                WHERE property_id = p.property_id
                    AND img_type = 'listing_gallery'
                ORDER BY photo_id ASC LIMIT 1
            ) as thumb
        FROM property_list as p
        LEFT JOIN locations_areas as a ON a.area_id = p.area
        LEFT JOIN locations_cities as c ON c.city_id = p.city
        LEFT JOIN locations_states as s ON s.state_id = p.state
        LEFT JOIN locations_towns as t ON t.town_id = p.county
        ORDER BY is_featured DESC, property_id DESC LIMIT ".$count,"select",array());

        if($getF->NumResults > 0){
            $this->Featured = array();
            foreach($getF->Response as $gf){
                $this->Featured[] = new QuickList($gf->property_id,$gf);
            }
        }
    }


    public function getPropertyTypes(){
        global $lang;
        $prop_types = array();

        $getPr = new SqlIt("SELECT pt.*, ps.*, pt.pr_type_id as tid FROM property_types as pt LEFT JOIN property_types_sub as ps ON pt.pr_type_id = ps.pr_type_id ORDER BY type_desc","select",array());

        if($getPr->NumResults > 0){
            foreach($getPr->Response as $gg){
                if($lang == 'es'){
                  $sdesc = $gg->sub_desc_es;
                  $tdesc = $gg->type_desc_es;
                }else{
                  $sdesc = $gg->sub_desc;
                  $tdesc = $gg->type_desc;
                }
                if(isset($prop_types[$gg->tid])){
                    $prop_types[$gg->tid]['subs'][$gg->sub_id] = $sdesc;
                }else{
                    $prop_types[$gg->tid]['desc'] = $tdesc;
                    $prop_types[$gg->tid]['desc_up'] = '';
                    $prop_types[$gg->tid]['subs'] = array();
                    if($gg->sub_id != ''){
                        $prop_types[$gg->tid]['subs'][$gg->sub_id] = $sdesc;
                    }
                }
            }
        }

        return $prop_types;
    }


    public function getListTypes(){
        $list_types = array();

        $getPr = new SqlIt("SELECT * FROM property_list_types ORDER BY type_name","select",array());

        if($getPr->NumResults > 0){
            foreach($getPr->Response as $gg){
                $list_types[$gg->list_type_id] = $gg->type_name;
            }
        }

        return $list_types;
    }


    public function getLocations(){
        $cities = array();
        $getLoc = new SqlIt("SELECT p.city, l.location FROM property_list AS p LEFT JOIN locations_cities AS l ON l.city_id = p.city ORDER BY l.location ASC","select",array());
        $getUpLoc = new SqlIt("SELECT city as up_city FROM listings_upload ORDER BY city ASC","select",array());

        if($getLoc->NumResults > 0){
            foreach($getLoc->Response as $gl){
                $cities[] = $gl->city;
            }
        }
        if($getUpLoc->NumResults > 0){
            foreach($getUpLoc->Response as $gl){
                $cities[] = $gl->up_city;
            }
        }
        return array_count_values($cities);
    }


    public function getLocations2(){
        $cities = array();
        $getLoc = new SqlIt("SELECT p.city, l.location FROM property_list AS p LEFT JOIN locations_cities AS l ON l.city_id = p.city ORDER BY l.location ASC","select",array());
        $getUpLoc = new SqlIt("SELECT city as up_city FROM listings_upload ORDER BY city ASC","select",array());

        if($getLoc->NumResults > 0){
            foreach($getLoc->Response as $gl){
              if(isset($cities[$gl->city])){
                $cities[$gl->city]['cnt']++;
              }else{
                $cities[$gl->city]['cnt'] = 1;
                $cities[$gl->city]['loc'] = utf8_encode($gl->location);
              }

            }
        }
        if($getUpLoc->NumResults > 0){
            foreach($getUpLoc->Response as $gl){
                $cities[] = $gl->up_city;
            }
        }
        return $cities;
    }


    public function getPriceRange($list_type=0){

        $where_budd = '';
        $where_uploaded = '';
        $min = 0;
        $max = 500000000;

        if($list_type != 0){
            if($list_type == 1 || $list_type == 2){
                $where_budd .= 'WHERE price_type = 2';
                $where_uploaded .= "WHERE property_group = 'Rental'";
            }elseif($list_type == 3){
                $where_budd .= 'WHERE price_type = 1';
                $where_uploaded .= "WHERE property_group <> 'Rental'";
            }
        }


        $getPrices = new SqlIt("SELECT MIN(price_amt) as min_amt, MAX(price_amt) as max_amt FROM property_pricing ".$where_budd,"select",array());

        //$getUpPrices = new SqlIt("SELECT MIN(price_current) as min_amt, MAX(price_current) as max_amt FROM listings_upload ".$where_uploaded,"select",array());

        if($getPrices->NumResults > 0){

            $min = $getPrices->Response[0]->min_amt;
            $max = $getPrices->Response[0]->max_amt;

        }

        /*if($getUpPrices->NumResults > 0){
            $up = $getUpPrices->Response[0];
            if($up->min_amt < $min || ($min == 0 && $up->min_amt > 0)){
                $min = $up->min_amt;
            }
            if($up->max_amt > $max){
                $max = $up->max_amt;
            }
        }*/
        return array($min,$max);
    }
}


class QuickList{
    public $PropId;
    public $MLS;
    public $IsFeatured;
    public $PropStatus;
    public $DisplayStatus;
    public $PropType;
    public $SaleType;
    public $PropThumb;
    public $PropLocation;
    public $PropTitle;
    public $PropDesc;
    public $PropSize;
    public $PropCosts;
    public $PropCostsMXN;

    public function __construct($prop_id=0,$vars=array(),$type='local'){
        global $lang;
        // Create elements to be added
        $this->PropLocation = new stdClass;
        $pp = $this->PropLocation;
        $pp->Address = '';
        $pp->CityID = 0;
        $pp->StateID = 0;
        $pp->AreaID = 0;
        $pp->TownID = 0;
        $pp->City = '';
        $pp->State = '';
        $pp->Zip = '';
        $pp->County = '';
        $pp->Latitude = '';
        $pp->Longitude = '';

        $this->PropSize = new stdClass;
        $pp = $this->PropSize;
        $pp->SqFt = 0;
        $pp->SqMt = 0;
        $pp->LotSize = 0;
        $pp->LotSizeMt = 0;
        $pp->TotalRooms = 0;
        $pp->Bedrooms = 0;
        $pp->Bathrooms = 0;
        $pp->TotalBaths = 0;
        $pp->Studio = 0;
        $pp->Loft = 0;


        if($prop_id != 0){
            $this->PropId = $prop_id;
            if(!empty($vars)){
                if($type == 'local'){
                    $this->MLS = $vars->mls_num;
                    $this->IsFeatured = $vars->is_featured;
                    $this->PropStatus = $vars->pr_status;
                    $this->DisplayStatus = $vars->pr_display_status;
                    $this->PropType = $vars->pr_type_id;
                    $this->SaleType = $vars->pr_list_type_id;
                    $this->PropTitle = $vars->property_title;
                    $this->PropDesc = $vars->property_desc;
                    if($lang == 'es'){
                      $getEs = new SqlIt("SELECT * FROM property_list_es WHERE property_id = ? AND lang = ?","select",array($this->PropId,'es'));
                      if($getEs->NumResults > 0){
                        $vv = $getEs->Response[0];
                        $this->PropTitle = $vv->prop_title;
                        $this->PropDesc = $vv->prop_desc;
                        if($vv->prop_display != ''){
                          $this->DisplayStatus = $vv->prop_display;
                        }
                      }
                    }
                    $this->PropLocation->Address = $vars->address.' '.$vars->unit_num;
                    $this->PropLocation->CityID = $vars->city;
                    $this->PropLocation->StateID = $vars->state;
                    $this->PropLocation->AreaID = $vars->area;
                    $this->PropLocation->TownID = $vars->county;
                    $this->PropLocation->Zip = $vars->zip;
                    $this->PropLocation->County = $this->ecoded($vars->town_name);
                    $this->PropLocation->City = $this->ecoded($vars->location);
                    $this->PropLocation->State = $this->ecoded($vars->state_name);
                    $this->PropLocation->Area = $this->ecoded($vars->area_name);
                    $this->PropLocation->Latitude = $vars->latitude;
                    $this->PropLocation->Longitude = $vars->longitude;
                    $this->PropSize->Bedrooms = $vars->bedrooms;
                    if($vars->room_type == 'studio'){
                      $this->PropSize->Studio = 1;
                    }
                    if($vars->room_type == 'loft'){
                      $this->PropSize->Loft = 1;
                    }
                    $this->PropSize->Bedrooms = $vars->bedrooms;
                    $this->PropSize->Bathrooms = $vars->bathrooms;
                    $this->PropSize->TotalRooms = $vars->bedrooms + $vars->bathrooms;
                    $this->PropSize->TotalBaths = $vars->bathrooms;
                    if($vars->half_baths > 0){
                      $half_baths = $vars->half_baths * .5;
                      $this->PropSize->TotalBaths = $vars->bathrooms + $half_baths;
                    }

                    $this->PropSize->SqFt = $this->rounded($vars->size_sq_ft);
                    $this->PropSize->SqMt = $this->rounded($vars->size_sq_mt);
                    $this->PropSize->LotSize = $this->rounded($vars->size_lot);
                    $this->PropSize->LotSizeMt = $this->rounded($vars->size_lot_mt);
                    $this->PropCosts = $vars->price;
                    if(isset($vars->price_mxn)){
                      $this->PropCostsMXN = $vars->price_mxn;
                    }else{
                      $this->PropCostsMXN = $vars->price * 20;
                    }
                    
                    $this->PropThumb = $vars->thumb;
                }
                // if($type == 'uploaded'){
                //     $this->MLS = $vars->listing_number;
                //     $this->PropStatus = 1;
                //     $this->PropType = $vars->card_format;
                //     $this->SaleType = $vars->property_group;
                //     if($vars->remark1 != ''){
                //         $this->PropTitle = get_words($vars->remark1);
                //         $this->PropDesc = $vars->remark1;
                //     }else{
                //         $this->PropTitle = $vars->property_group.' in '.$vars->city;
                //         $this->PropDesc = '<em>No description available</em>';
                //     }
                //     $this->PropLocation->Address = $vars->address;
                //     $this->PropLocation->City = $vars->city;
                //     $this->PropLocation->State = $vars->state;
                //     $this->PropLocation->Zip = $vars->zip;
                //     $this->PropLocation->County = $vars->county;
                //     $this->PropLocation->Latitude = $vars->geo_lat;
                //     $this->PropLocation->Longitude = $vars->geo_lon;
                //     $this->PropSize->Bedrooms = $vars->total_beds;
                //     $this->PropSize->Bathrooms = $vars->total_bath;
                //     $this->PropSize->TotalRooms = $vars->total_rooms;
                //     $this->PropSize->SqFt = round($vars->sqft_total);
                //     $this->PropCosts = $vars->price_current;
                //     $this->PropThumb = $vars->photo_url;
                // }
            }
        }
    }

    private function rounded($val){
      $newval = $val;
      if($val > 0){
        $newval = round($val);
      }
      return $newval;
    }

    private function ecoded($val){
      $newval = $val;
      if($val != null){
        $newval = utf8_encode($val);
      }
      return $newval;
    }
}


class Search{
    public $SearchType = 'basic';
    public $SearchBy = 'city';
    public $CityName = '';
    public $ReturnBuddQuery = array('query'=>array(),'vars'=>array());
    public $ReturnUpQuery = array('query'=>array(),'vars'=>array());
    public $BuddOrder;
    public $MapList = 0;

    public $Page = 0;
    public $OrderBy;

    public $Currency = 'usd';
    public $MLS = 0;
    public $ListingType;
    public $MinPrice = 0;
    public $MaxPrice = 10000000000;
    public $MinBeds = 0;
    public $MaxBeds = 0;
    public $MinBaths = 0;
    public $MaxBaths = 0;
    public $Studio = 0;
    public $Loft = 0;
    public $SqFt = 0;
    public $BuiltBefore = 0;
    public $BuiltAfter = 0;
    public $DevType;

    public $Counties = array();
    public $Cities = array();
    public $Zips = array();
    public $Areas = array();
    public $PropTypes = array();
    public $PropSubTypes = array();
    public $Features = array();
    public $PropStyles = array();

    public function __construct($post){
        if(isset($post['page'])){
            $this->Page = $post['page'];
        }
        if(!empty($post) && isset($post['search_type'])){
            $this->SearchType = $post['search_type'];

            // Check for advanced search parameters
            if(isset($post['adv_property_types'])){
               $this->SearchType = 'advanced';
            }

            if(isset($post['price_min'])){
              $this->MinPrice = str_replace('$','',$post['price_min']);
            }
            if(isset($post['price_max'])){
              $this->MaxPrice = str_replace('$','',$post['price_max']);
            }
            if(isset($post['order_by'])){
              $this->OrderBy = $post['order_by'];
              $this->BuddOrder = ' ORDER BY is_featured DESC ';
              switch($post['order_by']){
                case 'price_high':
                  $this->BuddOrder = ' ORDER BY price DESC ';
                  break;
                case 'price_low':
                  $this->BuddOrder = ' ORDER BY price ASC ';
                  break;
                case 'size_high':
                  $this->BuddOrder = ' ORDER BY size_sq_ft DESC ';
                  break;
                case 'size_low':
                  $this->BuddOrder = ' ORDER BY size_sq_ft ASC ';
                  break;
                case 'alpha':
                  $this->BuddOrder = ' ORDER BY property_title ASC ';
                  break;
                case 'alpha_desc':
                  $this->BuddOrder = ' ORDER BY property_title DESC ';
                  break;
              }
            }


            // Check if the maps are enabled and only return results with coordinates
            if(isset($post['map']) && $post['map'] == 1){
              $this->MapList = 1;
            }


            switch($this->SearchType){
                case 'basic':
                    //print_me($post);
                    $this->SearchBy = 'city';
                    if(isset($post['list_type'])){
                      $this->ListingType = $post['list_type'];
                    }
                    if($post['location'] == 0 && isset($post['cities']) && $post['cities'][0] > 0){
                      $this->Cities = $post['cities'];
                    }else{
                      $this->Cities[] = $post['location'];
                    }
                    if(isset($post['beds'])){
                      $this->MinBeds = $post['beds'];  
                    }
                    if(isset($post['baths'])){
                      $this->MinBeds = $post['baths'];  
                    }
                    
                    //$this->MaxBeds = $post['beds'];
                    //$this->MaxBaths = $post['baths'];
                    if(isset($post['studio'])){
                      //echo 'studio';
                      $this->Studio = $post['studio'];
                    }
                    if(isset($post['loft'])){
                      //echo 'loft';
                      $this->Loft = $post['loft'];
                    }
                    if($post['beds'] == 888){
                      $this->Studio = 1;
                      $this->Loft = 1;
                      //echo "hey";
                      $this->MinBeds = $this->MaxBeds = 0;
                    }

                    if($post['beds'] == 555 || $post['beds'] == '555'){
                      $this->Studio = 1;
                      $this->MinBeds = $this->MaxBeds = 0;
                    }

                    if($post['beds'] == 666 || $post['beds'] == '666'){
                      $this->Loft = 1;
                      $this->MinBeds = $this->MaxBeds = 0;
                    }

                    if(isset($post['property_type']) && !empty($post['property_type'])){
                      $this->PropTypes[] = $post['property_type'];
                    }
                    
                    if(isset($post['currency'])){
                      $this->Currency = $post['currency'];
                    }
                    if(isset($post['built_after']) && $post['built_after'] != 0 && $post['built_after'] != '0'){
                        $this->BuiltAfter = $post['built_after'];
                    }
                    if(isset($post['dev_type'])){
                        $this->DevType = $post['dev_type'];
                    }
                    $this->generateQuery();

                    break;
                case 'advanced':
                    if(isset($post['search_by'])){
                       $this->SearchBy = $post['search_by'];
                     }
                    if($post['location'] == 0 && isset($post['cities'])){
                      if($post['cities'][0] == 0){
                        unset($post['cities'][0]);
                        $post['cities'] = array_values($post['cities']);
                      }
                      if(!empty($post['cities'])){
                        $this->Cities = $post['cities'];
                      }
                    }elseif(isset($post['location'])){
                      $this->Cities[] = $post['location'];
                    }

                    if(isset($post['counties']) && !empty($post['counties'])){
                      if($post['counties'][0] == 0){
                        unset($post['counties'][0]);
                        reset($post['counties']);
                      }
                      if(!empty($post['counties'])){
                        $this->Counties = $post['counties'];
                        $this->SearchBy = 'counties';
                      }
                    }

                    if(isset($post['areas']) && !empty($post['areas'])){
                      if($post['areas'][0] == 0){
                        unset($post['areas'][0]);
                        reset($post['areas']);
                      }
                      if(!empty($post['areas'])){
                        $this->Areas = $post['areas'];
                        $this->SearchBy = 'areas';
                      }
                    }

                    if(isset($post['zips']) && !empty($post['zips'])){
                      if($post['zips'][0] == 0){
                        unset($post['zips'][0]);
                        reset($post['zips']);
                      }
                      if(!empty($post['zips'])){
                        $this->Zips = $post['zips'];
                      }
                    }

                    //print_me($post);
                    if(isset($post['adv_property_types'])){
                      $this->PropTypes = $post['adv_property_types'];
                    }

                    if(isset($post['min_beds'])){
                      $this->MinBeds = $post['min_beds'];  
                      //echo $this->MinBeds;
                    }
                    if(isset($post['max_beds'])){
                      $this->MaxBeds = $post['max_beds'];  
                    }
                    if(isset($post['min_baths'])){
                      $this->MinBaths = $post['min_baths'];  
                    }
                    
                    if(isset($post['studio'])){
                      $this->Studio = $post['studio'];
                    }
                    if(isset($post['loft'])){
                      $this->Studio = $post['loft'];
                    }
                    
                    if(isset($post['beds']) && ($post['beds'] == 555 || $post['beds'] == '555')){
                      $this->Studio = 1;
                      $this->MinBeds = 0;
                    }

                    if(isset($post['beds']) && ($post['beds'] == 666 || $post['beds'] == '666')){
                      $this->Loft = 1;
                      $this->MinBeds = 0;
                    }

                    if(isset($post['list_type'])){
                      $this->ListingType = $post['list_type'];
                    }
                    if(isset($post['sqft'])){
                      $this->SqFt = $post['sqft'];
                    }
                    if(isset($post['built_before']) && $post['built_before'] != 0 && $post['built_before'] != '0'){
                        $this->BuiltBefore = $post['built_before'];
                    }
                    if(isset($post['built_before']) && $post['built_before'] != 0 && $post['built_before'] != '0'){
                        $this->BuiltBefore = $post['built_before'];
                    }
                    if(isset($post['built_after']) && $post['built_after'] != 0 && $post['built_after'] != '0'){
                      $this->BuiltAfter = $post['built_after'];
                    }
                    if(isset($post['dev_type'])){
                      $this->DevType = $post['dev_type'];
                    }
                    if(isset($post['mls_num']) && $post['mls_num'] != ''){
                      $this->MLS = trim($post['mls_num']);
                      $this->SearchBy = 'mls';
                    }

                    // possible empty variables when nothing has been selected
                    if(isset($post['features'])){
                        $this->Features = $post['features'];
                    }
                    if(isset($post['adv_property_subs'])){
                        $this->PropSubTypes = $post['adv_property_subs'];
                    }
                    $this->generateQuery();
                    break;
            }
        }
    }


    public function generateQuery($type='all'){
        $budd_query = '';
        $up_query = '';
        $budd_vars = array();
        $up_vars = array();


        // map search
        if($this->MapList == 1){
            $this->ReturnBuddQuery['query'][] = ' AND p.latitude <> "" AND p.longitude <> "" ';
        }

        if($this->SearchBy == 'mls'){
          $this->ReturnBuddQuery['query'][] = ' AND p.mls_num = ? ';
          $this->ReturnBuddQuery['vars'][] = $this->MLS;
        }else{
          // cities search
          if(!empty($this->Cities) && $this->Cities[0] !== '0' && $this->SearchBy == 'city'){
              if(count($this->Cities) == 1){
                  $budd_query = $up_query = " AND city = ".$this->Cities[0]." ";
                  $getCity = new SqlIt("SELECT location FROM locations_cities WHERE city_id = ?","select",array($this->Cities[0]));
                  $this->CityName = $getCity->Response[0]->location;
              }else{
                  $budd_query .= " AND (city = ".$this->Cities[0]." ";

                  for($i=1;$i<count($this->Cities);$i++){
                      $budd_query .= " OR city = ".$this->Cities[$i];
                  }
                  $budd_query .= ")";
              }
              $up_query = $budd_query;
              $this->ReturnBuddQuery['query'][] = $budd_query;
          }

          // areas search
          if(!empty($this->Areas) && $this->Areas[0] !== '0'  && $this->SearchBy == 'areas'){
            $budd_query = '';
              if(count($this->Areas) == 1){
                  $budd_query = $up_query = " AND area = ? ";
                  $budd_vars[] = $this->Areas[0];
              }else{
                  $budd_query .= " AND (area = ? ";
                     $budd_vars[] = $this->Areas[0];

                  for($i=1;$i<count($this->Areas);$i++){
                      $budd_query .= " OR area = ? ";
                      $budd_vars[] = $this->Areas[$i];
                  }

                  $budd_query .= ")";
              }

              $up_query = $budd_query;
              $this->ReturnBuddQuery['query'][] = $budd_query;
              $this->ReturnBuddQuery['vars'] = $budd_vars;
          }

          // counties search
          if(!empty($this->Counties) && $this->Counties[0] != '0' && $this->SearchBy == 'counties'){
              $budd_query = '';
              if(count($this->Counties) == 1){
                  $budd_query = $up_query = " AND county = ? ";
                  $budd_vars[] = $this->Counties[0];
              }else{
                  $bb = array();
                  foreach($this->Counties as $cc){
                    $bb[] = " county = ? ";
                    $budd_vars[] = $cc;
                  }
                  $join = implode(' OR ',$bb);
                  $budd_query = "AND (".$join.")";
              }

              $up_query = $budd_query;
              $this->ReturnBuddQuery['query'][] = $budd_query;
              $this->ReturnBuddQuery['vars'] = $budd_vars;
          }

          // list type
          if($this->ListingType > 0){
            $this->ReturnBuddQuery['query'][] = " AND pr_list_type_id = ".$this->ListingType;
          }

          // zips search
         if(!empty($this->Zips) && $this->Zips[0] !== '0' && $this->SearchBy == 'zip'){
              if(count($this->Zips) === 1){
                  $budd_query = " AND zip = ?";
                  $budd_vars[] = $this->Zips[0];
                  $up_query = " AND zip LIKE '%".ltrim($this->Zips[0],'0')."'";
              }else{
                  $budd_query = " AND (zip = ? ";
                  $budd_vars[] = $this->Zips[0];
                  $up_query = " AND (zip LIKE '%".ltrim($this->Zips[0],'0')."' ";
                  for($i=1;$i<count($this->Zips);$i++){
                      $up_query .= " OR zip LIKE '%".ltrim($this->Zips[$i],'0')."'";
                      $budd_query .= " OR zip = ?";
                      $budd_vars[] = $this->Zips[$i];
                  }
                  $budd_query .= ")";
                  $up_query .= ")";
              }
              $this->ReturnBuddQuery['query'][] = $budd_query;
              $this->ReturnBuddQuery['vars'] = $budd_vars;
          }

          // property type search
          if(!empty($this->PropTypes)){
              $bquery = $uquery = array();
              $budd_query = '';
              foreach($this->PropTypes as $kk=>$pt){
                  /*$sep = explode('-',$pt);
                  $word = $sep[1];
                  $digit = $sep[0];*/
                  $digit = $pt;

                  if($pt !== '0' && $pt != ''){
                      $bquery[] = 'pr_type_id = ?';
                      $budd_vars[] = $digit;

                      /*$uquery[] = "property_group LIKE '%".$word."%'";
                      $uquery[] = "book_section LIKE '%".$word."%'";*/
                      if(count($this->PropTypes) == 1){
                          $budd_query = ' AND '.$bquery[0];
                          //$up_query = ' AND ('.$uquery[0].' OR '.$uquery[1].')';
                      }else{
                          $budd_query = ' AND ('.implode(' OR ',$bquery).')';
                          //$up_query = ' AND ('.implode(' OR ',$uquery).')';
                      }
                  }else{
                    unset($this->PropTypes[$kk]);
                  }


              }

              if(!empty($budd_vars)){
                $this->ReturnBuddQuery['query'][] = $budd_query;
                $this->ReturnBuddQuery['vars'] = $budd_vars;
                $this->ReturnUpQuery['query'][] = $up_query;
              }
          }




          // property subtype search
          if(!empty($this->PropSubTypes) && $this->PropSubTypes[0] !== '0'){
              $bquery = $uquery = array();
              $budd_query = $up_query = '';
              foreach($this->PropSubTypes as $pt){
                  /* $sep = explode('-',$pt);
                  $word = $sep[1];
                  $digit = $sep[0];*/
                  $digit = $pt;

                  if($pt !== '0' && $pt != ''){
                      $bquery[] = 'pr_sub_type_id = ?';
                      $budd_vars[] = $digit;
                      //$uquery[] = "book_section = ?";
                      //$up_vars[] = $word;
                      if(count($this->PropSubTypes) == 1){
                          $budd_query = ' AND '.$bquery[0];
                          //$up_query = ' AND '.$uquery[0];
                      }else{
                          //$budd_query = ' AND ('.implode(" OR ",$bquery).')';
                          $budd_query = "AND (".implode(' OR ',$bquery).")";
                          //$up_query = ' AND ('.implode(' OR ',$uquery).')';
                      }
                  }


              }
              if($budd_query != ''){
                $this->ReturnBuddQuery['query'][] = $budd_query;
                $this->ReturnBuddQuery['vars'] = $budd_vars;
                $this->ReturnUpQuery['query'][] = $up_query;
                $this->ReturnUpQuery['vars'] = $up_vars;
              }
          }
        
          // min beds
          if($this->MinBeds !== '0' && $this->MinBeds !== 0 && $this->MinBeds != ''){
              $this->ReturnBuddQuery['query'][] = ' AND bedrooms >= ?';
              $this->ReturnBuddQuery['vars'][] = $this->MinBeds;
          }

          // max beds
          if($this->MaxBeds !== '0' && $this->MaxBeds !== 0 && $this->MaxBeds != ''){
              $this->ReturnBuddQuery['query'][] = ' AND bedrooms <= ?';
              $this->ReturnBuddQuery['vars'][]= $this->MaxBeds;
          }

          // min baths
          if($this->MinBaths !== '0' && $this->MinBaths !== 0 && $this->MinBaths != ''){
              $this->ReturnBuddQuery['query'][] = ' AND bathrooms >= ?';
              $this->ReturnBuddQuery['vars'][] = $this->MinBaths;
          }

          // max baths
          if($this->MaxBaths !== '0' && $this->MinBaths !== 0 && $this->MaxBaths != ''){
              $this->ReturnBuddQuery['query'][] = ' AND bathrooms <= ?';
              $this->ReturnBuddQuery['vars'][] = $this->MaxBaths;
          }

          // studio
          if($this->Studio == 1 || $this->Studio == '1'){
            if($this->Loft == 1){
              $this->ReturnBuddQuery['query'][] = ' AND room_type = ? OR room_type = ?';
              $this->ReturnBuddQuery['vars'][] = 'studio';
              $this->ReturnBuddQuery['vars'][] = 'loft';
            }else{
              $this->ReturnBuddQuery['query'][] = ' AND room_type = ?';
              $this->ReturnBuddQuery['vars'][] = 'studio';
            }
            
          }

          // loft
          if($this->Loft == 1 || $this->Loft == '1'){
            if($this->Studio != 1 && $this->Studio != '1'){
              $this->ReturnBuddQuery['query'][] = ' AND room_type = ?';
              $this->ReturnBuddQuery['vars'][] = 'loft';
            }
          }

          // square footage
          if($this->SqFt > 0){
              $this->ReturnBuddQuery['query'][] = ' AND (size_sq_ft >= ? OR size_sq_ft = 0)';
              $this->ReturnBuddQuery['vars'][] = $this->SqFt;
          }

          // min price
          if($this->MinPrice !== '' && $this->MinPrice != 0 && $this->MinPrice != '0'){
              // remove all non numerical variables
              $min_price = str_replace(',','',$this->MinPrice);
              $min_price = str_replace('.','',$min_price);
              $this->ReturnBuddQuery['query'][] = ' AND pr.price_amt >= ? AND (pr.price_type = 1 OR pr.price_type = 2) ';
              //$this->ReturnBuddQuery['query'][] = ' AND pr.price_amt >= ? AND (pr.price_type = 1) ';
              $this->ReturnBuddQuery['vars'][] = $min_price;
          }

          // max price
          if($this->MaxPrice !== '' && $this->MaxPrice != 0 && $this->MaxPrice != '0'){
              // remove all non numerical variables
              $max_price = str_replace(',','',$this->MaxPrice);
              $max_price = str_replace('.','',$max_price);
              $this->ReturnBuddQuery['query'][] = ' AND pr.price_amt <= ? AND (pr.price_type = 1 OR pr.price_type = 2) ';
              $this->ReturnBuddQuery['vars'][] = $max_price;
          }

          // built before


          // built before
         if($this->BuiltBefore > 0){
              $this->ReturnBuddQuery['query'][] = ' AND year_built <= ?';
              $this->ReturnBuddQuery['vars'][] = $this->BuiltBefore;
          }

          // built after
          if($this->BuiltAfter > 0){
              $this->ReturnBuddQuery['query'][] = ' AND year_built >= ?';
              $this->ReturnBuddQuery['vars'][] = $this->BuiltAfter;
          }

          if($this->DevType != ''){
            switch($this->DevType){
              case 'construction':
                $query = ' AND is_construction = 1';
                break;
              case 'built':
                $query = ' AND is_construction = 0';
                break;
            }
            if(isset($query)){
                $this->ReturnBuddQuery['query'][] = $query;
            }
          }

          /* PROPERTY FEATURES ARE PENDING!!! */
          //print_me($this->ReturnBuddQuery);
      }
    }
}


class AdvSearch{
    public $SCities = array();
    public $SCounties = array();
    public $DCounties = array();
    public $SZips = array();
    public $SAreas = array();
    public $DAreas = array();
    public $SFeatures = array();
    public $MinMax = array();
    public $SidebarTowns = array();

    public function __construct(){

    }


    public function getLocations(){

        $counties = $areas =$zips = $cities = array();
        $getC = new SqlIt("SELECT p.county,p.city,p.zip,p.area,a.area_name, c.location, s.state as state_name, t.town_name, t.town_id FROM property_list as p
          LEFT JOIN locations_areas as a ON a.area_id = p.area
          LEFT JOIN locations_cities as c ON c.city_id = p.city
          LEFT JOIN locations_states as s ON s.state_id = p.state
          LEFT JOIN locations_towns as t ON t.town_id = p.county
          ORDER BY p.county ASC","select",array());
        //$getCup = new SqlIt("SELECT county,city,zip,area FROM listings_upload ORDER BY county ASC","select",array());

        if($getC->NumResults > 0){
            foreach($getC->Response as $gl){


                /*$cit_arr = array_count_values($cities);
                $zip_arr = array_count_values($zips);
                $area_arr = array_count_values($areas);
                $county_arr = array_count_values($counties);*/

                if(isset($this->SCities[$gl->city])){
                  $this->SCities[$gl->city]['cnt']++;
                }else{
                  $this->SCities[$gl->city]['cnt'] = 1;
                  $this->SCities[$gl->city]['name'] = $gl->location;
                }

                if(isset($this->SCounties[$gl->town_id])){
                  $this->SCounties[$gl->town_id]['cnt']++;
                }else{
                  $this->SCounties[$gl->town_id]['cnt'] = 1;
                  $this->SCounties[$gl->town_id]['name'] = $gl->town_name;
                  $this->SCounties[$gl->town_id]['parent'] = $gl->location;
                  $this->SCounties[$gl->town_id]['parent_id'] = $gl->city;
                  if($gl->town_name == ''){
                    $this->SCounties[$gl->town_id]['name'] = 'Other';
                  }
                }

                if($gl->town_id != 0){
                  if(!isset($this->DCounties[$gl->city])){
                    $this->DCounties[$gl->city] = array();
                  }

                  if(isset($this->DCounties[$gl->city][$gl->town_id])){
                    $this->DCounties[$gl->city][$gl->town_id]['cnt']++;
                  }else{
                    $this->DCounties[$gl->city][$gl->town_id]['cnt'] = 1;
                    $this->DCounties[$gl->city][$gl->town_id]['name'] = $gl->town_name;
                    $this->DCounties[$gl->city][$gl->town_id]['parent'] = $gl->location;
                    $this->DCounties[$gl->city][$gl->town_id]['parent_id'] = $gl->city;
                    $this->DCounties[$gl->city][$gl->town_id]['id'] = $gl->town_id;
                  }
                }


                if($gl->area != 0){
                  if(!isset($this->DAreas[$gl->city])){
                    $this->DAreas[$gl->city] = array();
                  }

                  if(isset($this->DAreas[$gl->city][$gl->area])){
                    $this->DAreas[$gl->city][$gl->area]['cnt']++;
                  }else{
                    $this->DAreas[$gl->city][$gl->area]['cnt'] = 1;
                    $this->DAreas[$gl->city][$gl->area]['name'] = $gl->area_name;
                    $this->DAreas[$gl->city][$gl->area]['parent'] = $gl->town_name;
                    $this->DAreas[$gl->city][$gl->area]['parent_id'] = $gl->county;
                    $this->DAreas[$gl->city][$gl->area]['id'] = $gl->area;
                  }
                }

                if(isset($this->SAreas[$gl->area])){
                  $this->SAreas[$gl->area]['cnt']++;
                }else{
                  $this->SAreas[$gl->area]['cnt'] = 1;
                  if($gl->area == 0){
                    $this->SAreas[$gl->area]['name'] = 'Other';
                    if($gl->town_name != ''){
                      $this->SAreas[$gl->area]['parent'] = $gl->town_name;
                      $this->SAreas[$gl->area]['parent_id'] = $gl->county;
                    }else{
                      $this->SAreas[$gl->area]['parent'] = $gl->location;
                      $this->SAreas[$gl->area]['parent_id'] = $gl->city;
                    }
                  }else{
                    $this->SAreas[$gl->area]['name'] = $gl->area_name;
                    $this->SAreas[$gl->area]['parent'] = $gl->town_name;
                    $this->SAreas[$gl->area]['parent_id'] = $gl->county;
                  }






                }

                if(isset($this->SZips[$gl->zip])){
                  $this->SZips[$gl->zip]['cnt']++;
                }else{
                  $this->SZips[$gl->zip]['cnt'] = 1;
                  $this->SZips[$gl->zip]['name'] = $gl->zip;
                }

            }


        }
        /*if($getCup->NumResults > 0){
            foreach($getCup->Response as $gl){
                $counties[] = $gl->county;
                $cities[] = $gl->city;
                if(strlen($gl->zip) < 5){
                    $gl->zip = '0'.$gl->zip;
                }
                $zips[] = $gl->zip;
                $areas[] = $gl->area;
            }
        }*/

        //$this->SCounties[$id]['cnt'] = array_count_values($counties);
        //$this->SCities[$id]['cnt'] = array_count_values($cities);

    }

    public function getTowns($city_id=0){
      $where_query = 'WHERE city_id = ? ';

      if(is_array($city_id)){
        $vars = $city_id;
        $i = 0;
        foreach($city_id as $cc){
          if($i != 0){
            $where_query .= ' OR city_id = ? ';
          }
        $i++;
      }
    }else{
      $vars = array($city_id);
    }
      //echo $where_query;
      if($city_id > 0){
         $getC = new SqlIt("SELECT * FROM locations_towns ".$where_query." ORDER BY town_name ASC","select",$vars);
         //echo "SELECT * FROM locations_towns ".$where_query." ORDER BY town_name ASC";
         if($getC->NumResults > 0){
            foreach($getC->Response as $rr){
               $this->SidebarTowns[$rr->town_id] = $rr->town_name;
            }

         }
      }
   }


   public function getAreas($town_ids=array()){
     if(!empty($town_ids)){

        $where_query = ' WHERE town_id <> 0 ';
        $var_arr = array();

        foreach($town_ids as $tt){
           $where_query .= ' OR town_id = ? ';
           $var_arr[] = $tt;
        }

        $getC = new SqlIt("SELECT * FROM locations_areas ".$where_query." ORDER BY area_name ASC","select",$var_arr);
        if($getC->NumResults > 0){
           foreach($getC->Response as $rr){
              $this->SidebarAreas[$rr->area_id] = $rr->area_name;
           }

        }
     }
  }


    public function getCountyCities($county=0){

        if($county != 0){
            $cities = array();
            $getCit = new SqlIt("SELECT city FROM property_list WHERE county LIKE '".$county."%' ORDER BY city ASC","select",array());
            // $getUpCit = new SqlIt("SELECT city as up_city FROM listings_upload WHERE county LIKE '".$county."%' ORDER BY city ASC","select",array());

            if($getCit->NumResults > 0){
                foreach($getCit->Response as $gl){
                    $cities[] = str_replace('County','',$gl->city);
                }
            }
            // if($getUpCit->NumResults > 0){
            //     foreach($getUpCit->Response as $gl){
            //         $cities[] = $gl->up_city;
            //     }
            // }
            return array_count_values($cities);
        }
    }


    public function getMinMax(){

        $min_bed = $min_bath = $max_bath = $min_bath = 0;

        $getMinMax = new SqlIt("SELECT MAX(bedrooms) as max_beds, MAX(bathrooms) as max_baths FROM property_list","select",array());
        /*$getMinMaxUp = new SqlIt("SELECT MIN(total_beds) as min_beds, MAX(total_beds) as max_beds, MIN(total_bath) as  min_baths, MAX(total_bath) as max_baths FROM listings_upload WHERE total_beds <> 0","select",array());*/

        if($getMinMax->NumResults > 0){
            $max_bed = $getMinMax->Response[0]->max_beds;
            $max_bath = $getMinMax->Response[0]->max_baths;
        }

        /*if($getMinMaxUp->NumResults > 0){
            $up = $getMinMaxUp->Response[0];
            if($up->min_beds < $min_bed || ($min_bed == 0 && $up->min_beds > 0)){
                $min_bed = $up->min_beds;
            }
            if($up->max_beds > $max_bed){
                $max_bed = $up->max_beds;
            }
            if($up->min_baths < $min_bath || ($min_bath == 0 && $up->min_baths > 0)){
                $min_bed = $up->min_baths;
            }
            if($up->max_baths > $max_bath){
                $max_bed = $up->max_baths;
            }
        }*/
        $this->MinMax = array('beds'=>array($min_bed,$max_bed),'baths'=>array($min_bath,$max_bath));
    }
}




class Emails{
  public $Property;
  public $SimilarProps = array();
  public $Request;

  public function __construct(){

  }

  public function getProp($prop_id=0){
    if($prop_id > 0){
      $getProp = new SqlIt("SELECT * FROM property_list WHERE property_id = ?","select",array($prop_id));

      if($getProp->NumResults == 1){
        $this->Property = $getProp->Response[0];

        $getImg = new SqlIt("SELECT img_folder,img_name FROM property_photos WHERE property_id = ? ORDER BY img_order ASC LIMIT 1","select",array($prop_id));

        if($getImg->NumResults == 1){
          $this->Property->image = $getImg->Response[0]->img_folder.'/'.$getImg->Response[0]->img_name;
        }
      }
    }
  }


  public function similarProps($prop_type=0,$city=0){
    $getSim = new SqlIt("
    SELECT
      property_title, property_id, (SELECT CONCAT(img_folder,img_name) FROM property_photos WHERE property_id = p.property_id ORDER BY img_order ASC LIMIT 1) as image
    FROM property_list as p
    WHERE pr_type_id = ? AND city = ?
    AND property_id <> ?
    LIMIT 2","select",array($prop_type,$city,$this->Property->property_id));

    if($getSim->NumResults > 0){
      $this->SimilarProps = $getSim->Response;
    }else{
      $getSim = new SqlIt("SELECT property_title, property_id (SELECT CONCAT(img_folder,img_name) FROM property_photos WHERE property_id = p.property_id ORDER BY img_order ASC LIMIT 1) as image FROM property_list as p WHERE pr_type_id = ?  AND property_id <> ? LIMIT 2","select",array($prop_type));
      if($getSim->NumResults > 0){
        $this->SimilarProps = $getSim->Response;
      }
    }
  }


  public function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


  public function saveRequest($post){
    $errors = array();
    $success = 0;
    $this->Request = new stdClass;

    // check that all fields are complete
    $name = $this->test_input($post['name']);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name) || strlen($name) < 3) {
      $errors[] = array('na',"Only letters and spaces are allowed");
    } else {
      $this->Request->name = $this->test_input($post["name"]);
    }

    $email = $this->test_input($post["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) < 3 ) {
      $errors[] = array('em',"Email is required");
    } else {
      $this->Request->email = $this->test_input($post["email"]);
    }

    if (strlen($post["phone"]) == 0) {
      $this->Request->phone = "";
    } else {
      $this->Request->phone = $this->test_input($_POST["phone"]);
    }

    if (strlen($post["notes"]) == 0) {
      $this->Request->notes = "";
    } else {
      $this->Request->notes = $this->test_input($_POST["notes"]);
    }

    if(isset($post['prop']) && $post['prop'] > 0){
      $this->Request->prop_id = $post['prop'];
    }else{
      $errors[] = array('prop_id','No Property ID selected');
    }

    if(empty($errors)){
      // save to database and send emails
      $addReq = new SqlIt("INSERT INTO property_requests (client_name,client_email,client_phone,client_notes,property_id,request_timestamp) VALUES (?,?,?,?,?,NOW())","insert",array($this->Request->name,$this->Request->email,$this->Request->phone,$this->Request->notes,$this->Request->prop_id));

      if($addReq){
        $success = 1;
      }
    }
    return array('errors'=>$errors,'success'=>$success);
  }
}

?>
