<?php
  include('config/session_user.php');

?><!DOCTYPE html>
<html lang="sr">
  <?php
    include('config/head.php');

    // MESEC

  $sql = "SELECT count(*) as c
  FROM package WHERE
  firm_id = $firm_id
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_tak = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id = 4
  AND firm_id = $firm_id
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_suc = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  pay = 1
  AND firm_id = $firm_id
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_fai = $row['c'];

  // NEDELJA

  $sql = "SELECT count(*) as c
  FROM package WHERE
  firm_id = $firm_id
  AND YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1);";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_tak_w = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  firm_id = $firm_id AND
  status_id = 4 AND
  YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1);";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_suc_w = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  firm_id = $firm_id AND
  pay = 1 AND
  YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1);";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_fai_w = $row['c'];

  ?>
  
  <body onload="prikaziDatumVreme()">
    
    <?php
      $active = 1;
      include('config/navbar.php');
    ?>

    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Statistika</h2>
      </div>
      <div class="container mt-5">
        <div class="row">
          <div
            class="statistika1 d-flex flex-column justify-content-between p-4 col-xs-12 col-sm-12 col-md-4"
          >
            <h3 class="opsteh3 mb-3">Ove nedelje</h3>
            <div class="d-flex justify-content-between">
              <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

              <spam class="spanh4 d-flex align-self-center"><?php echo $num_tak_w; ?></spam>
            </div>
            <div class="d-flex justify-content-between">
              <h5 class="opsteh4 mt-3">Broj vraćenih:</h5>

              <spam class="spanh4 d-flex align-self-center"><?php echo $num_suc_w; ?></spam>
            </div>
            <div class="d-flex justify-content-between">
              <h5 class="opsteh4 mt-3">Broj plaćenih:</h5>

              <spam class="spanh4 d-flex align-self-center"><?php echo $num_fai_w; ?></spam>
            </div>
          </div>
          <div class="col-xs-12 mt-3 mb-3 col-sm-12 col-md-4">
            <div class="container">
              <div class="row">
                <div class="col-md-12 m-auto mt-5">
                  <!-- HTML element za grafikon -->
                  <canvas id="myChart2"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div
            class="statistika1 d-flex flex-column justify-content-between p-4 col-xs-12 col-sm-12 col-md-4"
          >
            <h3 class="opsteh3 mb-3">Ovaj mesec</h3>
            <div class="d-flex justify-content-between">
              <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

              <spam class="spanh4 d-flex align-self-center"><?php echo $num_tak; ?></spam>
            </div>
            <div class="d-flex justify-content-between">
              <h5 class="opsteh4 mt-3">Broj vraćenih:</h5>

              <spam class="spanh4 d-flex align-self-center"><?php echo $num_suc; ?></spam>
            </div>
            <div class="d-flex justify-content-between">
              <h5 class="opsteh4 mt-3">Broj plaćenih:</h5>

              <spam class="spanh4 d-flex align-self-center"><?php echo $num_fai; ?></spam>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid mt-5">
        <div class="row">
          <div class="col">
            <div class="container">
              <div class="row">
                <div class="col-md-12 mx-auto mt-5">
                  <!-- HTML element za grafikon -->
                  <canvas id="myChart">test</canvas>
                </div>
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
  </body>
</html>
