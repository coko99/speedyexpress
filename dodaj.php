<?php
  include('config/session_admin.php');


  function getStreetId($street_name, $municipality_id, $db){
    $sql = "SELECT * FROM street WHERE municipality_id = $municipality_id AND name LIKE '$street_name'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) == 1) {
      return mysqli_fetch_array($result)['id'];
    }else{
      return "GREŠKA! ULICA NIJE PRONAĐENA ILI JE PRONAĐENO VIŠE ULICA SA TIM NAZIVOM IZ TE OPŠTINE!";
    }
  }

  if(isset($_GET['delete_firm_id'])){
    $id =  mysqli_real_escape_string($db, $_GET['delete_firm_id']);
    $sql = "UPDATE `firm` SET `status`='0' WHERE id=$id";
    $result = mysqli_query($db, $sql);
  }

  if(isset($_GET['delete_courier_id'])){
    $id =  mysqli_real_escape_string($db, $_GET['delete_courier_id']);
    $sql = "UPDATE `courier` SET `status`='0' WHERE id=$id";
    $result = mysqli_query($db, $sql);
  }


  if(isset($_POST['addcurier'])){
    $name =  mysqli_real_escape_string($db, $_POST['name']);
    $last_name =  mysqli_real_escape_string($db, $_POST['last_name']);
    $password =  mysqli_real_escape_string($db, $_POST['password']);
    $phone =  mysqli_real_escape_string($db, $_POST['phone']);
    if(isset($_POST['id_currier'])){
      $id_currier =  mysqli_real_escape_string($db, $_POST['id_currier']);
    }

    if(isset($id_currier)){
      $sql = "UPDATE `courier` SET `name`='$name',`last_name`='$last_name',`password`='1234',`token`='$password',`phone`='$phone' WHERE id=$id_currier";
    }else{
      $sql = "INSERT INTO `courier`(`name`, `last_name`, `password`, `token`, `phone`) VALUES ('$name','$last_name','1234','$password','$phone')";
    }

    $result = mysqli_query($db, $sql);
  }

  if(isset($_POST['add_client'])){
    if(isset($_POST['id_firm'])){
      $id_firm =  mysqli_real_escape_string($db, $_POST['id_firm']);
    }
    $street_name = mysqli_real_escape_string($db, $_POST['street']);
    $municipality_id = mysqli_real_escape_string($db, $_POST['municipality']);

    $street_id = getStreetId($street_name, $municipality_id, $db);

    $name =  mysqli_real_escape_string($db, $_POST['name']);
    $street_number =  mysqli_real_escape_string($db, $_POST['street_number']);
    $phone =  mysqli_real_escape_string($db, $_POST['phone']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    if(isset($_POST['password']) && strlen($_POST['password']) > 0){
      $password = password_hash(mysqli_real_escape_string($db, $_POST['password']), PASSWORD_DEFAULT);
    }

    if(isset($id_firm)){
      $sql = "UPDATE `firm` SET `name`='$name',`street_number`='$street_number',`street_id`='$street_id',`phone`='$phone' WHERE id = $id_firm";
      $result = mysqli_query($db, $sql);
    }else{
      $sql = "INSERT INTO `firm`(`name`, `street_number`, `street_id`, `phone`, `pib`) VALUES ('$name','$street_number','$street_id','$phone','1')";
      $result = mysqli_query($db, $sql);
      $firm_id = mysqli_insert_id($db);
    }

    if(isset($id_firm)){
      if(isset($password)){
        $sql = "UPDATE `user` SET `email`='$email',`password`='$password',`phone_number`='$phone'WHERE `firm_id`='$id_firm';";
      }else{
        $sql = "UPDATE `user` SET `email`='$email',`phone_number`='$phone'WHERE `firm_id`='$id_firm';";
      }
    }else{
      $sql = "INSERT INTO `user`(`email`, `password`, `firm_id`, `name`, 
      `last_name`, `phone_number`, `email_verified`, `phone_verified`, 
      `status`, `last_active`, `resettoken`, `resettokenexp`, `new_message`) 
      VALUES ('$email','$password','$firm_id','name','last_name',
      '$phone','1','1','1',current_timestamp,null, null,'1')";
    }

    $result = mysqli_query($db, $sql);
  }


  if(isset($_GET['edit_currier'])){
    $id_currier = mysqli_real_escape_string($db, $_GET['edit_currier']);
    $sql = "SELECT * FROM `courier` WHERE id = $id_currier";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) == 1) {
      $currier_edit = mysqli_fetch_array($result);
      $currier_edit_name = $currier_edit['name'];
      $currier_edit_last_name = $currier_edit['last_name'];
      $currier_edit_phone = $currier_edit['phone'];
      $currier_edit_token = $currier_edit['token'];
    }
  }

  if(isset($_GET['id_firm'])){
    $id_firm = mysqli_real_escape_string($db, $_GET['id_firm']);
    $sql = "SELECT firm.*,
    street.name AS street_name,
    municipality.name AS municipality_name,
    municipality.zip AS zip,
    municipality.id AS municipality_id,
    user.email AS user_email
    FROM `firm`
    LEFT JOIN street ON firm.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN user ON firm.id = user.firm_id
    WHERE firm.id = $id_firm
    ";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
      $firm_edit = mysqli_fetch_array($result);
      $firm_edit_name = $firm_edit['name'];
      $firm_edit_street = $firm_edit['street_name'];
      $firm_edit_street_number = $firm_edit['street_number'];
      $firm_edit_municipality = $firm_edit['municipality_name'];
      $firm_edit_zip = $firm_edit['zip'];
      $firm_edit_phone = $firm_edit['phone'];
      $firm_edit_email = $firm_edit['user_email'];
      $firm_edit_municipality_id = $firm_edit['municipality_id'];

    }
  }

  $sql = "SELECT *
  FROM `courier` where status = 1";
  $result = mysqli_query($db, $sql);
  $courieres = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($courieres, $row);
  }

  $sql = "SELECT firm.*,
  street.name AS street_name,
  municipality.name AS municipality_name,
  municipality.zip AS zip
  FROM `firm`
  LEFT JOIN street ON firm.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  WHERE firm.status = 1
  ";
  $result = mysqli_query($db, $sql);
  $firms = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($firms, $row);
  }

