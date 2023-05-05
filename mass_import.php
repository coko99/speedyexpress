<?php

include('config/session_user.php');
require 'vendor/autoload.php';

function insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment, $firm_id, $login_session, $db) {
    $token = time();
    $sql = "INSERT INTO `package`(`street_id`, 
  `firm_id`, `street_number`, `token`, `curier_id`, 
  `phone`, `ransom_type_id`, `shipping_fee`, 
  `recipient`, `content`, `comment`, `status_id`, `created_by`) 
  VALUES ('$street_id','$firm_id','$street_number',
  '$token', NULL, '$phone','$ransom_type_id','$shipping_fee'
  ,'$recipient','$content', '$comment', '1', '$login_session')";
    $result = mysqli_query($db, $sql);
    logEvent('User '.$login_session.': '.$sql);
  }

  function getStreetId($street_name, $municipality_name, $db){
    $sql = "SELECT street.id AS street_id FROM street 
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    WHERE UPPER(municipality.name) LIKE '$municipality_name%' 
    AND UPPER(street.name) LIKE '$street_name%'";

    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) != 0) {
      return mysqli_fetch_array($result)['street_id'];
    }else{
      return "GREŠKA! ULICA NIJE PRONAĐENA ILI JE PRONAĐENO VIŠE ULICA SA TIM NAZIVOM IZ TE OPŠTINE!";
    }
  }
   
  
  if (isset($_POST["submit"]))
  {

    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    
    if(in_array($_FILES["file"]["type"],$allowedFileType)){
  
        $targetPath = './uploads/'.time().$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        /**  Advise the Reader that we only want to load cell data  **/
        $reader->setReadDataOnly(true);

        $worksheetData = $reader->listWorksheetInfo($targetPath);
        $worksheet = $worksheetData[0];
        $sheetName = $worksheet['worksheetName'];

        
        $reader->setLoadSheetsOnly($sheetName);
        $spreadsheet = $reader->load($targetPath);

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $day_id = 0;
        $row_count = 0;
        $for_insert = [];
        foreach($rows as $row){
          $row_insert = [];
          if($row_count != 0){
            $row_count += 1;
            $i = 0;
            foreach($row as $cell){
                // do nothing

                if($i == 0){
                    $row_insert['recipient'] = $cell;
                }
                if($i == 1){
                    $street_name = mb_strtoupper($cell, 'UTF-8');
                }
                if($i == 2){
                    $municipality_name = mb_strtoupper($cell, 'UTF-8');
                    $row_insert['street_id'] = getStreetId($street_name, $municipality_name, $db);
                }
                if($i == 3){
                    $row_insert['street_number'] = $cell;
                }
                if($i == 4) {
                    $row_insert['phone'] = $cell;
                }
                if($i == 5){
                    $row_insert['ransom_type_id'] = ($cell == 'Primalac') ? 1 : 2 ;
                }
                if($i == 6){
                    $row_insert['shipping_fee'] = $cell;
                }
                if($i == 7){
                    $row_insert['content'] = $cell;
                }
                if($i == 8){
                    $row_insert['comment'] = $cell;
                }
                  
              
              $i++;
            }
            array_push($for_insert, $row_insert);
          }
          $row_count += 1;
        }

        if(!isset($error_msg)){
          foreach($for_insert as $insert_row) {
            insertPackage($insert_row['street_id'], $insert_row['street_number'], $insert_row['phone'], $insert_row['ransom_type_id'], $insert_row['shipping_fee'], $insert_row['recipient'], $insert_row['content'], $insert_row['comment'], $firm_id, $login_session, $db);            
          }
          $success_msg = "Dodatu paketi!";
          
        }
      }
  }

  header("Location: posaljiPaket.php");
?>