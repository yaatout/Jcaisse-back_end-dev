<?php
// Gets data from URL parameters.
if(isset($_GET['add_location'])) {
    add_location();
}
if(isset($_GET['confirm_location'])) {
    confirm_location();
}



function add_location(){
    require("connectionPDO.php");
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $description =$_GET['description'];
    // Inserts new row with place data.
    
    $req3 = $bdd->prepare("insert into `aaa-locations` (lat,lng,description) values (:la,:ln,:des) "); 
    $req3->execute(array(
                    'la'=>$latitude,
                    'ln'=>$longitude,
                    'des'=>$boutique['labelB']
                    )) or die(print_r($req3->errorInfo())); 

    echo "Inserted Successfully";    
}
function confirm_location(){
    require("connectionPDO.php");
    $id =$_GET['id'];
    $confirmed =$_GET['confirmed'];
    // update location with confirm if admin confirm.
    $req1 = $bdd->prepare("update `aaa-locations` set location_status = :co WHERE id = :i "); 
    $req1->execute(array(
                    'co'=>$confirmed,
                    'i'=>$id
                    )) or die(print_r($req1->errorInfo())); 

    echo "Inserted Successfully";
    
}
function get_confirmed_locations(){
    require("connectionPDO.php");
    // update location with location_status if admin location_status.

    $req1 = $bdd->prepare("select id ,lat,lng,description,location_status as isconfirmed
    from `aaa-locations` WHERE  location_status = 1");  

    $req1->execute() or die(print_r($req1->errorInfo()));      

    $rows = array();
    while($r = $req1->fetch()) {
        $rows[] = $r;

    }

    $indexed = array_map('array_values', $rows);
    //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function get_all_locations(){
    require("connectionPDO.php");
    $req1 = $bdd->prepare("select id ,lat,lng,description,location_status as isconfirmed
    from `aaa-locations`"); 
    $req1->execute() or die(print_r($req1->errorInfo()));  

    $rows = array();
    while($r = $req1->fetch()) {
        $rows[] = $r;
    }
  $indexed = array_map('array_values', $rows);
  //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function array_flatten($array) {
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        }
        else {
            $result[$key] = $value;
        }
    }
    return $result;
}

?>