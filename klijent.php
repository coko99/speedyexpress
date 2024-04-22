<?php
  include('config/session_admin.php');

  if(!isset($_GET['id'])){
    header('Location: klijenti.php');
  }
  $id = mysqli_real_escape_string($db, $_GET['id']);
  if(isset($_GET['dateFrom']) && isset($_GET['dateTo'])){
    $datetimeFrom = mysqli_real_escape_string($db, $_GET['dateFrom']);
    $datetimeTo = mysqli_real_escape_string($db, $_GET['dateTo']);
  }else{
    $datetimeFrom = date('d/m/Y');
    $datetimeTo = date('d/m/Y');

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
    header("Location: printPackagesKlient.php?idsForSearch=$ids");
  }

  if(isset($_POST['print_mesecni'])){
    $package_for_pay_array = [];
    foreach($_POST as $key => $value){
      if(str_starts_with($key, "print#")){
        array_push($package_for_pay_array, explode("#", $key)[1]);
      }
    }
    $ids = join(",",$package_for_pay_array);

    header("Location: printPackagesKlient.php?idsForSearch=$ids");
  }

  if(isset($_POST['print_excel'])){
    $package_for_pay_array = [];
    foreach($_POST as $key => $value){
      if(str_starts_with($key, "print#")){
        array_push($package_for_pay_array, explode("#", $key)[1]);
      }
    }
    $ids = join(",",$package_for_pay_array);

    header("Location: paketi.php?idsForSearch=$ids");
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
  courier.last_name as courier_last_name,
  grup.number_of_packages AS number_of_packages
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
  LEFT JOIN grup on package.group_id = grup.id
  WHERE firm.id = $id 
  AND package_status_tracking.status = 1";
  if(isset($datetimeFrom) && isset($datetimeTo) ){
    $sql.=" AND package_status_tracking.datetime BETWEEN STR_TO_DATE('$datetimeFrom', '%d/%m/%Y') AND DATE_ADD(STR_TO_DATE('$datetimeTo', '%d/%m/%Y'), INTERVAL 1 DAY);";
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
            <div class="row py-3 px-3">
              <div class="input-group input-daterange" id="datepicker">
                  <div class="input-group-addon mx-2 my-2">Datum od</div>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                  <input autocomplete="off" requried value="<?php if(isset($_GET['dateFrom'])) echo $_GET['dateFrom'];?>" required name='dateFrom' type="text" class="form-control"></input>
                  <div class="input-group-addon mx-2 my-2">do</div>
                  <span class="input-group-append">
                      <span class="input-group-text bg-white d-block">
                          <i class="fa fa-calendar"></i>
                      </span>
                  </span>
                  <input autocomplete="off" requried value="<?php if(isset($_GET['dateTo'])) echo $_GET['dateTo'];?>" required name='dateTo' type="text" class="form-control">
                </div>
                <button class='btn btn-info' type="submit">Potvrdi</button>
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
            <li class="nav-item" role="presentation">
              <button
                class="nav-link"
                id="neisporuceni"
                data-bs-toggle="tab"
                data-bs-target="#isplaceni"
                type="button"
                role="tab"
                aria-controls="isplaceni"
                aria-selected="false"
              >
                Isplaćeni paketi
              </button>
            </li>
            <li class="nav-item mt-2" role="presentation">
              <span id='otkup'>0</span> RSD
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
              
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="toggle-all" value=""></input>
                <label class="form-check-label" for="toggle-all">
                  Obeleži sve
                </label>
              </div>
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th scope="col ">#</th>
                      <th scope="col ">ID</th>

                      <th scope="col">Kurir</th>

                      <th scope="col">Lokacija</th>
                      <th scope="col">Preuzeto</th>
                      <th scope="col">Dostavljeno</th>
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
                        $package_ransome = $package['shipping_fee'];

                        $numOfPackages = $package['number_of_packages'];
                        $orderInGrupu = $package['order_in_group'];
                        $grupId = sprintf('SX%08d', $package['group_id']);

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
                        echo "<tr>
                        <td>
                            <input
                              class='checkbox_pay'
                              type='checkbox'
                              id='subscribeNews'
                              data-ransom='$package_ransome'
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
                          <td>
                            <h6>$date_time</h6>
                          </td>
                          <td>
                            <h6>$date_time_1</h6>
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

                        $numOfPackages = $package['number_of_packages'];
                        $orderInGrupu = $package['order_in_group'];
                        $grupId = sprintf('SX%08d', $package['group_id']);


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
            <div
              class="tab-pane fade border-0"
              id="isplaceni"
              role="tabpanel"
              aria-labelledby="isplaceni"
            >
              <div
                class="col-12 table-wrapper-scroll-y my-custom-scrollbar adminPaketi"
              >
              <form action="" method="POST">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col ">ID paketa</th>

                      <th scope="col">Kurir/baza</th>

                      <th scope="col">Lokacija</th>
                    </tr>
                  </thead>
                  <tbody class="isplaceni">
                  <?php
                    $counter = 0;
                    foreach($packages as $package){
                      if($package['status_id'] == 4 && $package['pay'] == 1){
                        $counter += 1;
                        $courier_name = $package['courier_name'];
                        $courier_last_name = $package['courier_last_name'];
                        $city_name = $package['city_name'];
                        $municipality_name = $package['municipality_name'];
                        $package_id = $package['id'];


                        echo "<tr>
                          <td><input
                          class='checkbox_print'
                          type='checkbox'
                          id='checkbox_print'
                          name='print#$package_id' 
                          ";
                          echo "/></td>
                          
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
                    <button name='print_mesecni' type="submit" class="btn btn-info">Štampaj</button>
                    <button name='print_excel' type="submit" class="btn btn-info">Excel</button>

                </form>
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

    $suma = 0.00;
    $('.checkbox_pay').change(function() {
        let otkup = parseFloat($(this).data("ransom") );
        if(this.checked) {
            $('#otkup').html($suma += otkup);
        }else{
          $('#otkup').html($suma -= otkup);
        }
    });

    $('#toggle-all').click(function() {
        $('.checkbox_pay').prop('checked', $(this).is(':checked'));
        
        $('.checkbox_pay').each(function(i, obj) {
          let otkup = parseFloat($(obj).data("ransom"));
          if(obj.checked) {
              $('#otkup').html($suma += otkup);
          }else{
            $('#otkup').html($suma -= otkup);
          }
        })
    });
        
    </script>
  </body>
</html>
