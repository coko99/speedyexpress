<?php
  include('config/session_admin.php');

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
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <a href="klijent.php"
                      ><button class="btn btn-info">Pregledaj</button></a
                    >
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
                  </td>
                </tr>
                <tr>
                  <th scope="row">1</th>
                  <td>
                    <h4 class="m-0 p-0">Milos Mijajlovic</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">101101101</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">Krusevacka 18</h4>
                  </td>
                  <td>
                    <h4 class="m-0 p-0">0621213122</h4>
                  </td>
                  <td>
                    <button class="btn btn-info align-self-center">
                      Pregledaj
                    </button>
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
