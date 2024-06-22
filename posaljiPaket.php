<?php

use chillerlan\QRCode\QRCode;

  include('config/session_user.php');
  require 'vendor/autoload.php';

  function insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, $groupId, $orderInGrupu, $login_session, $db) {
   if($orderInGrupu != null && $orderInGrupu != 1){
    $ptt = 0;
    $shipping_fee = 0;
   }
   if($groupId == NULL){
    $groupId = 'NULL';
    $orderInGrupu = 'NULL';
   }

    $token = time();
    $sql = "INSERT INTO `package`(`street_id`, 
  `firm_id`, `street_number`, `token`, `curier_id`, 
  `phone`, `ransom_type_id`, `shipping_fee`, 
  `recipient`, `content`, `comment`, `ptt`, `status_id`, `created_by`, `group_id`, `order_in_group`) 
  VALUES ('$street_id','$firm_id','$street_number',
  '$token', NULL, '$phone','$ransom_type_id','$shipping_fee'
  ,'$recipient','$content', '$comment', '$ptt', '1', '$login_session', $groupId, $orderInGrupu)";
    $result = mysqli_query($db, $sql);
    logEvent('User '.$login_session.': '.$sql);
  }

  function getStreetId($street_name, $municipality_id, $db){
    $sql = "SELECT * FROM street WHERE municipality_id = $municipality_id AND name LIKE '$street_name'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) == 1) {
      return mysqli_fetch_array($result)['id'];
    }
    else if (mysqli_num_rows($result) > 1){
      return mysqli_fetch_array($result)['id'];
    }else{
      return "GREŠKA! ULICA NIJE PRONAĐENA ILI JE PRONAĐENO VIŠE ULICA SA TIM NAZIVOM IZ TE OPŠTINE!";
    }
  }

  if(isset($_POST['send_packages'])){
    $now = time();
    $sql = "UPDATE `package` SET `status_id`='2', `send_time`=$now
      WHERE firm_id = $firm_id AND status_id = 1";
    $result = mysqli_query($db, $sql);
  }

  if(isset($_POST['add_single_package'])){

    $street_name = mysqli_real_escape_string($db, $_POST['street']);
    $municipality_id = mysqli_real_escape_string($db, $_POST['municipality']);

    $street_id = getStreetId($street_name, $municipality_id, $db);

    $street_number = mysqli_real_escape_string($db, $_POST['street_number']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $ransom_type_id = mysqli_real_escape_string($db, $_POST['paid_by']);
    $shipping_fee = mysqli_real_escape_string($db, $_POST['ransome']);
    $recipient = mysqli_real_escape_string($db, $_POST['name']);
    $content = mysqli_real_escape_string($db, $_POST['description']);
    $comment = mysqli_real_escape_string($db, $_POST['comment']);
    $ptt = mysqli_real_escape_string($db, $_POST['ptt']);
    $numOfPackages = mysqli_real_escape_string($db, $_POST['numOfPackages']);

    if($numOfPackages > 1){
      $sql = "INSERT INTO `grup`(`number_of_packages`) VALUES ($numOfPackages)";
      mysqli_query($db, $sql);
      $groupId = mysqli_insert_id($db);
      for($i = 0; $i < $numOfPackages; $i++){
        insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment,$ptt, $firm_id, $groupId, $i+1, $login_session, $db);
      }
    }else{
      insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment,$ptt, $firm_id, null, null, $login_session, $db);
    }
    
    header("Location: posaljiPaket.php");

  }

  if(isset($_GET['delete_id'])){
    $delete_id = mysqli_real_escape_string($db, $_GET['delete_id']);
    $sql = "DELETE FROM `package` WHERE id = $delete_id AND status_id = 1 AND firm_id = $firm_id";
    $result = mysqli_query($db, $sql);
    logEvent('User '.$login_session.': '.$sql);
  }

  if($firm_id == 43|| $firm_id == 35 || $firm_id == 111  || $firm_id == 114){
    $sql = "SELECT package.*, 
    municipality.name AS municipality_name, 
    municipality.zip AS zip,
    street.name AS street_name,
    grup.number_of_packages AS number_of_packages
    FROM `package`
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN grup ON package.group_id = grup.id
    WHERE firm_id = $firm_id AND status_id = 1
    order by package.content";
  }else{
    $sql = "SELECT package.*, 
    municipality.name AS municipality_name, 
    municipality.zip AS zip,
    street.name AS street_name,
    grup.number_of_packages AS number_of_packages
    FROM `package`
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    WHERE firm_id = $firm_id AND status_id = 1";
  }
  // $sql = "SELECT package.*, 
  // municipality.name AS municipality_name, 
  // municipality.zip AS zip,
  // street.name AS street_name 
  // FROM `package`
  // LEFT JOIN street ON package.street_id = street.id
  // LEFT JOIN municipality ON street.municipality_id = municipality.id
  // WHERE firm_id = $firm_id AND status_id = 1
  // order by package.comment";
  $result = mysqli_query($db, $sql);
  $packages = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
  }
  

