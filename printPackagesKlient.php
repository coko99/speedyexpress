<?php

use chillerlan\QRCode\QRCode;



include('config/session_admin.php');
require 'vendor/autoload.php';

$idsForSearch = mysqli_real_escape_string($db, $_GET['idsForSearch']);

$sql = "SELECT package.*, 
municipality.name AS municipality_name, 
municipality.zip AS zip,
street.name AS street_name,
status.name AS status_name,
firm_street.name AS firm_street_name,
firm_municipality.name AS firm_municipality_name, 
firm_municipality.zip AS firm_zip,
firm.name AS firm_name,
firm.street_number AS firm_street_number,
firm.phone AS firm_phone,
grup.number_of_packages AS number_of_packages
FROM `package`
LEFT JOIN street ON package.street_id = street.id
LEFT JOIN municipality ON street.municipality_id = municipality.id
LEFT JOIN status ON package.status_id = status.id
LEFT JOIN firm ON package.firm_id = firm.id
LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
LEFT JOIN municipality AS firm_municipality ON firm_street.municipality_id = firm_municipality.id
LEFT JOIN package_status_tracking ON package.id = package_status_tracking.package_id 
LEFT JOIN grup on package.group_id = grup.id
WHERE package.id in ($idsForSearch) AND package_status_tracking.status = 1
";
  $result = mysqli_query($db, $sql);
  $packages = [];
  $idsForUpdate = [];
while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
    array_push($idsForUpdate, $row['id']);
}
$ids = join(",",$idsForUpdate);
$sql = "UPDATE package set print=1 WHERE id in ($ids)";
mysqli_query($db, $sql);

$str = "
<div class='col-12 table-wrapper-scroll-y my-custom-scrollbar'>
  <table class='table table-bordered table-striped mb-0'>
    <thead>
      <tr>
        <th scope='col '>#ID</th>".
      //  " <th scope='col'>QR</th>".
       "<th scope='col'>Primalac</th>
        <th scope='col'>Pošiljalac</th>
        <th scope='col'>Detalji</th>
        <th scope='col'>Opis</th>
        <th scope='col'>Datum slanja</th>
        <th scope='col'>Preuzeto</th>
        <th scope='col'>Dostavljeno</th>
        <th scope='col'>Poštarina</th>

      </tr>
    </thead>
    <tbody>";
  $sum = 0.00;
    $counter = 0;
                foreach($packages as $package){
                  $counter += 1;
                  $recipient = $package['recipient'];
                  $phone = $package['phone'];
                  $ransome = $package['shipping_fee'];
                  $sum += $ransome;
                  $paid_by = ($package['ransom_type_id'] == 1) ? 'Primalac' : 'Pošiljalac';
                  $comment = $package['comment'];
                  $content = $package['content'];
                  $package_id = $package['id'];
                  $street_number = $package['street_number'];
                  $street_name = $package['street_name'];
                  $zip = $package['zip'];
                  $municipality_name = $package['municipality_name'];
                  $ptt = $package['ptt'];

                  $firm_name = $package['firm_name'];
                  $firm_street_number = $package['firm_street_number'];
                  $firm_street_name = $package['firm_street_name'];
                  $firm_zip = $package['firm_zip'];
                  $firm_municipality_name = $package['firm_municipality_name'];
                  $firm_phone = $package['firm_phone'];

                  $send_time = date('d-m-Y', $package['send_time']);

                  
                  $numOfPackages = $package['number_of_packages'];
                  $orderInGrupu = $package['order_in_group'];
                  $grupId = sprintf('SX%08d', $package['group_id']);

                  $package_status = $package['status_name'];

                  $token = $package['token'];

                  $sql = "SELECT * FROM package_status_tracking where package_id = $package_id AND status_id = 3 ORDER BY datetime asc";
                  $result = mysqli_query($db, $sql);
                  $row = mysqli_fetch_array($result);
                  $date_time = "";
                  if(isset($row)){
                    $date_time = $row['datetime'];
                  }

                  $sql = "SELECT * FROM package_status_tracking where package_id = $package_id AND status_id = 4 ORDER BY datetime desc";
                  $result = mysqli_query($db, $sql);
                  $row = mysqli_fetch_array($result);
                  $date_time_1 = "";
                  if(isset($row)){
                    $date_time_1 = $row['datetime'];
                  }


        $str.="<tr>
        <th scope='row'>$package_id
          <h6>$grupId</h6>
          <h6>$orderInGrupu/$numOfPackages</h6>  
        </th>".
        // "<td><img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='' /></td>".
        "<td>
        
          <h6>$recipient</h6>
          <h6>$municipality_name $zip</h6>
          <h6>$street_name $street_number</h6>
          <h6>$phone</h6>
        </td>
        <td>
          <h6>$firm_name</h6>
          <h6>$firm_municipality_name $firm_zip</h6>
          <h6>$firm_street_name $firm_street_number</h6>
          <h6>$firm_phone</h6>
        </td>
        <td>
          <h6><strong>Otkup: </strong>$ransome rsd</h6>
          <h6><strong>Plaća: </strong>$paid_by</h6>
          <h6><strong>Napomena: </strong>$comment</h6>
        </td>
        <td> $content <br/></td>
        <td>$send_time </td>
        <td><h6>$date_time</h6>
        <td> <h6>$date_time_1</h6></td>
        <td><h6>$ptt RSD</h6></td>

      </tr>";
    }

    $str.="</tbody>
    </table>
    <h5>SUMA: $sum RSD</h5>
  </div>";

  $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . "/../uploads"]);
  $mpdf->AddPage('L'); // Adds a new page in Landscape orientation
  $stylesheet = file_get_contents('print.css');

  // Write some HTML code:
  $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
  $mpdf->WriteHTML($str,\Mpdf\HTMLParserMode::HTML_BODY);


  // Output a PDF file directly to the browser
  $mpdf->Output();


?>