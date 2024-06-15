<?php

use chillerlan\QRCode\QRCode;

  include('config/session_user.php');
  require 'vendor/autoload.php';

  function insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, $login_session, $db) {
    $token = time();
    $sql = "INSERT INTO `package`(`street_id`, 
  `firm_id`, `street_number`, `token`, `curier_id`, 
  `phone`, `ransom_type_id`, `shipping_fee`, 
  `recipient`, `content`, `comment`, `ptt`, `status_id`, `created_by`) 
  VALUES ('$street_id','$firm_id','$street_number',
  '$token', NULL, '$phone','$ransom_type_id','$shipping_fee'
  ,'$recipient','$content', '$comment', '$ptt', '1', '$login_session')";
    $result = mysqli_query($db, $sql);
    logEvent('User '.$login_session.': '.$sql);
  }

  function getStreetId($street_name, $municipality_id, $db){
    $sql = "SELECT * FROM street WHERE municipality_id = $municipality_id AND name LIKE '$street_name'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) == 1) {
      return mysqli_fetch_array($result)['id'];
    }else{
      return "GREŠKA! ULICA NIJE PRONAĐENA ILI JE PRONAĐENO VIŠE ULICA SA TIM NAZIVOM IZ TE OPŠTINE!";
    }
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
    $edit_id = mysqli_real_escape_string($db, $_GET['edit_id']);


    insertPackage($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment,$ptt, $firm_id, $login_session, $db);
    $sql = "DELETE FROM `temporary_package` WHERE id = $edit_id AND firm_id = $firm_id";
    $result = mysqli_query($db, $sql);
    header("Location: posaljiPaketTemp.php");
  }

  if(isset($_GET['delete_id'])){
    $delete_id = mysqli_real_escape_string($db, $_GET['delete_id']);
    $sql = "DELETE FROM `temporary_package` WHERE id = $delete_id AND firm_id = $firm_id";
    $result = mysqli_query($db, $sql);
    logEvent('User '.$login_session.': '.$sql);
  }

  if(isset($_GET['edit_id'])){
    $edit_id = mysqli_real_escape_string($db, $_GET['edit_id']);
    $sql = "SELECT * FROM `temporary_package` WHERE id = $edit_id AND firm_id = $firm_id";
    $result = mysqli_query($db, $sql);
    $edit = mysqli_fetch_array($result);
    
  }

  $sql = "SELECT * 
  FROM `temporary_package`
  WHERE firm_id = $firm_id";
  $result = mysqli_query($db, $sql);
  $packages = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($packages, $row);
  }

  $sql = "SELECT * 
  FROM `firm`
  WHERE id = $firm_id";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $export_type = $row['mass_import_type'];
  

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
      $active = 4;
      include('config/navbar.php');
    ?>


    <div class="container mt-4">
      <div class="row mb-4">
        <h2 class="mt-3 mb-3">Nepronađeni paketi</h2>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <h3><strong>Popuni ručno</strong></h3>

          <!-- POSALJI PAKET RUCNO -->
          <form method='POST'>


            <?php
            if(isset($edit)){
              echo '<input name="edit_id" type="hidden" value="'.$edit['id'].'" />';
            }
            ?>
            <div class="form-group">
            <label for="name">Primalac: </label>
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Ime i Prezime"
                name="name"
                required
                autocomplete="off"
                <?php if(isset($edit)){
                  echo 'value="'.$edit['recipient'].'"';
                } 
                ?>
              />
            </div>
            <div class="form-group">
            <label for="street">Ulica: </label>
              <input
                type="text"
                class="form-control"
                placeholder="Ulica"
                name="street" 
                required
                id="term" 
                class="form-control"
                autocomplete="off"
                <?php if(isset($edit)){
                  echo 'value="'.$edit['street_name'].'"';
                } 
                ?>
              />
              <input name='street_id' type="hidden" id="street-id">
            </div>
            <div class="form-group">
            <label for="street_number">Broj: </label>
              <input
                type="text"
                class="form-control"
                id="street_number"
                placeholder="Broj"
                name="street_number"
                required
                <?php if(isset($edit)){
                  echo 'value="'.$edit['street_number'].'"';
                } 
                ?>
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
            <label for="phone">Telefon: </label>
              <input
                type="tel"
                class="form-control"
                id="inputAddress"
                placeholder="Broj telefona"
                name='phone'
                required
                <?php if(isset($edit)){
                  echo 'value="'.$edit['phone'].'"';
                } 
                ?>
              />
            </div>
            <div class="form-group">
            <label for="description">Opis pošiljke: </label>
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Opis pošiljke"
                required
                name='description'
                <?php if(isset($edit)){
                  echo 'value="'.$edit['content'].'"';
                } 
                ?>
              />
            </div>
            <div class="form-group" required>
              <label for="paid_by">Dostavu plaća</label>
              <select name="paid_by" id="inputState" class="form-control">
                <option value="1" selected>Primalac</option>
                <option value="2">Pošiljalac</option>
              </select>
            </div>
            <div class="form-group">
              <label for="ptt">PTT : </label>
              <input class="form-control" require type="number" step="0.1" name="ptt"
              <?php if(isset($edit)){
                  echo 'value="'.$edit['ptt'].'"';
                } else{
                  echo 'value="0"';
                }
                ?>
              />
            </div>
            <div class="form-group">
            <label for="ransome">Otkupnina: </label>
              <input
              required
                type="number"
                class="form-control"
                id="inputAddress"
                placeholder="Otkupnina"
                name="ransome"
                <?php if(isset($edit)){
                  echo 'value="'.$edit['shipping_fee'].'"';
                } 
                ?>
              />
            </div>
            <div class="form-group">
            <label for="comment">Napomena: </label>
              <input
              maxlength="255"
              required
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Napomena"
                name="comment"
                <?php if(isset($edit)){
                  echo 'value="'.$edit['comment'].'"';
                } 
                ?>
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
            <div class="col-6 align-self-center p-3">
              <h5>
                POŠILJKE ZA DAN
                <strong><span id="prikazDatumaVremena"></span></strong>
              </h5>
            </div>
            <div class="col-6 align-self-center">
              <div class="row">
                <div class="col-4 text-center">
                  <a href="<?php echo $export_type; ?>" class="btn btn-primary">Excel</a>
                </div>
              </div>
            </div>
          </div>

          <!-- SPISAK -->
          <div class="row">
            <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar1">
              <table class="table table-bordered table-striped mb-0">
                <thead>
                  <tr>
                    <th scope="col ">#ID</th>
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
                    $municipality_name = $package['municipality_name'];
                    $token = $package['token'];
                    $content = $package['content'];

                    echo "<tr>
                            <th scope='row'>$package_id</th>
                            <td>
                              <h6>$recipient</h6>
                              <h6>$municipality_name</h6>
                              <h6>$street_name</h6>
                              <h6>$phone</h6>
                              <h6>$content</h6>
                            </td>
                            <td>
                              <h6><strong>Otkup: </strong>$ransome rsd</h6>
                              <h6><strong>Vrednost: </strong>$ransome rsd</h6>
                              <h6><strong>Plaća: </strong>$paid_by</h6>
                              <h6><strong>napomena: </strong>$comment</h6>
                            </td>
                            <td >
                            <a href='?delete_id=$package_id' class='confirmation btn btn-danger'>Obrisi</a>
                            <a href='?edit_id=$package_id' class=' btn btn-success my-2'>Izmeni</a></td>
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
