<?php
require_once "../config/config.php";
if (isset($_GET['term'])) {
   $term = mysqli_real_escape_string($db, $_GET['term']);
     
   $query = "SELECT * FROM street WHERE name LIKE '$term%' LIMIT 25";
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