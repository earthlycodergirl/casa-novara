<?php 
include 'classes/sql.class.php';

//Upload File
if (isset($_POST['submit'])) {
    echo "<pre>"; print_r($_FILES); echo "</pre>";
    $target_dir = 'uploads/cvs/';
    $target_file = $target_dir . basename($_FILES["filename"]["name"]);
    if(move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)){ 
        
   
    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
        echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded 
 successfully." . "</h1>";
        echo "<h2>Displaying contents:</h2>";
        //readfile($_FILES['filename']['tmp_name']);
    }

    //Import uploaded file to Database
    $handle = fopen($target_dir . $_FILES["filename"]["name"], "r");  
        $i = 0;
        
        $active_ids = array();
        
    // SELECT all listing numbers currently in DB. To delete from table prior to reuploading. 
        
    $getIDS = new SqlIt("SELECT listing_number FROM listings_upload","select",array());
        if($getIDS->NumResults != 0){
            foreach($getIDS->Response as $gi){
                $active_ids[] = $gi->listing_number;
            }
        }
        
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        if($i > 0){
            
            
            
            // Create an array of data elements posted through the CVS
            $data_array = array();
            for($z=0;$z<=95;$z++){
                if(($z >= 9 && $z <= 13) || ($z >= 15 && $z <= 17)){
                    if($data[$z] != ''){
                        $data[$z] = date('Y-m-d',strtotime($data[$z]));
                    }
                    echo $data[$z].'<br>';
                }
                $data_array[] = $data[$z];
            }
            
            echo "<pre>"; print_r($data); echo "</pre>";
            
            // Check if the listing number already exists and delete if it does
            if(in_array($data[0],$active_ids)){
                new SqlIt("DELETE FROM listings_upload WHERE listing_number = ?","delete",array($data[0]));
            }
            
            new SqlIt("INSERT INTO listings_upload (
            listing_number,
            agency_name,
            agency_phone,
            agency_agent,
            co_listing_agent,
            property_group,
            card_format,
            book_section,
            days_on_market,
            begin_date,
            end_date,
            sold_date,
            construction_date,
            fallthrough_date,
            status,
            status_change,
            date_withdrawal,
            date_cancelled,
            contingent,
            contingent_remarks,
            price_original,
            price_current,
            price_sold,
            price_low,
            price_high,
            price_assessed,
            terms,
            financing,
            area,
            lb_num,
            address,
            city,
            state,
            county,
            country,
            zip,
            geo_county,
            geo_block,
            geo_lat,
            geo_lon,
            sqft_total,
            sqft1,
            sqft2,
            sqft3,
            sqft4,
            year_built,
            style,
            type,
            lot_size,
            lot_acres,
            buy_broker_com,
            sell_broker_com,
            other_com,
            selling_agency,
            selling_agent,
            co_selling_agent,
            stories,
            total_rooms,
            total_beds,
            total_bath,
            full_bath,
            half_bath,
            quarter_bath,
            garage_type,
            garage_stall,
            garage_remarks,
            zoning,
            taxes,
            tax_year,
            subdivision,
            remark1,
            remark2,
            parcel_num,
            legal,
            directions,
            owner,
            owner_phone,
            owner_contact,
            how_to_show,
            timestamp,
            school_area,
            school_elem,
            school_middle,
            school_high,
            school_other,
            price_increase,
            electric_paid,
            fireplace,
            lot_suffix,
            list_type,
            photo_url,
            asmt_total,
            asmt_improvements,
            num_car_garage,
            room_info,
            features
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)","insert",$data_array);
        }

        $i++;
    }

    fclose($handle);

    print "Import done";
    }else{
        echo 'Unable to upload file';
    }

//view upload form
} else {

    print "Upload new csv by browsing to file and clicking on Upload<br />\n";

    print "<form enctype='multipart/form-data' action='upload_cvs.php' method='post'>";

    print "File name to import:<br />\n";

    print "<input size='50' type='file' name='filename'><br />\n";

    print "<input type='submit' name='submit' value='Upload'></form>";

}
