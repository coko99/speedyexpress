<?php
use chillerlan\QRCode\QRCode;

  include('config/session_admin.php');
  require 'vendor/autoload.php';

  if(!isset($_GET['id'])){
    header('Location: kuriri.php');
  }
  $id = mysqli_real_escape_string($db, $_GET['id']);

  $sql = "SELECT *
  FROM `courier` WHERE id = $id";
  $result = mysqli_query($db, $sql);
  $courier = mysqli_fetch_array($result);
  if(!isset($courier)){
    header('Location: kuriri.php');
  }
  $courier_id = $courier['id'];
  $courier_name = $courier['name'];
  $courier_last_name = $courier['last_name'];

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
  firm.phone AS firm_phone
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  LEFT JOIN status ON package.status_id = status.id
  LEFT JOIN firm ON package.firm_id = firm.id
  LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
  LEFT JOIN municipality AS firm_municipality ON firm_street.municipality_id = firm_municipality.id
  WHERE curier_id = $courier_id AND status_id != 4";
  $result = mysqli_query($db, $sql);
  $packages = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
  }

  $sql = "SELECT count(*) as c
  FROM package
  WHERE curier_id = $courier_id
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_tak = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package
  WHERE curier_id = $courier_id
  AND status_id = 4
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_suc = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package
  WHERE curier_id = $courier_id
  AND status_id != 4
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_fai = $row['c'];

?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>

  <?php
      $active = 2;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4">
        <div class="col">
          <a href="kuriri.php"><button class="btn btn-info">NAZAD</button></a>
        </div>
      </div>
      <div class="row mb-4">
        <h2 class="mt-3 mb-3 text-center"><?php echo "$courier_name $courier_last_name" ?></h2>
        <div
          class="statistika1 d-flex flex-column justify-content-between p-4 col-xs-12 col-sm-12 col-md-4"
        >
          <h3 class="opsteh3 mb-3">Ovaj mesec</h3>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj preuzetih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_tak; ?></spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_suc; ?></spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj neisporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_fai; ?></spam>
          </div>
        </div>
        <div class="col-md-6 mx-auto mt-5">
          <!-- HTML element za grafikon -->
          <canvas id="myChart">test</canvas>
        </div>
        <div class="row">
          <div class="col-6">
            <h1 class="mt-3">Zaduženi paketi</h1>
            <a href="printPackagesCourier.php?id=<?php echo $courier_id; ?>" class="btn btn-primary mb-3">Štampaj</a>
          </div>

          <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th scope="col ">#ID</th>
                  <th scope="col">QR</th>
                  <th scope="col">Primalac</th>
                  <th scope="col">Pošiljalac</th>
                  <th scope="col">Opis</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
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

                  $firm_name = $package['firm_name'];
                  $firm_street_number = $package['firm_street_number'];
                  $firm_street_name = $package['firm_street_name'];
                  $firm_zip = $package['firm_zip'];
                  $firm_municipality_name = $package['firm_municipality_name'];
                  $firm_phone = $package['firm_phone'];

                  $package_status = $package['status_name'];

                  $token = $package['token'];

                  echo "<tr>
                        <th scope='row'>$counter</th>
                        <td><img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='' /></td>
                        <td>
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
                          <h6><strong>Vrednost: </strong>$ransome rsd</h6>
                          <h6><strong>Plaća: </strong>$paid_by</h6>
                          <h6><strong>napomena: </strong>$comment</h6>
                        </td>
                        <td>$package_status</td>

                      </tr>";

                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
