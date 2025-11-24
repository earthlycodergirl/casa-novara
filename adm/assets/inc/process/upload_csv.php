<?php 
$error = 0;
$success = 0;
$error_mess = array();

// Make sure the file is being uploaded
if(isset($_FILES["filename"])){
    
    $target_dir = 'uploads/cvs/';
    $target_file = $target_dir . basename($_FILES["filename"]["name"]);

    // allowed file types to be imported
    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    
    // stop file upload if not correct format (CSV)
    if(!in_array($_FILES['filename']['type'],$mimes)){
        $error = 1;
        $error_mess[] = 'The file uploaded is an invalid file type. Please only upload CSV files (excel)';
    }else{

        // Save file being imported
        if(move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)){ 


            /**
            // UPLOAD FILE CONTENTS TO DB 
            **/
            
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
        
            // Loop through file contents and insert per row
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            
                if($i > 0){
                    
                    // Create an array of data elements posted through the CVS
                    $data_array = array();
                    for($z=0;$z<=95;$z++){
                        if(($z >= 9 && $z <= 13) || ($z >= 15 && $z <= 17)){
                            if($data[$z] != ''){
                                $data[$z] = date('Y-m-d',strtotime($data[$z]));
                            }
                        }
                        $data_array[] = $data[$z];
                    }
            
            
                    // Check if the listing number already exists and delete if it does
                    if(in_array($data[0],$active_ids)){
                        new SqlIt("DELETE FROM listings_upload WHERE listing_number = ?","delete",array($data[0]));
                    }
                    
                    // Insert row to TB listings_upload
                    $add_listings = new SqlIt("
                    INSERT INTO listings_upload (
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
            
    if($add_listings){
        $success = 1;
    }else{
        $error = 1;
        $error_mess[] = 'We apologize, there was an issue importing this file. Please make sure that the file is not too large and that the content is valid content for uploading. If the problem persists, please contact <a href="mailto:sarah@graphic-freedom.com">technical support by clicking here.</a>';
    }

    }else{
        $error = 1;
        $error_mess[] = 'We apologize, there was an issue importing this file. Please make sure that the file is not too large and that the content is valid content for uploading. If the problem persists, please contact <a href="mailto:sarah@graphic-freedom.com">technical support by clicking here.</a>';
    }
}
}
    
    if($success == 1){
        $show_alert = 'success';
        $note_head = 'File imported successfully!';
        $note_txt = 'The file was imported successfully. You can view the added listings by closing this box now.';
    }elseif($error == 1){
        $show_alert = 'error';
        $note_head = 'File not imported';
        $note_txt = 'We apologize for the inconvenience, but the file was not uploaded successfully. DETAILS: '.implode(' - ',$error_mess);
    }
?>