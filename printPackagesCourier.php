<?php

use chillerlan\QRCode\QRCode;

if(!isset($_GET['id'])){
  header('Location: kuriri.php');
}

include('config/session_admin.php');
require 'vendor/autoload.php';

$id = mysqli_real_escape_string($db, $_GET['id']);

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
LEFT JOIN grup on package.group_id = grup.id
WHERE curier_id = $id AND status_id != 4
order by municipality_name ASC";
  $result = mysqli_query($db, $sql);
  $packages = [];
while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
}

$sql = "select * from courier where id = $id";
$result = mysqli_query($db, $sql);
$curier = mysqli_fetch_array($result);

$suma = 0;
$pttSuma = 0;
$pttOtkupSuma = 0;
foreach($packages as $pack){
  $suma += $pack['shipping_fee'];
  $pttSuma += $pack['ptt'];
  $pttOtkupSuma += $pack['shipping_fee'] + $pack['ptt'];
}

$str = "
<div class='col-12 table-wrapper-scroll-y my-custom-scrollbar'>
  <h4>
    ".
    $curier['name'] . " " . $curier['last_name'] . " - " . date("Y/m/d") . " - Zaduženo: " . sizeof($packages) . " - Suma: " . $suma." - PTT: " .$pttSuma . " - Ukupno: " .$pttOtkupSuma."
  </h4>
  <table class='table table-bordered table-striped mb-0'>
    <thead>
      <tr>
        <th scope='col '>#ID</th>
        <th scope='col'>QR</th>
        <th scope='col'>Primalac</th>
        <th scope='col'>Pošiljalac</th>
        <th scope='col'>Opis</th>
        <th scope='col'>Status</th>
        <th scope='col'>PTT</th>
      </tr>
    </thead>
    <tbody>";

    $counter = 0;
    $mun_grp = "";
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

                  $firm_name = $package['firm_name'];
                  $firm_street_number = $package['firm_street_number'];
                  $firm_street_name = $package['firm_street_name'];
                  $firm_zip = $package['firm_zip'];
                  $firm_municipality_name = $package['firm_municipality_name'];
                  $firm_phone = $package['firm_phone'];

                  $numOfPackages = $package['number_of_packages'];
                  $orderInGrupu = $package['order_in_group'];
                  $grupId = sprintf('SX%08d', $package['group_id']);
                  
                  $ptt = $package['ptt'];

                  $package_status = $package['status_name'];

                  $token = $package['token'];
                  if($mun_grp != $municipality_name){
                    $mun_grp = $municipality_name;
                    $str.="<tr>
                      <td  style='background-color: #BEBEBE;' colspan='7'><h4>$municipality_name</h4></td>
                    </td>";
                  }


        $str.="<tr>
        <th scope='row'>$package_id
          <h6>$grupId</h6>
          <h6>$orderInGrupu/$numOfPackages</h6>  
        </th>
        <td><img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='' /></td>
        <td>
          ID: $package_id<br/>
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
          <h6>Opis:</h6> $content <br/>
          <h6>Datum slanja:</h6> $send_time <br/>
        </td>
        <td>$package_status</td>
        <td>
          <h6>$ptt</h6>
          <hr/>
          <h3>".$ptt + $ransome."</h3>
        </td>
      </tr>";
    }

    $str.="</tbody>
    </table>
  </div>";

  // echo $str;
  $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . "/../uploads"]);
//   $stylesheet = file_get_contents('../css/invoice.css');

  // Write some HTML code:
//   $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
  $mpdf->WriteHTML($str,\Mpdf\HTMLParserMode::HTML_BODY);


  // Output a PDF file directly to the browser
  $mpdf->Output();


?>