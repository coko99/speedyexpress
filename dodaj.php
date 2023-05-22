<?php
  include('config/session_admin.php');


  if(isset($_POST['addcurier'])){
    $name =  mysqli_real_escape_string($db, $_POST['name']);
    $last_name =  mysqli_real_escape_string($db, $_POST['last_name']);
    $password =  mysqli_real_escape_string($db, $_POST['password']);
    $phone =  mysqli_real_escape_string($db, $_POST['phone']);

    $sql = "INSERT INTO `courier`(`name`, `last_name`, `password`, `token`, `phone`) VALUES ('$name','$last_name','1234','$password','$phone')";
    $result = mysqli_query($db, $sql);
  }

  if(isset($_POST['addfirm'])){
    $name =  mysqli_real_escape_string($db, $_POST['name']);
    $street_number =  mysqli_real_escape_string($db, $_POST['street_number']);
    $street_id =  mysqli_real_escape_string($db, $_POST['street_id']);
    $phone =  mysqli_real_escape_string($db, $_POST['phone']);
    // $pib =  mysqli_real_escape_string($db, $_POST['pib']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($db, $_POST['password']), PASSWORD_DEFAULT);

    $sql = "INSERT INTO `firm`(`name`, `street_number`, `street_id`, `phone`, `pib`) VALUES ('$name','$street_number','$street_id','$phone','1')";
    $result = mysqli_query($db, $sql);
    $firm_id = mysqli_insert_id($db);

    $sql = "INSERT INTO `user`(`email`, `password`, `firm_id`, `name`, 
    `last_name`, `phone_number`, `email_verified`, `phone_verified`, 
    `status`, `last_active`, `resettoken`, `resettokenexp`, `new_message`) 
    VALUES ('$email','$password','$firm_id','name','last_name',
    '$phone','1','1','1',','', '','1')";
    $result = mysqli_query($db, $sql);
  }

  $sql = "SELECT *
  FROM `courier`";
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
                  <div class="row">
                    <div class="col-12">
                      <div class="md-form mb-3">
                        <input
                        placeholder="Ime"
                          type="text"
                          id="name"
                          name="name"
                          class="form-control"
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
                        />

                      </div>
                    </div>
                  </div>
                  <button name='addcurier' class="btn btn-success" type="submit">
                    Dodaj kurira
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
                      <a href='kurir.html'>
                        <button class='btn btn-danger align-self-center'>
                          Izbriši
                        </button></a
                      >
                      <a href='kurir.html'>
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
              <form id="" name="" action="" method="">
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Ime firme"
                        id="name"
                        name="name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Mesto"
                        id="name"
                        name="municipality-id"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Ulica"
                        id="name"
                        name="street_id"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="md-form mb-3">
                      <input
                      type="text"
                      placeholder="Broj"
                        id="name"
                        name="street_number"
                        class="form-control"
                      />
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
                <button class="btn btn-success" type="submit">
                  Dodaj klijenta
                </button>
                <button class="btn btn-warning disabled  " type="submit">
                  Sačuvaj izmene
                </button>
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
                          <a href='kurir.html'>
                            <button class='btn btn-danger align-self-center'>
                              izbriši
                            </button></a
                          >
                          <a href='kurir.html'>
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



    
    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
