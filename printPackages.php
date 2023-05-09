<?php

use chillerlan\QRCode\QRCode;

include('config/session_user.php');
require 'vendor/autoload.php';


$sql = "SELECT package.*, 
  municipality.name AS municipality_name, 
  municipality.zip AS zip,
  street.name AS street_name 
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  WHERE firm_id = $firm_id AND status_id = 1";
  $result = mysqli_query($db, $sql);
  $packages = [];
while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
}

$str = "
<div class='col-12 table-wrapper-scroll-y my-custom-scrollbar'>
  <table class='table table-bordered table-striped mb-0'>
    <thead>
      <tr>
        <th scope='col'>QR</th>
        <th scope='col'>OPIS</th>
        <th scope='col'>QR</th>
        <th scope='col'>OPIS</th>
      </tr>
    </thead>
    <tbody><tr>";

    $counter = 0;
    foreach($packages as $package){
        $counter += 1;
        $recipient = $package['recipient'];
        $phone = $package['phone'];
        $ransome = $package['shipping_fee'];
        $paid_by = ($package['ransom_type_id'] == 1) ? 'Primalac' : 'Pošiljalac';
        $comment = $package['comment'];
        $package_id = $package['id'];
        $street_number = $package['street_number'];
        $street_name = $package['street_name'];
        $zip = $package['zip'];
        $municipality_name = $package['municipality_name'];
        $token = $package['token'];

        if($counter % 2 != 0){
          $str.="<tr>";
        }
        $str.="
                <td>
                    <img class='qr-slika' src='".(new QRCode())->render($token)."' alt='QR Code' />
                </td>
                <td>
                    <h6>$recipient</h6>
                    <h6>$municipality_name $zip</h6>
                    <h6>$street_name $street_number</h6>
                    <h6>$phone</h6>
                    <h6><strong>Otkup: </strong>$ransome rsd</h6>
                    <h6><strong>Vrednost: </strong>$ransome rsd</h6>
                    <h6><strong>Plaća: </strong>$paid_by</h6>
                    <h6><strong>napomena: </strong>$comment</h6>
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
//   $stylesheet = file_get_contents('../css/invoice.css');

  // Write some HTML code:
//   $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
  $mpdf->WriteHTML($str,\Mpdf\HTMLParserMode::HTML_BODY);


  // Output a PDF file directly to the browser
  $mpdf->Output();


?>