<?php
require_once "config/config.php";
if (isset($_GET['term'])) {
     
   $query = "SELECT * FROM street WHERE name LIKE '{$_GET['term']}%' LIMIT 25";
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