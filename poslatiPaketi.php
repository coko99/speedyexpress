<?php
  include('config/session_user.php');

?><!DOCTYPE html>
<html lang="sr">
  <?php
    include('config/head.php');

  ?>
  
  <body onload="prikaziDatumVreme()">
    
    <?php
      $active = 2;
      include('config/navbar.php');
    ?>


    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Poslati paketi</h2>
      </div>

      <div class="row">
        <div class="col-12">
          <img style="width: 100%" src="poslatipaketi.JPG " alt="" />
        </div>
      </div>
    </div>

    <script src="index.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
