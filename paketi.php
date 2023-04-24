<?php
  include('config/session_admin.php');

?><!DOCTYPE html>
<html lang="sr">
<?php
  include('config/head.php');

?>
  <body>

  <?php
      $active = 4;
    include('config/navbar.php');
  ?>

    <div class="container mt-4">
      <div class="row mb-4 text-left">
        <h2 class="mt-3 mb-3">Paketa kurira</h2>
        <div class="row">
          <h6 class="mt-4 mb-4">filter</h6>
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

    <script src="index.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
