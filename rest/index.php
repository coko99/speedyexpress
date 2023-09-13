<?php
include('../config/config.php');

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

    if(isset($status_id)){
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
    city.name AS city_name
    FROM `package`
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN city ON municipality.city_id = city.id
    LEFT JOIN firm ON package.firm_id = firm.id
    WHERE token = $package_token 
    AND package.id = $package_id 
    AND package.status_id != 0 
    AND package.status_id != 4 ";

    $result = mysqli_query($db, $sql);
    $data_response = [];
    while($row = mysqli_fetch_array($result)) {
      array_push($data_response, $row);
    }
    // logEvent('User '.$courier_id.': '.$sql);
    if($status_id == 4){
      $sql = "SELECT package.*, 
      municipality.name AS municipality_name, 
      municipality.zip AS zip,
      street.name AS street_name, 
      firm.name AS firm,
      city.name AS city_name
      FROM `package`
      LEFT JOIN street ON package.street_id = street.id
      LEFT JOIN municipality ON street.municipality_id = municipality.id
      LEFT JOIN city ON municipality.city_id = city.id
      LEFT JOIN firm ON package.firm_id = firm.id
      WHERE token = $package_token 
      AND package.id = $package_id 
      AND package.status_id != 0";

      $result = mysqli_query($db, $sql);
      $row = mysqli_fetch_array($result);
      array_push($data_response, $row);
    }else if($status_id == 3){
      $curl = curl_init();

      $request_text='{
        "destinations": [
        "'.$data_response[0]['phone'].'"
        ],
        "sender": "AKTON",
        "transactionId": "4e519e10-db88-4f53-b960-3a30e874af84",
        "message": "Poštovani, Vaš paket je preuzeo kurir. Očekujte dostavu od 09h do 16h. Vaš SpeedyExpress.",
        "ttl": 60,
        "sms": {
            "originator": "SMSakt",
            "message": "Poštovani, Vaš paket je preuzeo kurir. Očekujte dostavu od 09h do 16h. Vaš SpeedyExpress.",
            "unicode": true
        }
    }';

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://viber.starionbgd.com/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$request_text,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic ',
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data_response);
}else{
    http_response_code(404); exit;
}

