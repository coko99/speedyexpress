<?php
  include('config/session_admin.php');

?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>

  <?php
      $active = 2;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4">
        <div class="col">
          <a href="kuriri.php"><button class="btn btn-info">NAZAD</button></a>
        </div>
      </div>
      <div class="row mb-4">
        <h2 class="mt-3 mb-3 text-center">Milos Mijajlovic</h2>
        <div
          class="statistika1 d-flex flex-column justify-content-between p-4 col-xs-12 col-sm-12 col-md-4"
        >
          <h3 class="opsteh3 mb-3">Ovaj mesec</h3>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center">500</spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center">500</spam>
          </div>
          <div class="d-flex justify-content-between">
            <h5 class="opsteh4 mt-3">Broj isporučenih:</h5>

            <spam class="spanh4 d-flex align-self-center">500</spam>
          </div>
        </div>
        <div class="col-md-6 mx-auto mt-5">
          <!-- HTML element za grafikon -->
          <canvas id="myChart">test</canvas>
        </div>
        <div class="row">
          <div class="col-6">
            <h1 class="mt-3">Zaduženi paketi</h1>
            <button class="btn btn-primary mb-3">Štampaj</button>
          </div>

          <div class="col-12 table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered table-striped mb-0">
              <thead>
                <tr>
                  <th scope="col ">#ID</th>
                  <th scope="col">QR</th>
                  <th scope="col">Primalac</th>
                  <th scope="col">Pošiljalac</th>
                  <th scope="col">Opis</th>
                  <th scope="col">Akcija</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                  <td>
                    <h6>Milos Mijajlovic</h6>
                    <h6>Krusevac 37000</h6>
                    <h6>Kruševačka 17</h6>
                    <h6>+381621872069</h6>
                  </td>
                  <td>
                    <h6>Milos Mijajlovic</h6>
                    <h6>Krusevac 37000</h6>
                    <h6>Kruševačka 17</h6>
                    <h6>+381621872069</h6>
                  </td>
                  <td>
                    <h6><strong>Otkup: </strong>20.000 rsd</h6>
                    <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                    <h6><strong>Plaća: </strong>Primalac</h6>
                    <h6><strong>napomena: </strong>tekst</h6>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td><img class="qr-slika" src="qr kod.png" alt="" /></td>
                  <td>
                    <h6>Milos Mijajlovic</h6>
                    <h6>Krusevac 37000</h6>
                    <h6>Kruševačka 17</h6>
                    <h6>+381621872069</h6>
                  </td>
                  <td>
                    <h6>Milos Mijajlovic</h6>
                    <h6>Krusevac 37000</h6>
                    <h6>Kruševačka 17</h6>
                    <h6>+381621872069</h6>
                  </td>
                  <td>
                    <h6><strong>Otkup: </strong>20.000 rsd</h6>
                    <h6><strong>Vrednost: </strong>20.000 rsd</h6>
                    <h6><strong>Plaća: </strong>Primalac</h6>
                    <h6><strong>napomena: </strong>tekst</h6>
                  </td>
                </tr>
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
