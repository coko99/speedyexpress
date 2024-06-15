<?php

// Include the autoloader
require 'vendor/autoload.php';
include('config/session_user.php');

function cyrillicToLatin($text) {
  $cyrillic = [
      'А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л', 'Љ', 'М', 'Н', 'Њ', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Џ', 'Ш',
      'а', 'б', 'в', 'г', 'д', 'ђ', 'е', 'ж', 'з', 'и', 'ј', 'к', 'л', 'љ', 'м', 'н', 'њ', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'џ', 'ш'
  ];
  
  $latin = [
      'A', 'B', 'V', 'G', 'D', 'Đ', 'E', 'Ž', 'Z', 'I', 'J', 'K', 'L', 'Lj', 'M', 'N', 'Nj', 'O', 'P', 'R', 'S', 'T', 'Ć', 'U', 'F', 'H', 'C', 'Č', 'Dž', 'Š',
      'a', 'b', 'v', 'g', 'd', 'đ', 'e', 'ž', 'z', 'i', 'j', 'k', 'l', 'lj', 'm', 'n', 'nj', 'o', 'p', 'r', 's', 't', 'ć', 'u', 'f', 'h', 'c', 'č', 'dž', 'š'
  ];
  
  return str_replace($cyrillic, $latin, $text);
}

function extractStreetNameAndNumber($address) {
  
  // Regular expression to match the street number at the end of the string
  $pattern = '/^(.*?)(?:\s(\d+))?$/';

  if (preg_match($pattern, $address, $matches)) {
      // If a match is found, assign values to the street name and street number
      $streetName = trim($matches[1]);
      $streetNumber = isset($matches[2]) ? $matches[2] : $address;
  } else {
      // If no match is found, assign the whole value to both variables
      $streetName = $address;
      $streetNumber = $address;
  }

  return [$streetName, $streetNumber];
}

function insertPackageTemp($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, $found_street, $found_municipality, $street_name, $municipality_name, $login_session, $db) {
  $token = time();
  $sql = "INSERT INTO `temporary_package`(`street_id`, 
`firm_id`, `street_number`, `token`, `curier_id`, 
`phone`, `ransom_type_id`, `shipping_fee`, 
`recipient`, `content`, `comment`, `ptt`,  `status_id`, `created_by`, `found_municipality`, `found_street`,
`street_name`, `municipality_name`) 
VALUES ('$street_id','$firm_id','$street_number',
'$token', NULL, '$phone','$ransom_type_id','$shipping_fee'
,'$recipient','$content', '$comment', '$ptt', '1', '$login_session', $found_street, $found_municipality, '$street_name', '$municipality_name')";
  $result = mysqli_query($db, $sql);
  logEvent('User '.$login_session.': '.$sql);
}

function insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, $login_session, $db) {
  $token = time();
  $sql = "INSERT INTO `package`(`street_id`, 
`firm_id`, `street_number`, `token`, `curier_id`, 
`phone`, `ransom_type_id`, `shipping_fee`, 
`recipient`, `content`, `comment`, `ptt`,  `status_id`, `created_by`) 
VALUES ('$street_id','$firm_id','$street_number',
'$token', NULL, '$phone','$ransom_type_id','$shipping_fee'
,'$recipient','$content', '$comment', '$ptt', '1', '$login_session')";
  $result = mysqli_query($db, $sql);
  logEvent('User '.$login_session.': '.$sql);
}

use PhpOffice\PhpSpreadsheet\IOFactory;
$data = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['excel_file']['tmp_name'];

        // Load the Excel file
        $spreadsheet = IOFactory::load($fileTmpPath);

        // Get the first sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Get the highest row and column indexes
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Initialize an array to store the parsed data

        // Iterate through each row of the sheet
        for ($row = 2; $row <= $highestRow; $row++) {
            // Initialize an array for each row
            $rowData = [];

            // Iterate through each column of the row
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                // Get the cell value
                $cellValue = $sheet->getCell($col.$row)->getValue();

                // Add the cell value to the row data array
                $rowData[] = $cellValue;
            }

            // Add the row data to the main data array
            $data[] = $rowData;
        }

        
    } else {
        echo 'Please upload an Excel file.';
    }
    
    foreach ($data as $row) {

      // Check if all values in the current array element are empty
      $allEmpty = true;
      foreach ($row as $value) {
          if (!empty($value)) {
              $allEmpty = false;
              break;
          }
      }

      // If all values are empty, continue to the next iteration
      if ($allEmpty) {
        continue;
      }

      $recipient = $row[0];
      $phone = $row[4];
      $content = $row[10];
      $shipping_fee = $row[5];
      $ptt = 0;
      
      $comment = $row[6];
      $ransome_type_id = 1;
      

      $municipality_name = $row[2];
      $municipality_zip = $row[3];

      $extract_addres = extractStreetNameAndNumber($row[1]);
      
      $street_number = $extract_addres[1];
      $street_name = $extract_addres[0];

      $mun_ids = [];
      $municipality_name_upper = cyrillicToLatin(strtoupper($municipality_name));
      $street_name_upper = cyrillicToLatin(strtoupper($street_name));
      $no_match = True;

      $sql = "SELECT * FROM municipality where zip = $municipality_zip";
      $result = mysqli_query($db, $sql);
      if(mysqli_num_rows($result) != 0){
        $mun = mysqli_fetch_array($result);
        if(str_contains($municipality_name_upper, strtoupper($mun['name']))){
          array_push($mun_ids, $mun['id']);
          $no_match = False;
        }
      }
      
      if($no_match){
        $sql = "SELECT * FROM municipality where UPPER(name) like '%$municipality_name_upper%'";
        $result = mysqli_query($db, $sql);
        if(mysqli_num_rows($result) != 0){
          $no_match = False;
          while($row = mysqli_fetch_array($result)) {
            array_push($mun_ids, $row['id']);
          }
        }
      }
      
      if($no_match){
        $sql = "SELECT * FROM street WHERE INSTR('$street_name_upper', street.search_name) > 0";
      } else{
        $sql = "SELECT * FROM street WHERE municipality_id IN (".implode(",", $mun_ids).") AND INSTR('$street_name_upper', UPPER(street.search_name)) > 0";
      }
      $result = mysqli_query($db, $sql);
      $street_id = 0;
      if(mysqli_num_rows($result) != 0){
        $row = mysqli_fetch_array($result);
        $street_id = $row['id'];
        $street_number = str_replace(strtoupper($row['search_name']), "",$street_name_upper);
      }

      if($street_id != 0){
        insertPackage($street_id, $street_number, $phone, $ransome_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, $login_session, $db);
      }elseif(!$no_match){
        insertPackageTemp($street_id, $street_number, $phone, $ransome_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, 0, 1, $street_name, $municipality_name, $login_session, $db);
      }else{
        insertPackageTemp($street_id, $street_number, $phone, $ransome_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, 0, 0, $street_name, $municipality_name, $login_session, $db);
      }
      header("Location: posaljiPaketTemp.php");
      
  }


}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Excel Parser</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file">
        <input type="submit" value="Parse Excel">
    </form>
</body>
</html>