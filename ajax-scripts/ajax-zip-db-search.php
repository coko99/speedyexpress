<?php
require_once "../config/config.php";
if (isset($_GET['municipality'])) {
   $term = mysqli_real_escape_string($db, $_GET['municipality']);

   $query = "SELECT * FROM municipality 
   WHERE name =  '$term'";
    $result = mysqli_query($db, $query);
 
    if (mysqli_num_rows($result) > 0) {
     while ($street = mysqli_fetch_array($result)) {
        $res = $street['zip'];
     }
    } else {
      $res = "ZIP";
    }
    //return json res
    echo $res;
}
?>