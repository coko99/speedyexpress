<?php
  include('config/session_admin.php');

  if(!isset($_GET['id'])){
    header('Location: klijenti.php');
  }
  $id = mysqli_real_escape_string($db, $_GET['id']);
  if(isset($_GET['date'])){
    $datetime = mysqli_real_escape_string($db, $_GET['date']);
  }

  if(isset($_POST['pay'])){
    $package_for_pay_array = [];
    foreach($_POST as $key => $value){
      if(str_starts_with($key, "paycheck#")){
        array_push($package_for_pay_array, explode("#", $key)[1]);
      }
    }
    $ids = join(",",$package_for_pay_array);
    $sql = "UPDATE `package` SET `pay` = 1 WHERE package.id in ($ids);";
    $result = mysqli_query($db, $sql);
  }

  $sql = "SELECT firm.*,
    street.name AS street_name,
    municipality.name AS municipality_name,
    municipality.zip AS zip,
    city.name AS city_name
    FROM `firm`
    LEFT JOIN street ON firm.street_id = street.id
    LEFT JOIN municipality ON street.municipality_id = municipality.id
    LEFT JOIN city ON municipality.city_id = city.id
    WHERE firm.id = $id
  ";
  $result = mysqli_query($db, $sql);
  $firm = mysqli_fetch_array($result);

  $sql = "SELECT package.*, 
  city.name AS city_name, 
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
  courier.name as courier_name,
  courier.last_name as courier_last_name
  FROM `package`
  LEFT JOIN street ON package.street_id = street.id
  LEFT JOIN municipality ON street.municipality_id = municipality.id
  LEFT JOIN city ON municipality.city_id = city.id
  LEFT JOIN status ON package.status_id = status.id
  LEFT JOIN firm ON package.firm_id = firm.id
  LEFT JOIN street AS firm_street ON firm.street_id = firm_street.id
  LEFT JOIN municipality AS firm_municipality ON firm_street.municipality_id = firm_municipality.id
  LEFT JOIN package_status_tracking ON package.id = package_status_tracking.package_id 
  LEFT JOIN courier ON package.curier_id = courier.id 
  WHERE firm.id = $id 
  AND package_status_tracking.status = 1";
  if(isset($datetime)){
    $sql.=" AND package_status_tracking.datetime BETWEEN STR_TO_DATE('$datetime', '%d/%m/%Y') AND DATE_ADD(STR_TO_DATE('$datetime', '%d/%m/%Y'), INTERVAL 1 DAY);";
  }
  
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

  <?php
    $active = 3;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4">
        <div class="col">
          <a href="klijenti.php"
            ><button class="btn btn-info">NAZAD</button></a
          >
        </div>
      </div>
      <h2 class="mt-3 mb-3"><?php echo $firm['name']; ?></h2>
      <div class="row d-flex justify-content-end">
        <div class="col-sm-12 col-md-4">
          <table class="table">
            <tbody>
              <tr>
                <th scope="row"><h5>Grad</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0"><?php echo $firm['city_name']; ?></h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Opština</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0"><?php echo $firm['municipality_name']; ?></h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Ulica</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0"><?php echo $firm['street_name']." ".$firm['street_number']; ?></h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Poštanski broj</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0"><?php echo $firm['zip']; ?></h5>
                </td>
              </tr>
              <tr>
                <th scope="row"><h5>Broj telefona</h5></th>
                <td class="align-items-center">
                  <h5 class="m-0 p-0"><?php echo $firm['phone']; ?></h5>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-sm-12 col-md-8">
          <h1>PREGLED PAKETA</h1>

          <form method="GET">
          <input type="hidden" name="id" value="<?php echo $id; ?>"> 
                <div class="input-group date" id="datepicker">
                  <input onchange="this.form.submit()" autocomplete="off" value="<?php if(isset($_GET['date'])) echo $_GET['date'];?>" placeholder="Izaberi datum" required name='date' type="text" class="form-control"></input>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                </div>
          </form>

          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button
                class="nav-link active"
                id="isporuceni"
                data-bs-toggle="tab"
                data-bs-target="#home"
                type="button"
                role="tab"
                aria-controls="home"
                aria-selected="true"
              >
                Isporučeni paketi
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button
                class="nav-link"
                id="neisporuceni"
                data-bs-toggle="tab"
                data-bs-target="#profile"
                type="button"
                role="tab"
                aria-controls="profile"
                aria-selected="false"
              >
                Neisporučeni paketi
              </button>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div
              class="tab-pane fade show active border-0"
              id="home"
              role="tabpanel"
              aria-labelledby="isporuceni"
            >
            <form action='' method='post'>
              <div
                class="col-12 table-wrapper-scroll-y my-custom-scrollbar adminPaketi"
              >
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th scope="col ">#</th>
                      <th scope="col ">ID paketa</th>

                      <th scope="col">Kurir</th>

                      <th scope="col">Lokacija</th>
                    </tr>
                  </thead>
                  <tbody class="isporuceni">
                    <?php
                    $counter = 0;
                    foreach($packages as $package){
                      $checked = $package['pay'];
                      if($checked == 1){
                        $print = true;
                      }
                      if($package['status_id'] == 4 && $package['pay'] == 0){
                        $counter += 1;
                        $courier_name = $package['courier_name'];
                        $courier_last_name = $package['courier_last_name'];
                        $city_name = $package['city_name'];
                        $municipality_name = $package['municipality_name'];
                        $package_id = $package['id'];

                        echo "<tr>
                        <td>
                            <input
                              type='checkbox'
                              id='subscribeNews'
                              name='paycheck#$package_id' 
                              ";
                          
                              echo "/>
                          <th scope='row'>$package_id</th>
                          <td>
                            <h6>$courier_name $courier_last_name</h6>
                          </td>
                          <td>
                            <h6>$city_name-$municipality_name</h6>
                          </td>
                        </tr>";
                      }
                    }
                      
                    ?>                    
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="col d-flex justify-content-end mt-3">
                  <button name="pay" class="btn btn-success">ISPLATI</button>
                  <a href="printPackagesKlient.php?id=<?php echo $id; ?>&date=<?php echo $datetime; ?>" type="submit" class="btn <?php if (!isset($print)) echo "disabled"; ?> btn-success ml-3">ŠTAMPAJ</a>
                </div>
              </div>
              </form>
            </div>
            <div
              class="tab-pane fade border-0"
              id="profile"
              role="tabpanel"
              aria-labelledby="neisporuceni"
            >
              <div
                class="col-12 table-wrapper-scroll-y my-custom-scrollbar adminPaketi"
              >
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th scope="col ">ID paketa</th>

                      <th scope="col">Kurir/baza</th>

                      <th scope="col">Lokacija</th>
                    </tr>
                  </thead>
                  <tbody class="neisporučeni">
                  <?php
                    $counter = 0;
                    foreach($packages as $package){
                      if($package['status_id'] != 4 && $package['status_id'] != 2){
                        $counter += 1;
                        $courier_name = $package['courier_name'];
                        $courier_last_name = $package['courier_last_name'];
                        $city_name = $package['city_name'];
                        $municipality_name = $package['municipality_name'];
                        $package_id = $package['id'];

                        echo "<tr>
                          <th scope='row'>$package_id</th>
                          <td>
                            <h6>$courier_name $courier_last_name</h6>
                          </td>
                          <td>
                            <h6>$city_name-$municipality_name</h6>
                          </td>
                        </tr>";
                      }
                    }
                    ?>   
                  </tbody>
                </table>
              </div>
            </div>
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
    <script src="bootstrap-datetimepicker.js"></script>
    <script type="text/javascript">
    $(function() {
        $('#datepicker').datepicker({
          format: 'dd/mm/yyyy',
          language: 'rs-latin',
          
        });
    });

    var startDate,
        endDate;
        
      $('#weekpicker').datepicker({
        autoclose: true,
        format :'dd/mm/yyyy',
        forceParse :false
    }).on("changeDate", function(e) {
        //console.log(e.date);
        var date = e.date;
        startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
        endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay()+6);
        //$('#weekpicker').datepicker("setDate", startDate);
        $('#weekpicker').datepicker('update', startDate);
        $('#weekpicker').val(startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate()  + ' / ' + endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate());
    });
        
    </script>
  </body>
</html>
