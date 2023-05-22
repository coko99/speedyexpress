<?php

use chillerlan\QRCode\QRCode;

  include('config/session_user.php');
  require 'vendor/autoload.php';

  function update($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment, $ptt, $firm_id, $login_session, $db, $package_id) {
    $token = time();
    $sql = "UPDATE `package` SET 
    `street_id`='$street_id',`firm_id`='$firm_id',
    `street_number`='$street_number',
    `phone`='$phone', `ransom_type_id`='$ransom_type_id',
    `shipping_fee`='$shipping_fee',
    `recipient`='$recipient',`content`='$content',
    `comment`='$comment',`ptt`='$ptt' WHERE id = $package_id";
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
    $package_id =  mysqli_real_escape_string($db, $_POST['id']);


    update($street_id, $street_number, $phone, $ransom_type_id, $shipping_fee, $recipient, $content, $comment,$ptt, $firm_id, $login_session, $db, $package_id);
    
  }

  if(isset($_GET['id'])){
    $package_id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT package.*, 
    municipality.name AS municipality_name,
    municipality.id AS municipality_id,
    municipality.zip AS zip,
    street.name AS street_name 
    FROM `package`
    LEFT JOIN street ON package.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    WHERE package.id = $package_id";
    $result = mysqli_query($db, $sql);
    $package = mysqli_fetch_array($result);
  }else{
    header('Location: poslatiPaketi.php');
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
      $active = 2;
      include('config/navbar.php');
    ?>


    <div class="container mt-12">
      <div class="row mb-4">
        <h2 class="mt-3 mb-3">Izmena podataka o paketu</h2>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <!-- POSALJI PAKET RUCNO -->
          <form method='POST'>
            <input type="hidden" value="<?php echo $package_id; ?>" name='id'/>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="inputAddress"
                placeholder="Ime i Prezime"
                name="name"
                required
                autocomplete="off"
                value="<?php echo $package['recipient']; ?>"
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
                value="<?php echo $package['street_name']; ?>"
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
                value="<?php echo $package['street_number']; ?>"
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
                  <option value="<?php echo $package['municipality_id']; ?>"><?php echo $package['municipality_name']; ?></option>
                </select>
                <span class="input-group-text" id="basic-addon2"><?php echo $package['zip']; ?></span>
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
                value="<?php echo $package['phone']; ?>"
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
                value="<?php echo $package['content']; ?>"
              />
            </div>
            <div class="form-group" required>
              <label for="inputState">Dostavu plaća</label>
              <select name="paid_by" id="inputState" class="form-control">
                <option value="1" <?php echo $package['ransom_type_id'] == 1 ? "selected" : ""; ?>>Primalac</option>
                <option value="2" <?php echo $package['ransom_type_id'] == 2 ? "selected" : ""; ?>>Pošiljalac</option>
              </select>
            </div>
            <div class="form-group">
              <label for="inputState">PTT : </label>
              <input class="form-control" 
              require type="number" 
              value="<?php echo $package['ptt']; ?>"
              step="0.1" 
              name="ptt" />
            </div>
            <div class="form-group">
              <input
              required
                type="number"
                class="form-control"
                id="inputAddress"
                placeholder="Otkupnina"
                name="ransome"
                value="<?php echo $package['shipping_fee']; ?>"
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
                value="<?php echo $package['comment']; ?>"
              />
            </div>
            <button name="add_single_package" type="submit" class="btn btn-primary mt-3 mb-3">
              Izmeni
            </button>
          </form>
        </div>

        <!-- Komande za pošiljke -->
        
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
