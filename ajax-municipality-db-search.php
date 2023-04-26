<?php
require_once "config/config.php";
if (isset($_GET['street'])) {
     
   $query = "SELECT municipality.* FROM municipality 
   LEFT JOIN street on municipality.id = street.municipality_id
   WHERE street.name LIKE '{$_GET['street']}%' LIMIT 25";
    $result = mysqli_query($db, $query);
 
    if (mysqli_num_rows($result) > 0) {
     while ($street = mysqli_fetch_array($result)) {
        $res[] = $street['name'];
     }
    } else {
      $res = array();
    }
    //return json res
    echo json_encode($res);
}
?>