<?php
include('../config/config.php');

$admin_curier = 1;

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function logEvent($message) {
    if ($message != '') {
        // Add a timestamp to the start of the $message
        $message = date("Y/m/d H:i:s").': '.$message;
        $fp = fopen('log.txt', 'a');
        fwrite($fp, $message."\n");
        fclose($fp);
    }
 }

    $token = null;
    $courier = null;
  $headers = apache_request_headers();
  if(isset($headers['Authorization'])){
    $matches = array();
    preg_match('/Token token="(.*)"/', $headers['Authorization'], $matches);
    if(isset($matches[1])){
      $token = $matches[1];
      $sql = "SELECT * from courier WHERE token = '$token' and status = 1";
      $result = mysqli_query($db, $sql);
      $courier = mysqli_fetch_array($result);
    }
  } 

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($courier) && isset($token)) {
    
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $package_data = explode("-", mysqli_real_escape_string($db, $data->package_data));
    $package_id = $package_data[0];
    $package_token = $package_data[1];
    $status_id = (isset($data->status_id)) ? mysqli_real_escape_string($db, $data->status_id) : null;
    $courier_id = $courier['id'];

    if($status_id == 66 && $courier_id != $admin_curier){
      return;
    }

    $sql = "SELECT status_id as c from package where id = $package_id";
    $result=mysqli_query($db, $sql);
    $current_status =mysqli_fetch_assoc($result)['c'];

    if(isset($status_id) && ($current_status != 4 || $courier_id == $admin_curier)){
      if($status_id == 11){
        $sql = "UPDATE `package` 
        SET `status_id`='$status_id',
        `curier_id`=NULL
        WHERE id = $package_id 
        AND token = $package_token 
        AND status_id != 0";
      }else{
        $sql = "UPDATE `package` 
        SET `status_id`='$status_id',
        `curier_id`='$courier_id'
        WHERE id = $package_id 
        AND token = $package_token 
        AND status_id != 0";
      }
        $result = mysqli_query($db, $sql);
      
        $sql = "UPDATE `package_status_tracking` 
        SET `status`=0
        WHERE package_id = $package_id";
        $result = mysqli_query($db, $sql);

        $sql = "INSERT INTO `package_status_tracking`
        (`package_id`, `status_id`, `courier_id`) 
        VALUES ('$package_id','$status_id','$courier_id')";
        $result = mysqli_query($db, $sql);
        logEvent('User '.$courier_id.': '.$sql);
    }

    $sql = "SELECT package.*, 
    municipality.name AS municipality_name, 
    municipality.zip AS zip,
    street.name AS street_name, 
    firm.name AS firm,
    city.name AS city_name,
    grup.number_of_packages AS number_of_packages
    FROM `package`
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN city ON municipality.city_id = city.id
    LEFT JOIN firm ON package.firm_id = firm.id
    LEFT JOIN grup on package.group_id = grup.id
    WHERE token = $package_token 
    AND package.id = $package_id 
    AND package.status_id != 0 ";

    $result = mysqli_query($db, $sql);
    $data_response = [];
    while($row = mysqli_fetch_array($result)) {
      if($courier_id == $admin_curier){
        $row['can_return'] = 1;
      }
      array_push($data_response, $row);
    }

    $sql = "SELECT * 
    FROM configuration WHERE id = 1;";
    $result = mysqli_query($db, $sql);
    $send_sms = mysqli_fetch_array($result)['send_sms'];

    // logEvent('User '.$courier_id.': '.$sql);
    if($status_id == 4){
    //   $sql = "SELECT package.*, 
    //   municipality.name AS municipality_name, 
    //   municipality.zip AS zip,
    //   street.name AS street_name, 
    //   firm.name AS firm,
    //   city.name AS city_name
    //   FROM `package`
    //   LEFT JOIN street ON package.street_id = street.id
    //   LEFT JOIN municipality ON street.municipality_id = municipality.id
    //   LEFT JOIN city ON municipality.city_id = city.id
    //   LEFT JOIN firm ON package.firm_id = firm.id
    //   WHERE token = $package_token 
    //   AND package.id = $package_id 
    //   AND package.status_id != 0";

    //   $result = mysqli_query($db, $sql);
    //   $row = mysqli_fetch_array($result);
    //   array_push($data_response, $row);
    }else if(($status_id == 3 || $status_id == 9) && $send_sms == 1){
        
        $phone = $data_response[0]['phone'];
          if(str_starts_with($phone, "+")){
            $phone = str_replace("+","",$phone);
          }else if(str_starts_with($phone, "0")){
            $phone = preg_replace("/0/","381",$phone, 1);
          }
          $phone = str_replace(" ","",$phone);
     
        $curl = curl_init();
        
        $ptt = $data_response[0]['ptt'];
        $shipping_fee = $data_response[0]['shipping_fee'];
        $fee = 0;
        if($data_response[0]['ransom_type_id'] == 1){
            $fee = $ptt + $shipping_fee;
        }else{
            $fee = $shipping_fee;
        }

        $sms_text = "";
        if($status_id == 3){
          $sms_text = '{
            "username":"speedexpviber",
            "password":"hs0!lqM22",
            "originator":"SPEEDYkurir",
            "msisdn":["'.$phone.'"],
            "type":"text",
            "message":"Postovani, Kurir speedyexpressa je preuzeo vas paket koji mozete ocekivati u toku dana. Iznos za uplatu vaseg paketa je '.$fee.' din. Vas speedyexpress. www.speedyexpress.rs",
            "sequence":"'.time().'",
            "priority":1,
            "dr":false
            }';
        }else{
          $sms_text = '{
            "username":"speedexpviber",
            "password":"hs0!lqM22",
            "originator":"SPEEDYkurir",
            "msisdn":["'.$phone.'"],
            "type":"text",
            "message":"Postovni, doslo je do kvara dostavnog vozila kurira. Vas paket ce sutra biti isporucen.Hvala na razumevanju i strpljenju. Vas Speedy kurir www.speedyexpress.rs",
            "sequence":"'.time().'",
            "priority":1,
            "dr":false
            }';
        }
        
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://bulk.dopler.rs/api/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$sms_text,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = "";
        curl_close($curl);

        $sql = "INSERT INTO SMS (`sms`, `package_id`, `response`, `status_id`) VALUES ('$sms_text', $package_id, '$response', $status_id)";
        $result = mysqli_query($db, $sql);
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data_response);
}else{
    http_response_code(404); exit;
}