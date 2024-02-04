<?php

use chillerlan\QRCode\QRCode;

include('config/session_admin.php');
require 'vendor/autoload.php';

if(!isset($_GET['id'])){
  header('Location: paketi.php');
}
$id = mysqli_real_escape_string($db, $_GET['id']);

$sql = "SELECT package.*, 
  municipality.name AS municipality_name, 
  municipality.zip AS zip,
  street.name AS street_name,
  firm.name AS firm_name,
  firm.street_number AS firm_street_number,
  firm_street.name AS firm_street_name,
  firm_municipality.name AS firm_municipality_name,
  firm_municipality.zip AS firm_municipality_zip,
  firm.phone AS firm_phone,
  grup.number_of_packages AS number_of_packages
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  LEFT JOIN firm ON package.firm_id = firm.id
  LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
  LEFT JOIN municipality as firm_municipality ON firm_street.municipality_id = firm_municipality.id
  LEFT JOIN grup on package.group_id = grup.id
  WHERE package.id = $id";
  $result = mysqli_query($db, $sql);
  $packages = [];
  while($row = mysqli_fetch_array($result)) {
      array_push($packages, $row);
  }

$str = "
<div class='col-12 table-wrapper-scroll-y my-custom-scrollbar'>
  <table class='table table-bordered table-striped mb-0'>
    <tbody><tr>";

    $counter = 0;
    foreach($packages as $package){
        $counter += 1;
        $recipient = $package['recipient'];
        $phone = $package['phone'];
        $ransome = $package['shipping_fee'];
        $paid_by = ($package['ransom_type_id'] == 1) ? 'Primalac' : 'Pošiljalac';
        $comment = $package['comment'];
        $content = $package['content'];
        $package_id = $package['id'];
        $street_number = $package['street_number'];
        $street_name = $package['street_name'];
        $zip = $package['zip'];
        $municipality_name = $package['municipality_name'];
        $send_time = date('d-m-Y', $package['send_time']);

        $token = $package['token'];
        $ptt = $package['ptt'];


        $firm_name = $package['firm_name'];
        $firm_street = $package['firm_street_name'];
        $firm_municipality_name = $package['firm_municipality_name'];
        $firm_municipality_zip = $package['firm_municipality_zip'];
        $firm_street_number = $package['firm_street_number'];
        $firm_phone = $package['firm_phone'];

        $numOfPackages = $package['number_of_packages'];
        $orderInGrupu = $package['order_in_group'];
        $grupId = sprintf('SX%08d', $package['group_id']);


        if($counter % 2 != 0){
          $str.="<tr>";
        }
        $str.="
                
                <td class='seccond'>
                    <h6><strong>Grupa:</strong> $grupId</h6>
                    <h6>$orderInGrupu/$numOfPackages</h6>   
                    <img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='QR Code' />
                    <h6>Pošiljalac</h6>
                    ID: $package_id<br/>
                    $firm_name<br/>
                    $firm_municipality_name $firm_municipality_zip<br/>
                    $firm_street $firm_street_number<br/>
                    $firm_phone;
                    <h6>Primalac</h6>
                    $recipient<br/>
                    $municipality_name $zip<br/>
                    $street_name $street_number<br/>
                    $phone
                </td>
                <td >
                  <img width='100px' src='logosajt.jpeg' />
                   <div class='napomena'>
                    <br/><h6>Otkup:</h6> $ransome RSD <br/>
                    <h6>PTT:</h6> $ptt RSD <br/>
                    <h6>Plaća:</h6> $paid_by RSD <br/>
                    <h6>Napomena:</h6> $comment <br/>
                    <h6>Opis:</h6> $content <br/>
                    <h6>Datum slanja:</h6> $send_time <br/>
                    </div>
                </td>
              ";

              if($counter % 2 == 0){
                $str.="<tr>";
              }
    }

    $str.="</tbody>
    </table>
  </div>";

  $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . "/../uploads"]);
  $stylesheet = file_get_contents('print1.css');

  // Write some HTML code:
  $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
  $mpdf->WriteHTML($str,\Mpdf\HTMLParserMode::HTML_BODY);


  // Output a PDF file directly to the browser
  $mpdf->Output();


?>