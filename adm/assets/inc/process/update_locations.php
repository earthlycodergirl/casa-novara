<?php
if(isset($_POST)){
  require('../../../classes/sql.class.php');

  $errors = $return_array = array();
  $success = 0;

  if(isset($_POST['dtype']) && strlen($_POST['dtype']) > 2){
    $db_table = $tb_id = $tb_field = '';
    switch($_POST['dtype']){
      case 'city':
        $db_table = 'locations_cities';
        $tb_id = 'city_id';
        $tb_field = 'location';
        $par_id = 'state_id';
        break;
      case 'town':
        $db_table = 'locations_towns';
        $tb_id = 'town_id';
        $tb_field = 'town_name';
        $par_id = 'city_id';
        break;
      case 'area':
        $db_table = 'locations_areas';
        $tb_id = 'area_id';
        $tb_field = 'area_name';
        $par_id = 'town_id';
        break;
    }


    // UPDATE LOCATION
    if($_POST['act'] == 'update'){
      $upIt = new SqlIt("UPDATE ".$db_table." SET ".$tb_field." = ? WHERE ".$tb_id." = ?","update",array(htmlentities($_POST['dname']),$_POST['did']));
      if($upIt){
        $success = 1;
        $return_array = array('name'=>$_POST['dname'],"id"=>$_POST['did']);
      }else{
        $errors = array('The location could not be updated successfully. Please refresh the page and try again.');
      }
    }
  }




  // update featured destinations on navigation menu
  if(isset($_POST['subtype']) && $_POST['subtype'] == 354){
    $dtype = $_POST['dt'];
    $did = $_POST['di'];

    if($_POST['dv'] != 'nope'){
      if($_POST['dv'] == 'no'){
        $val = 0;
      }elseif($_POST['dv'] == 'yes'){
        $val = 1;
      }

      $vars = array($val,$did);

      switch($dtype){
        case 'city':
          $sql = "UPDATE locations_cities SET is_featured = ? WHERE city_id = ?";
          break;
        case 'area':
          $sql = "UPDATE locations_areas SET is_featured = ? WHERE area_id = ?";
          break;
        case 'town':
          $sql = "UPDATE locations_towns SET is_featured = ? WHERE town_id = ?";
          break;
      }

      

      $upLoc = new SqlIt($sql,"update",$vars);

      if($upLoc){
        $success = 1;
      }else{
        $errors = 'Database not updated';
      }

    }else{
      $errors = 'No value sent';
    }
  }

  echo json_encode(array('success'=>$success,'errors'=>$errors,'return'=>$return_array));
}
?>