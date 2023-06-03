<?php
  include('config/session_admin.php');
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

?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>

  <?php
    $active = 3;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Spisak Klijenata</h2>
        <div class="row">
          <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th scope="col">#ID</th>
                  <th scope="col">Ime firme</th>
                  <th scope="col">PIB</th>
                  <th scope="col">Adresa</th>
                  <th scope="col">Broj telefona</th>
                  <th scope="col">Akcija</th>
                </tr>
              </thead>
              <tbody>
              <?php 
                $counter = 0;
                foreach($firms as $firm){
                  $counter += 1;
                  $firm_id = $firm['id'];
                  $firm_name = $firm['name'];
                  $firm_phone = $firm['phone'];
                  $firm_pib = $firm['pib'];
                  $street = $firm['street_name'];
                  $street_num = $firm['street_number'];
                  $municipality_name = $firm['municipality_name'];
                  $municipality_zip = $firm['zip'];

                  echo "
                  <tr>
                    <th scope='row'>$counter</th>
                    <td>
                      <h4 class='m-0 p-0'>$firm_name</h4>
                    </td>
                    <td>
                      <h4 class='m-0 p-0'>$firm_pib</h4>
                    </td>
                    <td>
                      <h4 class='m-0 p-0'>$street $street_num</h4>
                    </td>
                    <td>
                      <h4 class='m-0 p-0'>$firm_phone</h4>
                    </td>
                    <td>
                      <a href='klijent.php?id=$firm_id'
                        ><button class='btn btn-info'>Pregledaj</button></a
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