?>
<!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>
  <?php
    $active = 5;
    include('config/navbar.php');
  ?>
<!--DODAJ KURIRA-->
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-6 col-sm-12  ">
          <section class="">
            <div class="row">
              <div class="col-md-9 col-sm-12">
                <h2 class="h1-responsive font-weight-bold text-center my-4">
                  Dodaj kurira
                </h2>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <form action="" method="post">
                  <?php echo (isset($currier_edit)) ? "<input name='id_currier' type='hidden' value='$id_currier' ></input>" : ""; ?>
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Ime"
                          type="text"
                          id="name"
                          name="name"
                          class="form-control"
                          value='<?php echo (isset($currier_edit)) ? $currier_edit_name : ""; ?>'
                        />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Prezime"
                          type="text"
                          id="last_name"
                          name="last_name"
                          class="form-control"
                          value='<?php echo (isset($currier_edit)) ? $currier_edit_last_name : ""; ?>'
                        />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Broj telefona"
                          type="number"
                          id=""
                          name="phone"
                          class="form-control"
                          value='<?php echo (isset($currier_edit)) ? $currier_edit_phone : ""; ?>'
                        />
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Šifra"
                          type="number"
                          id=""
                          name="password"
                          class="form-control"
                          value='<?php echo (isset($currier_edit)) ? $currier_edit_token : ""; ?>'
                        />

                      </div>
                    </div>
                  </div>
                  <button name='addcurier' class="btn btn-success" type="submit">
                    <?php echo (isset($currier_edit)) ? "Izmeni kurira" : "Dodaj kurira"; ?>
                  </button>
                </form>
              </div>
            </div>
          </section>
        </div>
        <div class="col-md-6 col-sm-12 ">
          <div class="col-12">
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <h2 class="h1-responsive font-weight-bold text-center my-4">
                Spisak kurira
              </h2>
            </div>
          </div>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#ID</th>
                <th scope="col">Ime i Prezime</th>
                <th scope="col">Akcija</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach($courieres as $courier){
                  $id = $courier['id'];
                  $name = $courier['name'];
                  $last_name = $courier['last_name'];

                  echo "
                  <tr>
                    <th scope='row'>$id</th>
                    <td class='align-items-center'>
                      <h4 class='m-0 p-0'>$name $last_name</h4>
                    </td>
                    <td>
                      <a href='dodaj.php?delete_courier_id=$id'>
                        <button class='btn btn-danger align-self-center confirmation'>
                          Izbriši
                        </button></a
                      >
                      <a href='dodaj.php?edit_currier=$id'>
                        <button class='btn btn-warning align-self-center'>
                          Izmeni
                        </button></a
                      >
                    </td>
                  </tr>
                  ";
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <div class="row mt-5  mb-5" style="height:10px;background-color: #1A75F0;"></div>
<!--DODAJ FIRMU-->
    <div class="row">
      <div class="col-md-6 col-sm-12  ">
        <section class="">
          <div class="row">
            <div class="col-md-9 col-sm-12">
              <h2 class="h1-responsive font-weight-bold text-center my-4">
                Dodaj klijenta
              </h2>
            </div>
          </div>
          <div class="row">
            <div class="col-md-9">
              <form action="" method="post">
              <?php echo (isset($firm_edit)) ? "<input name='id_firm' type='hidden' value='$id_firm' ></input>" : ""; ?>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Ime firme"
                        id="name"
                        name="name"
                        class="form-control"
                        value='<?php echo (isset($firm_edit)) ? $firm_edit_name : ""; ?>'
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
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
                value='<?php echo (isset($firm_edit)) ? $firm_edit_street : ""; ?>'
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
                value='<?php echo (isset($firm_edit)) ? $firm_edit_street_number : ""; ?>'
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
                <?php echo (isset($firm_edit)) ? "<option value='$firm_edit_municipality_id' selected>$firm_edit_municipality</option>" : "<option disabled value='' selected>Opština</option>"; ?>
                </select>
                <span class="input-group-text" id="basic-addon2"><?php echo (isset($firm_edit)) ? "$firm_edit_zip" : "ZIP"; ?></span>
              </div>
            </div>
                </div>
                
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Broj telefona"
                        id=""
                        name="phone"
                        class="form-control"
                        value='<?php echo (isset($firm_edit)) ? $firm_edit_phone : ""; ?>'
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      placeholder="Korisničko ime"
                        id="name"
                        name="email"
                        type="text"
                        class="form-control"
                        value='<?php echo (isset($firm_edit)) ? $firm_edit_email : ""; ?>'
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Šifra"
                        type="number"
                        id=""
                        name="password"
                        class="form-control"
                      />

                    </div>
                  </div>
                </div>
                <!-- <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      placeholder="PIB"
                        id="name"
                        name="pib"
                        type="text"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div> -->
                <button name='add_client' class="btn btn-success" type="submit">
                <?php echo (isset($firm_edit)) ? "Izmeni klijenta" : "Dodaj klijenta"; ?>
                </button>
                <!-- <button class="btn btn-warning disabled  " type="submit">
                  Sačuvaj izmene
                </button> -->
              </form>
            </div>
          </div>
        </section>
      </div>

      
      <div class="col-md-6 col-sm-12 ">
        <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <h2 class="h1-responsive font-weight-bold text-center my-4">
              Spisak klijenata
            </h2>
          </div>
        </div>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">#ID</th>
              <th scope="col">Ime i Prezime</th>
              <th scope="col">Akcija</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach($firms as $firm){

                $id = $firm['id'];
                $name = $firm['name'];

                echo "<tr>
                        <th scope='row'>$id</th>
                        <td class='align-items-center'>
                          <h4 class='m-0 p-0'>$name</h4>
                        </td>
                        <td>
                          <a href='dodaj.php?delete_firm_id=$id'>
                            <button class='btn btn-danger align-self-center confirmation'>
                              izbriši
                            </button></a
                          >
                          <a href='dodaj.php?id_firm=$id'>
                            <button class='btn btn-warning align-self-center'>
                              Izmeni
                            </button></a
                          >
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
        return confirm('Da li ste sigurni da želite da obrišete?');
      });

      $( "#importexcel" ).on( "submit", function( event ) {
        $('#overlay').removeClass('d-none');

      });

      
    </script>
  </body>
</html>
