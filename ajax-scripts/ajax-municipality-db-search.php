<?php
require_once "../config/config.php";
if (isset($_GET['street'])) {
   $term = mysqli_real_escape_string($db, $_GET['street']);

   $query = "SELECT municipality.* FROM municipality 
   LEFT JOIN street on municipality.id = street.municipality_id
   WHERE street.name = '$term'";
    $result = mysqli_query($db, $query);
 
    if (mysqli_num_rows($result) > 0) {
     while ($municipality = mysqli_fetch_array($result)) {
         $item = array(
            "name" => $municipality['name'],
            "zip" => $municipality['zip'],
            "id" =>  $municipality['id']
         );
        $res[] = $item;
     }
    } else {
      $res = array();
    }
    //return json res
    echo json_encode($res);
}
if(isset($_GET['name'])){
   $term = mysqli_real_escape_string($db, $_GET['street']);

   $query = "SELECT municipality.* FROM municipality 
   WHERE municipality.name LIKE '$term%'  LIMIT 25";
    $result = mysqli_query($db, $query);
 
    if (mysqli_num_rows($result) > 0) {
     while ($municipality = mysqli_fetch_array($result)) {
         $item = array(
            "name" => $municipality['name'],
            "zip" => $municipality['zip'],
            "id" =>  $municipality['id']
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