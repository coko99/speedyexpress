<?php
  include('config/session_admin.php');

?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

  if(isset($_POST['send_sms_of'])){
    $sql = "UPDATE `configuration` SET `send_sms` = 0 WHERE id = 1;";
    $result = mysqli_query($db, $sql);
  }
  if(isset($_POST['send_sms_on'])){
    $sql = "UPDATE `configuration` SET `send_sms` = 1 WHERE id = 1;";
    $result = mysqli_query($db, $sql);
  }

  //MESEC

  $sql = "SELECT count(*) as c
  FROM package WHERE
  created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_tak = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id = 4
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_suc = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id != 4
  AND created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_fai = $row['c'];

  // NEDELJA

  $sql = "SELECT count(*) as c
  FROM package WHERE
  YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1);";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_tak_w = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id = 4 AND
  YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1);";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_suc_w = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id != 4 AND
  YEARWEEK(`created_at`, 1) = YEARWEEK(CURDATE(), 1);";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_fai_w = $row['c'];

  //DAN

  $sql = "SELECT count(*) as c
  FROM package WHERE
  created_at > CURDATE() 
  AND created_at <  CURDATE() + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_tak_day = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id = 4
  AND created_at > CURDATE()
  AND created_at <  CURDATE() + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_suc_day = $row['c'];

  $sql = "SELECT count(*) as c
  FROM package WHERE
  status_id != 4
  AND created_at > CURDATE() 
  AND created_at <  CURDATE() + INTERVAL 1 DAY;";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  $num_fai_day = $row['c'];


  // KURIR

  $sql = "SELECT courier.name, count(*) as c
  FROM package 
  LEFT JOIN courier ON package.curier_id = courier.id
  WHERE created_at >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND created_at <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY
  GROUP BY curier_id;";
  $result = mysqli_query($db, $sql);
  $kuriri_paketi = [];
  while($row = mysqli_fetch_array($result)) {
    array_push($kuriri_paketi, $row);
  }

  $sql = "SELECT count(*) as c
  FROM SMS 
  WHERE timestamp >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH
  AND timestamp <  LAST_DAY(CURDATE()) + INTERVAL 1 DAY";
  $result = mysqli_query($db, $sql);
  $sms_count = mysqli_fetch_array($result)[0];

  $sql = "SELECT * 
  FROM configuration WHERE id = 1;";
  $result = mysqli_query($db, $sql);
  $send_sms = mysqli_fetch_array($result)['send_sms'];


?>
  <body>

  <?php
    $active = 1;
    include('config/navbar.php');
  ?>

    <div class="container mt-5">
      <div class="row">
        <div class="col-4">Broj poslatih poruka za ovaj mesec - <?php echo $sms_count; ?></div>
      </div>
      <div class="row mb-1">
        <div class="col-4">
          <form method="POST">
            <?php if($send_sms == 0) {?>
              <button name="send_sms_on" type="submit" class="btn btn-success">Upali SMS</button>
            <?php }else{ ?>
              <button name="send_sms_of" type="submit" class="btn btn-danger">Ugasi SMS</button>
            <?php }?>
          </form>
        </div>
      </div>
      <div class="row">
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
        
        <div
          class="statistika1 d-flex flex-column justify-content-between p-4 col-xs-12 col-sm-12 col-md-4"
        >
          <h3 class="opsteh3 mb-3">Ove nedelje</h3>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj preuzetih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_tak_w; ?></spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_suc_w; ?></spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj neisporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_fai_w; ?></spam>
          </div>
        </div>

        <div
          class="statistika1 d-flex flex-column justify-content-between p-4 col-xs-12 col-sm-12 col-md-4"
        >
          <h3 class="opsteh3 mb-3">Ovaj dan</h3>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj preuzetih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_tak_day; ?></spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_suc_day; ?></spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj neisporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center"><?php echo $num_fai_day; ?></spam>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container-fluid mt-1">
      <div class="col mt-3 mb-3 ">
          <div class="container">
            <div class="row">
              <div class="col-md-12 m-auto mt-5">
                <!-- HTML element za grafikon -->
                <canvas id="myChart2"></canvas>
              </div>
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

    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>

    <script>

      <?php
      echo "let kuriri = [";
      foreach($kuriri_paketi as $kur){
        $kur_name = $kur['name'];

        echo "'$kur_name', ";
      }
      echo "];";
      ?>

      <?php
      echo "let paketi = [";
      foreach($kuriri_paketi as $kur){
        $kur_name = $kur['c'];

        echo "$kur_name, ";
      }
      echo "];";
      ?>
      
      
      var ctx = document.getElementById("myChart2").getContext("2d");
      var myChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          datasets: [
            {
              data: paketi,
              backgroundColor: ["rgba(255, 99, 132, 0.6)", "rgba(54, 162, 235, 0.6)", "rgba(54, 162, 54, 0.6)", "rgba(54, 32, 235, 0.6)", "rgba(54, 162, 162, 0.6)", "rgba(0, 162, 235, 0.6)", "rgba(162, 162, 235, 0.6)", "rgba(235, 52, 235, 0.6)", "rgba(162, 235, 52, 0.6)"],
            },
          ],
          labels: kuriri,
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutoutPercentage: 50, // podesiti na 50 da bude polukružni grafikon
          legend: {
            display: false, // isključiti legendu
          },
          animation: {
            animateScale: true,
            animateRotate: true,
          },
        },
      });
      </script>
  </body>
</html>