?><!DOCTYPE html>
<html lang="sr">
  <?php
    include('config/head.php');
  ?>
  
  <body>
  <div id='overlay' class="overlay d-none" >
      <div class="d-flex justify-content-center">  
        <div class="spinner-border text-primary text-primary" role="status" style="width: 16rem; height: 16rem; z-index: 20;">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>

    
    <?php
      $active = 3;
      include('config/navbar.php');
    ?>


    <div class="container mt-4">
      <div class="row mb-4">
        <h2 class="mt-3 mb-3">Zakazivanje kurira</h2>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <h3><strong>Popuni ručno</strong></h3>

          <!-- POSALJI PAKET RUCNO -->
          <form method='POST'>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Ime i Prezime"
                name="name"
                required
                autocomplete="off"
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                placeholder="Ulica"
                name="street" 
                required
                id="term" 
                class="form-control"
                autocomplete="off"
              />
              <input name='street_id' type="hidden" id="street-id">
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="street_number"
                placeholder="Broj"
                name="street_number"
                required
              />
            </div>
            <div class="form-group">
              <div class="input-group">
                <select
                  class="form-control"
                  id="municipality"
                  name="municipality"
                  required
                  placeholder="Opština"
                >
                  <option disabled value="" selected>Opština</option>
                </select>
                <span class="input-group-text" id="basic-addon2">ZIP</span>
              </div>
            </div>
            <div class="form-group">
              <input
                type="tel"
                class="form-control"
                id="inputAddress"
                placeholder="Broj telefona"
                name='phone'
                required
              />
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Opis pošiljke"
                required
                name='description'
              />
            </div>
            <div class="form-group" required>
              <label for="inputState">Dostavu plaća</label>
              <select name="paid_by" id="inputState" class="form-control">
                <option value="1" selected>Primalac</option>
                <option value="2">Pošiljalac</option>
              </select>
            </div>
            <div class="form-group">
              <label for="inputState">PTT : </label>
              <input class="form-control" require type="number" value="0" step="0.1" name="ptt" />
            </div>
            <div class="form-group">
              <input
              required
                type="number"
                class="form-control"
                id="inputAddress"
                placeholder="Otkupnina"
                name="ransome"
              />
            </div>
            <div class="form-group">
              <input
              maxlength="255"
              required
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Napomena"
                name="comment"
              />
            </div>
            <div class="form-group">
              <input
              required
                type="number"
                class="form-control"
                id="number_of_packages"
                placeholder="Broj paketa u grupi:"
                name="numOfPackages"
                value="1"
                min="1" 
                max="100"
              />
            </div>
            <button name="add_single_package" type="submit" class="btn btn-primary mt-3 mb-3">
              Ubaci
            </button>
          </form>
        </div>

        <!-- Komande za pošiljke -->
        <div class="col-md-8 col-sm-12 border mb-5">
          <div class="row d-flex justify-content-between">
            <div class="col-4 align-self-center p-3">
              <h5>
                POŠILJKE ZA DAN
                <strong><span id="prikazDatumaVremena"></span></strong>
              </h5>
            </div>
            <div class="col-8 align-self-center">
              <div class="row">
                <!-- <div class="col-3 text-center">
                  <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-primary">Excel</button>
                </div> -->
                <div class="col-3 text-center">
                  <a href="printPackages.php" class="btn btn-primary">Štampaj</a>
                </div>

                <div class="col-3 text-center">
                  <a href="print_single_package_grup.php" class="btn btn-primary">Štampaj</a>
                </div>
                <div class="col-3 text-center">
                  <form method='POST'><button name='send_packages' type='submit' class="btn btn-success">POŠALJI</button></form>
                </div>
              </div>
            </div>
          </div>

          <!-- SPISAK -->
          <div class="row">
            <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
              <table class="table table-bordered table-striped mb-0">
                <thead>
                  <tr>
                    <th scope="col ">#ID</th>
                    <th scope="col ">Opis</th>
                    <th scope="col">QR</th>
                    <th scope="col">Primalac</th>
                    <th scope="col">Opis</th>
                    <th scope="col">Akcija</th>
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
                    $token = $package['token'];
                    $content = $package['content'];
                    $numOfPackages = $package['number_of_packages'];
                    $orderInGrupu = $package['order_in_group'];
                    $grupId = sprintf('SX%08d', $package['group_id']);

                    echo "<tr>
                            <th scope='row'>$package_id</th>";
                              echo "<td>
                      <h6><strong>$content </h6>
                      </td>";
                            
                      
                      echo "<td>
                              <img class='qr-slika' src='".(new QRCode())->render($package_id.'-'.$token)."' alt='QR Code' />
                            </td>
                            <td>
                              <h6>$recipient</h6>
                              <h6>$municipality_name $zip</h6>
                              <h6>$street_name $street_number</h6>
                              <h6>$phone</h6>
                            </td>
                            <td>
                              <h6><strong>Otkup: </strong>$ransome rsd</h6>
                              <h6><strong>Vrednost: </strong>$ransome rsd</h6>
                              <h6><strong>Plaća: </strong>$paid_by</h6>
                              <h6><strong>Groupa:</strong> $grupId</h6>
                              <h6>$orderInGrupu/$numOfPackages</h6>
                            </td>
                            <td><a href='?delete_id=$package_id' class='confirmation btn btn-danger'>Obrisi</a>
                            <a href='print_single_package.php?print_id=$package_id' class='btn btn-success my-2'>Štampaj</a>
                            </td>
                          </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import paketa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id='importexcel' action="mass_import.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input class="form-control" type="file" name='file' id="formFile">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
          <button type="submit" name='submit' class="btn btn-primary">Sačuvaj</button>
        </div>
      </form>
    </div>
  </div>
</div> -->

    <!-- <script src="index.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    ></script>
    <script>
      var availableTags = [];
      $( function() {
        $( "#term" ).autocomplete({
          source: 'ajax-scripts/ajax-street-db-search.php',
        });
        } 
      );
      $( "#term" ).on( "autocompleteselect", function( event, ui ) {
          $.get( "ajax-scripts/ajax-municipality-db-search.php?street=" + ui.item.value, function( data ) {
            $('#municipality').empty();
            JSON.parse(data).forEach(element => {
              $('#municipality').append('<option zip="'+element["zip"]+'" value="'+ element["id"] + '">' + element["name"] + '</option>');
              }
            );
            set_zip();
          });
      } );

      $('#municipality').on( "change", function(e) {
        set_zip();
      } );

      function set_zip(v){
        $('#basic-addon2').html(
          $('#municipality').children("option:selected").attr('zip')
          );
      }

      $('.confirmation').on('click', function () {
        return confirm('Da li ste sigurni da želite da obrišete paket.');
      });

      $( "#importexcel" ).on( "submit", function( event ) {
        $('#overlay').removeClass('d-none');

      });

      
    </script>
  </body>

</html>
