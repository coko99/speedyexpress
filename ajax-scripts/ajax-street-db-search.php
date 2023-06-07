<?php
require_once "../config/config.php";
if (isset($_GET['term'])) {
   $term = mysqli_real_escape_string($db, $_GET['term']);
     
   $query = "SELECT * FROM street WHERE name LIKE '$term%' LIMIT 25";
    $result = mysqli_query($db, $query);
 
    if (mysqli_num_rows($result) > 0) {
      $names = [];
     while ($street = mysqli_fetch_array($result)) {
      if(! in_array($street['name'], $names)){
        array_push($names, $street['name']);
         $item = array(
            "value" => $street['name'],
            "label" => $street['name'],
            "id" =>  $street['id']
      );
        $res[] = $item;
      }
     }
    } else {
      $res = array();
    }
    //return json res
    echo json_encode($res);
}else if (isset($_GET['municipality'])) {
  $municipality = mysqli_real_escape_string($db, $_GET['municipality']);
    
  $query = "SELECT street.* FROM street 
  WHERE municipality_id = $municipality";
   $result = mysqli_query($db, $query);

   if (mysqli_num_rows($result) > 0) {
    while ($street = mysqli_fetch_array($result)) {
      $item = array(
        "value" => $street['name'],
        "label" => $street['name'],
        "id" =>  $street['id']
     );
     $res[] = $item;
    }
   } else {
     $res = array();
   }
   //return json res
   echo json_encode($res);
}else if (isset($_GET['municipality-id'])) {
  $municipality = mysqli_real_escape_string($db, $_GET['municipality-id']);
    
  $query = "SELECT * FROM street 
  WHERE municipality_id = $municipality";
   $result = mysqli_query($db, $query);

   if (mysqli_num_rows($result) > 0) {
    while ($street = mysqli_fetch_array($result)) {
      $item = array(
        "value" => $street['name'],
        "label" => $street['name'],
        "id" =>  $street['id']
     );
     $res[] = $item;
    }
   } else {
     $res = array();
   }
   //return json res
   echo json_encode($res);
}
?>